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


    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'spplier_id', 'id');
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
