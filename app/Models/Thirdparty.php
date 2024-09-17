<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thirdparty extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone', 'firstname', 'lastname', 'email_address','float_balance', 'interest_balance', 'pending_pool', 'approved_pool', 'status','branch_id'
    ];
}
