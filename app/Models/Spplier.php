<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
    ];


    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class,'id','spplier_id');
    }

    protected $searchable = [
        'name',
        'phone',
        'address',
    ];

    public function scopeSearch(Builder $builder , string $term){


        foreach ($this->searchable as $searchable){
            $builder->orWhere($searchable , 'like' , "%$term%");
        }

        return $builder;
    }
}
