<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Blogs\BlogsController;
use App\Http\Controllers\Controller;
use App\Http\Traits\CommonTrait;
use App\Models\Admins;
use App\Models\CourseCategory;
use App\Models\CourseChapters;
use App\Models\CourseDiscounts;
use App\Models\CourseFaq;
use App\Models\CourseLesson;
use App\Models\Courses\Course;
use App\Models\CourseType;
use App\Models\Customers;
use App\Models\DiscountedCourses;
use App\Models\FeaturedCourses;
use App\Models\GeneralComment;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WebCoursesController extends Controller
{

    use CommonTrait;
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function learning_dashboard()
    {
        # code...
        $today = today();
        $tulipo = Carbon::createFromDate($today->year, $today->month)->format('d');
        $registrations = [];
        $dates = [];

        for ($i = 1; $i <= $tulipo; ++$i) {

            $day = Carbon::createFromDate($today->year, $today->month, $i)->format('d');
            $month = Carbon::createFromDate($today->year, $today->month, $i)->format('m');
            $year = Carbon::createFromDate($today->year, $today->month, $i)->format('Y');

            $dates[] = $day;

            $registrations[] = Student::whereDay('created_at', $day)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();

            //->sum('paid_amount');
        }

        $review_courses = Course::where(['status' => 'pending', 'deleted' => false])->count();
        $total_courses = Course::where(['deleted' => false])->count();
        $new_tickets = rand(5, 18);
        $new_comment = rand(5, 8);
        $new_sale = rand(4, 25);
        $total_duration = rand(5, 60);
        $total_sales = rand(500, 1800);

        $data['data'] = ([
            'review_courses' => $review_courses, //number_format($unpaid_investor, 0, '.', ','),
            'total_courses' => $total_courses,
            'new_tickets' => $new_tickets,
            'new_comment' => $new_comment,
            'new_sale' => $new_sale,
            'total_duration' => $total_duration,
            'total_sales' => number_format($total_sales, 0, '.', ''),
            'dates' => json_encode(array_values($dates)),
            'registrations' => json_encode(array_values($registrations)),
        ]);
        return view('courses.dashboard', $data);
    }

    public function list_courses()
    {
        $data['courses'] = Course::join('users', 'courses.teacher_id', '=', 'users.id')
            ->orderBy('courses.created_at', 'desc')
            ->get(['courses.*', 'users.name as username']);
        return view('courses.index', $data);
    }
    public function list_featured_courses()
    {
        # code...
        $ids = FeaturedCourses::list_all()->pluck('course_id'); //->toArray();
        $data['courses'] = Course::join('users', 'courses.teacher_id', '=', 'users.id')
            ->whereIn('courses.id', $ids)
            ->orderBy('courses.created_at', 'DESC')
            ->get(['courses.*', 'users.name as username']);
        return view('courses.index', $data);
        $courses = Course::whereIn('id', $ids)->orderBy('id', 'DESC')->get();
    }
    public function list_types()
    {
        $data['types'] = CourseType::list();
        return view('courses.types', $data);
    }

    public function list_course_categories()
    {
        # code...
        $data['categories'] = CourseCategory::list();
        return view('courses.categories', $data);
    }
    public function new_type(Request $request)
    {
        # code...
        $attr = $request->validate([
            'title' => 'required|string|max:255|unique:course_types,title',
        ]);
        $title = $attr['title'];
        $type = CourseType::create($title);
        return redirect()->route('list_types')->with('success', 'Course Type created successfully');
    }
    public function new_course_category(Request $request)
    {
        # code...
        $attr = $request->validate([
            'title' => 'required|string|max:255|unique:course_categories,title',
        ]);
        $title = $attr['title'];
        $profile = 'icon.png';
        if ($request->hasFile('image')) {
            // profile
            $profile = $request->file('image');
            $profile = $this->generate_file_name($profile);
        }
        $data = CourseCategory::create([
            'title' => $title,
            'icon' => $profile,
        ]);
        if ($data) {
            return redirect()->route('list_course_categories')->with('success', 'Course Category created successfully');
        } else {

            return redirect()->route('list_course_categories')->with('error', 'Experienced Problem creating category');
        }
    }


    public function new_course()
    {
        $data['types'] = CourseType::list();
        $data['categories'] = CourseCategory::list();
        $data['instructors'] = Admins::join('users', 'admins.email', '=', 'users.email')
            ->orderBy('admins.created_at', 'desc')
            ->where(['users.is_teacher' => true])
            ->get(['admins.*', 'users.name as username']);
        return view('courses.add', $data);
    }

    public function view_course($id)
    {
        # code...

        $data['discounts'] = CourseDiscounts::list_discounts();
        $course = Course::join('users', 'courses.teacher_id', '=', 'users.id')
            ->leftjoin('course_categories', 'courses.course_category_id', '=', 'course_categories.id')
            ->leftjoin('course_types', 'courses.course_type_id', '=', 'course_types.id')
            ->where(['courses.id' => $id])
            ->first(['courses.*', 'users.name as username', 'users.id as user_id', 'course_categories.title as category_title', 'course_categories.id as category_id', 'course_types.title as types_title', 'course_types.id as types_id']);
        $chapters = $course->chapters;
        if ($chapters) {
            foreach ($chapters as $chap) {
                $chap['lessons'] = $chap->lesson;
            }
        }
        $data['data'] = $course;
        $data['chapters'] = $chapters;
        $data['faqs'] = $course->faqs;
        // return $data;
        return view('courses.view', $data);
    }
    public function list_instructors()
    {
        # code... 
        $data['admins'] = Admins::join('users', 'admins.email', '=', 'users.email')
            ->orderBy('admins.created_at', 'desc')
            ->where(['users.is_teacher' => false])
            ->get(['admins.*', 'users.name as username']);

        $data['instructors'] = Admins::join('users', 'admins.email', '=', 'users.email')
            ->orderBy('admins.created_at', 'desc')
            ->where(['users.is_teacher' => true])
            ->get(['admins.*', 'users.name as username']);
        return view('instructors.index', $data);
    }
    public function view_instructor($id)
    {
        # code...

        $data['instructor'] = Admins::join('users', 'admins.email', '=', 'users.email')
            ->orderBy('admins.created_at', 'desc')
            ->where(['users.is_teacher' => true, 'admins.id' => $id])
            ->first(['admins.*', 'users.name as username']);
        return view('instructors.view', $data);
    }

    public function list_students()
    {
        # code...
        $data['customers'] = Customers::getApprovedCustomers();
        $data['students'] = Student::join('customers', 'customers.id', '=', 'students.customers_id')
            ->orderBy('students.id', 'DESC')
            ->get(['customers.firstname', 'customers.lastname', 'customers.phone', 'customers.email', 'students.created_at']);
        return view('students.index', $data);
    }

    public function add_instructor(Request $request)
    {
        # code...
        $attr = $request->validate([
            'email' => 'required|string|max:255|email',
        ]);
        $email = $attr['email'];

        //get user by email address
        $user = User::where(['email' => $email])->first();
        if (!$user) {
            return redirect()->route('list_instructors')->with('error', 'Experienced problems processing request');
        }
        $user->is_teacher = true;
        $user->save();
        $admin = Admins::where(['email' => $email])->first();
        if ($admin) {
            $admin->avatar = "smp.jpg";
            $admin->save();
        }
        return redirect()->route('list_instructors')->with('success', 'Instructor added successfully');
    }

    public function update_profile(Request $request, $id)
    {
        # code...
        $user = Admins::where(['id' => $id])->first();
        if (!$user) {
            return redirect()->route('list_instructors')->with('error', 'Experienced problems processing request');
        }

        $profile = $user->avatar;
        if ($request->hasFile('image')) {
            // profile
            $profile = $request->file('image');
            $profile = $this->generate_file_name($profile);
        }
        $user->avatar = $profile;
        $user->save();
        return redirect()->route('view_instructor', $id)->with('success', 'Profile successfully Updated');
    }
    public function update_instructor(Request $request, $id)
    {
        # code...
        $user = Admins::where(['id' => $id])->first();
        if (!$user) {
            return redirect()->route('list_instructors')->with('kerror', 'Experienced problems processing request');
        }
        $attr = $request->validate([
            'offline' => 'required|string|max:255',
            'offline_message' => 'required|string|max:255',
            'verified' => 'required|string|max:255',
            'meeting_status' => 'required|string|max:255',
            'bio' => 'required|string',
            'role_name' => 'required|string',
            'address' => 'required|string'

        ]);
        $offline = $request->get('offline');
        $user->offline = $offline;
        $user->offline_message = $request->offline_message;
        $user->verified = $request->verified;
        $user->meeting_status = $request->meeting_status;
        $user->bio = $request->bio;
        $user->role_name = $request->role_name;
        $user->address = $request->address;
        $user->save();

        return redirect()->route('view_instructor', $id)->with('ksuccess', 'Profile Details updated successfully');
    }




    public function add_student(Request $request)
    {
        # code...
        $attr = $request->validate([
            'phone' => 'required|string|max:255',
        ]);
        $phone = $attr['phone'];

        //get user by email address
        $user = Customers::where(['phone' => $phone])->first();
        if (!$user) {
            return redirect()->route('list_students')->with('error', 'Experienced problems processing request');
        }
        $password = rand(10000, 100000);
        $activation_code = rand(10000, 100000);
        $password = Hash::make($password);
        $stu = Student::updateOrCreate(
            ['customers_id' =>  $user->id],
            ['email_address' => $user->email, 'password' => $password, 'activation_code' => $activation_code]

        );
        return redirect()->route('list_students')->with('success', 'Student added successfully');
    }



    public function create_course(Request $request)
    {
        # code...
        $attr = $request->validate([
            'type_id' => 'required|string|max:255',
            'category_id' => 'required|string|max:255',
            'teacher_id' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'video' => 'required|string|max:255',
            'description' => 'required|string',
            'capacity' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'start_date' => 'required|string|max:255',
            'end_date' => 'required|string|max:255',
        ]);
        $title = $attr['title'];
        $start_date = Carbon::parse($request->start_date); //->toDateTimeString();
        $end_date = Carbon::parse($request->end_date); //->toDateTimeString();

        //check if end date is valid after start date
        $result = $end_date->gt($start_date);
        if ($result == false) {

            return redirect()->route('list_courses')->with('error', 'Please enter a valid end date');
        }

        $profile = 'icon.png';
        if ($request->hasFile('image')) {
            // profile
            $profile = $request->file('image');
            $profile = $this->generate_file_name($profile);
        }
        $data = Course::create([
            'title' => $title,
            'image_cover' => $profile,
            'teacher_id' => $request->teacher_id,
            'creator_user_id' => Auth::user()->id,
            'course_type_id' => $request->type_id,
            'course_category_id' => $request->category_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'video_demo' => $request->video,
            'description' => $request->description,
            'capacity' => $request->capacity,
            'price' => $request->price,
            'status' => 'pending'

        ]);
        if ($data) {
            return redirect()->route('list_courses')->with('success', 'Course created successfully');
        } else {

            return redirect()->route('list_courses')->with('error', 'Experienced Problem creating course');
        }
    }
    public function update_course(Request $request, $id)
    {
        # code...
        $attr = $request->validate([
            'type_id' => 'required|string|max:255',
            'category_id' => 'required|string|max:255',
            'teacher_id' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'video' => 'required|string|max:255',
            'description' => 'required|string',
            'capacity' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'start_date' => 'required|string|max:255',
            'end_date' => 'required|string|max:255',
        ]);
        $title = $attr['title'];

        $start_date = Carbon::parse($request->start_date); //->toDateTimeString();
        $end_date = Carbon::parse($request->end_date); //->toDateTimeString();

        //check if end date is valid after start date
        $result = $end_date->gt($start_date);
        if ($result == false) {

            return redirect()->route('view_course', $id)->with('error', 'Please enter a valid end date');
        }


        $course = Course::where(['id' => $id])->first();
        if ($course) {
            $profile = $course->image_cover;
            if ($request->hasFile('image')) {
                // profile
                $profile = $request->file('image');
                $profile = $this->generate_file_name($profile);
            }
            $course->title = $title;
            $course->image_cover = $profile;
            $course->teacher_id = $request->teacher_id;
            $course->creator_user_id = Auth::user()->id;
            $course->course_type_id = $request->type_id;
            $course->course_category_id = $request->category_id;
            $course->start_date = $request->start_date;
            $course->end_date = $request->end_date;
            $course->video_demo = $request->video;
            $course->description = $request->description;
            $course->capacity = $request->capacity;
            $course->price = $request->price;
            $course->status = 'pending';
            $course->save();
            if ($course) {
                return redirect()->route('view_course', $id)->with('success', 'Course Updated successfully');
            } else {

                return redirect()->route('view_course', $id)->with('error', 'Experienced Problem creating course');
            }
        } else {
            return redirect()->route('list_courses')->with('error', 'Experienced Problem creating course');
        }
    }

    public function list_discounts()
    {
        # code...
        $data['discounts'] = CourseDiscounts::list_discounts();
        return view('discounts.index', $data);
    }

    public function new_discount(Request $request)
    {
        # code...
        $attr = $request->validate([
            'title' => 'required|string|max:255',
            'is_percentage' => 'required|string|max:255',
            'coupon' => 'required|string|max:255',
            'amount' => 'required|string|max:255',
            'usable_times' => 'required|string|max:255',
            'expiry_time' => 'required|string',
        ]);
        $title = $attr['title'];
        $is_percentage = $attr['is_percentage'];
        $coupon = $attr['coupon'];
        $amount = $attr['amount'];
        $usable_times = $attr['usable_times'];
        $expiry_time = $attr['expiry_time'];
        $disc = CourseDiscounts::create([
            'title' => $title,
            'amount' => $amount,
            'usable_times' => $usable_times,
            'expiry_time' => $expiry_time,
            'coupon' => $coupon,
            'is_percentage' => $is_percentage
        ]);
        if ($disc) {
            return redirect()->route('list_discounts')->with('success', 'Discount created successfully');
        } else {
            return redirect()->route('list_discounts')->with('error', 'Experienced Problem creating course');
        }
    }

    public function apply_discount(Request $request, $id)
    {
        # code...
        $attr = $request->validate([
            'discount' => 'required|string|max:255',
        ]);
        $discount = $attr['discount'];
        //
        $disc = DiscountedCourses::updateOrCreate(
            ['course_id' => $id],
            ['course_discounts_id' => $discount],

        );
        if ($disc) {
            return redirect()->route('view_course', $id)->with('success', 'Discount applied successfully');
        } else {
            return redirect()->route('view_course', $id)->with('error', 'Experienced Problem creating course');
        }
    }
    public function apply_featured(Request $request, $id)
    {
        $disc = FeaturedCourses::updateOrCreate(
            ['course_id' => $id],
            ['deleted' => false],

        );
        if ($disc) {
            return redirect()->route('view_course', $id)->with('success', 'Featured applied successfully');
        } else {
            return redirect()->route('view_course', $id)->with('error', 'Experienced Problem creating course');
        }
    }

    public function add_faq(Request $request, $id)
    {
        # code...
        $attr = $request->validate([
            'title' => 'required|string|max:255',
            'answer' => 'required|string|max:255',
        ]);

        $course = Course::find($id);

        $faq = new CourseFaq();
        $faq->title = $attr['title'];
        $faq->answer = $attr['answer'];
        $course = $course->faqs()->save($faq);
        if ($course) {
            return redirect()->route('view_course', $id)->with('success', 'FAQ added successfully');
        } else {
            return redirect()->route('view_course', $id)->with('error', 'Experienced problems adding course FAQ');
        }
    }

    public function create_chapter_lesson(Request $request, $course_id, $chapter)
    {
        # code...
        $attr = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        $chapter = CourseChapters::find($chapter);

        $lesson = new CourseLesson();
        $lesson->title = $attr['title'];
        $lesson->description = $attr['description'];
        $lesson->date = date('Y-m-d H:i:s');
        $lesson->duration = "3";
        $chapter = $chapter->lesson()->save($lesson);
        if ($chapter) {
            return redirect()->route('view_course', $course_id)->with('success', 'Chapter Lesson added successfully');
        } else {
            return redirect()->route('view_course', $course_id)->with('error', 'Experienced problems adding course Chapter Lesson');
        }
    }

    public function add_chapter_lesson($course, $id)
    {
        # code...
        $data['course'] = Course::find($course);
        $data['chapter'] = CourseChapters::find($id);
        // return $data;
        return view('courses.lessons.index', $data);
    }

    public function add_chapter(Request $request, $id)
    {
        # code...
        $attr = $request->validate([
            'title' => 'required|string|max:255',
            'pass' => 'required|string|max:255',
        ]);
        $pass = $attr['pass'] === 'true' ? true : false;
        $title = $attr['title'];

        $add = CourseChapters::create([
            'course_id' => $id, 'title' => $title, 'check_all_contents_pass' => $pass
        ]);
        if ($add) {
            return redirect()->route('view_course', $id)->with('success', 'Chapter Added successfully');
        } else {
            return redirect()->route('view_course', $id)->with('error', 'Experienced Problem creating course');
        }
    }

    public function delete_chapter(Request $request, $couse, $id)
    {
        # code...
        $chapter = CourseChapters::find($id);
        if ($chapter) {
            $chapter->delete();
        }
        return redirect()->route('view_course', $couse)->with('success', 'Chapter Deleted successfully');
    }
    public function edit_chapter(Request $request, $couse, $id)
    {
        $attr = $request->validate([
            'title' => 'required|string|max:255',
            'pass' => 'required|string|max:255',
        ]);
        $pass = $attr['pass'] === 'true' ? true : false;
        $title = $attr['title'];
        $chapter = CourseChapters::find($id);
        if ($chapter) {
            $chapter->title = $title;
            $chapter->check_all_contents_pass = $pass;
            $chapter->save();
        }
        return redirect()->route('view_course', $couse)->with('success', 'Chapter Update successfully');
    }
    public function edit_faq(Request $request, $couse, $id)
    {
        $attr = $request->validate([
            'title' => 'required|string|max:255',
            'answer' => 'required|string|max:255',
        ]);
        $pass = $attr['answer'];
        $title = $attr['title'];
        $chapter = CourseFaq::find($id);
        if ($chapter) {
            $chapter->title = $title;
            $chapter->answer = $pass;
            $chapter->save();
        }
        return redirect()->route('view_course', $couse)->with('success', 'Chapter FAQ updated successfully');
    }
    public function delete_faq(Request $request, $couse, $id)
    {
        $chapter = CourseFaq::find($id);
        if ($chapter) {
            $chapter->delete();
        }
        return redirect()->route('view_course', $couse)->with('success', 'Chapter FAQ deleted successfully');
    }

    public function list_reviews()
    {
        # code...

    }
    public function list_comments()
    {
        # code...
        $comments = GeneralComment::orderBy('id', 'desc')->get();
        if ($comments) {
            foreach ($comments as $com) {
                $student = "N/A";
                $course = "N/A";
                if($com->course){
                    $course=$com->course->title;
                }
                if($com->student){
                     $sasa= Student::join('customers', 'customers.id', '=', 'students.customers_id')
                    ->where(['students.id'=>$com->student_id])
                    ->first(['customers.firstname', 'customers.lastname', 'customers.phone', 'customers.email', 'students.created_at']);
                    if ($sasa){
                        $student="{$sasa->firstname} {$sasa->lastname}";
                    }
                }
                $com['student_name'] = $student;
                $com['course_title'] = $course;
            }
        }
        $data['comments'] = $comments;
        return view('courses.comments', $data);
    }
}
