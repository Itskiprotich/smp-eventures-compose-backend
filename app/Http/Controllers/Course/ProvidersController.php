<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Traits\CommonTrait;
use App\Models\Admins;
use App\Models\Customers;
use App\Models\Student;
use Illuminate\Http\Request;

class ProvidersController extends Controller
{
    use  CommonTrait;

    public function view_instructors()
    {
        $total = 0;
        $users = [];
        $instructors = Admins::join('users', 'admins.email', '=', 'users.email')
            ->orderBy('admins.created_at', 'desc')
            ->where(['users.is_teacher' => true, 'admins.role_name' => 'Teacher'])
            ->get(['admins.*', 'users.name as username']);
        if ($instructors) {
            $total = $instructors->count();
            foreach ($instructors as $inst) {
                $base_url = env('APP_URL', '') . "/uploads/";
                $avatar = "{$base_url}{$inst->avatar}";
                $users[] = [
                    "id" => $inst->id,
                    "full_name" => $inst->username,
                    "role_name" => $inst->role_name,
                    "bio" => htmlspecialchars_decode($inst->bio),
                    "offline" => $inst->offline,
                    "offline_message" => $inst->offline_message,
                    "verified" => $inst->verified,
                    "rate" => "3.88",
                    "avatar" => $avatar,
                    "meeting_status" => $inst->meeting_status,
                    "user_group" => null,
                    // "user_group" => [
                    //     "id" => 2,
                    //     "name" => "Vip Instructors",
                    //     "status" => "active",
                    //     "commission" => 10,
                    //     "discount" => 20
                    // ],
                    "address" => $inst->address,
                ];
            }
        }

        $data = ([
            'count' => $total,
            'users' => $users,

        ]);
        return $this->successResponse("success", $data);
    }

