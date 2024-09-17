<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'date', 'duration', 'moderator_secret'];
    public function chapter()
    {
        return $this->belongsTo(CourseChapters::class);
    }
}
