<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'customers_id','email_address','password','activation_code'
    ];
 

    public static function createStudent($customer_id)
    {
        $student = new Student();
        $student->customers_id = $customer_id;
        $student->save();
        return $student;
    }
    // list all students
    public static function list()
    {
        $students = Student::orderBy('id', 'ASC')->get();;
        return $students;
    }
    //list active students

    public static function listActive()
    {
        $student = Student::where(['active' => true])->get();
        return $student;
    }
}
