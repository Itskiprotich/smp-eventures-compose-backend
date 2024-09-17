<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kopo extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone', 'amount', 'initiator'
    ];
}