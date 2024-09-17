<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'title',
        'body'
    ];

    // public function getCreatedAtAttribute($date)
    // {
    //    // return Carbon::createFromFormat('Y-m-d H:i:s', $date);//->format('Y-m-d');
    // }

    // public function getUpdatedAtAttribute($date)
    // {
    //    // return Carbon::createFromFormat('Y-m-d H:i:s', $date);//->format('Y-m-d');
    // }
}
