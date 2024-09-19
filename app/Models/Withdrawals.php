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
