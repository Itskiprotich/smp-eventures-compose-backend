<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseType extends Model
{
    use HasFactory;
    //  

   protected $fillable=['title'];

//    create  a new course type
    public static function create($title)
    {
        $courseType = new self;
        $courseType->title = $title;
        $courseType->save();
        return $courseType;
    }

    //list all not deleted
    public static function list()
    {
        # code..
        return CourseType::where(['deleted' => false])->orderBy('id','DESC')->get();
    }
}
