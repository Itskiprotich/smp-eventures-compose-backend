<?php

namespace App\Models\Courses;

use App\Models\CourseCategory;
use App\Models\CourseChapters;
use App\Models\CourseFaq;
use App\Models\CourseReview;
use App\Models\GeneralComment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'teacher_id',
        'creator_user_id',
        'course_type_id', 'capacity', 'price', 'status',
        'course_category_id', 'start_date', 'end_date', 'image_cover', 'video_demo',

    ];

    public function chapters()
    {
        return $this->hasMany(CourseChapters::class);
    }
    public function faqs()
    {
        return $this->hasMany(CourseFaq::class);
    }
    public function reviews()
    {
        return $this->hasMany(CourseReview::class);
    }
    public function comments()
    {
        return $this->hasMany(GeneralComment::class);
    }
    public function category()
    {
        return $this->belongsTo(CourseCategory::class);
    }
    // public function teacher()
    // {
    //     # code...
    //     // return $this->belongsTo('App\User', 'teacher_id', 'id');

    //     return $this->belongsTo(User::class, 'teacher_id', 'id');
    // }
    // public function creator()
    // {
    //     # code...
    //     // return $this->belongsTo('App\User', 'creator_user_id', 'id');

    //     return $this->belongsTo(User::class, 'creator_user_id', 'id');
    // }
    // public function type()
    // {
    //     # code...
    //     return $this->belongsTo('App\User', 'creator_user_id', 'id');
    // }

}
