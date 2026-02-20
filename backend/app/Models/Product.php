<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    protected $casts = [
        'images'          => 'array',
        'is_featured'     => 'boolean',
        'is_active'       => 'boolean',
        'promo_starts_at' => 'datetime',
        'promo_ends_at'   => 'datetime',
    ];

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
}