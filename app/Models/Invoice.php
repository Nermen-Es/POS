<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
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


    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Spplier::class, 'id', 'spplier_id');
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
