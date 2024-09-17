<?php

namespace App\Http\Controllers;

use App\Models\CustomerGroup;
use Illuminate\Http\Request;
use App\Models\Customers;
use App\Models\CustomerSavings;
use App\Models\Dividend;
use App\Models\JournalEntries;
use App\Models\Mode;
use App\Models\ProductGroup;
use App\Models\SavingInterest;
use App\Models\Savings;
use App\Models\SavingsProducts;
use App\Models\Setting;
use App\Models\Share;
use App\Models\SystemLogs;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Welfare;
use App\Models\WithdrawalTransaction;
use Illuminate\Support\Facades\DB;

class SavingsController extends Controller
{


    public function getAvailableBalance(Request $request)
    {
        $phone = $request->query('phone');
        $product = $request->query('product');

        // Perform logic to get available balance based on phone and product
        // For demonstration, let's assume you have a method to get the balance

        $balance = $this->calculateAvailableBalance($phone, $product);

        return response()->json(['balance' => $balance]);
    }

    private function calculateAvailableBalance($phone, $product)
    {
        $total=0;
        $product  = SavingsProducts::select(DB::raw('savings_products.*, ifnull(SUM(savings.amount),0) as revenue'))
        ->leftJoin('savings', 'savings.product', '=', 'savings_products.product_code')
        ->where(['savings.phone' => $phone])
        ->where(['savings_products.product_code' => $product])
        ->groupBy(['savings_products.id', 'savings_products.product_code', 'savings_products.product_name', 'savings_products.duration', 'savings_products.max_limit', 'savings_products.min_limit', 'savings_products.interest_rate', 'savings_products.admin_fee', 'savings_products.active', 'savings_products.created_at', 'savings_products.updated_at'])
        ->first();

        if($product){
            $total=$product->revenue;

        }

        return "KES {$total}";
    }

    public function responseJson($message, $statusCode, $data, $isSuccess = true)
    {
        if ($isSuccess)
            return response()->json([
                "message" => $message,
                "success" => true,
                "code" => $statusCode,
                "data" => $data,
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

    public function generateRandomString($length = 25)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public function lipaNaMpesaPassword()
    {
        $lipa_time = Carbon::rawParse('now')->format('YmdHms');
        $passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
        $BusinessShortCode = 174379;
        $timestamp = $lipa_time;
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode . $passkey . $timestamp);
        return $lipa_na_mpesa_password;
    }

    public function generateAccessToken()
    {
        $consumer_key = "sMpgnYW62glBlxPXbyTBEGdPib8eJLOL";
        $consumer_secret = "IcK2PkAFArVVVffU";
        $credentials = base64_encode($consumer_key . ":" . $consumer_secret);
        $url = "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic " . $credentials));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        $access_token = json_decode($curl_response);
        return $access_token->access_token;
        // Basic 
    }

    public function index()
    {
        //
    }


