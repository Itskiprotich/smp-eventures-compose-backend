<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountBalance extends Model
{
    use HasFactory;
    protected $fillable = [
        'paybill',
        'bulk',
        'sms',
        'status','branch_id'
    ];
}
