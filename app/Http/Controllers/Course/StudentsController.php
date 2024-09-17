<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Http\Traits\CommonTrait;
use App\Models\CourseReview;
use App\Models\Courses\Course;
use App\Models\Customers;
use App\Models\GeneralComment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentsController extends Controller
{
    use CommonTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function login_courses(Request $request)
    {
        # code...
        $attr = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);
        $email = $attr['username'];
        $password = $attr['password'];
        $student = Student::where(['email_address' => $email])->first();
        if (!$student) {
            return response()->json([
                "success" => false, "status" => "incorrect", "message" => "auth.incorrect.not.found"
            ], 200);
        }
        //proceed  to login
        // if (!Hash::check($password,$user->password)) {
        //     return response()->json([
        //         "success" => false, "status" => "incorrect", "message" => "auth.incorrect"
        //     ], 200);
        // }
        $user = User::find(1);
        $token = $user->createToken('tokens')->plainTextToken;
        return response()->json([
            "success" => true, "status" => "login", "message" => "Login", "data" => array("token" => $token, "user_id" => $student->id)
        ], 200);
    }

    public function forget_password(Request $request)
    {
        # code...
        $attr = $request->validate([
            'email' => 'required|string|max:255',
        ]);
        $email = $attr['email'];
        $user = Student::where(['email_address' => $email])->first();
        if (!$user) {
            return response()->json([
                "success" => false, "status" => "validation_error", "message" => "request validation error", "data" => [
                    "errors" => ["email" => ["The selected email is invalid."]]
                ]
            ], 200);
        }
    }

    public function step_one_register(Request $request)
    {
        # code...
        $attr = $request->validate([
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'password_confirmation' => 'required|string|max:255',
        ]);
        $email = $attr['email'];

        //get user by email address
        $user = Customers::where(['email' => $email])->first();
        if (!$user) {
            return response()->json([
                "success" => false, "status" => "go_step_2", "message" => "Experienced problems processing request", "data" => []
            ], 200);
        }
        $password = rand(10000, 100000);
        $activation_code = rand(10000, 100000);

        $password = Hash::make($password);
        $stu = Student::updateOrCreate(
            ['customers_id' =>  $user->id],
            ['email_address' => $user->email, 'password' => $password, 'activation_code' => $activation_code]

        );
        //send the verification email
        $info = "To verify your email address {$email}., enter the following code on SMP Eventure App. {$activation_code}";
        $title = "Verification";
        $email = (new EmailController)->student_email($user, $email, $info, $title);
        return response()->json([
            "success" => false, "status" => "go_step_2", "message" => "api.auth.go_step_2", "data" => ["user_id" => $stu->id]
        ], 200);
    }


    public function step_two_register(Request $request)
    {
        # code...
        $attr = $request->validate([
            'code' => 'required|string|max:255',
            'user_id' => 'required|string|max:255',
        ]);
        $user_id = $attr['user_id'];
        $code = $attr['code'];

        //get user by email address
        $user = Student::where(['id' => $user_id])->first();
        if (!$user) {
            return response()->json([
                "success" => false, "status" => "validation_error", "message" => "request validation error", "data" => [
                    "errors" => ["The selected code is invalid"]
                ]
            ], 200);
        }
        $server_code = $user->activation_code;

        if ($server_code != $code) {
            return response()->json([
                "success" => false, "status" => "validation_error", "message" => "request validation error", "data" => [
                    "errors" => ["The selected code is invalid"]
                ]
            ], 200);
        } else {

            return response()->json([
                "success" => true, "status" => "go_step_3", "message" => "api.auth.verified"
            ], 200);
        }
    }

    public function step_three_register(Request $request)
    {

        return response()->json(["avatar" => null, "bio" => null, "email" => null, "newsletter" => false, "id" => 0, "meeting_status" => null, "mobile" => null, "full_name" => "imeja", "offline" => 0, "public_message" => 0, "rate" => 0.0, "referral_code" => null, "role_name" => "", "user_id" => 1021], 200);
    }
    public function achievements(Request $request)
    {


        $data = [];
        return $this->successResponse("success", $data);
    }

    public function profile_setting(Request $request)
    {
        # code...
        $attr = $request->validate([
            'newsletter' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
        ]);

        $newsletter = $attr['newsletter'];
        $full_name = $attr['full_name'];
        $public_message = $attr['public_message'];

        return response()->json([
            "success" => true, "status" => "go_step_3", "message" => "api.auth.verified"
        ], 200);
    }

    public function add_reviews(Request $request)
    {
        $attr = $request->validate([
            'content_quality' => 'required',
            'description' => 'required',
            'instructor_skills' => 'required',
            'purchase_worth' => 'required',
            // 'reason' => 'required',
            'support_quality' => 'required',
            // 'user' => 'required',
            'webinar_id' => 'required',
        ]);
        $course = Course::find($attr['webinar_id']);
        if ($course) {
            $faq = new CourseReview();
            $faq->content_quality = $attr['content_quality'];
            $faq->description = $attr['description'];
            $faq->instructor_skills = $attr['instructor_skills'];
            $faq->purchase_worth = $attr['purchase_worth'];
            $faq->reason = $request->reason;
            $faq->support_quality = $attr['support_quality'];
            $faq->student_id = $request->user;
            $course = $course->reviews()->save($faq);
        }
        return response()->json([
            "success" => true, "status" => "go_step_3", "message" => "api.auth.verified"
        ], 200);
    }

    public function add_comments(Request $request)
    {
        # code... {"blog":null,"comment":"sample","webinar":null,"create_at":0,"id":0,"item_id":10,"item_name":"webinar","message":null,"reason":null,"replies":null,"reply":null,"status":null,"user":null}
        $attr = $request->validate([
            // 'blog' => 'required', //partial
            'comment' => 'required',
            // 'webinar' => 'required', //partial
            'item_id' => 'required', //course
            'item_name' => 'required', //course name or blog
            // 'message' => 'required', //null
            // 'reason' => 'required', //null
            // 'reply' => 'required', //null            
            // 'status' => 'required', //null
            'user' => 'required', //student id
        ]);
        $type = $attr['item_name'];
        if ($type == "webinar") {
              $course = Course::find($attr['item_id']);
            if ($course) {
                $cm = new GeneralComment();
                $cm->student_id = $request->user;
                $cm->user_id = null;
                $cm->comment = $request->comment;
                $course = $course->comments()->save($cm);
            }else{
                return response()->json([
                    "success" => false, "status" => "failed", "message" => "Failed to Create Comment",
                ], 200);
            }
        }
        return response()->json([
            "success" => true, "status" => "go_step_3", "message" => "Comment created successfully"
        ], 200);
    }
}
