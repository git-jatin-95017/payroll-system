<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalUnPaid extends Model
{
    use  HasFactory;

    protected $table = 'additional_unpaids';

    public $timestamps = false;
        
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    
    public function leaveType() {
        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'id');
    }

    public function leaveBalance() {
        return $this->hasOne(LeaveBalance::class, 'leave_type_id', 'leave_type_id')
            ->where('user_id', $this->user_id);
    }
}
