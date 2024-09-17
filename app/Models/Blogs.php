<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'blog_categories_id',
        'title',
        'slug',
        'image',
        'description',
        'content',
        'visit_count',
        'enable_comment',
        'status',
        'deleted'
    ]; 
    // get all the blog 
    public static function getBlogs()
    {
        return Blogs::where(['deleted'=>false])->get();
    }

    public static function getBlogsByCategory($cat)
    {
        # code...
        return Blogs::where(['deleted'=>false,'blog_categories_id'=>$cat])->get();
    }
    // has maby comments
    public function categories()
    { 
        return $this->belongsTo(BlogCategories::class);
    }
    public function user()
    { 
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(BlogsComments::class)->orderBy('created_at', 'DESC')->get();
    }
}
