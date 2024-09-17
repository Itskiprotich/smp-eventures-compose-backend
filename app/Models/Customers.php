<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;
    protected $fillable = [
        'firstname',
        'lastname',
        'phone',
        'devicename',
        'device_id',
        'type',
        'email',
        'password',
        'membership_no',
        'national_id',
        'gender',
        'loanlimit','branch_id'
    ];

    public static function getApprovedCustomers()
    {
        # code...

        return Customers::where(['status' => 'Approved'])->orderBy('id', 'asc')->get();
    }
}
