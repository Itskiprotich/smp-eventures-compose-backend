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