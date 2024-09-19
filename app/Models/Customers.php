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
