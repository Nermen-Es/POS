<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'minimum',
    ];

  //stock has many product
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'id', 'product_id');
    }
}
