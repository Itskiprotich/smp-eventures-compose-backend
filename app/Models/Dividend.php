<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dividend extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone', 'reference', 'amount', 'paid','year','available'
    ];

    public function getamountAttribute($value)
    {
        return $value !== null ? $value : 0;
    }
}