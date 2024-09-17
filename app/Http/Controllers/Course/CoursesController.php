<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Traits\CommonTrait;
use App\Models\Admins;
use App\Models\CourseCategory;
use App\Models\CourseChapters;
use App\Models\Courses\Course;
use App\Models\DiscountedCourses;
use App\Models\FeaturedCourses;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    //

    use CommonTrait;

    /**
     * Create a new controller instance.
     *
     * /
     */

    public function config(Request $request)
    {
        # code...
        $inactive = [];
        $active = [];
        for ($i = 0; $i < 4; $i++) {
            $inactive[] = [
                "id" => 1,
                "title" => "Alipay",
                "class_name" => "Alipay",
                "status" => "inactive",
                "image" => "/store/1/default_images/payment gateways/alipay.png",
                "settings" => "",
                "created_at" => "1654755044"
            ];

            $active[] = [
                "id" => 19,
                "title" => "Paypal",
                "class_name" => "Paypal",
                "status" => "active",
                "image" => "/store/1/default_images/payment gateways/paypal.png",
                "settings" => "",
                "created_at" => "1654755044"
            ];
        }

        return response()->json([
            "register_method" => "email",
            "offline_bank_account" => [
                "Qatar National Bank",
                "State Bank of India",
                "JPMorgan"
            ],
            "user_language" => [
                "AR" => "Arabic",
                "EN" => "English",
                "ES" => "Spanish"
            ],
            "payment_channels" => [
                "inactive" => $inactive,
                "active" => $active
            ],
            "minimum_payout_amount" => "50",
            "currency" => [
                "sign" => "KES ",
                "name" => "Kenyan Shilling",
            ],
            "price_display" => "only_price",
            "currency_position" => "left"
        ], 200);
    }

    public function featured_courses(Request $request)
    {
        # code...
        $ids = FeaturedCourses::list_all()->pluck('course_id'); //->toArray();
        $courses = Course::whereIn('id', $ids)
            ->orderBy('id', 'DESC')
            ->get();
        $total = 0;
        $filtered_courses = [];
        if ($courses) {
            foreach ($courses as $course) {
                $total++;
                $base_url = env('APP_URL', '') . "/uploads/";
                $image = "{$base_url}{$course->image_cover}";

                $teacher = Admins::join('users', 'admins.email', '=', 'users.email')
                    ->where(['admins.id' => $course->teacher_id])
                    ->first(['admins.id', 'users.name as full_name', 'admins.role_name', 'admins.bio', 'admins.offline', 'admins.offline_message', 'admins.address', 'admins.verified', 'admins.avatar', 'admins.meeting_status']);
                if ($teacher) {
                    $avatar = "{$base_url}{$teacher->avatar}";
                    $teacher['avatar'] = $avatar;
                    $teacher['rate'] = "3.42";
                    $teacher['meeting_status'] = $teacher->meeting_status == "1" ? "Available" : "Unavailable";
                }
                $filtered_courses[] = [
                    "image" => $image,
                    "auth" => false,
                    "can" => [
                        "view" => true
                    ],
                    "can_view_error" => null,
                    "id" => $course->id,
                    "status" => "active",
                    "label" => "Course",
                    "title" => $course->title,
                    "type" => "course",
                    "link" => $image,
                    "access_days" => 365,
                    "live_webinar_status" => null,
                    "auth_has_bought" => null,
                    "sales" => [
                        "count" => 3,
                        "amount" => 0
                    ],
                    "is_favorite" => false,
                    "price_string" => null,
                    "best_ticket_string" => null,
                    "price" => $course->price,
                    "tax" => 0,
                    "tax_with_discount" => 0,
                    "best_ticket_price" => $course->price,
                    "discount_percent" => 0,
                    "course_page_tax" => 0,
                    "price_with_discount" => $course->price,
                    "discount_amount" => 0,
                    "active_special_offer" => null,
                    "duration" => 90,
                    "teacher" => $teacher,
                    "students_count" => 3,
                    "rate" => "4.00",
                    "rate_type" => [
                        "content_quality" => 4,
                        "instructor_skills" => 4,
                        "purchase_worth" => 4,
                        "support_quality" => 4
                    ],
                    "created_at" => 1655799277,
                    "start_date" => null,
                    "purchased_at" => null,
                    "reviews_count" => 1,
                    "points" => null,
                    "progress" => null,
                    "progress_percent" => null,
                    "category" => "Language",
                    "capacity" => null
                ];
            }
        }
        $data = $filtered_courses;
        return $this->successResponse("success", $data);
    }
    public function all_courses(Request $request)
    {
        $offset = $request->get('offset');
        $limit = $request->get('limit');
        $sort = $request->get('sort');
        // conditions
        $free = $request->get('free');
        $discount = $request->get('discount');

        //check if $offset and $limit are set in the request
        if (isset($offset) && isset($limit)) {
            if (isset($sort)) {
                if ($sort == "newest") {
                }
                if ($sort == "bestsellers") {
                }
                if ($sort == "best_rates") {
                }
                $courses = Course::orderBy('id', 'DESC')
                    ->take($limit)
                    ->skip($offset)
                    ->get();
                return $this->refined_courses($courses);
            }
            if (isset($free)) {

                $courses = Course::where(['price' => 0])
                    ->orderBy('id', 'DESC')
                    ->take($limit)
                    ->skip($offset)
                    ->get();
                return $this->refined_courses($courses);
            }

            if (isset($discount)) {
                // $ids = [];
                $ids = DiscountedCourses::list_all()->pluck('course_id'); //->toArray();

                $courses = Course::whereIn('id', $ids)
                    ->orderBy('id', 'DESC')
                    ->take($limit)
                    ->skip($offset)
                    ->get();
                return $this->refined_courses($courses);
            }
            $courses = Course::orderBy('id', 'DESC')
                ->take($limit)
                ->skip($offset)
                ->get();

            // foreach ($courses as $course) {
            //     $newcourse = $course->replicate();
            //     $newcourse->price=rand(0,2500);
            //     $newcourse->save();
            // }
            return $this->refined_courses($courses);
        }
    }

    public function refined_courses($courses)
    {
        # code...
        $total = 0;
        $filtered_courses = [];
        if ($courses) {
            foreach ($courses as $course) {
                $total++;
                $base_url = env('APP_URL', '') . "/uploads/";
                $image = "{$base_url}{$course->image_cover}";

                $teacher = Admins::join('users', 'admins.email', '=', 'users.email')
                    ->where(['admins.id' => $course->teacher_id])
                    ->first(['admins.id', 'users.name as full_name', 'admins.role_name', 'admins.bio', 'admins.offline', 'admins.offline_message', 'admins.address', 'admins.verified', 'admins.avatar', 'admins.meeting_status']);
                if ($teacher) {
                    $avatar = "{$base_url}{$teacher->avatar}";
                    $teacher['avatar'] = $avatar;
                    $teacher['rate'] = "3.42";
                    $teacher['meeting_status'] = $teacher->meeting_status == "1" ? "Available" : "Unavailable";
                }
                $filtered_courses[] = [
                    "image" => $image,
                    "auth" => false,
                    "can" => [
                        "view" => true
                    ],
                    "can_view_error" => null,
                    "id" => $course->id,
                    "status" => "active",
                    "label" => "Course",
                    "title" => $course->title,
                    "type" => "course",
                    "link" => $image,
                    "access_days" => 365,
                    "live_webinar_status" => null,
                    "auth_has_bought" => null,
                    "sales" => [
                        "count" => 3,
                        "amount" => 0
                    ],
                    "is_favorite" => false,
                    "price_string" => null,
                    "best_ticket_string" => null,
                    "price" => $course->price,
                    "tax" => 0,
                    "tax_with_discount" => 0,
                    "best_ticket_price" => $course->price,
                    "discount_percent" => 0,
                    "course_page_tax" => 0,
                    "price_with_discount" => $course->price,
                    "discount_amount" => 0,
                    "active_special_offer" => null,
                    "duration" => 90,
                    "teacher" => $teacher,
                    "students_count" => 3,
                    "rate" => "4.00",
                    "rate_type" => [
                        "content_quality" => 4,
                        "instructor_skills" => 4,
                        "purchase_worth" => 4,
                        "support_quality" => 4
                    ],
                    "created_at" => 1655799277,
                    "start_date" => null,
                    "purchased_at" => null,
                    "reviews_count" => 1,
                    "points" => null,
                    "progress" => null,
                    "progress_percent" => null,
                    "category" => "Language",
                    "capacity" => null
                ];
            }
        }
        $data = $filtered_courses;
        return $this->successResponse("success", $data);
    }

    public function generate_sessions($chap)
    {
        # code...
        $lesson = $chap->lesson;
        $sessions = [];
        if ($lesson) {
            foreach ($lesson as $less){ 
                $sessions[] = [
                    "id" => 76,
                    "title" => $less->title,
                    "auth_has_read" => null,
                    "user_has_access" => true,
                    "is_finished" => false,
                    "is_started" => true,
                    "status" => "active",
                    "order" => 1,
                    "moderator_secret" => null,
                    "date" => Carbon::parse($less->date)->timestamp,
                    "duration" => $less->duration,
                    "link" => null,
                    "join_link" => null,
                    "can_join" => true,
                    "session_api" => "agora",
                    "zoom_start_link" => null,
                    "api_secret" => null,
                    "description" => nl2br($less->description),
                    "created_at" => Carbon::parse($less->created_at)->timestamp,
                    "updated_at" => null,
                    "agora_settings " => "{\"chat\":true,\"record\":false}"
                ];
            }
        }

        return $sessions;
    }
    public function course_details(Request $request, $id)
    {
        # code...   
        $course = Course::where('id', $id)->first();
        // if ($course) {

        $base_url = env('APP_URL', '') . "/uploads/";
        $image = "{$base_url}{$course->image_cover}";

        $teacher = Admins::join('users', 'admins.email', '=', 'users.email')
            ->where(['admins.id' => $course->teacher_id])
            ->first(['admins.id', 'users.name as full_name', 'admins.role_name', 'admins.bio', 'admins.offline', 'admins.offline_message', 'admins.address', 'admins.verified', 'admins.avatar', 'admins.meeting_status']);
        if ($teacher) {
            $avatar = "{$base_url}{$teacher->avatar}";
            $teacher['avatar'] = $avatar;
            $teacher['rate'] = "3.42";
            $teacher['meeting_status'] = $teacher->meeting_status == "1" ? "Available" : "Unavailable";
        }

        $cat = CourseCategory::where(['id' => $course->course_category_id])->first();


        $chapters = $course->chapters;
        $session_chapters = [];
        if ($chapters) {
            foreach ($chapters as $chap) {
                $session_chapters[] = [
                    "id" => 30,
                    "title" => $chap->title,
                    "topics_count" => 3,
                    "duration" => "166666:45",
                    "status" => "active",
                    "order" => null,
                    "type" => null,
                    "created_at" => Carbon::parse($chap->created_at)->timestamp,
                    "textLessons" => [],
                    "sessions" => $this->generate_sessions($chap),
                    "files" => [],
                    "quizzes" => []
                ];
            }
        }
        //   return $chapters=$course->chapters();

        $data = [
            "image" => $image,
            "auth" => false,
            "can" => [
                "view" => true
            ],
            "can_view_error" => null,
            "id" => $id,
            "status" => "active",
            "label" => "Not Started",
            "title" => $course->title,
            "type" => "webinar",
            "link" =>  $image,
            "access_days" => 300,
            "live_webinar_status" => "not_conducted",
            "auth_has_bought" => true,
            "sales" => [
                "count" => 0,
                "amount" => 0
            ],
            "is_favorite" => true,
            "price_string" => $course->price,
            "best_ticket_string" => null,
            "price" => $course->price,
            "tax" => 0,
            "tax_with_discount" => 0,
            "best_ticket_price" => 0,
            "discount_percent" => 0,
            "course_page_tax" => 0,
            "price_with_discount" => $course->price,
            "discount_amount" => 0,
            "active_special_offer" => null,
            "duration" => 150,
            "teacher" => $teacher,
            "students_count" => 0,
            "rate" => "5.00",
            "rate_type" => [
                "content_quality" => 5,
                "instructor_skills" => 5,
                "purchase_worth" => 5,
                "support_quality" => 5
            ],
            "created_at" => $course->created_at->timestamp,
            "start_date" => Carbon::parse($course->start_date)->timestamp,
            "purchased_at" => Carbon::parse($course->start_date)->timestamp,
            "reviews_count" => 1,
            "points" => 200,
            "progress" => 10,
            "progress_percent" => 40,
            "category" => $cat->title,
            "capacity" => 10,
            "support" => true,
            "subscribe" => true,
            "description" => nl2br($course->description),
            "prerequisites" => [],
            "faqs" => $course->faqs,
            "comments" => [],
            "session_chapters" => $session_chapters,
            "sessions_without_chapter" => [],
            "sessions_count" => 3,
            "files_chapters" => [],
            "files_without_chapter" => [],
            "files_count" => 0,
            "text_lesson_chapters" => [],
            "text_lessons_without_chapter" => [],
            "text_lessons_count" => 0,
            "quizzes" => [],
            "quizzes_count" => 0,
            "certificate" => [],
            "auth_certificates" => [],
            "reviews" => [],
            "video_demo" => "null", // $course->video_demo, 
            "video_demo_source" => "null",
            "image_cover" => $image,
            "tickets" => [],
            "isDownloadable" => true,
            "teacher_is_offline" => false,
            "tags" => [],
            "auth_has_subscription" => true,
            "can_add_to_cart" => true,
            "can_buy_with_points" => true
        ];
        return $this->successResponse("success", $data);
        // } else {
        //     return $this->successResponse("success", []);
        // }
    }
}
