<?php

namespace App\Models;

use App\Models\Courses\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseChapters extends Model
{
    use HasFactory;

    protected $fillable=['course_id','title','check_all_contents_pass'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function lesson()
    {
        return $this->hasMany(CourseLesson::class);
    }
}
