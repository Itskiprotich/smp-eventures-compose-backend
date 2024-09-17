<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkStatement extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference',
        'action_by',
        'approved_by',
        'amount',
        'balance',
        'narration',
        'status','branch_id'
    ];
}
