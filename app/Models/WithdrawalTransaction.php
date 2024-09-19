<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone', 
        'reference',
        'trans_id',
        'amount','branch_id'
    ];
      // Custom accessor for created_at in a specific format
      public function getCreatedAtFormattedAttribute()
      {
          return $this->created_at->format('Y-m-d H:i:s'); // Adjust format as needed
      }
  
      // Custom accessor for updated_at in a specific format
      public function getUpdatedAtFormattedAttribute()
      {
          return $this->updated_at->format('Y-m-d H:i:s'); // Adjust format as needed
      }
}
