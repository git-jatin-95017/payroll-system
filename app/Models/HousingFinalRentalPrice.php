<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousingFinalRentalPrice extends Model
{
    use HasFactory;

    protected $guarded = [];
    
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
