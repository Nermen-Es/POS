<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'product',
        'price',
        'quantity',
        'sub_total',
    ];


    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    protected $searchable = [
        'product',
        'price',
        'sub_total',
    ];

    public function scopeSearch(Builder $builder , string $term){


        foreach ($this->searchable as $searchable){
            $builder->orWhere($searchable , 'like' , "%$term%");
        }

        return $builder;
    }

}