    public function user_profile(Request $request, $id)
    {
        # code...
        $data = [];

        // Check from customers
             $user = Student::join('customers', 'customers.id', '=', 'students.customers_id')
            ->where(['students.id' => $id])
            ->first(['customers.*', 'students.*']);
        if ($user) { 
            $avatar = "{$user->photo}";

            $data = ([
                'count' => $id,
                'user' => [
                    "id" => $user->id,
                    "full_name" => "{$user->firstname} {$user->lastname}",
                    "role_name" => "student",
                    "bio" => null,
                    "offline" => 0,
                    "offline_message" => 0,
                    "verified" => 0,
                    "rate" => "3.88",
                    "avatar" => $avatar,
                    "meeting_status" => 0,
                    "user_group" => null,
                    "address" => null,
                    "status" => "active",
                    "email" => $user->email,
                    "mobile" => $user->phone,
                    "language" => "EN",
                    "newsletter" => false,
                    "public_message" => 0,
                    "active_subscription" => null,
                    "headline" => null,
                    "courses_count" => 0,
                    "reviews_count" => 0,
                    "appointments_count" => 0,
                    "students_count" => 0,
                    "followers_count" => 0,
                    "following_count" => 0,
                    "badges" => [],
                    "students" => [],
                    "followers" => [],
                    "following" => [],
                    "auth_user_is_follower" => false,
                    "referral" => null,
                    "education" => [
                        "Associate of Applied Business from Stanford University",
                        "Bachelor of Science in Business from Harvard University",
                        "Master of Computational Finance from University of Chicago"
                    ],
                    "experience" => [
                        "Five-time TED speaker"
                    ],
                    "occupations" => [
                        "Science",
                        "Marketing",
                        "Management",
                        "Business Strategy",
                        "Web Development"
                    ],
                    "about" => null,
                    "webinars" => [],
                    "meeting" => [
                        "time_zone" => "America/New_York",
                        "gmt" => "GMT -05=>00",
                        "id" => 34,
                        "disabled" => 1,
                        "discount" => null,
                        "price" => 50,
                        "price_with_discount" => 50,
                        "in_person" => 0,
                        "in_person_price" => 0,
                        "in_person_price_with_discount" => 0,
                        "in_person_group_min_student" => null,
                        "in_person_group_max_student" => null,
                        "in_person_group_amount " => null,
                        "group_meeting" => 0,
                        "online_group_min_student" => null,
                        "online_group_max_student" => null,
                        "online_group_amount" => null,
                        "timing" => [],
                        "timing_group_by_day" => [
                            "monday" => [],
                            "tuesday" => [],
                            "wednesday" => [],
                            "thursday" => []
                        ]
                    ],
                    "organization_teachers" => [],
                    "country_id" => 20,
                    "province_id" => 33,
                    "city_id" => 34,
                    "district_id" => 42,
                    "account_type" => "State Bank of India",
                    "iban" => "IN74BARC20032649126989",
                    "account_id" => "5234903165288",
                    "identity_scan" => "https://app.rocket-soft.org/store/3/passport.jpg",
                    "certificate" => null
                ]

            ]);
        }
        return $this->successResponse("success", $data);
    }
    public function instructor_profile(Request $request, $id)
    {
        # code...
        $data = [];
        $user = Admins::join('users', 'admins.email', '=', 'users.email')
            ->orderBy('admins.created_at', 'desc')
            ->where(['admins.id' => $id])
            ->first(['admins.*', 'users.name as username']);
        if ($user) {
            $base_url = env('APP_URL', '') . "/uploads/";
            $avatar = "{$base_url}{$user->avatar}";

            $data = ([
                'count' => $id,
                'user' => [
                    "id" => $user->id,
                    "full_name" => $user->username,
                    "role_name" => $user->role_name,
                    "bio" => htmlspecialchars_decode($user->bio),
                    "offline" => $user->offline,
                    "offline_message" => $user->offline_message,
                    "verified" => $user->verified,
                    "rate" => "3.88",
                    "avatar" => $avatar,
                    "meeting_status" => $user->meeting_status,
                    "user_group" => null,
                    "address" => $user->address,
                    "status" => "active",
                    "email" => $user->email,
                    "mobile" => $user->phone,
                    "language" => "EN",
                    "newsletter" => false,
                    "public_message" => 0,
                    "active_subscription" => null,
                    "headline" => null,
                    "courses_count" => 0,
                    "reviews_count" => 0,
                    "appointments_count" => 0,
                    "students_count" => 0,
                    "followers_count" => 0,
                    "following_count" => 0,
                    "badges" => [],
                    "students" => [],
                    "followers" => [],
                    "following" => [],
                    "auth_user_is_follower" => false,
                    "referral" => null,
                    "education" => [
                        "Associate of Applied Business from Stanford University",
                        "Bachelor of Science in Business from Harvard University",
                        "Master of Computational Finance from University of Chicago"
                    ],
                    "experience" => [
                        "Five-time TED speaker"
                    ],
                    "occupations" => [
                        "Science",
                        "Marketing",
                        "Management",
                        "Business Strategy",
                        "Web Development"
                    ],
                    "about" => $user->bio,
                    "webinars" => [],
                    "meeting" => [
                        "time_zone" => "America/New_York",
                        "gmt" => "GMT -05=>00",
                        "id" => 34,
                        "disabled" => 1,
                        "discount" => null,
                        "price" => 50,
                        "price_with_discount" => 50,
                        "in_person" => 0,
                        "in_person_price" => 0,
                        "in_person_price_with_discount" => 0,
                        "in_person_group_min_student" => null,
                        "in_person_group_max_student" => null,
                        "in_person_group_amount " => null,
                        "group_meeting" => 0,
                        "online_group_min_student" => null,
                        "online_group_max_student" => null,
                        "online_group_amount" => null,
                        "timing" => [],
                        "timing_group_by_day" => [
                            "monday" => [],
                            "tuesday" => [],
                            "wednesday" => [],
                            "thursday" => []
                        ]
                    ],
                    "organization_teachers" => [],
                    "country_id" => 20,
                    "province_id" => 33,
                    "city_id" => 34,
                    "district_id" => 42,
                    "account_type" => "State Bank of India",
                    "iban" => "IN74BARC20032649126989",
                    "account_id" => "5234903165288",
                    "identity_scan" => "https://app.rocket-soft.org/store/3/passport.jpg",
                    "certificate" => null
                ]

            ]);
        }

        return $this->successResponse("success", $data);
    }
    public function view_consultations()
    {
        $total = 0;
        $users = [];
        $instructors = Admins::join('users', 'admins.email', '=', 'users.email')
            ->orderBy('admins.created_at', 'desc')
            ->where(['users.is_teacher' => true, 'admins.role_name' => 'Consultant'])
            ->get(['admins.*', 'users.name as username']);
        if ($instructors) {
            $total = $instructors->count();
            foreach ($instructors as $inst) {
                $base_url = env('APP_URL', '') . "/uploads/";
                $avatar = "{$base_url}{$inst->avatar}";
                $users[] = [
                    "id" => $inst->id,
                    "full_name" => $inst->username,
                    "role_name" => $inst->role_name,
                    "bio" => htmlspecialchars_decode($inst->bio),
                    "offline" => $inst->offline,
                    "offline_message" => $inst->offline_message,
                    "verified" => $inst->verified,
                    "rate" => "3.88",
                    "avatar" => $avatar,
                    "meeting_status" => $inst->meeting_status,
                    "user_group" => null,
                    // "user_group" => [
                    //     "id" => 2,
                    //     "name" => "Vip Instructors",
                    //     "status" => "active",
                    //     "commission" => 10,
                    //     "discount" => 20
                    // ],
                    "address" => $inst->address,
                ];
            }
        }

        $data = ([
            'count' => $total,
            'users' => $users,

        ]);
        return $this->successResponse("success", $data);
    }
    public function view_organizations()
    {
        $total = 0;
        $users = [];
        $instructors = Admins::join('users', 'admins.email', '=', 'users.email')
            ->orderBy('admins.created_at', 'desc')
            ->where(['users.is_teacher' => true, 'admins.role_name' => 'Organization'])
            ->get(['admins.*', 'users.name as username']);
        if ($instructors) {
            $total = $instructors->count();
            foreach ($instructors as $inst) {
                $base_url = env('APP_URL', '') . "/uploads/";
                $avatar = "{$base_url}{$inst->avatar}";
                $users[] = [
                    "id" => $inst->id,
                    "full_name" => $inst->username,
                    "role_name" => $inst->role_name,
                    "bio" => htmlspecialchars_decode($inst->bio),
                    "offline" => $inst->offline,
                    "offline_message" => $inst->offline_message,
                    "verified" => $inst->verified,
                    "rate" => "3.88",
                    "avatar" => $avatar,
                    "meeting_status" => $inst->meeting_status,
                    "user_group" => null,
                    // "user_group" => [
                    //     "id" => 2,
                    //     "name" => "Vip Instructors",
                    //     "status" => "active",
                    //     "commission" => 10,
                    //     "discount" => 20
                    // ],
                    "address" => $inst->address,
                ];
            }
        }

        $data = ([
            'count' => $total,
            'users' => $users,

        ]);
        return $this->successResponse("success", $data);
    }

