<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admins extends Model
{
    use HasFactory;
    protected $fillable = [
        'email', 
        'password', 
        'phone', 
        'firstname', 
        'lastname',
        'usertype', 
        'status'
    ];
  
}
