<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSavings extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone', 
        'amount',
        'name', 
        'share_capital',
        'welfare','branch_id'
    ];
 
}
