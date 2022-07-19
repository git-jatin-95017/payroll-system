<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenditureSample extends Model
{
	use HasFactory;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'country',
		'currency',
		'type',
		'num_of_adult',
		'num_of_child',
		'coefficient_a',
		'coefficient_b',
	];
	
}
