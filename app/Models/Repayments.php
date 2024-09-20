<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repayments extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone',
        'loan_ref',
        'date_paid',
        'initiator',
        'reference',
        'paid_amount',
        'balance','branch_id'
        
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
