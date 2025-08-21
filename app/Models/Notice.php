<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

	public $timestamps = false;
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $guarded = [];

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
