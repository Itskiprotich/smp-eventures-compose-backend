<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FCMTokens extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone', 
        'fcm_token'
    ];
}
 
