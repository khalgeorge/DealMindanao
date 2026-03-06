<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'brand_id',
        'model_code',
        'variant',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'supplier_price',
        'srp',
        'price',        // legacy — kept in sync with srp
        'discount',
        'promo_label',
        'promo_starts_at',
        'promo_ends_at',
        'stock_quantity',
        'images',
        'supplier_id',
        'category_id',
        'is_featured',
        'is_active',
        'status',   // draft | published
        'specifications',
        'variants',
    ];

    /**
     * Fields hidden from JSON/array serialisation.
     * supplier_price is an internal cost figure and must never appear in
     * public API responses.
     */
    protected $hidden = ['supplier_price'];

    protected $casts = [
        'images'          => 'array',
        'is_featured'     => 'boolean',
        'is_active'       => 'boolean',
        'promo_starts_at' => 'datetime',
        'promo_ends_at'   => 'datetime',
        'status'          => 'string',
        'supplier_price'  => 'decimal:2',
        'srp'             => 'decimal:2',
        'price'           => 'decimal:2',
        'discount'        => 'decimal:2',
        'specifications'  => 'array',
        'variants'        => 'array',
    ];

    // ─── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Products that are live on the public storefront.
     * Must be explicitly published AND marked active.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('is_active', true);
    }

    // ─── Pricing helpers ──────────────────────────────────────────────────────

    /**
     * Compute the customer-facing SRP from a supplier cost and a margin.
     *
     * @param  float       $supplierPrice  The cost price paid to the supplier.
     * @param  float|null  $margin         Decimal markup, e.g. 0.25 for 25 %.
     *                                     Defaults to PRODUCT_DEFAULT_MARGIN env var.
     * @return float  Rounded to 2 decimal places.
     */
    public static function computeSrp(float $supplierPrice, ?float $margin = null): float
    {
        $margin ??= (float) config('products.default_margin', 0.25);

        return round($supplierPrice * (1 + $margin), 2);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Heuristic check: returns true when the product looks like seeder/demo data.
     * Used by the CleanDemoProducts artisan command — not for hard business logic.
     */
    public function isDemoProduct(): bool
    {
        // Old-style seeder image paths (relative, not uploaded via the admin)
        $seederImages = [
            '/products/coffee.jpg',
            '/products/durian.jpg',
            '/products/woven-bag.jpg',
            '/products/mandaya-shirt.jpg',
            '/products/pomelo.jpg',
            '/products/malong.jpg',
            '/products/banana-chips.jpg',
            '/products/brass.jpg',
        ];

        $images = $this->images ?? [];

        foreach ($images as $img) {
            if (in_array($img, $seederImages, true)) {
                return true;
            }
        }

        // Obvious test name patterns (e.g. "xxxx12", "Test Product", "Demo Item")
        if (preg_match('/^x{3,}/i', $this->name) ||
            preg_match('/^(test|demo|sample)\b/i', $this->name)) {
            return true;
        }

        // No images AND from the placeholder seeder supplier → demo
        if (empty($images) && $this->supplier?->name === 'DealMindanao Marketplace') {
            return true;
        }

        return false;
    }

    // ─── Promo helpers ────────────────────────────────────────────────────────

    /**
     * Returns true when a non-zero discount is currently active within its
     * optional date window.
     */
    public function isOnPromo(): bool
    {
        if (empty($this->discount) || $this->discount <= 0) {
            return false;
        }

        $now = now();

        if ($this->promo_starts_at && $now->lt($this->promo_starts_at)) {
            return false; // not started yet
        }

        if ($this->promo_ends_at && $now->gt($this->promo_ends_at)) {
            return false; // expired
        }

        return true;
    }

    /**
     * The price the customer actually pays.
     * Uses srp as the authoritative selling price; falls back to the legacy
     * price column for records that pre-date the pricing normalisation.
     * Applies the active promo discount when one is running.
     */
    public function displayPrice(): float
    {
        $base = (float) ($this->srp ?? $this->price ?? 0);

        if (!$this->isOnPromo()) {
            return $base;
        }

        return (float) max(0, $base - $this->discount);
    }

    /**
     * Integer percentage saved (0 when no active promo).
     */
    public function discountPercent(): int
    {
        $base = (float) ($this->srp ?? $this->price ?? 0);

        if (!$this->isOnPromo() || $base <= 0) {
            return 0;
        }

        return (int) round(($this->discount / $base) * 100);
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * The partner seller (supplier) who lists this product on DealMindanao.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
