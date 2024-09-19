<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanTypes extends Model
{
    use HasFactory;
    protected $fillable = [
        'loan_code',
        'loan_name',
        'duration',  
        'min_limit',
        'max_limit',
        'admin_fee',
        'interest_rate'

    ];
      //return human readable date for created_at
   public function getCreatedAtAttribute($value)
   {
       return date('d-m-Y H:m:i', strtotime($value));
   }

   //return human readable date for updated_at
   public function getUpdatedAtAttribute($value)
   {
       return date('d-m-Y H:m:i', strtotime($value));
   }
}
