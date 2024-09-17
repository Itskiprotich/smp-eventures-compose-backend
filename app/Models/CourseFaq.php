<?php

namespace App\Models;

use App\Models\Courses\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseFaq extends Model
{
    use HasFactory;

    protected $fillable=['title', 'answer'];
    public function course()
    { 
        return $this->belongsTo(Course::class);
    }
}
