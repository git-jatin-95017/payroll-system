<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollAmount extends Model
{
    use  HasFactory;

    // public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    public function additionalEarnings() {
        return $this->hasMany(AdditionalEarning::class, 'payroll_amount_id');
    }

    public function additionalPaids() {
        return $this->hasMany(AdditionalPaid::class, 'payroll_amount_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
