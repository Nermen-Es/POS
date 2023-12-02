<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partially extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_id',
        'supplier_name',
        'paid_amount',
        'remaining_amount',
    ];


    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class , 'invoice_id' , 'id');
    }

    public function user(): BelongsTo
    {
         return $this->belongsTo(User::class, 'user_id', 'id');
    }


}
