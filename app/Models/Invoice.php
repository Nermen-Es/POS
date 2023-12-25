<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Invoice extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'spplier_id',
        'invoice_number',
        'invoice_date',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'note',
    ];


    /**
     * Get the user that owns the Sales_invoices
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Spplier::class, 'spplier_id', 'id');
    }


    public function invoice_details(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id', 'id');
    }


    public function partialies(): HasMany
    {
        return $this->hasMany(Partially::class,'invoice_id', 'id');
    }

    protected $searchable = [
        'invoice_number',
        'invoice_date',
        'status'
    ];

    public function scopeSearch(Builder $builder , string $term){


        foreach ($this->searchable as $searchable){
            $builder->orWhere($searchable , 'like' , "%$term%");
        }

        return $builder;
    }


}
