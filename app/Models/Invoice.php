<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Invoice extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'spplier_id',
        'invoice_number',
        'invoice_date',
        'total_price',
        'paid_amount',
        'remaining_amount',
        'status',
        'note',
    ];


    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Spplier::class, 'id', 'Supplier_id');
    }


    public function invoice_details(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id', 'id');
    }
}
