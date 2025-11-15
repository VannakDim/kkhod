<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'video_url',
        'file_attachments',
        'duration',
        'order',
        'is_preview',
        'course_id'
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Helpers
    public function getVideoUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function getFileAttachmentsAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        
        return "{$minutes}m";
    }
}