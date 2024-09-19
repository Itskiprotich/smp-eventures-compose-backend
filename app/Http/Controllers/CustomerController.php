<?php

namespace App\Http\Controllers;

use App\Http\Traits\CommonTrait;
use App\Models\ChatRoom;
use App\Models\Chats;
use App\Models\User;
use App\Models\Branch;
use App\Models\Customers;
use App\Models\CustomerSavings;
use App\Models\Dividend;
use App\Models\Loans;
use App\Models\Notification;
use App\Models\Savings;
use App\Models\SavingsProducts;
use App\Models\Social;
use App\Models\SystemLogs;
use App\Models\WithdrawalTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Contracts\DataTable;

class CustomerController extends Controller
{
    // use CommonTrait;


    public function getBaseUrl()
    {
        return "https://sacco.imejadevelopers.co.ke/api/";
    }

    public function responseJson($message, $statusCode, $data, $isSuccess = true)
    {
        if ($isSuccess)
            return response()->json([
                "message" => $message,
                "success" => true,
                "code" => $statusCode,
                "data" => $data
            ], $statusCode);

        return response()->json([
            "message" => $message,
            "success" => false,
            "code" => $statusCode
        ], $statusCode);
    }

    public function successResponse($message, $data)
    {
        return $this->responseJson($message, 200, $data);
    }

    public function errorResponse($message)
    {
        return $this->responseJson($message, 400, null, false);
    }

    public function add()
    {
        return view('customers.add');
    }
    public function index()
    {
        $url = $this->getBaseUrl() . "admin/customer/all";
        $customers = Http::get($url)->json();


        return view('customers.pending', ['customers' => $customers]);
    }
    public function all()
    {
        $customers = DB::table('customers')->get();

        return $this->successResponse("success", $customers);
    }


