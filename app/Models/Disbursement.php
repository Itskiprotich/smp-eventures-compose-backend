<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_ref', 
        'loan_code',
        'amount',
        'trans_code',
        'phone','branch_id'
    ];

 
}
