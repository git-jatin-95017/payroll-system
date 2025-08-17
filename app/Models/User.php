<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone_number',
        'user_code',
        'kiosk_code',
        'is_proifle_edit_access',
        'logo',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['created_at_formatted', 'updated_at_formatted'];

    public function companyProfile() {
        return $this->hasOne(CompanyProfile::class);
    }

    public function employeeProfile() {
        return $this->hasOne(EmployeeProfile::class);
    }

    public function paymentProfile() {
        return $this->hasOne(PaymentDetail::class);
    }

    public function getCreatedAtFormattedAttribute() {
        return !empty($this->attributes['created_at']) ? date('Y-m-d H:i:s', strtotime($this->attributes['created_at'])) : NULL;
    }
    
    public function getUpdatedAtFormattedAttribute()
    {
        return !empty($this->attributes['created_at']) ? date('Y-m-d H:i:s', strtotime($this->attributes['created_at'])) : NULL;
    }

    public function payrollSheet() {
        return $this->hasMany(PayrollSheet::class, 'emp_id');
    }

    public function departments() {
        return $this->hasMany(EmpDepartment::class, 'user_id');
    }

    public function payheads() {
        return $this->hasMany(Paystructure::class, 'user_id');
    }

    public function leavePolicies() {
        return $this->hasMany(EmpLeavePolicy::class, 'user_id');
    }

    public static function boot()
    {
        parent::boot();

        // Event listener for creating a new user
        static::creating(function ($model) {
            // Only set created_by if it's not already set
            // This allows controllers to manually set created_by when needed
            if (!isset($model->created_by) || empty($model->created_by)) {
                $model->created_by = auth()->user()->id;
            }
        });
    }
}
