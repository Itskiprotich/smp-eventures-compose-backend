<?php

namespace App\Models;

use App\Models\Courses\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_quality',
        'description',
        'instructor_skills',
        'purchase_worth',
        'reason',
        'support_quality',
        'student_id',
        'course_id',
    ];

    public function course()
    { 
        return $this->belongsTo(Course::class);
    }
}
