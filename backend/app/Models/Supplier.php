<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /**
     * Suppliers are the partner sellers on DealMindanao
     * (e.g. GEEANN Hardware Trading). They are the businesses
     * that list products through our platform.
     */
    protected $fillable = [
        'name',
        'region',
        'contact_person',
        'contact_email',
        'contact_phone',
        'internal_notes',
        'is_active',
        'is_verified',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_verified' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function reviews()
    {
        return $this->morphMany(\App\Models\Review::class, 'reviewable');
    }
}
