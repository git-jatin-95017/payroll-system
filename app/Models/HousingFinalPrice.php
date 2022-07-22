<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousingFinalPrice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'location_codes',
        'housing_codes',
        'price_type',
        'house_type',
        'bedrooms',
        'price_level',
        'price',
        'currency',
    ];

    public function getCreatedAtAttribute($date){
        return date('Y-m-d H:i:s', strtotime($date));
    }
    
    public function getUpdatedAtAttribute($date)
    {
        return date('Y-m-d H:i:s', strtotime($date));
    }

}