    public function quick_information(Request $request)
    {
        # code...
        return response()->json([
            "offline" => 0,
            "spent_points" => 0,
            "total_points" => 0,
            "available_points" => 0,
            "role_name" => "user",
            "full_name" => "Japheth Kiprotich",
            "financial_approval" => 0,
            "unread_notifications" => [
                "count" => 0,
                "notifications" => []
            ],
            "unread_noticeboards" => [],
            "balance" => 0,
            "can_drawable" => false,
            "badges" => [
                "next_badge" => "New User",
                "percent" => 100,
                "earned" => ""
            ],
            "count_cart_items" => 0,
            "pendingAppointments" => 0,
            "monthlySalesCount" => 0,
            "monthlyChart" => [
                "months" => [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec"
                ],
                "data" => [
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0
                ]
            ],
            "webinarsCount" => 0,
            "reserveMeetingsCount" => 0,
            "supportsCount" => 0,
            "commentsCount" => 0
        ], 200);
    }

    public function panel_meetings(Request $request)
    {
        # code...
        $data = ([
            'reservations' => array(
                "count" => 0,
                "meetings" => []

            ),
            'requests' => array(
                "count" => 0,
                "meetings" => []
            ),

        ]);
        return $this->successResponse("success", $data);
    }

    public function panel_comments(Request $request)
    {
        # code...
        $data = ([
            'my_comment' => array(
                "blogs" => [],
                "webinar" => []
            ),
            'class_comment' => []

        ]);
        return $this->successResponse("success", $data);
    }
    public function webinars_purchases()
    {
        # code...

        // "id": 1995,
        //         "image": "https://app.rocket-soft.org/store/1016/1.jpg",
        //         "image_cover": "https://app.rocket-soft.org/store/1016/1_c.jpg",
        //         "status": "active",
        //         "title": "Become a Product Manager",
        //         "can": {
        //             "view": true
        //         },
        //         "reviews_count": 3,
        //         "can_view_error": null,
        //         "type": "course",
        //         "link": "https://app.rocket-soft.org/course/Become-a-Product-Manager",
        //         "label": "Course",
        //         "progress": 0,
        //         "progress_percent": 0,
        //         "price": 0,
        //         "best_ticket": 0,
        //         "active_special_offer": null,
        //         "rate": "4.58",
        //         "access_days": null,
        //         "expired": false,
        //         "expire_on": null,
        //         "category": "Business Strategy",
        //         "sales_amount": "0.00",
        //         "sales_count": 5,
        //         "created_at": 1624867858,
        //         "purchased_at": 1673069698,
        //         "start_date": null,
        //         "duration": 150,
        //         "specification": {
        //             "duration": 150,
        //             "files_count": 4,
        //             "downloadable": true
        //         },
        //         "teacher": {
        //             "id": 1016,
        //             "full_name": "Ricardo dave",
        //             "role_name": null,
        //             "bio": null,
        //             "offline": null,
        //             "offline_message": null,
        //             "verified": null,
        //             "rate": "4.58",
        //             "avatar": "https://app.rocket-soft.org/getDefaultAvatar?item=1016&name=Ricardo dave&size=40",
        //             "meeting_status": "available",
        //             "user_group": {
        //                 "id": 2,
        //                 "name": "Vip Instructors",
        //                 "status": "active",
        //                 "commission": 10,
        //                 "discount": 20
        //             },
        //             "address": null
        //         },
        //         "capacity": null
        $data = ([
            'webinars' => [],

        ]);
        return $this->successResponse("success", $data);
    }
    public function webinars_organizations()
    {
        # code...

        $data = ([
            'webinars' => [],

        ]);
        return $this->successResponse("success", $data);
    }

