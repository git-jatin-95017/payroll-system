<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Spatie\Sluggable\HasSlug;
// use Spatie\Sluggable\SlugOptions;

class Position extends Model
{
    use HasSlug;

    protected $fillable = [
        'title',
        'description',
        'slug',
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function setTitleAttribute($value){
        $this->attributes['title'] = ucwords($value);
    }

    public function getRouteKeyName()
    {
        return 'slug';
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
