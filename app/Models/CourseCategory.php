<?php

namespace App\Models;

use App\Models\Courses\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','icon'
    ];
    
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    //list all the categories
    public static function list()
    {
        # code..
        return CourseCategory::where(['deleted' => false])->orderBy('id','DESC')->get();
    }


 
}
