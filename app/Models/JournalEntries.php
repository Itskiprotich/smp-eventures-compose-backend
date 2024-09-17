<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntries extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference',
        'amount',
        'debit_account',
        'credit_account',
        'trans_date',
        'narration',
        'loan_type',
        'payment_ref',
        'name',
        'phone'
        
    ];
 
}
