<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FloatStatements extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone', 'reference','description','amount','total'
    ];
}