    public function approve(Request $request)
    {

        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'loanlimit' => 'required|string|max:255',
            'automatic' => 'required|boolean',
            'action_by' => 'required|string|max:255',
            'approved_by' => 'required|string|max:255'

        ]);

        $customer = Customers::where('phone', $attr['phone'])->first();
        if ($customer) {
            $customer->loanlimit = $attr['loanlimit'];
            $customer->automatic = $attr['automatic'];
            $customer->action_by = $attr['action_by'];
            $customer->approved_by = $attr['approved_by'];
            $customer->status = 'Approved';
            $customer->save();

            $data = ([
                'code' => 200,
                'message' => "customer Approval successfull",

            ]);

            return $this->successResponse("success", $data);
        } else {

            return $this->errorResponse("Account Not Found");
        }
    }
    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        $phone = $attr['email'];

        if (Customers::where('phone', $phone)->exists()) {
            $cust = Customers::where(['phone' => $phone,])->first();
            if ($cust) {
                if (!Hash::check($attr['password'], $cust->password)) {
                    $data = ([
                        'proceed' => 0,
                        'message' => "Invalid Login Credentials",

                    ]);
                    return $this->successResponse("success", $data);
                }
                $user = User::find(1);


                $token = $user->createToken('tokens')->plainTextToken;
                $is_blacklist = $cust->blacklist;
                if ($is_blacklist) {
                    $data = ([
                        'proceed' => 0,
                        'message' => "Please Contact Administrator",

                    ]);
                    return $this->successResponse("success", $data);
                }
                $deactivated = $cust->deactivated;
                if ($deactivated) {
                    $data = ([
                        'proceed' => 0,
                        'message' => "Please Contact Administrator",

                    ]);
                    return $this->successResponse("success", $data);
                }
                $data = ([
                    'proceed' => 1,
                    'id' => $cust->id,
                    'unique' => $user->id,
                    'firstname' => $cust->firstname,
                    'lastname' => $cust->lastname,
                    'email' => $cust->email,
                    'phone' => $cust->phone,
                    'national_id' => $cust->national_id,
                    'status' => $cust->status,
                    'membership_no' => $cust->membership_no,
                    'updated_at' => $cust->updated_at,
                    'created_at' => $cust->created_at,
                    'access_token' => $token,

                ]);
                return $this->successResponse("success", $data);
            } else {
                $data = ([
                    'proceed' => 0,
                    'message' => "Invalid Login Credentials",
                ]);
                return $this->successResponse("success", $data);
            }
        } else {

            $data = ([
                'proceed' => 0,
                'message' => "Whoops!, Something went wrong. Please try again",
            ]);
            return $this->successResponse("success", $data);
        }
    }
    public function autoload_token(Request $request, $phone)
    {

        if (Customers::where('phone', $phone)->exists()) {
            $cust = Customers::where(['phone' => $phone,])->first();
            if ($cust) {

                $user = User::find(1);


                $token = $user->createToken('tokens')->plainTextToken;
                $is_blacklist = $cust->blacklist;
                if ($is_blacklist) {
                    $data = ([
                        'proceed' => 0,
                        'message' => "Please Contact Administrator",

                    ]);
                    return $this->successResponse("success", $data);
                }
                $deactivated = $cust->deactivated;
                if ($deactivated) {
                    $data = ([
                        'proceed' => 0,
                        'message' => "Please Contact Administrator",

                    ]);
                    return $this->successResponse("success", $data);
                }
                $data = ([
                    'proceed' => 1,
                    'id' => $cust->id,
                    'unique' => $user->id,
                    'firstname' => $cust->firstname,
                    'lastname' => $cust->lastname,
                    'email' => $cust->email,
                    'phone' => $cust->phone,
                    'national_id' => $cust->national_id,
                    'status' => $cust->status,
                    'membership_no' => $cust->membership_no,
                    'updated_at' => $cust->updated_at,
                    'created_at' => $cust->created_at,
                    'access_token' => $token,

                ]);
                return $this->successResponse("success", $data);
            } else {
                $data = ([
                    'proceed' => 0,
                    'message' => "Invalid Login Credentials",
                ]);
                return $this->successResponse("success", $data);
            }
        } else {

            $data = ([
                'proceed' => 0,
                'message' => "Whoops!, Something went wrong. Please try again",
            ]);
            return $this->successResponse("success", $data);
        }
    }
    public function verify_login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        $phone = $attr['email'];

        if (Customers::where('phone', $phone)->exists()) {
            $cust = Customers::where(['phone' => $phone,])->first();
            if ($cust) {
                if (!Hash::check($attr['password'], $cust->password)) {
                    $data = ([
                        'proceed' => 0,
                        'message' => "Check your Password and Try Again!",

                    ]);
                    return $this->successResponse("success", $data);
                }
                $user = User::find(1);


                $token = $user->createToken('tokens')->plainTextToken;
                $is_blacklist = $cust->blacklist;
                if ($is_blacklist) {
                    $data = ([
                        'proceed' => 0,
                        'message' => "Please Contact Administrator",

                    ]);
                    return $this->successResponse("success", $data);
                }
                $data = ([
                    'proceed' => 1,
                    'id' => $cust->id,
                    'unique' => $user->id,
                    'firstname' => $cust->firstname,
                    'lastname' => $cust->lastname,
                    'email' => $cust->email,
                    'phone' => $cust->phone,
                    'national_id' => $cust->national_id,
                    'status' => $cust->status,
                    'membership_no' => $cust->membership_no,
                    'updated_at' => $cust->updated_at,
                    'created_at' => $cust->created_at,
                    'access_token' => $token,

                ]);
                return $this->successResponse("success", $data);
            } else {
                $data = ([
                    'proceed' => 0,
                    'message' => "Check your Password and Try Again!",
                ]);
                return $this->successResponse("success", $data);
            }
        } else {

            $data = ([
                'proceed' => 0,
                'message' => "Please Check your data and Try Again",
            ]);
            return $this->successResponse("success", $data);
        }
    }

    public function confirm_pass(Request $request)
    {

        $attr = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        $phone = $attr['email'];
        $password = $attr['password'];

        $cust = Customers::where('phone', $phone)->first();
        if ($cust) {
            $cust->password = Hash::make($password);
            $cust->save();

            $user = User::find(1);
            $token = $user->createToken('tokens')->plainTextToken;
            $data = ([
                'proceed' => 1,
                'id' => $cust->id,
                'unique' => $user->id,
                'firstname' => $cust->firstname,
                'lastname' => $cust->lastname,
                'email' => $cust->email,
                'phone' => $cust->phone,
                'national_id' => $cust->national_id,
                'status' => $cust->status,
                'membership_no' => $cust->membership_no,
                'updated_at' => $cust->updated_at,
                'created_at' => $cust->created_at,
                'access_token' => $token,

            ]);


            return $this->successResponse("success", $data);
        } else {
            return $this->errorResponse("Request Failed");
        }
    }

    public function verify_code(Request $request)
    {

        $attr = $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string'
        ]);
        $phone = $attr['phone'];
        $code = $attr['code'];
        $cust = Customers::where(['phone' => $phone, 'otp' => $code])->first();
        if ($cust) {
            $data = ([
                'proceed' => 1,
                'message' => "Success",

            ]);
            return $this->successResponse("success", $data);
        } else {
            $data = ([
                'proceed' => 0,
                'message' => "Invalid Code",

            ]);
            return $this->successResponse("success", $data);
        }
    }
    public function send_code(Request $request)
    {

        $attr = $request->validate([
            'email' => 'required|string',
        ]);
        $phone = $attr['email'];

        if (Customers::where('phone', $phone)->exists()) {

            $cust = Customers::where(['phone' => $phone,])->first();
            $characters = '123456789';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 6; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $code = $randomString;

            $cust->otp = $code;
            $cust->save();

            $info = "Your Verification Code is {$code}.";

            $result = (new EmailController)->customer_otp_email($cust, $info);

            $data = ([
                'proceed' => 1,
                'message' => "Code Sent Successfully!!",
                'results' => $result,

            ]);
            return $this->successResponse("success", $data);
        } else {

            $data = ([
                'proceed' => 0,
                'message' => "Please Check your data and Try Again",
            ]);
            return $this->successResponse("success", $data);
        }
    }

    public function store(Request $request)
    {
        $attr = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'devicename' => 'required|string|max:255',
            'device_id' => 'required|string|max:255',
            'national' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'invite' => 'required|string|max:255',
        ]);
        $member = Customers::latest()->first();
        if ($member) {
            $add = $member->id + 1;
        } else {
            $add = 1;
        }
         $valid=date('Y');
        if ($attr['invite'] != $valid) {
            $data = ([
                'proceed' => 1,
                'message' => 'Invalid Invite Code,please contact administrator'
            ]);
            return $this->successResponse("success", $data);
        }
        if (Customers::where('device_id', '=', $attr['device_id'])->exists()) {
            $data = ([
                'proceed' => 1,
                'message' => 'A User with the same device already exist'
            ]);
            return $this->successResponse("success", $data);
        }
        if (Customers::where('phone', '=', $attr['phone'])->exists()) {
            $data = ([
                'proceed' => 1,
                'message' => 'A User with the same phone number already exist'
            ]);
            return $this->successResponse("success", $data);
        }
        if (Customers::where('email', '=', $attr['email'])->exists()) {
            $data = ([
                'proceed' => 1,
                'message' => 'A User with the same email address already exist'
            ]);
            return $this->successResponse("success", $data);
        }

        $name = $attr['firstname'] . " " . $attr['lastname'];

        // get current branch_id

        $active_branch = Branch::where(['recruit' => true])->first();
        if (!$active_branch) {
            $data = ([
                'proceed' => 1,
                'message' => 'Please contact administrator'
            ]);
            $message = "Please set active branch for customer registrations";
            // $result = (new EmailController)->new_branch_email($message);
            return $this->successResponse("success", $data);
        }

        $customer = Customers::create([
            'firstname' => $attr['firstname'],
            'lastname' => $attr['lastname'],
            'phone' => $attr['phone'],
            'devicename' => $attr['devicename'],
            'device_id' => $attr['device_id'],
            'type' => $attr['type'],
            'national_id' => $attr['national'],
            'gender' => $attr['gender'],
            'membership_no' => "S" . $add . "E",
            'branch_id' => $active_branch->id,
            'password' => Hash::make($attr['password']),
            'email' => $attr['email'],
        ]);
        if ($customer) {

            $cs = CustomerSavings::updateOrCreate(
                ['phone' =>   $attr['phone']],
                [
                    'branch_id' => $active_branch->id, 
                    'amount' => 0, 'name' => $name, 'share_capital' => 0, 'welfare' => 0]
            );
            $message = "You have a new customer registration {$customer->firstname} {$customer->lastname} - {$customer->phone}";
            // $result = (new EmailController)->new_customer_email($customer, $message);
// 
            $data = ([
                'proceed' => 0,
                'message' => 'Account creation Successfull'
            ]);


            return $this->successResponse("success", $data);
        } else {

            $data = ([
                'proceed' => 1,
                'message' => 'Experienced Problems Creating Account'
            ]);
            return $this->successResponse("success", $data);
        }
    }

    public function view_notification($id)
    {
        $loans = Notification::where(['phone' => $id,])->get();
        if ($loans) {
            return $this->successResponse("success", $loans);
        } else {
            return $this->errorResponse("No Such Loan Record");
        }
    }

    public function send_chats(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'message' => 'required|string|max:255'
        ]);
        $is_admin = 0;
        $room = ChatRoom::updateOrCreate(
            ['phone' =>  request('phone')],
            ['message' => request('message'), 'is_admin' => $is_admin]

        );

        $chat = Chats::create([
            'phone' => $attr['phone'],
            'message' => $attr['message'],
            'is_admin' => $is_admin
        ]);

        $result = (new EmailController)->chat_email($chat);

        if ($chat) {
            return $this->successResponse($result, $chat);
        } else {
            return $this->errorResponse("Request Failed");
        }
    }
    public function view_chats($id)
    {
        $loans = Chats::where(['phone' => $id,])->get();
        if ($loans) {
            return $this->successResponse("success", $loans);
        } else {
            return $this->errorResponse("No Such Loan Record");
        }
    }
    public function view($id)
    {
        $customer = Customers::where('phone', $id)->first();
        return $this->successResponse("success", $customer);
    }

    public function logs($id)
    {
        $data = [];
        $logs = SystemLogs::where('phone', $id)->orderBy('created_at', 'desc')->get();

        if ($logs) {

            foreach ($logs as $log) {
                $data[] = ([
                    "id" => $log->id,
                    "phone" => $log->phone,
                    "title" => $log->title,
                    "body" => $log->body,
                    "status" => $log->status,
                    "created_at" =>  $log->created_at->format('Y-m-d H:i'),
                    "updated_at" => $log->updated_at->format('Y-m-d H:i')
                ]);
            }

            return $this->successResponse("success", $data);
        } else {

            return $this->successResponse("success", $data);
        }
    }
    public function show($id)
    {
        $customer = Customers::where('phone', $id)->first();

        $message = "";
        $borrow = "0";

        if ($customer->blacklist) {
            $message = "Invalid Phone Number, please try again";
            $borrow = "1";
            $data = ([
                'message' => $message,
                'borrow' => $borrow,
            ]);
            return $this->successResponse("success", $data);
        }

        if ($customer->blacklist) {
            $message = "Your credit profile is too low. please contact the administrator ";
            $borrow = "1";
        } else {
            $has_loan = Loans::where('phone', '=', $customer->phone)->exists();
            if ($has_loan) {
                $pending = Loans::where(['phone' => $customer->phone, 'loan_status' => 'pending'])->first();
                if ($pending) {

                    $message = "You loan of " . $pending->principle . " is waiting approval please contact the administrator +254706289514";
                    $borrow = "0";
                } else {
                    $disbursed = Loans::where(['phone' => $customer->phone, 'loan_status' => 'disbursed', 'repayment_status' => false])->first();
                    if ($disbursed) {

                        $message = "You have an existing loan of " . $disbursed->loan_balance . " Please Pay to increase your loan limit";
                        $borrow = "0";
                    } else {

                        $message = "You are qualified for an instant loan of KES " . $customer->loanlimit . " Borrow and pay on time.";
                        $borrow = "1";
                    }
                }
            } else {
                if ($customer->loanlimit == 0) {
                    $message = "Wait for account activation and loan prequalification";
                    $borrow = "0";
                } else {

                    $message = "You are qualified for an instant loan of KES " . $customer->loanlimit . " Borrow and pay on time.";
                    $borrow = "1";
                }
            }
        }
        $no_loans = Loans::where('phone', '=', $customer->phone)->count();
        $sum_total = Loans::where('phone', '=', $customer->phone)->sum('principle');
        $dividends = Dividend::where(['phone' => $customer->phone, 'paid' => false])->sum('available');
        $user_savings = CustomerSavings::where('phone', '=', $customer->phone)->first();
        $total_savings = 0;
        $welfare = 0;
        $shares = 0;
        if ($user_savings) {
            $total_savings = $user_savings->amount;
            $welfare = $user_savings->welfare;
            $shares = $user_savings->share_capital;
        }
        if ($dividends < 0) {
            $dividends = 0;
        }
        $data = ([
            'id' => $customer->id,
            'membership_number' => $customer->membership_no,
            'loan_limit' => $customer->loanlimit,
            'message' => $message,
            'borrow' => $borrow,
            'registration_fee' => 0,
            'total_savings' => $total_savings,
            'number_of_loans' => $no_loans,
            'sum_total' => $sum_total,
            'welfare' => $welfare,
            'shares' => $shares,
            'dividends' => $dividends

        ]);

        return $this->successResponse("success", $data);
    }
    public function show_web($id)
    {
        $customer = Customers::where('phone', $id)->first();

        $message = "";
        $borrow = "0";
        if ($customer->blacklist) {
            $message = "Your credit profile is too low. please contact the administrator ";
            $borrow = "1";
        } else {
            $has_loan = Loans::where('phone', '=', $customer->phone)->exists();
            if ($has_loan) {
                $pending = Loans::where(['phone' => $customer->phone, 'loan_status' => 'pending'])->first();
                if ($pending) {

                    $message = "You loan of " . $pending->principle . " is waiting approval please contact the administrator +254706289514";
                    $borrow = "0";
                } else {
                    $disbursed = Loans::where(['phone' => $customer->phone, 'loan_status' => 'disbursed', 'repayment_status' => false])->first();
                    if ($disbursed) {

                        $message = "You have an existing loan of " . $disbursed->loan_balance . " Please Pay to increase your loan limit";
                        $borrow = "0";
                    } else {

                        $message = "You are qualified for an instant loan of KES " . $customer->loanlimit . " Borrow and pay on time.";
                        $borrow = "1";
                    }
                }
            } else {
                if ($customer->loanlimit == 0) {
                    $message = "Wait for account activation and loan prequalification";
                    $borrow = "0";
                } else {

                    $message = "You are qualified for an instant loan of KES " . $customer->loanlimit . " Borrow and pay on time.";
                    $borrow = "1";
                }
            }
        }
        $no_loans = Loans::where('phone', '=', $customer->phone)->count();
        $sum_total = Loans::where('phone', '=', $customer->phone)->sum('principle');
        $dividends = Dividend::where(['phone' => $customer->phone, 'paid' => false])->sum('available');
        $user_savings = CustomerSavings::where('phone', '=', $customer->phone)->first();
        $total_savings = 0;
        $welfare = 0;
        $shares = 0;
        if ($user_savings) {
            $total_savings = $user_savings->amount;
            $welfare = $user_savings->welfare;
            $shares = $user_savings->share_capital;
        }
        if ($dividends < 0) {
            $dividends = 0;
        }
        $saving_products = SavingsProducts::select(DB::raw('savings_products.*, ifnull(SUM(savings.amount),0) as revenue'))
            ->leftJoin('savings', 'savings.product', '=', 'savings_products.product_code')
            ->where(['savings.phone' => $id])
            ->groupBy(['savings_products.id', 'savings_products.product_code', 'savings_products.product_name', 'savings_products.duration', 'savings_products.max_limit', 'savings_products.min_limit', 'savings_products.interest_rate', 'savings_products.admin_fee', 'savings_products.active', 'savings_products.created_at', 'savings_products.updated_at'])
            ->get();

        $logs_data = [];
        $logs = SystemLogs::where('phone', $id)->orderBy('created_at', 'desc')->get();

        $today = today();
        $dates = [];
        $depos = [];
        $withs = [];
        $tulipo = Carbon::createFromDate($today->year, $today->month)->format('d');
        DB::table('temp__chartsof_accs')->delete();

        for ($i = 1; $i <= $tulipo; ++$i) {
            $leo = Carbon::createFromDate($today->year, $today->month, $i)->format('d');
            $two = Carbon::createFromDate($today->year, $today->month, $i)->format('m');
            $three = Carbon::createFromDate($today->year, $today->month, $i)->format('Y');

            $dates[] = $leo;
            $depos[] = Savings::whereDay('created_at', $leo)
                ->whereMonth('created_at', $two)
                ->whereYear('created_at', $three)
                ->where('phone', $id)
                ->sum('amount');
            $withs[] = WithdrawalTransaction::whereDay('created_at', $leo)
                ->whereMonth('created_at', $two)
                ->whereYear('created_at', $three)
                ->where('phone', $id)
                ->sum('amount');
        }

        if ($logs) {

            foreach ($logs as $log) {
                $logs_data[] = ([
                    "id" => $log->id,
                    "phone" => $log->phone,
                    "title" => $log->title,
                    "body" => $log->body,
                    "status" => $log->status,
                    "created_at" =>  $log->created_at->format('Y-m-d H:i'),
                    "updated_at" => $log->updated_at->format('Y-m-d H:i')
                ]);
            }
        }
        $instagram = "#";
        $youtube = "#";
        $twitter = "#";
        $facebook = "#";


        $social_pg = Social::where(['active' => true])->first();
        if ($social_pg) {
            $facebook = $social_pg->facebook;
            $youtube = $social_pg->youtube;
            $instagram = $social_pg->instagram;
            $twitter = $social_pg->twitter;
        }
        $date = Carbon::today();
        $pendingl =  Loans::where(['phone' => $customer->phone, 'loan_status' => 'pending'])->count();
        $approved =  Loans::where(['phone' => $customer->phone, 'loan_status' => 'disbursed'])->count();
        $paid =  Loans::where(['phone' => $customer->phone, 'loan_status' => 'paid'])->count();
        $overdue =  Loans::whereDate('repayment_date', '<=', $date)->where(['loan_status' => 'disbursed', 'repayment_status' => false, 'phone' => $customer->phone])->count();
        $rejected =  Loans::where(['phone' => $customer->phone, 'loan_status' => 'rejected'])->count();
        $data = ([
            'id' => $customer->id,
            'membership_number' => $customer->membership_no,
            'loan_limit' => $customer->loanlimit,
            'message' => $message,
            'borrow' => $borrow,
            'registration_fee' => 0,
            'total_savings' => $total_savings,
            'number_of_loans' => $no_loans,
            'sum_total' => $sum_total,
            'welfare' => $welfare,
            'shares' => $shares,
            'dividends' => $dividends,
            'transactions' => $logs_data,
            'facebook' => $facebook,
            'twitter' => $twitter,
            'instagram' => $instagram,
            'youtube' => $youtube,
            'saving_products' => $saving_products,
            'count_products' => $saving_products->count(),
            'dates' => json_encode(array_values($dates)),
            'depos' => json_encode(array_values($depos)),
            'withs' => json_encode(array_values($withs)),
            'pending' => $pendingl,
            'approved' => $approved,
            'paid' => $paid,
            'overdue' => $overdue,
            'rejected' => $rejected,

        ]);

        return $this->successResponse("success", $data);
    }

    public function reset_password(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255'
        ]);
        $phone =  $attr['phone'];

        $characters = '123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $password = $randomString;

        $customer = Customers::where('phone', $phone)->first();
        if ($customer) {
            $customer->password = Hash::make($password);
            $customer->save();
            $data = ([
                'proceed' => 0,
                'message' => 'Password Reset Successfull',
                'danger' => $password
            ]);
            $message = "Your new PIN has been updated, kindly use {$password} to Log on to the Jetpack Compose Mobile App";
            $result = (new EmailController)->reset_password_email($customer, $message);

            return $this->successResponse("success", $data);
        } else {
            return $this->errorResponse("Request Failed");
        }
    }
    public function upload(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'profile' => 'required|string|max:255'
        ]);
        $phone = $request->phone;
        $profile = $request->profile;

        $customer = Customers::where('phone', $phone)->first();
        if ($customer) {
            $customer->photo = $profile;
            $customer->save();
            $data = ([
                'proceed' => 0,
                'message' => 'Profile Upload Successfull'
            ]);

            return $this->successResponse("success", $data);
        } else {
            return $this->errorResponse("Request Failed");
        }
    }
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
