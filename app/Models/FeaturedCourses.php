<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedCourses extends Model
{
    use HasFactory;
    protected $fillable=[
        'course_id'
    ];

    public static function list_all()
    {
        return FeaturedCourses::where(['deleted' => false])->get();
    }
}
