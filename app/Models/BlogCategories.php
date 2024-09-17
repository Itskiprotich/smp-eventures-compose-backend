<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategories extends Model
{
    use HasFactory;

    protected $table = 'blog_categories';
    protected $fillable = [
        'title',
        'slug',
    ];
    public function blogs()
    {
        return $this->hasMany(Blogs::class)->orderBy('created_at', 'DESC');
    }

    public static function list_active_categories()
    {
        return BlogCategories::orderBy('id', 'asc')->get();
    }

    //get category by id
    public static  function categoryById($id)
    {
        return BlogCategories::where(['id' => $id])->first();
    }
}
