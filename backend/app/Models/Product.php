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
        'description',
        'meta_title',
        'meta_description',
        'price',
        'discount',
        'promo_label',
        'promo_starts_at',
        'promo_ends_at',
        'stock_quantity',
        'images',
        'company_id',
        'category_id',
        'is_featured',
        'is_active',
        'status',   // draft | published
    ];

    protected $casts = [
        'images'          => 'array',
        'is_featured'     => 'boolean',
        'is_active'       => 'boolean',
        'promo_starts_at' => 'datetime',
        'promo_ends_at'   => 'datetime',
        'status'          => 'string',
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

        // No images AND from the placeholder seeder company → demo
        if (empty($images) && $this->company?->name === 'DealMindanao Marketplace') {
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
     * Falls back to the base price when the promo is inactive.
     */
    public function displayPrice(): float
    {
        if (!$this->isOnPromo()) {
            return (float) $this->price;
        }

        return (float) max(0, $this->price - $this->discount);
    }

    /**
     * Integer percentage saved (0 when no active promo).
     */
    public function discountPercent(): int
    {
        if (!$this->isOnPromo() || (float) $this->price <= 0) {
            return 0;
        }

        return (int) round(($this->discount / $this->price) * 100);
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function company()
    {
        return $this->belongsTo(Company::class);
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
