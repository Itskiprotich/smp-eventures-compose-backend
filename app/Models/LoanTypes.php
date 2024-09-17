<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanTypes extends Model
{
    use HasFactory;
    protected $fillable = [
        'loan_code',
        'loan_name',
        'duration',  
        'min_limit',
        'max_limit',
        'admin_fee',
        'interest_rate'

    ];
}
