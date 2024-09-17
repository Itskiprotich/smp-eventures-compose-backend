<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartsofAcc extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_no', 
        'chart_name',
        'product_id',
        'category_id',
        'sub_category_id'
    ];

}
