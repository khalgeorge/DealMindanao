<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name','city','contact_email','contact_phone','messenger_link','logo'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}