    public function store(Request $request)
    {

        $attr = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|integer|between:1,100000',
            'payment_ref' => 'required|string',
            'code' => 'required|string'
        ]);

        $amount = $attr['amount'];
        $phone = $attr['phone'];
        $code = $attr['code'];

        $exists = SavingsProducts::where(['product_code' => $code])->exists();
        if ($exists) {
            $product = SavingsProducts::where(['product_code' => $code])->first();
            $min = $product->min_limit;
            $max = $product->max_limit;
            if ($amount < $min) {

                $data = ([
                    'proceed' => 1,
                    'message' => 'Minimum amount to deposit is KES ' . $min
                ]);
                return $this->successResponse("success", $data);
            }

            if ($amount > $max) {

                $data = ([
                    'proceed' => 1,
                    'message' => 'Maximum amount to deposit is KES ' . $max
                ]);
                return $this->successResponse("success", $data);
            }
            if ($request->has('account')) {
                $account = $request->account;
            }
            $reference = $this->generateRandomString(12);
            $mode = Mode::updateOrCreate(
                ['phone' =>  $phone],
                ['description' => $code, 'amount' => $amount, 'account' => $account, 'mode' => '2', 'reference' => $reference]
            );

            $result = (new IPayController)->make_payment($phone, $amount);
            $data = ([
                'proceed' => 0,
                'message' => 'Payment successfull, Please wait for STK Push'
            ]);
            return $this->successResponse("success", $data);
        } else {
            $data = ([
                'proceed' => 1,
                'message' => 'Saving Product Not Found'
            ]);
            return $this->successResponse("success", $data);
        }
    }
    public function store_tiny(Request $request)
    {

        $attr = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|integer|between:1,100000',
            'payment_ref' => 'required|string'
        ]);

        $amount = $attr['amount'];
        $phone = $attr['phone'];
        $code = $attr['payment_ref'];

        $exists = SavingsProducts::where(['product_code' => $code])->exists();
        if ($exists) {
            $product = SavingsProducts::where(['product_code' => $code])->first();
            $min = $product->min_limit;
            $max = $product->max_limit;
            if ($amount < $min) {

                $data = ([
                    'proceed' => 1,
                    'message' => 'Minimum amount to deposit is KES ' . $min
                ]);
                return $this->successResponse("success", $data);
            }

            if ($amount > $max) {

                $data = ([
                    'proceed' => 1,
                    'message' => 'Maximum amount to deposit is KES ' . $max
                ]);
                return $this->successResponse("success", $data);
            }
            // check if account is coming from the request
            $account = $phone;
            if ($request->has('account')) {
                $account = $request->account;
            }
            $reference = $this->generateRandomString(12);

            $mode = Mode::updateOrCreate(
                ['phone' =>  $phone],
                ['description' => $code, 'amount' => $amount, 'account' => $account, 'mode' => '2', 'reference' => $reference]
            );

            $result = (new IPayController)->make_payment($phone, $amount);
            $data = ([
                'result' => $result,
                'proceed' => 0,
                'message' => 'Payment successfull, Please wait for STK Push'
            ]);
            return $this->successResponse("success", $data);
        } else {
            $data = ([
                'proceed' => 1,
                'message' => 'Saving Product Not Found'
            ]);
            return $this->successResponse("success", $data);
        }
    }
    public function store_welfare(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|integer|between:1,100000',
            'payment_ref' => 'required|string'
        ]);


        $amount = $attr['amount'];
        $phone = $attr['phone'];
        $reference = $this->generateRandomString(12);
        if ($request->has('account')) {
            $account = $request->account;
        }
        $mode = Mode::updateOrCreate(
            ['phone' =>  $phone],
            ['description' => 'Payment for Welfare', 'account' => $account, 'amount' => $amount, 'mode' => '3', 'reference' => $reference]
        );


        $result = (new IPayController)->make_payment($phone, $amount);
        $data = ([
            'borrow' => 1,
            'message' => 'Payment successfull, Please wait for STK Push'
        ]);
        return $this->successResponse("success", $data);
    }

    public function store_shares(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|integer|between:1,100000',
            'payment_ref' => 'required|string'
        ]);


        $amount = $attr['amount'];
        $phone = $attr['phone'];
        $reference = $this->generateRandomString(12);
        if ($request->has('account')) {
            $account = $request->account;
        }
        $mode = Mode::updateOrCreate(
            ['phone' =>  $phone],
            ['description' => 'Payment for Shares', 'account' => $account, 'amount' => $amount, 'mode' => '4', 'reference' => $reference]
        );


        $result = (new IPayController)->make_payment($phone, $amount);
        $data = ([
            'borrow' => 1,
            'message' => 'Payment successfull, Please wait for STK Push'
        ]);
        return $this->successResponse("success", $data);
    }

    public function store_original(Request $request)
    {

        $attr = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|integer|between:1,100000',
            'payment_ref' => 'required|string'
        ]);
        if (Customers::where('phone', $attr['phone'])->exists()) {

            $total_savings = 0;

            $customer = Customers::where(['phone' => $attr['phone']])->first();
            $savings_history = Savings::where(['phone' => $attr['phone']])->first();

            $saving = new Savings();
            $phone = $attr['phone'];
            $amount = $attr['amount'];
            $ref = $this->generateRandomString(12);
            $product = $attr['payment_ref'];
            $total_savings = 0;


            if ($savings_history) {
                $sum_total = Savings::where('phone', '=', $phone)->sum('amount');

                $total_savings = $sum_total + $attr['amount'];

                $saving->phone = $phone;
                $saving->reference = $ref;
                $saving->amount = $attr['amount'];
                $saving->total = $total_savings;
                $saving->product = $product;
                $saving->save();
            } else {
                $total_savings = $attr['amount'];

                $saving->phone = $phone;
                $saving->reference = $this->generateRandomString(12);
                $saving->amount = $attr['amount'];
                $saving->total = $attr['amount'];
                $saving->product = $product;
                $saving->save();
            }

            $fcm = CustomerSavings::updateOrCreate(
                ['phone' =>  $phone],
                ['amount' => $total_savings, 'name' => $customer->firstname . " " . $customer->lastname],
            );
            $narration = "Contribution payment for {$phone}";
            $name = "{$customer->firstname} {$customer->lastname}";

            $now = Carbon::rawParse('now')->format('Y-m-d');
            $today = Carbon::createFromFormat('Y-m-d', $now);

            $logs = SystemLogs::create([
                'phone' => $attr['phone'],
                'title' => "Cash Deposit",
                'body' => "Cash Deposit of KES {$amount}. current balance KES {$total_savings}"
            ]);

            $loop = JournalEntries::create([
                'reference' => $phone,
                'amount' => $attr['amount'],
                'debit_account' => "B035",
                'credit_account' => "S200",
                'trans_date' => $today,
                'narration' => $narration,
                'loan_type' => "Contribution",
                'payment_ref' => $attr['payment_ref'],
                'name' => $name,
                'phone' => $phone,

            ]);

            $info = "Thank you, your deposit of KES. {$amount} has been received and Your savings are now Ksh. {$total_savings}";

            $result = (new EmailController)->cash_deposit($customer, $saving, $info);
            return $this->successResponse("success", $saving);
        } else {
            return $this->errorResponse("Account Not Found");
        }
    }

    public function savings_callback($phone, $amount, $ref, $product)
    {
        if (Customers::where('phone', $phone)->exists()) {

            $total_savings = 0;

            $customer = Customers::where(['phone' => $phone])->first();
            $savings_history = CustomerSavings::where(['phone' => $phone])->first();

            $saving = new Savings();
            $total_savings = 0;



            if ($savings_history) {

                $total_savings  = $savings_history->amount + $amount;

                $saving->phone = $phone;
                $saving->branch_id=$customer->branch_id;
                $saving->reference = $ref;
                $saving->amount = $amount;
                $saving->total = $total_savings;
                $saving->product = $product;
                $saving->save();
            } else {
                $total_savings = $amount;

                $saving->phone = $phone;
                $saving->branch_id=$customer->branch_id;
                $saving->reference = $this->generateRandomString(12);
                $saving->amount = $amount;
                $saving->total = $amount;
                $saving->product = $product;
                $saving->save();
            }

            $fcm = CustomerSavings::updateOrCreate(
                ['phone' =>  $phone],
                ['amount' => $total_savings, 'name' => $customer->firstname . " " . $customer->lastname],
            );
            $narration = "Contribution payment for {$phone}";
            $name = "{$customer->firstname} {$customer->lastname}";

            $now = Carbon::rawParse('now')->format('Y-m-d');
            $today = Carbon::createFromFormat('Y-m-d', $now);

            $logs = SystemLogs::create([
                'phone' => $phone,
                'title' => "Cash Deposit",
                'body' => "Cash Deposit of KES {$amount}. current balance KES {$total_savings}"
            ]);

            $loop = JournalEntries::create([
                'reference' => $phone,
                'amount' => $amount,
                'debit_account' => "B035",
                'credit_account' => "S200",
                'trans_date' => $today,
                'narration' => $narration,
                'loan_type' => "Contribution",
                'payment_ref' => $ref,
                'name' => $name,
                'phone' => $phone,

            ]);

            // $prod = SavingsProducts::where(['product_code' => $product])->first();
            // $min = $prod->min_limit;
            // $max = $prod->max_limit;
            // $prod_name = $prod->product_name;

            // $total_by_product = Savings::where([['phone' => $phone, 'product' => $product]])->sum('amount');

            // $remainder = $max - $total_by_product;
            // $wow = 0;
            // if ($remainder < 0) {
            //     $wow = 0;
            // } else {
            //     $wow = $remainder;
            // }
            // $info = "Thank you, your deposit of KES. {$amount} has been received and Your savings are now Ksh. {$total_savings}. The current balance for the {$prod_name} product is {$wow}";
            $info = "Thank you, your deposit of KES. {$amount} has been received and Your savings are now Ksh. {$total_savings}";
            $result = (new EmailController)->cash_deposit($customer, $saving, $info);
            return $this->successResponse("success", $saving);
        } else {
            return $this->errorResponse("Account Not Found");
        }
    }
    public function welfare_callback($phone, $amount, $ref)
    {

        if (Customers::where('phone', $phone)->exists()) {

            $total_savings = 0;

            $customer = Customers::where(['phone' => $phone])->first();
            $savings_history = CustomerSavings::where(['phone' => $phone])->first();

            $saving = new Welfare();
            $total_savings = 0;


            if ($savings_history) {

                $total_savings = $savings_history->welfare + $amount;

                $saving->phone = $phone;
                $saving->branch_id = $customer->branch_id;
                $saving->reference = $ref;
                $saving->amount = $amount;
                $saving->total = $total_savings;
                $saving->save();
            } else {
                $total_savings = $amount;

                $saving->phone = $phone;
                $saving->branch_id = $customer->branch_id;
                $saving->reference = $ref;
                $saving->amount = $amount;
                $saving->total = $amount;
                $saving->save();
            }

            $fcm = CustomerSavings::updateOrCreate(
                ['phone' =>  $phone],
                ['welfare' => $total_savings, 'name' => $customer->firstname . " " . $customer->lastname],
            );
            $narration = "Contribution payment for {$phone}";
            $name = "{$customer->firstname} {$customer->lastname}";

            $now = Carbon::rawParse('now')->format('Y-m-d');
            $today = Carbon::createFromFormat('Y-m-d', $now);

            $logs = SystemLogs::create([
                'phone' => $phone,
                'title' => "Cash Deposit",
                'body' => "Cash Deposit of KES {$amount}. current balance KES {$total_savings}"
            ]);

            $loop = JournalEntries::create([
                'reference' => $phone,
                'amount' => $amount,
                'debit_account' => "B035",
                'credit_account' => "S200",
                'trans_date' => $today,
                'narration' => $narration,
                'loan_type' => "Contribution",
                'payment_ref' => $ref,
                'name' => $name,
                'phone' => $phone,

            ]);

            $info = "Thank you, your deposit of KES. {$amount} has been received and Your Welfare are now Ksh. {$total_savings}";

            $result = (new EmailController)->cash_deposit($customer, $saving, $info);
            return $this->successResponse("success", $saving);
        } else {
            return $this->errorResponse("Account Not Found");
        }
    }
    public function shares_callbacl($phone, $amount, $ref)
    {

        if (Customers::where('phone', $phone)->exists()) {

            $total_savings = 0;

            $customer = Customers::where(['phone' => $phone])->first();
            $savings_history = CustomerSavings::where(['phone' => $phone])->first();

            $saving = new Share();
            $total_savings = 0;


            if ($savings_history) {

                $total_savings = $savings_history->share_capital + $amount;

                $saving->phone = $phone;
                $saving->branch_id = $customer->branch_id;
                $saving->reference = $ref;
                $saving->amount = $amount;
                $saving->total = $total_savings;
                $saving->save();
            } else {
                $total_savings = $amount;

                $saving->phone = $phone;
                $saving->branch_id = $customer->branch_id;
                $saving->reference = $ref;
                $saving->amount = $amount;
                $saving->total = $amount;
                $saving->save();
            }

            $fcm = CustomerSavings::updateOrCreate(
                ['phone' =>  $phone],
                ['share_capital' => $total_savings, 'name' => $customer->firstname . " " . $customer->lastname],
            );
            $narration = "Contribution payment for {$phone}";
            $name = "{$customer->firstname} {$customer->lastname}";

            $now = Carbon::rawParse('now')->format('Y-m-d');
            $today = Carbon::createFromFormat('Y-m-d', $now);

            $logs = SystemLogs::create([
                'phone' => $phone,
                'title' => "Cash Deposit",
                'body' => "Cash Deposit of KES {$amount}. current balance KES {$total_savings}"
            ]);

            $loop = JournalEntries::create([
                'reference' => $phone,
                'amount' => $amount,
                'debit_account' => "B035",
                'credit_account' => "S200",
                'trans_date' => $today,
                'narration' => $narration,
                'loan_type' => "Contribution",
                'payment_ref' => $ref,
                'name' => $name,
                'phone' => $phone,

            ]);

            $info = "Thank you, your deposit of KES. {$amount} has been received and Your Shares are now Ksh. {$total_savings}";

            $result = (new EmailController)->cash_deposit($customer, $saving, $info);
            return $this->successResponse("success", $saving);
        } else {
            return $this->errorResponse("Account Not Found");
        }
    }
    public function store_welfare_original(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|integer|between:1,100000',
            'payment_ref' => 'required|string'
        ]);
        if (Customers::where('phone', $attr['phone'])->exists()) {

            $total_savings = 0;

            $customer = Customers::where(['phone' => $attr['phone']])->first();
            $savings_history = Welfare::where(['phone' => $attr['phone']])->first();

            $saving = new Welfare();
            $phone = $attr['phone'];
            $amount = $attr['amount'];
            $ref = $this->generateRandomString(12);
            $total_savings = 0;


            if ($savings_history) {
                $sum_total = Welfare::where('phone', '=', $phone)->sum('amount');

                $total_savings = $sum_total + $attr['amount'];

                $saving->phone = $phone;
                $saving->reference = $ref;
                $saving->amount = $attr['amount'];
                $saving->total = $total_savings;
                $saving->save();
            } else {
                $total_savings = $attr['amount'];

                $saving->phone = $phone;
                $saving->reference = $this->generateRandomString(12);
                $saving->amount = $attr['amount'];
                $saving->total = $attr['amount'];
                $saving->save();
            }

            $fcm = CustomerSavings::updateOrCreate(
                ['phone' =>  $phone],
                ['welfare' => $total_savings, 'name' => $customer->firstname . " " . $customer->lastname],
            );
            $narration = "Contribution payment for {$phone}";
            $name = "{$customer->firstname} {$customer->lastname}";

            $now = Carbon::rawParse('now')->format('Y-m-d');
            $today = Carbon::createFromFormat('Y-m-d', $now);

            $logs = SystemLogs::create([
                'phone' => $attr['phone'],
                'title' => "Cash Deposit",
                'body' => "Cash Deposit of KES {$amount}. current balance KES {$total_savings}"
            ]);

            $loop = JournalEntries::create([
                'reference' => $phone,
                'amount' => $attr['amount'],
                'debit_account' => "B035",
                'credit_account' => "S200",
                'trans_date' => $today,
                'narration' => $narration,
                'loan_type' => "Contribution",
                'payment_ref' => $attr['payment_ref'],
                'name' => $name,
                'phone' => $phone,

            ]);

            $info = "Thank you, your deposit of KES. {$amount} has been received and Your Welfare are now Ksh. {$total_savings}";

            $result = (new EmailController)->cash_deposit($customer, $saving, $info);
            return $this->successResponse("success", $saving);
        } else {
            return $this->errorResponse("Account Not Found");
        }
    }
    public function store_shares_original(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|integer|between:1,100000',
            'payment_ref' => 'required|string'
        ]);
        if (Customers::where('phone', $attr['phone'])->exists()) {

            $total_savings = 0;

            $customer = Customers::where(['phone' => $attr['phone']])->first();
            $savings_history = Share::where(['phone' => $attr['phone']])->first();

            $saving = new Share();
            $phone = $attr['phone'];
            $amount = $attr['amount'];
            $ref = $this->generateRandomString(12);
            $total_savings = 0;


            if ($savings_history) {
                $sum_total = Share::where('phone', '=', $phone)->sum('amount');

                $total_savings = $sum_total + $attr['amount'];

                $saving->phone = $phone;
                $saving->reference = $ref;
                $saving->amount = $attr['amount'];
                $saving->total = $total_savings;
                $saving->save();
            } else {
                $total_savings = $attr['amount'];

                $saving->phone = $phone;
                $saving->reference = $this->generateRandomString(12);
                $saving->amount = $attr['amount'];
                $saving->total = $attr['amount'];
                $saving->save();
            }

            $fcm = CustomerSavings::updateOrCreate(
                ['phone' =>  $phone],
                ['share_capital' => $total_savings, 'name' => $customer->firstname . " " . $customer->lastname],
            );
            $narration = "Contribution payment for {$phone}";
            $name = "{$customer->firstname} {$customer->lastname}";

            $now = Carbon::rawParse('now')->format('Y-m-d');
            $today = Carbon::createFromFormat('Y-m-d', $now);

            $logs = SystemLogs::create([
                'phone' => $attr['phone'],
                'title' => "Cash Deposit",
                'body' => "Cash Deposit of KES {$amount}. current balance KES {$total_savings}"
            ]);

            $loop = JournalEntries::create([
                'reference' => $phone,
                'amount' => $attr['amount'],
                'debit_account' => "B035",
                'credit_account' => "S200",
                'trans_date' => $today,
                'narration' => $narration,
                'loan_type' => "Contribution",
                'payment_ref' => $attr['payment_ref'],
                'name' => $name,
                'phone' => $phone,

            ]);

            $info = "Thank you, your deposit of KES. {$amount} has been received and Your Shares are now Ksh. {$total_savings}";

            $result = (new EmailController)->cash_deposit($customer, $saving, $info);
            return $this->successResponse("success", $saving);
        } else {
            return $this->errorResponse("Account Not Found");
        }
    }
    public function products($id)
    {
        $product = SavingsProducts::where(['active' => true])->orderBy('created_at', 'desc')->get();
        if ($product) {
            return $this->successResponse("success", $product);
        } else {

            return $this->errorResponse("No Saving Record Found");
        }
    }
    public function assigned_products($phone)
    {
        $customer = Customers::where('phone', $phone)->first();
        $assigned_groups = [];
        if ($customer) {
            $grps = CustomerGroup::where(['customers_id' => $customer->id])->orderBy('created_at', 'desc')->get();
            if ($grps) {
                foreach ($grps as $one) {
                    $one_grp = ProductGroup::where(['id' => $one->product_group_id])->first();
                    $assigned_groups[] = $one_grp->id;
                }
            }
            // return $assigned_groups;
            $product = SavingsProducts::where(['active' => true])
                ->whereIn('product_group_id', $assigned_groups)
                ->orWhereNull('product_group_id')
                ->orderBy('created_at', 'desc')->get();
            if ($product) {
                if (count($product) > 0) {
                    $proceed = "0";
                    $message = "Saving products successfully retrieved";
                } else {
                    $proceed = "1";
                    $message = "You've not been assigned any saving product, please contact the system administrator";
                }
                $data = ([
                    'proceed' => $proceed,
                    'message' => $message,
                    'products' => $product
                ]);
                return $this->successResponse("success", $data);
            } else {

                $data = ([
                    'proceed' => 1,
                    'message' => "No Saving Product Record Found",
                    'products' => []
                ]);
                return $this->successResponse("success", $data);
            }
        } else {
            $data = ([
                'proceed' => 1,
                'message' => "Failed to Fetch Savings Products",
                'products' => []
            ]);
            return $this->successResponse("success", $data);
        }
    }


    public function savings_by_products($id)
    {
        $products   = SavingsProducts::select(DB::raw('savings_products.*, ifnull(SUM(savings.amount),0) as revenue'))
            ->leftJoin('savings', 'savings.product', '=', 'savings_products.product_code')
            ->where(['savings.phone' => $id])
            ->groupBy(['savings_products.id', 'savings_products.product_code', 'savings_products.product_name', 'savings_products.duration', 'savings_products.max_limit', 'savings_products.min_limit', 'savings_products.interest_rate', 'savings_products.admin_fee', 'savings_products.active', 'savings_products.created_at', 'savings_products.updated_at'])
            ->get();
        return $this->successResponse("success", $products);
    }
    public function show($id)
    {
        // $savings = Savings::where('phone', $id)->orderBy('created_at', 'desc')->get();
        $savings =   Savings::join('savings_products', 'savings_products.product_code', '=', 'savings.product')
            ->where(['savings.phone' => $id])
            ->orderBy('savings.created_at', 'desc')
            ->get(['savings.*', 'savings_products.*', 'savings.created_at as saved','savings.id as id']);
        if ($savings) {
            return $this->successResponse("success", $savings);
        } else {

            return $this->errorResponse("No Saving Record Found");
        }
    }
    public function show_welfare($id)
    {
        $savings = Welfare::where('phone', $id)->orderBy('created_at', 'desc')->get();
        if ($savings) {
            return $this->successResponse("success", $savings);
        } else {

            return $this->errorResponse("No Saving Record Found");
        }
    }

    public function show_shares($id)
    {
        $savings = Share::where('phone', $id)->orderBy('created_at', 'desc')->get();
        if ($savings) {
            return $this->successResponse("success", $savings);
        } else {

            return $this->errorResponse("No Saving Record Found");
        }
    }


    public function show_withdraw($id)
    {
        $withdrawals = WithdrawalTransaction::where(['phone' => $id, 'deleted' => false])
            ->orderBy('created_at', 'desc')->get();
        if ($withdrawals) {
            return $this->successResponse("success", $withdrawals);
        } else {

            return $this->errorResponse("No Saving Record Found");
        }
    }

    public function withdraw(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|integer|between:1,100000',
            'loan_code' => 'required|string',
        ]);
        $phone = $attr['phone'];
        $amount = $attr['amount'];
        $product_code = $attr['loan_code'];

        if (Customers::where('phone', $phone)->exists()) {

            $user = CustomerSavings::where('phone', '=', $phone)->first();
            if ($user) {

                if ($user->amount > $amount) {
                    $balance = $user->amount - $amount;
                    // check the specific product
                    $product  = SavingsProducts::select(DB::raw('savings_products.*, ifnull(SUM(savings.amount),0) as revenue'))
                        ->leftJoin('savings', 'savings.product', '=', 'savings_products.product_code')
                        ->where(['savings.phone' => $phone])
                        ->where(['savings_products.product_code' => $product_code])
                        ->groupBy(['savings_products.id', 'savings_products.product_code', 'savings_products.product_name', 'savings_products.duration', 'savings_products.max_limit', 'savings_products.min_limit', 'savings_products.interest_rate', 'savings_products.admin_fee', 'savings_products.active', 'savings_products.created_at', 'savings_products.updated_at'])
                        ->first();
                    if ($product) {

                        $max_amount = $product->revenue;
                        if ($amount > $max_amount) {
                            # code...
                            $data = ([
                                'proceed' => 0,
                                'message' => "Insufficient Funds, Maximum allowed is KES {$max_amount}"
                            ]);

                            return $this->successResponse("success", $data);
                        }

                        // Check for pending transactions
                        $pen = WithdrawalTransaction::where(['phone' => $phone, 'status' => false, 'deleted' => false])->first();

                        if ($pen) {
                            $data = ([
                                'proceed' => 0,
                                'message' => "You have a pending transaction"
                            ]);

                            return $this->successResponse("success", $data);
                        }
                        $cust = Customers::where('phone', '=', $phone)->first();
                        $trans_id = $this->generateRandomString(12);
                        $over = WithdrawalTransaction::create([
                            'reference' => $trans_id,
                            'amount' => $amount,
                            'trans_id' => $product_code,
                            'phone' => $phone,
                            'branch_id'=>$cust->branch_id

                        ]);


                        $message = "You have a new saving withdrawal application of KES {$amount} for the product {$product->product_name} by {$user->name}- {$phone}";
                        $title = "New Saving Withdrawal Application";
                        $result = (new EmailController)->new_application_email($user, $message, $title);

                        $data = ([
                            'proceed' => 1,
                            'message' => 'Savings withdrawal Successfull'
                        ]);

                        return $this->successResponse("success", $data);
                    } else {
                        $data = ([
                            'proceed' => 0,
                            'message' => 'Insufficient Funds'
                        ]);
                    }


                    return $this->successResponse("success", $data);
                } else {
                    $data = ([
                        'proceed' => 0,
                        'message' => 'Insufficient Funds'
                    ]);

                    return $this->successResponse("success", $data);
                }
            } else {
                $data = ([
                    'proceed' => 0,
                    'message' => 'Insufficient Funds, Please make some deposits'
                ]);

                return $this->successResponse("success", $data);
            }
        } else {
            return $this->errorResponse("Account Not Found");
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
    public function earn_active_interest()
    {

        // return "Success";
        $interest = [];
        $setting = Setting::where(['status' => true])->first();
        if ($setting) {

            $date = Carbon::now();
            $year = $date->format('Y');

            $total_days = ($date->isLeapYear() ? 366 : 365);
            $rate = $setting->saving_rate;
            $daily_rate = $rate / $total_days;


            $mwanzo = '04/04/2024'; // . $year;
            $mwisho = $date->subDays(92)->format('m/d/Y');


            $start_date = Carbon::parse($mwanzo); //->toDateTimeString();
            $end_date = Carbon::parse($mwisho); //->toDateTimeString(); 

            $diff_in_days = $start_date->diffInDays($end_date); 
            $product = ['lYFMmU5WupvK', 'dYdBxedzG2Ux'];

              $savings = Savings::groupBy('phone')
                ->select(DB::raw("SUM(`amount`) AS `total`"), 'phone')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->whereIn('product', $product)
                // ->where('product', 'lYFMmU5WupvK')
                // ->orWhere('product', 'dYdBxedzG2Ux')
                ->get();



            foreach ($savings as $saving) {
                //deduct paid dividens

                $days_since_first_deposit = $this->getInitialSavingDate($mwisho, $saving->phone);
                $paid = Dividend::where(['phone' => $saving->phone, 'paid' => true])->sum('amount');
                $earning = $daily_rate * $saving->total;
                // $total_earning=$earning-$paid;
                $total_earning = $earning * $days_since_first_deposit;
 
                if($earning<0){
                    $earning=0;
                }
                $interest[] = ([ 
                    'phone' => $saving->phone,
                    'total' => $saving->total,
                    'rate' => $daily_rate,
                    'daily_earning' => $earning,
                    'total_earning' => $total_earning,
                    'total_days' => $days_since_first_deposit,
                ]);

                $fcm = Dividend::updateOrCreate(
                    ['phone' =>  $saving->phone, 'paid' => false, 'year' => $year],
                    ['available' => $total_earning]
                );
            }
        } else {

            return $this->successResponse("success", "Please specify settings");
        }

        return $this->successResponse("success", $interest);
    }
    public function earn_interest()
    {
        // return "Success";
        $interest = [];
        $setting = Setting::where(['status' => true])->first();
        if ($setting) {

            $date = Carbon::now();
            $year = $date->format('Y');

            $total_days = ($date->isLeapYear() ? 366 : 365);
            $rate = $setting->saving_rate;
            $daily_rate = $rate / $total_days;


            $mwanzo = '04/04/2024'; // . $year;
            $mwisho = $date->subDays(1)->format('m/d/Y');


            $start_date = Carbon::parse($mwanzo); //->toDateTimeString();
            $end_date = Carbon::parse($mwisho); //->toDateTimeString(); 

            $diff_in_days = $start_date->diffInDays($end_date);

            $product = ['lYFMmU5WupvK', 'dYdBxedzG2Ux'];

            $savings = Savings::groupBy('phone')
                ->select(DB::raw("SUM(`amount`) AS `total`"), 'phone')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->whereIn('product', $product)
                ->get();
            // Dividend::where(['paid' => false])->delete();
            foreach ($savings as $saving) {

                $days_since_first_deposit = $this->getInitialSavingDate($mwisho, $saving->phone);
                $earning = $daily_rate * $saving->total;
                $total_earning = $earning * $days_since_first_deposit;
                $interest[] = ([
                    'phone' => $saving->phone,
                    'total' => $saving->total,
                    'rate' => $daily_rate,
                    'daily_earning' => $earning,
                    'total_earning' => $total_earning,
                    'total_days' => $diff_in_days,
                ]);

                $fcm = Dividend::updateOrCreate(
                    ['phone' =>  $saving->phone, 'paid' => false, 'year' => $year],
                    ['reference' => $this->generateRandomString(12), 'amount' => $total_earning]
                );
            }
        } else {

            return $this->successResponse("success", "Please specify settings");
        }

        return $this->successResponse("success", $interest);
    }

    public function getInitialSavingDate($mwisho, $phone)
    {
        # code... 
        $saving = Savings::where(['phone' => $phone])->first();
        $created_at = Carbon::parse($saving->created_at)->format('m/d/Y');

        $start_date = Carbon::parse($created_at); 
        $end_date = Carbon::parse($mwisho);

        $diff_in_days = $start_date->diffInDays($end_date);
        return $diff_in_days;
    }
}
