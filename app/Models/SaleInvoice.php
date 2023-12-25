<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleInvoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'start_day_date',
        'total_amount',
        'note'
    ];


public function user(): BelongsTo
{
    return $this->belongsTo(User::class, 'user_id', 'id');
}

protected $casts = [
    'start_day_date' => 'date',
    'created_at' => 'date',
    'updated_at' => 'date'
];
}
