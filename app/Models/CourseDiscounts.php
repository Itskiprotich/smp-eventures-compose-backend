<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDiscounts extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount', 'coupon', 'is_percentage', 'title', 'usable_times', 'expiry_time'
    ];



    public static function list_discounts()
    {
        return CourseDiscounts::where(['deleted' => false])->orderBy('id', 'DESC')->get();
    }
}
