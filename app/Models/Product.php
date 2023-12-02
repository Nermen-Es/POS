<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    //product => stock
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    protected $searchable = [
        'name',
        'brand',
        'unit',
        'description',
    ];

    public function scopeSearch(Builder $builder , string $term){


        foreach ($this->searchable as $searchable){
            $builder->orWhere($searchable , 'like' , "%$term%");
        }

        return $builder;
    }


}
