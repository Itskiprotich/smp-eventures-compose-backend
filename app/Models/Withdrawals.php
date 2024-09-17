<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawals extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone',
        'reference',
        'action_by', 'approved_by', 'amount', 'balance', 'narration', 'mode', 'status'
    ];
}
