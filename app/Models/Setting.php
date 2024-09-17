<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_email',
        'code',
        'days',
        'saving_rate',
        'system_rate',
        'developer_rate',
        'investor_rate',
        'status',
    ];
}