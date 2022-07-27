<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'location_codes',
        'city',
        'province',
        'postal_code',
        'city_province',
        'country',
        'country_code',
        'province_code',
        'metropolitan_codes',
        'sub_metropolitan_codes',
        'region',
        'iso_3166_alpha_2',
        'iso_3166_alpha_3',
        'iso_4217_currency_name',
        'iso_4217_alphabetic_Codes',
        'iso_4217_numeric_Codes',
        'tax_codes',
        'city_province_country',
    ];

    public function getCreatedAtAttribute($date){
        return date('Y-m-d H:i:s', strtotime($date));
    }
    
    public function getUpdatedAtAttribute($date)
    {
        return date('Y-m-d H:i:s', strtotime($date));
    }
}
