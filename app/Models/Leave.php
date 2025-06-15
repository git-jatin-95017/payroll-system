<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
	use HasApiTokens, HasFactory, Notifiable;

	public $timestamps = false;
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $guarded = [];
	
	public function leaveType() {
        return $this->belongsTo(LeaveType::class, 'type_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function leaveBalance() {
        return $this->hasOne(LeaveBalance::class, 'leave_type_id', 'type_id')
            ->where('user_id', $this->user_id);
    }

    public static function boot()
    {
        parent::boot();

        // Event listener for creating a new department
        static::creating(function ($model) {
            // Set the created_by field to the current user's ID
            $model->created_by = auth()->user()->id;
        });
    }
}
