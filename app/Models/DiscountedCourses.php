<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountedCourses extends Model
{
    use HasFactory;

    protected $fillable=[
        'course_id','course_discounts_id'
    ];

    public static function list_all()
    {
        return DiscountedCourses::where(['deleted' => false])->get();
    }
}
