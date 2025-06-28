<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'title', 'start_date', 'end_date', 'description'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
