<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsProducts extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name',
        'duration',
        'product_code',
        'min_limit',
        'max_limit',
        'product_group_id','branch_id'
    ];

   
}
