<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsCodeSample extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
       'item_codes',
       'master_item_codes',
       'final_item',
       'component_items',
       'category',
       'store_type',
       'details',
       'standard_amounts',
       'standard_units',
       'unit_type',
    ];
    
    public function getCreatedAtAttribute($date){
        return date('Y-m-d H:i:s', strtotime($date));
    }
    
    public function getUpdatedAtAttribute($date)
    {
        return date('Y-m-d H:i:s', strtotime($date));
    }
}
