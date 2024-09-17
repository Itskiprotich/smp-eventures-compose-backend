<?php

namespace App\Models;

use App\Models\Courses\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralComment extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'student_id',
        'course_id',
        'user_id',
        'comment',
        'type',
        'status'
    ];

    public function course()
    { 
        return $this->belongsTo(Course::class);
    }
    public function student()
    { 
        return $this->belongsTo(Student::class);
    }
}
