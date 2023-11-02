<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    //product => stock
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
