<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'message',
        'is_admin'
    ];
    public static function countMessages()
    {
        return ChatRoom::where(['is_admin' => "0"])->count();
    }
}
