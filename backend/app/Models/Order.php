<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'total',
        'status',
        'payment_method',
        'shipping_address',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'phone',
        'notes',
        'tracking_number',
    ];
    
    protected $appends = ['customer_name', 'email'];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Accessor for customer_name
    public function getCustomerNameAttribute()
    {
        return $this->user?->name ?? 'Guest';
    }
    
    // Accessor for email
    public function getEmailAttribute()
    {
        return $this->user?->email ?? 'No email';
    }
}
