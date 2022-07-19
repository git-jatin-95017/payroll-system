<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousingSample extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'location_codes',
        'source',
        'url',
        'price_type',
        'house_type',
        'price',
        'currency',
        'bedrooms',
        'bathrooms',
        'size',
        'size_units',
        'address',
        'housing_codes',
    ];
}
