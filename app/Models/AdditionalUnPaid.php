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
    
    public function leaveTypes() {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }
}
