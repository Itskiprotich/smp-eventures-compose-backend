<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LockBuy extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone', 'reference', 'amount', 'balance', 'owner'
    ];
}
