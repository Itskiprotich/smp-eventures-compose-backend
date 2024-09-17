<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repayments extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone',
        'loan_ref',
        'date_paid',
        'initiator',
        'reference',
        'paid_amount',
        'balance','branch_id'
        
    ];
}
