<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'outline',
        'thumbnail',
        'price',
        'is_published',
        'user_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class)->orderBy('order');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledUsers()
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }

    // Helpers
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : asset('images/default-course.jpg');
    }

    public function getTotalDurationAttribute()
    {
        return $this->episodes->sum('duration');
    }

    public function getEpisodesCountAttribute()
    {
        return $this->episodes->count();
    }
}