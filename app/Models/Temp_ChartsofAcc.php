<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temp_ChartsofAcc extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_type', 
        'account_code', 
        'account_name', 
        'amount_cr', 
        'amount_dr', 
    ];
  
}
