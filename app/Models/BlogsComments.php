<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogsComments extends Model
{
    use HasFactory;

    protected $fillable=[
        'blogs_id','user_id','reply_id','comment','backend','deleted'
    ];

    //get all comments

    public static function list_all_comments()
    {
        return BlogsComments::all();
    }


}
