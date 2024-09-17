<?php

namespace App\Http\Controllers\Blogs;

use App\Http\Controllers\Controller;
use App\Models\BlogCategories;
use App\Models\Blogs;
use App\Models\BlogsComments;
use App\Models\CourseDiscounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list_blogs()
    {
        $data['blogs'] = Blogs::join('users', 'blogs.user_id', '=', 'users.id')
            ->leftJoin('blog_categories', 'blogs.blog_categories_id', '=', 'blog_categories.id')
            ->orderBy('blogs.created_at', 'desc')
            ->get(['blogs.*', 'users.name as username', 'blog_categories.title as category_name']);
        return view('blogs.index', $data);
    }
    public function blogs_per_category($id)
    {
        $data['blogs'] = Blogs::join('users', 'blogs.user_id', '=', 'users.id')
            ->leftJoin('blog_categories', 'blogs.blog_categories_id', '=', 'blog_categories.id')
            ->orderBy('blogs.created_at', 'desc')
            ->where(['blogs.blog_categories_id' => $id])
            ->get(['blogs.*', 'users.name as username', 'blog_categories.title as category_name']);
        return view('blogs.index', $data);
    }



    public function new_blog()
    {
        $data['categories'] = BlogCategories::list_active_categories();
        return view('blogs.add', $data);
    }
    public function edit_blog()
    {
        $data['blog'] = Blogs::join('users', 'blogs.user_id', '=', 'users.id')
            ->leftJoin('blog_categories', 'blogs.blog_categories_id', '=', 'blog_categories.id')
            ->orderBy('blogs.created_at', 'desc')
            ->first(['blogs.*', 'users.name as username', 'blog_categories.title as category_name', 'blog_categories.id as category_id']);
        $data['blogs'] = Blogs::join('users', 'blogs.user_id', '=', 'users.id')
            ->leftJoin('blog_categories', 'blogs.blog_categories_id', '=', 'blog_categories.id')
            ->orderBy('blogs.created_at', 'desc')
            ->get(['blogs.*', 'users.name as username', 'blog_categories.title as category_name', 'blog_categories.id as category_id']);

        $data['categories'] = BlogCategories::list_active_categories();
        return view('blogs.edit', $data);
    }
    public function view_blog($id)
    {
        $comments = [];
        $blog = Blogs::join('users', 'blogs.user_id', '=', 'users.id')
            ->leftJoin('blog_categories', 'blogs.blog_categories_id', '=', 'blog_categories.id')
            ->orderBy('blogs.created_at', 'desc')
            ->where(['blogs.id' => $id])
            ->first(['blogs.*', 'users.name as username', 'blog_categories.title as category_name', 'blog_categories.id as category_id']);

        if ($blog) {

            //get the blog comments
            $all = $blog->comments(); //->where(['status'=>'active']);
            foreach ($all as $comment) {
                if ($comment['status'] == 'active') {
                    $comments[] = $comment;
                }
            }
        }

        $blogs = Blogs::join('users', 'blogs.user_id', '=', 'users.id')
            ->leftJoin('blog_categories', 'blogs.blog_categories_id', '=', 'blog_categories.id')
            ->orderBy('blogs.created_at', 'desc')
            ->get(['blogs.*', 'users.name as username', 'blog_categories.title as category_name', 'blog_categories.id as category_id']);

        $categories = BlogCategories::list_active_categories();

        $data['blog'] = $blog;
        $data['blogs'] = $blogs;
        $data['categories'] = $categories;
        $data['comments'] = $comments;
        return view('blogs.view', $data);
    }

    public function list_categories()
    {
        $data['categories'] = BlogCategories::list_active_categories();

        return view('blogs.categories', $data);
    }

    public function edit_category($id)
    {
        $data['categories'] = BlogCategories::list_active_categories();
        $data['category'] = BlogCategories::categoryById($id);
        return view('blogs.categoriesedit', $data);
    }
 
    public function list_comments()
    {
        $comments = BlogsComments::join('blogs', 'blogs_comments.blogs_id', '=', 'blogs.id')
            ->leftJoin('users', 'blogs_comments.user_id', '=', 'users.id')
            ->orderBy('blogs_comments.created_at', 'desc')
            ->get(['blogs_comments.*', 'users.name as username', 'blogs.title as blog_name', 'blogs.id as blog_id']);


        $data['comments'] = $comments; //BlogsComments::list_all_comments();
        return view('blogs.comments', $data);
    }
    public function new_comment(Request $request, $id)
    {
        $attr = $request->validate([
            'comment' => 'required|string',
        ]);
        $comment = $attr['comment'];
        $data = BlogsComments::create([
            'blogs_id' => $id,
            'user_id' => Auth::user()->id,
            'comment' => $comment,
            'backend' => true,
        ]);
        if ($data) {

            return redirect()->route('view_blog', $id)->with('success', 'Category created successfully');
        } else {

            return redirect()->route('view_blog', $id)->with('error', 'Failed to create category');
        }
    }
    public function approve_comment(Request $request, $id)
    {
        $data = BlogsComments::find($id);
        if ($data) {
            $data->status = "active";
            $data->save();
            return redirect()->route('list_comments')->with('success', 'Comment Successfully Approved');
        } else {

            return redirect()->route('list_comments')->with('error', 'Failed to Approve Comment');
        }
    }
    public function reject_comment(Request $request, $id)
    {
        $data = BlogsComments::find($id);
        if ($data) {
            $data->deleted = true;
            $data->deleted_at = date('Y-m-d H:i:s');
            $data->save();
            return redirect()->route('list_comments')->with('success', 'Comment Successfully Rejected');
        } else {

            return redirect()->route('list_comments')->with('error', 'Failed to Reject Comment');
        }
    }
    public function new_category(Request $request)
    {
        $attr = $request->validate([
            'title' => 'required|string|unique:blog_categories,title',
        ]);
        $title = $attr['title'];
        $data = BlogCategories::create([
            'title' => $title,
            'slug' => $title,
        ]);
        if ($data) {

            return redirect()->route('list_categories')->with('success', 'Category created successfully');
        } else {

            return redirect()->route('list_categories')->with('error', 'Failed to create category');
        }
    }

    public function update_category(Request $request, $id)
    {
        $attr = $request->validate([
            'title' => 'required|string',
        ]);
        $title = $attr['title'];
        $data = BlogCategories::find($id);

        if ($data) {
            $data->title = $title;
            $data->slug = $title;
            $data->save();

            return redirect()->route('list_categories')->with('success', 'Category updated successfully');
        } else {

            return redirect()->route('list_categories')->with('error', 'Failed to update category');
        }
    }
    public function generate_file_name($file)
    {
        $filename =  $file->getClientOriginalName();
        $location = 'uploads';

        $file->move($location, $filename);
        $profile = public_path($location . "/" . $filename);
        $profile = substr($profile, strrpos($profile, '/') + 1);

        return $profile;
    }

    public function register_blog(Request $request)
    {
        $attr = $request->validate([
            'title' => 'required|string|unique:blogs,title',
            'description' => 'required|string',
            'content' => 'required|string',
            'enable_comment' => 'required|string',
            'category_id' => 'required|integer|exists:blog_categories,id',
            'publish' => 'required|string'
        ]);
        $title = $attr['title'];
        // if enable_comment is Yes the update variable to true else false
        $enable = $attr['enable_comment'] === 'Yes' ? true : false;
        $status = $attr['publish'] === 'Yes' ? 'publish' : 'pending';
        $profile = 'icon.png';
        if ($request->hasFile('image')) {
            // profile
            $profile = $request->file('image');
            $profile = $this->generate_file_name($profile);
        }
        $data = Blogs::create([
            'title' => $title,
            'slug' => $title,
            'image' => $profile,
            'user_id' => Auth::user()->id,
            'blog_categories_id' => $attr['category_id'],
            'description' => $attr['description'],
            'content' => $attr['content'],
            'enable_comment' => $enable,
            'status' => $status,
        ]);
        if ($data) {

            return redirect()->route('list_blogs')->with('success', 'Blog post successfully created');
        } else {
            return redirect()->route('list_blogs')->with('error', 'Failed to create a blog');
        }
    }
    public function update_blog(Request $request, $id)
    {
        $attr = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'content' => 'required|string',
            'enable_comment' => 'required|string',
            'category_id' => 'required|integer|exists:blog_categories,id',
            'publish' => 'required|string'
        ]);
        $title = $attr['title'];
        // if enable_comment is Yes the update variable to true else false
        $enable = $attr['enable_comment'] === 'Yes' ? true : false;
        $status = $attr['publish'] === 'Yes' ? 'publish' : 'pending';
        $profile = 'icon.png';
        if ($request->hasFile('image')) {
            // profile
            $profile = $request->file('image');
            $profile = $this->generate_file_name($profile);
        }
        $blog = Blogs::find($id);

        if (!$blog) {
            return redirect()->route('list_blogs')->with('error', 'Failed to update the blog');
        }


        $blog->title = $title;
        $blog->slug = $title;
        $blog->image = $profile;
        $blog->user_id = Auth::user()->id;
        $blog->blog_categories_id = $attr['category_id'];
        $blog->description = $attr['description'];
        $blog->content = $attr['content'];
        $blog->enable_comment = $enable;
        $blog->status = $status;
        $save = $blog->save();
        if ($save) {

            return redirect()->route('list_blogs')->with('success', 'Blog post successfully updated');
        } else {
            return redirect()->route('list_blogs')->with('error', 'Failed to update the blog');
        }
    }
}
