<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overpayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone',
        'loan_ref',
        'amount',
        
    ];
}
