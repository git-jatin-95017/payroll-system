<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
	use HasApiTokens, HasFactory, Notifiable;

	protected $table = 'employee_profile';

	public $timestamps = false;
	
	protected $appends = ['full_name'];
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $guarded = [];
	
	public function getFullNameAttribute() // notice that the attribute name is in CamelCase.
	{
	    return $this->first_name . ' ' . $this->last_name;
	}
}
