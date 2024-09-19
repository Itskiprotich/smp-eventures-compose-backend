<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawals extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone',
        'reference',
        'action_by', 'approved_by', 'amount', 'balance', 'narration', 'mode', 'status'
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
