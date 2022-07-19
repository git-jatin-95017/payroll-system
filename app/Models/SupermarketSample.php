<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupermarketSample extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'location_codes',
        'item_codes',
        'item_id',
        'postal_code',
        'url',
        'name',
        'price',
        'currency',
        'source',
        'number_of_units',
        'final_units',
    ];
}