    public function offline_payments(Request $request)
    {
        # code...

        $data = [];
        return $this->successResponse("success", $data);
    }
    public function  financial_summary(Request $request)
    {
        # code...
        $data = ([
            "balance" => 0,
            'history' => [],

        ]);
        return $this->successResponse("success", $data);
    }

    public function panel_subscribe(Request $request)
    {
        # code...
        $subscribes = [];
        for ($i = 0; $i < 4; $i++) {
            $subscribes[] = [
                "id" => 5,
                "title" => "Silver",
                "description" => "Suggested for small businesses",
                "usable_count" => 400,
                "days" => 30,
                "price" => 50,
                "is_popular" => 0,
                "image" => "https://app.rocket-soft.org/store/1/default_images/subscribe_packages/silver.png",
                "created_at" => 1635442132
            ];
        }


        $data = ([
            "subscribed" => false,
            "subscribe_id" => null,
            "subscribed_title" => null,
            "remained_downloads" => null,
            "days_remained" => null,
            "dayOfUse" => 0,
            'subscribes' => $subscribes,

        ]);
        return $this->successResponse("success", $data);
    }

    public function tickets_support(Request $request)
    {
        # code...

        $data = [];
        return $this->successResponse("success", $data);
    }
    public function class_support(Request $request)
    {
        # code...

        $data = [];
        return $this->successResponse("success", $data);
    }
    public function support_departments(Request $request)
    {
        # code...
        $data = [];
        $string = "Financial,Content,Marketing";
        $str_arr = explode(",", $string);
        $total = count($str_arr);
        for ($i = 0; $i < $total; $i++) {
            $data[] = array(
                "id" => $i + 1,
                "title" => $str_arr[$i]
            );
        }
        return response()->json($data);
    }
    public function panel_favorites(Request $request)
    {
        # code...
        $data = ([
            'favorites' => [],

        ]);
        return $this->successResponse("success", $data);
    }

    public function notifications(Request $request)
    {
        # code...
        $data = ([
            "count" => 0,
            'notifications' => [],

        ]);
        return $this->successResponse("success", $data);
    }

    public function quizzes_not_participated(Request $request)
    {
        # code...
        $data = ([
            'quizzes' => [],

        ]);
        return $this->successResponse("success", $data);
    }
    public function quizzes_my_results(Request $request)
    {
        # code...
        $data = ([
            'results' => [],

        ]);
        return $this->successResponse("success", $data);
    }

    public function cart_list(Request $request)
    {
        # code...
        $data = array('cart'=>null);
        return $this->successResponse("success", $data);
    }
}
