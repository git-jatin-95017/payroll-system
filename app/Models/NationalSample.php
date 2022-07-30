<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalSample extends Model
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
        'product',
        'price',
        'currency',
        'website',
        'store',
        'notes',
        'amount',
        'units',
        'price_date'
    ];

    public function getPriceDateAttribute($date){
        return !empty($date) ? date('Y-m-d', strtotime($date)) : NULL;
    }
    
    public function getCreatedAtAttribute($date){
        return date('Y-m-d H:i:s', strtotime($date));
    }
    
    public function getUpdatedAtAttribute($date)
    {
        return date('Y-m-d H:i:s', strtotime($date));
    }
}
