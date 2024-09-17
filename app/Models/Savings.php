<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Savings extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone', 'reference', 'product', 'amount', 'total', 'withdrawal', 'synced'
    ];
}