<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Traits\CommonTrait;
use App\Models\BlogCategories;
use App\Models\Blogs;
use App\Models\CourseCategory;
use App\Models\Courses\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    //
    use  CommonTrait;

    public function trend_categories()
    {
        $categories = CourseCategory::list()->take(4);
        if ($categories) {
            foreach ($categories as $category) {
                //replace the icon with full url
                $base_url = env("APP_URL", "");
                $icon = $category->icon;
                $base_url = "{$base_url}/uploads/{$icon}";
                $category['icon'] = $base_url;
                $category['color'] = $this->randomHex();
            }
        }
        $data = ([
            'count' => $categories->count(),
            'categories' => $categories,

        ]);
        return $this->successResponse("success", $data);
    }


    public function randomHex()
    {
        $chars = 'ABCDEF0123456789';
        $color = '#';
        for ($i = 0; $i < 6; $i++) {
            $color .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $color;
    }
    public function categories()
    { 
        $categories = CourseCategory::list();
        if ($categories) {
            foreach ($categories as $category) {
                //replace the icon with full url
                $base_url = env("APP_URL", "");
                $icon = $category->icon;
                $base_url = "{$base_url}/uploads/{$icon}";
                $category['icon'] = $base_url;
                $category['color'] = $this->randomHex();
                $category['sub_categories'] = [];
            }
        }
        $data = ([
            'count' => $categories->count(),
            'categories' => $categories,

        ]);
        return $this->successResponse("success", $data);
    }

    private function generate_sub_categories($i)
    {
        $sub_categories = [];
        for ($i = 0; $i < 3; $i++) {
            $sub_categories[] = [
                "id" => 601,
                "title" => "Math",
                "icon" => "https://app.rocket-soft.org/store/1/default_images/categories_icons/sub_categories/divide-square.png",
                "webinars_count" => 2
            ];
        }
        return $sub_categories;
    }

    public function blogs_categories(Request $request)
    {
        # code...
        $data = [];
        $cats = BlogCategories::list_active_categories();
        if ($cats) {
            foreach ($cats as $cat) {
                $data[] = [
                    'id' => $cat->id,
                    'title' => $cat->title,
                ];
            }
        }

        return $this->successResponse("success", $data);
    }


    public function blogs(Request $request)
    {
        $cat = $request->get('cat');
        // get the host url 
        $base_url = env('APP_URL', '') . "/uploads/";
        $data = [];
        if (isset($cat)) {
            $blogs =  Blogs::getBlogsByCategory($cat);
        } else {
            $blogs =  Blogs::getBlogs();
        }


        if ($blogs) {
            foreach ($blogs as $blog) {
                $title = "";

                $cat = BlogCategories::where(['id' => $blog->blog_categories_id])->first();
                if ($cat) {
                    $title = $cat->title;
                }

                $data[] = [
                    "id" => $blog->id,
                    "title" => $blog->title,
                    "image" => "{$base_url}{$blog->image}",
                    'description' => $blog->description,
                    'content' => $blog->content,
                    'created_at' => 1625094412, //$blog->created_at, 
                    'locale' => "EN",
                    'comment_count' => 0,
                    'comments' => $blog->comments(),
                    'category_id' => $blog->blog_categories_id,
                    'category' => $title,
                    "author" => [
                        "id" => 1,
                        "full_name" => "Admin",
                        "role_name" => "admin",
                        "bio" => "Senior software developer",
                        "offline" => 0,
                        "offline_message" => null,
                        "verified" => 1,
                        "rate" => 0,
                        "avatar" => "https://app.rocket-soft.org/store/1/default_images/logo-new.jpg",
                        "meeting_status" => "no",
                        "user_group" => null,
                        "address" => null
                    ],
                ];
            }
        }

        // for ($i = 0; $i < $total; $i++) {
        //     $data[] = [
        //         "id" => 23,
        //         "title" => "Become a Straight-A Student",
        //         "image" => "https://app.rocket-soft.org/store/1014/blog3.jpg",
        //         "description" => "<p>In this article, I\u2019ll explain the two rules I followed to become a straight-A student.\u00a0If you take my advice, you\u2019ll get better grades and lead a more ...",
        //         "content" => "<p>A strong academic record can open doors for you down the road. More importantly, through the process of becoming a straight-A student, you\u2019ll learn values like hard work, discipline and determination.</p><h3 style=\"color: rgb(0, 0, 0); font-family: Lato, sans-serif; margin-right: 0px; margin-bottom: 16px; margin-left: 0px; padding: 0px; font-size: 24px;\">Rule #1: Always have a plan.</h3><div><div>(a) As the semester progresses, keep track of key dates: tests and exams, project submission deadlines, term breaks, etc.</div><div><br></div><div>Enter these dates into a physical or digital calendar.</div><div><br></div><div><div>If you choose to use a digital calendar, I recommend Google Calendar.</div><div><br></div><div>(b) Schedule a fixed time every week where you review your upcoming events over the next two months. Mark down when you\u2019ll start preparing for that Math exam, working on that History project, or writing that English paper.</div><div><br></div><div>(d) Next, note your commitments for the coming week, e.g. extracurricular activities, family gatherings, extra classes. On your calendar, highlight the blocks of time you\u2019ll have for schoolwork.</div><div><br></div><div>This planning process might sound time-consuming, but it\u2019ll typically take just 15 minutes every week.</div><div><br></div><div>This is a wise investment of time as a student, because the rest of your week will become far more productive.</div><div><br></div><div>This way, you\u2019ll be studying smart, not just hard!</div><div><br></div><div><h3 style=\"color: rgb(0, 0, 0); font-family: Lato, sans-serif; margin-right: 0px; margin-bottom: 16px; margin-left: 0px; padding: 0px; font-size: 24px;\">Rule #2: Be organized.</h3></div></div></div><div><div>Ever had trouble finding your notes or assignments when you needed them? You probably ended up wasting precious time looking for them, before you finally asked to borrow them from your friend.</div><div><br></div><div>Many students tell me that they keep all their notes and assignments in one big pile, and only sort them out before their exams!</div><div><br></div><div>Being organized \u2013 it\u2019s easier said than done, I know.</div></div>",
        //         "created_at" => 1625094412,
        //         "locale" => "EN",
        //         "author" => $this->get_author($i),
        //         "comment_count" => 0, // $comment_count,
        //         "comments" => [], //$this->get_comments($i,$comment_count),
        //         "category" => "Events"
        //     ];
        // }
        return $this->successResponse("success", $data);
    }
    public function get_comments($i, $total)
    {
        for ($i = 0; $i < $total; $i++) {

            $data =   [
                "id" => 1,
                "full_name" => "Admin",
                "role_name" => "admin",
                "bio" => "Senior software developer",
                "offline" => 0,
                "offline_message" => null,
                "verified" => 1,
                "rate" => 0,
                "avatar" => "https://app.rocket-soft.org/store/1/default_images/logo-new.jpg",
                "meeting_status" => "no",
                "user_group" => null,
                "address" => null
            ];
        }
        return $data;
    }
    public function get_author($i)
    {
        $data =   [
            "id" => 1,
            "full_name" => "Admin",
            "role_name" => "admin",
            "bio" => "Senior software developer",
            "offline" => 0,
            "offline_message" => null,
            "verified" => 1,
            "rate" => 0,
            "avatar" => "https://app.rocket-soft.org/store/1/default_images/logo-new.jpg",
            "meeting_status" => "no",
            "user_group" => null,
            "address" => null
        ];
        return $data;
    }
}
