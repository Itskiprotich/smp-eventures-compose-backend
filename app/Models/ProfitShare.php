<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitShare extends Model
{
    use HasFactory;
    protected $fillable = [
        'year', 'month', 'name', 'phone', 'float_balance', 'ratio', 'earnings', 'status'
    ];
}