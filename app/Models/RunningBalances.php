<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RunningBalances extends Model
{
    use HasFactory;
    protected $fillable = [
        'month', 
        'amount'
    ];
}
