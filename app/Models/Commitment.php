<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commitment extends Model
{
    use HasFactory;  
    protected $fillable = [
        'phone', 
        'reference',
        'trans_code',
        'amount'
    ];
}
