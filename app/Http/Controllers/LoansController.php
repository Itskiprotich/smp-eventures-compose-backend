<?php

namespace App\Http\Controllers;

use App\Models\CustomerGroup;
use App\Models\Customers;
use App\Models\Disbursement;
use App\Models\JournalEntries;
use App\Models\Loans;
use App\Models\LoanTypes;
use App\Models\Mode;
use App\Models\Overpayment;
use App\Models\ProductGroup;
use App\Models\Repayments;
use App\Models\RunningBalances;
use App\Models\Schedule;
use App\Models\SystemLogs;
use App\Models\Transactions;
use App\Models\Verification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOption\None;

class LoansController extends Controller
{

    public function callbackDisbursement($trans_id, $branch_id)
    {
        $callback_response = "Disbursement successfull";
        $trans_ref = "CONFIRMED";
        $result_code = 0;

        $transaction = Transactions::where(['trans_id' => $trans_id])->first();
        $transaction->callback_response = $callback_response;
        $transaction->trans_ref = $trans_ref;
        $transaction->result_code = $result_code;
        $transaction->save();


        $now = Carbon::rawParse('now')->format('Y-m-d');
        $today = Carbon::createFromFormat('Y-m-d', $now);

        if ($result_code == 0) {
            $loan = Loans::where(['loan_ref' => $transaction->loan_ref])->first();

            $disburse = Disbursement::create([
                'loan_ref' => $loan->loan_ref,
                'loan_code' => $loan->loan_code,
                'amount' => $loan->principle,
                'trans_code' => $trans_id,
                'branch_id' => $branch_id,
                'phone' => $loan->phone,

            ]);

            $narration = "Loan Disbursed to " . $loan->phone;

            $narration_over = "Previous Overpaid Loan by " . $loan->phone;

            $overpayment = Overpayment::where(['phone' => $loan->phone, 'status' => true])->sum('amount');
            if ($overpayment > 0) {

                $balance = $loan->loan_balance - $overpayment;
                if ($balance > 0) {
                    $loan->loan_balance = $balance;
                    $loan->save();
                } else {
                    $loan->loan_balance = 0;
                    $loan->repayment_status = true;
                    $loan->loan_status = 'paid';
                    $loan->clear_date = $today;
                    $loan->save();

                    $over = Overpayment::create([
                        'phone' => $loan->phone,
                        'loan_ref' => $loan->loan_ref,
                        'amount' => $overpayment * -1

                    ]);
                }
                $overpays = Overpayment::where(['phone' => $loan->phone,])->get();
                foreach ($overpays as $over) {
                    $over->status = false;
                    $over->save();
                }

                $loop = JournalEntries::create([
                    'reference' => $loan->loan_ref,
                    'amount' => $overpayment,
                    'debit_account' => "C140",
                    'credit_account' => "B020",
                    'trans_date' => $today,
                    'narration' => $narration_over,
                    'loan_type' => "Suspense Account",
                    'payment_ref' => $trans_ref,
                    'name' => $loan->customer_name,
                    'phone' => $loan->phone,

                ]);
            }


            $principle = $loan->principle;
            $repayment_date = $loan->repayment_date;

            // $strip = $repayment_date->format('Y-m-d');
            // $lipa = Carbon::createFromFormat('Y-m-d', $repayment_date);
            $body = "You have a loan disbursement of KES {$principle} to be due on {$repayment_date}";

            $logs = SystemLogs::create([
                'phone' => $loan->phone,
                'title' => "Loan Disbursement",
                'body' => $body
            ]);

            $journal = JournalEntries::create([
                'reference' => $loan->loan_ref,
                'amount' => $loan->principle,
                'debit_account' => "B020",
                'credit_account' => "B090",
                'trans_date' => $today,
                'narration' => $narration,
                'loan_type' => "Cash Advance",
                'payment_ref' => $trans_ref,
                'name' => $loan->customer_name,
                'phone' => $loan->phone,

            ]);


            $charges = 0;
            if ($principle > 1000) {
                $charges = 22.4;
            } else {
                $charges = 15.27;
            }

            $charge = JournalEntries::create([
                'reference' => $loan->loan_ref,
                'amount' => $charges,
                'debit_account' => "J005",
                'credit_account' => "B090",
                'trans_date' => $today,
                'narration' => $narration,
                'loan_type' => "Transaction Charges",
                'payment_ref' => $trans_ref,
                'name' => $loan->customer_name,
                'phone' => $loan->phone,

            ]);
            $customer = Customers::where('phone', $loan->phone)->first();
            $message = "Your Loan of {$principle} has been processed successfully. The loan will be due on {$repayment_date}";
            $result = (new EmailController)->disbursement_email($customer, $loan, $message);
        }
    }

    public function handleAutomatic($loan_ref, $branch_id)
    {
        $loan = Loans::where(['loan_ref' => $loan_ref])->first();

        $trans_id = $this->generateRandomString(12);
        $over = Transactions::create([
            'loan_ref' => $loan_ref,
            'amount' => $loan->principle,
            'trans_id' => $trans_id,
            'phone' => $loan->phone,

        ]);
        $this->callbackDisbursement($trans_id, $branch_id);
    }

    public function journal_b035_b020($loan_ref, $principle, $today, $narration, $transaction_code)
    {
        $loan = Loans::where(['loan_ref' => $loan_ref])->first();
        $over = JournalEntries::create([
            'reference' => $loan_ref,
            'amount' => $principle,
            'debit_account' => "B035",
            'credit_account' => "B020",
            'trans_date' => $today,
            'narration' => $narration,
            'loan_type' => "Cash Advance",
            'payment_ref' => $transaction_code,
            'name' => $loan->customer_name,
            'phone' => $loan->phone,

        ]);
    }


    public function journal_b035_f100($loan_ref, $b, $today, $narration, $transaction_code)
    {
        $loan = Loans::where(['loan_ref' => $loan_ref])->first();
        $over = JournalEntries::create([
            'reference' => $loan_ref,
            'amount' => $b,
            'debit_account' => "B035",
            'credit_account' => "F100",
            'trans_date' => $today,
            'narration' => $narration,
            'loan_type' => "Commision",
            'payment_ref' => $transaction_code,
            'name' => $loan->customer_name,
            'phone' => $loan->phone,

        ]);
    }

    public function journal_b035_f200($loan_ref, $admin_fee, $today, $narration, $transaction_code)
    {
        $loan = Loans::where(['loan_ref' => $loan_ref])->first();
        $over = JournalEntries::create([
            'reference' => $loan_ref,
            'amount' => $admin_fee,
            'debit_account' => "B035",
            'credit_account' => "F200",
            'trans_date' => $today,
            'narration' => $narration,
            'loan_type' => "Commision",
            'payment_ref' => $transaction_code,
            'name' => $loan->customer_name,
            'phone' => $loan->phone,

        ]);
    }


    public function journal_b035_c140($loan_ref, $overpayment, $today, $narration, $transaction_code)
    {
        $loan = Loans::where(['loan_ref' => $loan_ref])->first();
        $over = JournalEntries::create([
            'reference' => $loan_ref,
            'amount' => $overpayment,
            'debit_account' => "B035",
            'credit_account' => "C140",
            'trans_date' => $today,
            'narration' => $narration,
            'loan_type' => "Commision",
            'payment_ref' => $transaction_code,
            'name' => $loan->customer_name,
            'phone' => $loan->phone,

        ]);
    }


    public function journal_c140_b020($loan_ref, $overpayment, $today, $narration, $transaction_code)
    {
        $loan = Loans::where(['loan_ref' => $loan_ref])->first();
        $over = JournalEntries::create([
            'reference' => $loan_ref,
            'amount' => $overpayment,
            'debit_account' => "C140",
            'credit_account' => "B020",
            'trans_date' => $today,
            'narration' => $narration,
            'loan_type' => "Commision",
            'payment_ref' => $transaction_code,
            'name' => $loan->customer_name,
            'phone' => $loan->phone,

        ]);
    }

    public function journal_b020_b090($loan_ref, $loan_amount, $today, $narration, $transaction_code)
    {
        $loan = Loans::where(['loan_ref' => $loan_ref])->first();
        $over = JournalEntries::create([
            'reference' => $loan_ref,
            'amount' => $loan_amount,
            'debit_account' => "B020",
            'credit_account' => "B090",
            'trans_date' => $today,
            'narration' => $narration,
            'loan_type' => "Cash Advance",
            'payment_ref' => $transaction_code,
            'name' => $loan->customer_name,
            'phone' => $loan->phone,

        ]);
    }

    public function journal_j005_b090($loan_ref, $total_charge, $today, $narration, $transaction_code)
    {
        $loan = Loans::where(['loan_ref' => $loan_ref])->first();
        $over = JournalEntries::create([
            'reference' => $loan_ref,
            'amount' => $total_charge,
            'debit_account' => "J005",
            'credit_account' => "B090",
            'trans_date' => $today,
            'narration' => $narration,
            'loan_type' => "Transaction Charges",
            'payment_ref' => $transaction_code,
            'name' => $loan->customer_name,
            'phone' => $loan->phone,

        ]);
    }

    public function journal_j007_b092($amount, $today, $narration, $transaction_code)
    {
        $over = JournalEntries::create([
            'reference' => $transaction_code,
            'amount' => $amount,
            'debit_account' => "J007",
            'credit_account' => "B092",
            'trans_date' => $today,
            'narration' => $narration,
            'loan_type' => "Service Charges",
            'payment_ref' => $transaction_code,

        ]);
    }


    public function journal_j006_b091($bill_amount, $today, $narration, $transaction_code)
    {

        $over = JournalEntries::create([
            'reference' => $transaction_code,
            'amount' => $bill_amount,
            'debit_account' => "J006",
            'credit_account' => "B091",
            'trans_date' => $today,
            'narration' => $narration,
            'loan_type' => "SAS Billing",
            'payment_ref' => $transaction_code,

        ]);
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

    public function index() {}

    public function user_loan_types(Request $request, $phone)
    {
        # code...

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
            //  return $assigned_groups;
            $loan_types = DB::table('loan_types')->where('active', '=', true)->orderBy('created_at', 'DESC')->get();
            if ($loan_types) {
                $data = ([
                    'borrow' => 0,
                    'message' => "Loan Types Successfully Retrieved",
                    'loan_types' => $loan_types
                ]);
                return $this->successResponse("success", $data);
            } else {
                $data = ([
                    'borrow' => 1,
                    'message' => "Failed to Fetch Loan Type",
                    'loan_types' => []
                ]);
                return $this->successResponse("success", $data);
            }
        } else {
            $data = ([
                'borrow' => 1,
                'message' => "Failed to Fetch Loan Type",
                'loan_types' => []
            ]);
            return $this->successResponse("success", $data);
        }
    }


    public function verify(Request $request, $phone)
    {

        $customer = Customers::where('phone', $phone)->first();
        if ($customer) {
            if ($customer->blacklist) {
                $message = "Welcome to SMP Eventures, Your credit profile is too low. please contact the administrator";

                $data = ([
                    'borrow' => "0",
                    'message' => $message,
                    'limit' => $customer->loanlimit,
                    'access_code' => "0"
                ]);
                return $this->successResponse("success", $data);
            } else {

                $pending = Loans::where([
                    'phone' => $phone,
                    'loan_status' => 'pending'
                ])->first();
                if ($pending) {
                    $message = "You loan of " . $pending->principle . " is waiting approval please contact the administrator";

                    $data = ([
                        'borrow' => "0",
                        'message' => $message,
                        'limit' => $customer->loanlimit,
                        'access_code' => "0"
                    ]);
                    return $this->successResponse("success", $data);
                } else {
                    $unpaid = Loans::where([
                        'phone' => $phone,
                        'loan_status' => 'disbursed',

                    ])->first();
                    if ($unpaid) {

                        $message = "You have an existing loan of " . $unpaid->loan_balance . " Please Pay to increase your loan limit";


                        $data = ([
                            'borrow' => "0",
                            'message' => $message,
                            'limit' => $customer->loanlimit,
                            'access_code' => "0"
                        ]);
                        return $this->successResponse("success", $data);
                    } else {
                        if ($customer->loanlimit == 0) {
                            $message = "Welcome to SMP Eventures. Wait for account activation and loan prequalification";


                            $data = ([
                                'borrow' => "0",
                                'message' => $message,
                                'limit' => $customer->loanlimit,
                                'access_code' => "0"
                            ]);
                            return $this->successResponse("success", $data);
                        } else {
                            $message = "Welcome to SMP Eventures. You are qualified for an instant loan of KES " . $customer->loanlimit . " Borrow and pay on time.";

                            $access_code = $this->generateRandomString(12);

                            $access = Verification::where('phone', $phone)->first();
                            if ($access) {
                                $access->access_code = $access_code;
                                $access->save();
                            } else {

                                $verified = new Verification();
                                $verified->phone = $phone;
                                $verified->access_code = $access_code;
                                $verified->save();
                            }

                            $data = ([
                                'borrow' => "1",
                                'message' => $message,
                                'limit' => $customer->loanlimit,
                                'access_code' => $access_code
                            ]);
                            return $this->successResponse("success", $data);
                        }
                    }
                }
            }
        } else {

            $data = ([
                'borrow' => "0",
                'message' => "No Such Account Number",
                'limit' => "0",
                'access_code' => "0"
            ]);
            return $this->successResponse("success", $data);
        }
    }

    public function store_manual_loans($customer, $phone, $loan_code, $principle, $disbursment_date, $branch_id)
    {


        $pending = Loans::where(['phone' => $phone, 'loan_status' => 'pending'])->first();
        if ($pending) {
            $message = "You loan of " . $pending->principle . " is waiting approval please contact the administrator";
            return $message;
        }
        $unpaid = Loans::where(['phone' => $phone, 'loan_status' => 'disbursed'])->first();
        if ($unpaid) {
            $message = "You have an existing loan of " . $unpaid->loan_balance . " Please Pay on time  to increase your loan limit";
            return $message;
        }

        $loan_type_available = LoanTypes::where(['loan_code' => $loan_code])->first();
        if ($loan_type_available) {

            $max_borrow = $loan_type_available->max_limit;
            $min_borrow = $loan_type_available->min_limit;
            if ($principle > $max_borrow) {
                $message = "Welcome to SMP Eventures. Maximum amount of KES {$max_borrow} is allowed";

                return $message;
            }
            if ($principle < $min_borrow) {
                $message = "Welcome to SMP Eventures. Minimum amount of KES {$min_borrow} is allowed";

                return $message;
            }

            $customer_name = $customer->firstname . " " . $customer->lastname;
            $repayment_period = $loan_type_available->duration;

            $admin_fee = $loan_type_available->admin_fee;
            $interest = $loan_type_available->interest_rate * $principle;
            $loan_amount = $principle + $admin_fee + $interest;
            $loan_balance = $loan_amount;


            $repayment_date = date('Y-m-d ', strtotime($disbursment_date . ' + ' . $repayment_period . ' days'));
            $penalty_date = date('Y-m-d', strtotime($repayment_date . ' + 2 days'));
            // $repayment_date = $disbursment_date->addDays($repayment_period);
            // $penalty_date = $repayment_date->addDays(2);

            if ($customer->automatic) {
                $loan_status = "pending";
                $automatic = true;
            } else {
                $loan_status = "pending";
                $automatic = false;
            }
            $loan_ref = $this->generateRandomString(16);
            $loan = Loans::create([
                'phone' => $phone,
                'loan_code' => $loan_code,
                'loan_ref' => $loan_ref,
                'principle' => $principle,
                'automatic' => $automatic,
                'loan_disbursed' => $principle,
                'rate_applied' => $loan_type_available->interest_rate,
                'admin_fee' => $admin_fee,
                'interest' => $interest,
                'loan_amount' => $loan_amount,
                'loan_balance' => $loan_balance,
                'repayment_period' => $repayment_period,
                'customer_name' => $customer_name,
                'disbursment_date' => $disbursment_date,
                'loan_status' => $loan_status,
                'repayment_date' => $repayment_date,
                'penalty_date' => $penalty_date,
                'approved_by' => 'Auto',
                'branch_id' => $branch_id,
                'actioned_by' => 'Auto',

            ]);
            // if ($automatic) {
            //     $this->handleAutomatic($loan_ref);
            // }
            $times = $repayment_period / 7;
            $schedule_amount = $loan_balance / $times;
            for ($i = 1; $i <= $times; $i++) {
                $day = $i * 7;

                $repeat = strtotime("+ {$day} days", strtotime($disbursment_date));
                $schedule_date = date('Y-m-d', $repeat);

                $loan = Schedule::create([
                    'phone' => $loan->phone,
                    'loan_ref' => $loan_ref,
                    'due_date' => $schedule_date,
                    'amount' => $schedule_amount,
                    'balance' => $schedule_amount,
                ]);
            }
            return "Your loan Application was Successfull";
        } else {
            return "Your loan Application was not Successfull, Please check the loan product";
        }
    }
    public function sample_store(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'startdate' => 'required|string|max:255',
            'loan_code' => 'required|string|max:255',
            'principle' => 'required|string|max:255',
        ]);
        $phone = $attr['phone'];
        $principle = $attr['principle'];
        $loan_code = $attr['loan_code'];
        $startdate = $attr['startdate'];

        $phone = preg_replace("/^0/", "254", $phone);

        $customer = Customers::where('phone', $phone)->first();
        if ($customer) {
            $branch_id = $customer->branch_id;

            $data = $this->store_manual_loans($customer, $phone, $loan_code, $principle, $startdate, $branch_id);
            return $this->successResponse("success", $data);
        } else {
            return $this->successResponse("success", "Customer Does not Exists");
        }
    }

    public function store(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'access_code' => 'required|string|max:255',
            'loan_code' => 'required|string|max:255',
            'principle' => 'required|string|max:255',
        ]);
        $customer = Customers::where('phone', $attr['phone'])->first();
        if ($customer) {

            $loanlimit = $customer->loanlimit;
            $principle = $attr['principle'];

            if ($principle > $loanlimit) {
                $message = "Welcome to SMP Eventures, Please check the loan amount. please contact the administrator";

                $data = ([
                    'borrow' => "0",
                    'message' => $message,
                    'access_code' => "0"
                ]);
                return $this->successResponse("success", $data);
            }
            if ($customer->blacklist) {
                $message = "Welcome to SMP Eventures, Your credit profile is too low. please contact the administrator";

                $data = ([
                    'borrow' => "0",
                    'message' => $message,
                    'access_code' => "0"
                ]);
                return $this->successResponse("success", $data);
            } else {

                $pending = Loans::where([
                    'phone' => $attr['phone'],
                    'loan_status' => 'pending'
                ])->first();
                if ($pending) {
                    $message = "You loan of " . $pending->principle . " is waiting approval please contact the administrator";

                    $data = ([
                        'borrow' => "0",
                        'message' => $message,
                        'access_code' => "0"
                    ]);
                    return $this->successResponse("success", $data);
                } else {
                    $unpaid = Loans::where([
                        'phone' => $attr['phone'],
                        'loan_status' => 'disbursed',

                    ])->first();
                    if ($unpaid) {

                        $message = "You have an existing loan of " . $unpaid->loan_balance . " Please Pay on time  to increase your loan limit";


                        $data = ([
                            'borrow' => "0",
                            'message' => $message,
                            'access_code' => "0"
                        ]);
                        return $this->successResponse("success", $data);
                    } else {
                        if ($customer->loanlimit == 0) {
                            $message = "Welcome to SMP Eventures. Wait for account activation and loan prequalification";


                            $data = ([
                                'borrow' => "0",
                                'message' => $message,
                                'access_code' => "0"
                            ]);
                            return $this->successResponse("success", $data);
                        } else {


                            $access = Verification::where([
                                'phone' => $attr['phone'],
                                'access_code' => $attr['access_code']
                            ])->first();
                            if ($access) {

                                $loan_type_available = LoanTypes::where([
                                    'loan_code' => $attr['loan_code']
                                ])->first();
                                if ($loan_type_available) {

                                    // check if maximum exceeded:
                                    $max_borrow = $loan_type_available->max_limit;
                                    $min_borrow = $loan_type_available->min_limit;
                                    if ($principle > $max_borrow) {
                                        $message = "Welcome to SMP Eventures. Maximum amount of KES {$max_borrow} is allowed";


                                        $data = ([
                                            'borrow' => "0",
                                            'message' => $message,
                                            'access_code' => "0"
                                        ]);
                                        return $this->successResponse("success", $data);
                                    }
                                    if ($principle < $min_borrow) {
                                        $message = "Welcome to SMP Eventures. Minimum amount of KES {$min_borrow} is allowed";


                                        $data = ([
                                            'borrow' => "0",
                                            'message' => $message,
                                            'access_code' => "0"
                                        ]);
                                        return $this->successResponse("success", $data);
                                    }

                                    $customer_name = $customer->firstname . " " . $customer->lastname;
                                    $repayment_period = $loan_type_available->duration;
                                    $principle = $attr['principle'];
                                    $admin_fee = $loan_type_available->admin_fee;
                                    $interest = $loan_type_available->interest_rate * $principle;
                                    $loan_amount = $principle + $admin_fee + $interest;
                                    $loan_balance = $loan_amount;

                                    $disbursment_date = Carbon::now();
                                    $now = Carbon::rawParse('now')->format('Y-m-d');
                                    $date = Carbon::createFromFormat('Y-m-d', $now);
                                    $repayment_date = $date->addDays($repayment_period);
                                    $penalty_date = $repayment_date->addDays(2);

                                    if ($customer->automatic) {
                                        $loan_status = "pending";
                                        $automatic = true;
                                    } else {
                                        $loan_status = "pending";
                                        $automatic = false;
                                    }
                                    $loan_ref = $this->generateRandomString(16);
                                    $phone = $attr['phone'];
                                    $loan = Loans::create([
                                        'phone' => $phone,
                                        'loan_code' => $attr['loan_code'],
                                        'loan_ref' => $loan_ref,
                                        'principle' => $principle,
                                        'automatic' => $automatic,
                                        'loan_disbursed' => $principle,
                                        'rate_applied' => $loan_type_available->interest_rate,
                                        'admin_fee' => $admin_fee,
                                        'interest' => $interest,
                                        'loan_amount' => $loan_amount,
                                        'loan_balance' => $loan_balance,
                                        'repayment_period' => $repayment_period,
                                        'customer_name' => $customer_name,
                                        'disbursment_date' => $disbursment_date,
                                        'loan_status' => $loan_status,
                                        'repayment_date' => $repayment_date,
                                        'penalty_date' => $penalty_date,
                                        'branch_id' => $customer->branch_id,
                                        'approved_by' => 'Auto',
                                        'actioned_by' => 'Auto',

                                    ]);
                                    // if ($automatic) {
                                    //     $this->handleAutomatic($loan_ref);
                                    // }
                                    $times = $repayment_period / 7;
                                    $schedule_amount = $loan_balance / $times;
                                    for ($i = 1; $i <= $times; $i++) {

                                        $schedule_date = $date->addDays(7);

                                        $loan = Schedule::create([
                                            'phone' => $loan->phone,
                                            'loan_ref' => $loan_ref,
                                            'due_date' => $schedule_date,
                                            'amount' => $schedule_amount,
                                            'balance' => $schedule_amount,
                                        ]);
                                    }
                                    if ($loan) {

                                        $message = "You have a new loan application of KES {$principle} by {$customer_name}- {$phone}";
                                        $title = "New Loan Application";
                                        $result = (new EmailController)->new_application_email($customer, $message, $title);
                                        $data = ([
                                            'borrow' => "1",
                                            'message' => "Loan Application successful",
                                            'access_code' => "0"
                                        ]);
                                        return $this->successResponse("success", $data);
                                    } else {
                                        $data = ([
                                            'borrow' => "0",
                                            'message' => "Failed to Add Loan",
                                            'access_code' => "0"
                                        ]);
                                    }
                                } else {

                                    $data = ([
                                        'borrow' => "0",
                                        'message' => "Invalid Loan Type",
                                        'access_code' => "0"
                                    ]);
                                    return $this->successResponse("success", $data);
                                }
                            } else {

                                $data = ([
                                    'borrow' => "0",
                                    'message' => "Invalid Verification Code",
                                    'access_code' => "0"
                                ]);
                                return $this->successResponse("success", $data);
                            }
                        }
                    }
                }
            }
        } else {

            return $this->errorResponse("No Such Account Number");
        }
    }
    public function activeLoan(Request $request, $phone)
    {

        $loans = Loans::where(['phone' => $phone, 'repayment_status' => false, 'loan_status' => 'disbursed'])->first();
        if ($loans) {
            $data = ([
                'borrow' => "1",
                'message' => 'Loan retrieved successfully',
                'loan' => $loans
            ]);

            return $this->successResponse("success", $data);
        } else {
            $data = ([
                'borrow' => "0",
                'message' => 'You don\'t have any active loan currently, please proceed to Borrow',
                'loan' => new \stdClass()
            ]);
            return $this->successResponse("success", $data);
        }
    }
    public function active_web_loan(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255',
        ]);
        $loans = Loans::where(['phone' => $attr['phone'], 'repayment_status' => false, 'loan_status' => 'disbursed'])->first();
        if ($loans) {
            $data = ([
                'borrow' => 1,
                'message' => 'Loan retrieved successfully',
                'loans' => $loans
            ]);

            return $this->successResponse("success", $data);
        } else {
            $data = ([
                'borrow' => 0,
                'message' => 'You do not have have active loan',
                'loans' => $loans
            ]);
            return $this->successResponse("success", $data);
        }
    }


    public function waive_loan(Request $request)
    {
        $attr = $request->validate([
            'loan_ref' => 'required|string|max:255',
            'trans_code' => 'required|string|max:255',
            'amount' => 'required|integer|between:1,100000',
        ]);

        $amount = $attr['amount'];
        $loan_ref = $attr['loan_ref'];

        $loan = Loans::where(['loan_ref' => $loan_ref])->first();
        if ($loan) {
            $loan_balance = $loan->loan_balance;
            $penalty_amount = $loan->penalty_amount;

            $end = $loan_balance - $amount;
            $end_penalty = $penalty_amount - $amount;

            $less = 0;

            if ($end_penalty > 0) {
                # code...
                $less = $end_penalty;
            }

            if ($end > 0) {
                $loan->loan_balance = $end;
                $loan->penalty_amount = $less;
                $loan->save();
            }
        }

        return $this->successResponse("success", $loan);
    }
    public function payLoanLive(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'trans_code' => 'required|string|max:255',
            'amount' => 'required|integer|between:1,100000',
        ]);

        $amount = $attr['amount'];
        $phone = $attr['phone'];
        // check if account is coming from the request
        $account = $phone;
        if ($request->has('account')) {
            $account = $request->account;
        }
        $reference = $this->generateRandomString(12);
        $mode = Mode::updateOrCreate(
            ['phone' =>  $phone],
            ['description' => 'Payment for Loan', 'amount' => $amount, 'account' => $account, 'mode' => '1', 'reference' => $reference]
        );

        $result = (new IPayController)->make_payment($phone, $amount);
        $data = ([
            'proceed' => "1",
            'result' => $result,
            'message' => 'Payment successfull, Please wait for STK Push'
        ]);
        return $this->successResponse("success", $data);
    }
    public function payLoanOriginal(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'trans_code' => 'required|string|max:255',
            'amount' => 'required|integer|between:1,100000',
        ]);

        $loans = Loans::where(['phone' => $attr['phone'], 'repayment_status' => false, 'loan_status' => 'disbursed'])->first();
        if ($loans) {

            $amount = $attr['amount'];
            $trans_code = $attr['trans_code'];

            $balance = $loans->loan_balance;
            $remainder = $balance - $amount;
            $now = Carbon::rawParse('now')->format('Y-m-d');
            $reference = $this->generateRandomString(12);


            $this->handleReports($loans->loan_ref, $amount, $trans_code);

            if ($remainder > 0) {
                $loans->loan_balance = $remainder;
                $loans->save();
                $payment = Repayments::create([
                    'phone' => $attr['phone'],
                    'loan_ref' => $loans->loan_ref,
                    'date_paid' => $now,
                    'initiator' => $attr['phone'],
                    'reference' => $reference,
                    'paid_amount' => $attr['amount'],
                    'balance' => $remainder,

                ]);
            } else {
                $loans->loan_balance = 0;
                $loans->repayment_status = true;
                $loans->loan_status = 'paid';
                $loans->clear_date = $now;
                $loans->save();

                $repay = Repayments::create([
                    'phone' => $attr['phone'],
                    'loan_ref' => $loans->loan_ref,
                    'date_paid' => $now,
                    'initiator' => $attr['phone'],
                    'reference' => $reference,
                    'paid_amount' => $balance,
                    'balance' => 0,

                ]);
            }
            $customer = Customers::where('phone', $loans->phone)->first();
            $message = "";
            $body = "";
            if ($remainder == 0) {
                $body = "Loan Payment of KES {$amount}. current balance KES {$remainder}";
                $message = "Thank you!! Your loan payment of KES.{$amount} has been received, your loan has been cleared. Thank you for using SMP Eventures. You can borrow again";
            } else  if ($remainder > 0) {
                $body = "Loan Payment of KES {$amount}. current balance KES {$remainder}";
                $message = "Thank you! Your loan payment of KES.{$amount} has been received, your loan balance is {$remainder}. Thank you for using SMP Eventures.";
            } else  if ($remainder < 0) {
                $positive = $remainder * -1;
                $body = "Loan Payment of KES {$amount}. current balance KES 0.00. Overpayment of KES {$positive}";
                $message = "Thank your!! Your loan payment of KES.{$amount} has been received, your  overpayment amount of {$positive} will be deposited to savings. Thank your for using SMP Eventures";
                $this->clear_overpayments_and_deposit_to_savings($loans->phone, $positive);
            }
            $logs = SystemLogs::create([
                'phone' => $loans->phone,
                'title' => "Loan Payment",
                'body' => $body
            ]);

            $result = (new EmailController)->repayment_email($customer, $loans, $message);
            $data = ([
                'borrow' => 1,
                'message' => 'Overpayment Payment successful'
            ]);
            return $this->successResponse("success", $data);
        } else {
            return $this->errorResponse("No Such Loan Record");
        }
    }

    public function clear_overpayments_and_deposit_to_savings($phone, $amount)
    {
        $overpays = Overpayment::where(['phone' => $phone,])->get();
        foreach ($overpays as $over) {
            $over->status = false;
            $over->save();
        }
        $reference = $this->generateRandomString(12);
        $result = (new SavingsController)->savings_callback($phone, $amount, $reference, "hMazYj52pNsZ");
    }
    // Pay Loan Callback URL
    public function pay_loan_callback($phone, $amount, $trans_code, $branch_id)
    {

        $loans = Loans::where(['phone' => $phone, 'repayment_status' => false, 'loan_status' => 'disbursed'])->first();
        if ($loans) {

            $balance = $loans->loan_balance;
            $remainder = $balance - $amount;
            $now = Carbon::rawParse('now')->format('Y-m-d');
            $reference = $loans->loan_ref;


            $this->handleReports($loans->loan_ref, $amount, $trans_code);

            if ($remainder > 0) {
                $loans->loan_balance = $remainder;
                $loans->save();
                $payment = Repayments::create([
                    'phone' => $phone,
                    'loan_ref' => $loans->loan_ref,
                    'date_paid' => $now,
                    'initiator' => $phone,
                    'reference' => $reference,
                    'paid_amount' => $amount,
                    'branch_id' => $branch_id,
                    'balance' => $remainder,

                ]);
            } else {
                $loans->loan_balance = 0;
                $loans->repayment_status = true;
                $loans->loan_status = 'paid';
                $loans->clear_date = $now;
                $loans->save();

                $repay = Repayments::create([
                    'phone' => $phone,
                    'loan_ref' => $loans->loan_ref,
                    'date_paid' => $now,
                    'initiator' => $phone,
                    'branch_id' => $branch_id,
                    'reference' => $reference,
                    'paid_amount' => $amount,
                    'balance' => 0,

                ]);
            }
            $customer = Customers::where('phone', $loans->phone)->first();
            $message = "";
            $body = "";
            if ($remainder == 0) {
                $body = "Loan Payment of KES {$amount}. current balance KES {$remainder}";
                $message = "Thank you!! Your loan payment of KES.{$amount} has been received, your loan has been cleared. Thank you for using SMP Eventures. You can borrow again";
            } else  if ($remainder > 0) {
                $body = "Loan Payment of KES {$amount}. current balance KES {$remainder}";
                $message = "Thank you! Your loan payment of KES.{$amount} has been received, your loan balance is {$remainder}. Thank you for using SMP Eventures.";
            } else  if ($remainder < 0) {
                $positive = $remainder * -1;
                $body = "Loan Payment of KES {$amount}. current balance KES 0.00. Overpayment of KES {$positive}";
                $message = "Thank your!! Your loan payment of KES.{$amount} has been received, your  overpayment amount of {$positive} will be deposited to savings. Thank your for using SMP Eventures";

                $this->clear_overpayments_and_deposit_to_savings($loans->phone, $positive);
            }
            $logs = SystemLogs::create([
                'phone' => $loans->phone,
                'title' => "Loan Payment",
                'body' => $body
            ]);

            $result = (new EmailController)->repayment_email($customer, $loans, $message);
            $data = ([
                'borrow' => 1,
                'message' => 'Overpayment Payment successful'
            ]);
            return $this->successResponse("success", $data);
        } else {
            return $this->errorResponse("No Such Loan Record");
        }
    }

    public function view_single_loan(Request $request, $loan_ref)
    {
        $loan = Loans::where(['loan_ref' => $loan_ref])
            ->first();

        if ($loan) {
            $payments=Repayments::where(['loan_ref' => $loan->loan_ref])->orderBy('created_at', 'desc')->get();
            $schedule=Schedule::where(['loan_ref' => $loan->loan_ref])->orderBy('due_date', 'desc')->get();
            $data = ([
                'proceed' => "1",
                'message' => 'Loan detail successfully retrieved',
                'loan' => $loan,
                'schedule' =>$schedule,
                'payments'=>$payments
            ]);
            return $this->successResponse("success", $data);
        } else {
            $data = ([
                'proceed' => "0",
                'message' => 'Failed to retrieved loan detail'
            ]);
            return $this->successResponse("success", $data);
        }
    }
    public function viewLoans(Request $request, $phone)
    {

        $status = ['pending', 'disbursed', 'paid'];
        $loans = Loans::where(['phone' => $phone])
            ->whereIn('loan_status', $status)
            ->get();
        if ($loans) {
            return $this->successResponse("success", $loans);
        } else {
            return $this->errorResponse("No Such Loan Record");
        }
    }
    public function schedule($id)
    {
        $schedules = Schedule::where(['loan_ref' => $id,])->orderBy('due_date', 'desc')->get();
        if ($schedules) {

            return $this->successResponse("success", $schedules);
        } else {
            return $this->errorResponse("No Such Loan Record");
        }
    }
    public function repayments($id)
    {
        $repayment = Repayments::where(['loan_ref' => $id,])->orderBy('created_at', 'desc')->get();
        if ($repayment) {

            return $this->successResponse("success", $repayment);
        } else {
            return $this->errorResponse("No Such Loan Record");
        }
    }
    public function update_penalty_date()
    {
        $all = Loans::all();
        if ($all) {
            foreach ($all as $loan) {
                $penalty_date =  (new Carbon($loan->repayment_date))->addDays(2);
                $all_paid = Repayments::where(['loan_ref' => $loan->loan_ref])->sum('paid_amount');

                $la = $loan->loan_amount;
                $ba = $la - $all_paid;

                $loan->loan_balance = $ba;
                $loan->penalty_date = $penalty_date;
                $loan->penalty_amount = 0;
                $loan->save();
            }

            return $this->successResponse("success", $all);
        } else {
            return $this->errorResponse("No Such Loan Record");
        }
    }
    public function apply_penalty()
    {
        $date = Carbon::today();
        $overdue = Loans::whereDate('penalty_date', '<=', $date)->where(['loan_status' => 'disbursed', 'repayment_status' => false])->orderBy('created_at', 'desc')->get();
        $penalty_amount = 0;
        if ($overdue) {
            foreach ($overdue as $single) {

                $penalty_date =  (new Carbon($single->penalty_date))->addDays($single->repayment_period);

                $max = 2 * $single->principle;

                $next = $single->loan_balance * $single->rate_applied;
                if ($next > $max) {
                    $penalty_amount = $max - $single->principle;
                } else {

                    $penalty_amount = $next;
                }

                $single->penalty_date = $penalty_date;
                $single->penalty_amount = $single->penalty_amount + $penalty_amount;
                // $single->save();
            }

            return $this->successResponse("success", $overdue);
        } else {
            return $this->errorResponse("No Such Loan Record");
        }
    }
    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function close_month(Request $request)
    {
        $attr = $request->validate([
            'month' => 'required|string|max:255',
            'amount' => 'required|string|max:255'
        ]);

        $month = Carbon::parse($request->month);
        $fcm = RunningBalances::updateOrCreate(
            ['month' =>  $month->format('F Y')],
            ['amount' => request('amount')]
        );

        if ($fcm) {
            return $this->successResponse("success", $fcm);
        } else {
            return $this->errorResponse("Failed to Close Month");
        }
    }

    public function view_months()
    {
        $all = RunningBalances::all();
        if ($all) {

            return $this->successResponse("success", $all);
        } else {
            return $this->errorResponse("No Such Loan Record");
        }
    }

    public function handleReports($loan_ref, $amount, $trans_code)
    {
        $loan = Loans::where(['loan_ref' => $loan_ref])->first();

        $principle_paid = JournalEntries::where(['reference' => $loan_ref, 'credit_account' => "B020"])->sum('amount');
        $interest_paid = JournalEntries::where(['reference' => $loan_ref, 'credit_account' => "F100"])->sum('amount');
        $admin_paid = JournalEntries::where(['reference' => $loan_ref, 'credit_account' => "F200"])->sum('amount');

        $narration = "Loan Repayment for " . $loan->phone;

        $now = Carbon::rawParse('now')->format('Y-m-d');
        $today = Carbon::createFromFormat('Y-m-d', $now);

        $principle = $loan->principle;
        $phone = $loan->phone;
        $interest = $loan->interest;
        $admin_fee = $loan->admin_fee;
        $balance = $loan->loan_balance - $amount;

        $penalty = $loan->penalty_amount;

        if ($principle_paid == 0) {
            if ($amount == $principle) {
                $this->journal_b035_b020($loan_ref, $principle, $today, $narration, $trans_code);
            } else if ($amount < $principle) {
                $this->journal_b035_b020($loan_ref, $amount, $today, $narration, $trans_code);
            } else if ($amount > $principle) {
                $b = $amount - $principle;
                if ($b <= $interest) {
                    $this->journal_b035_f100($loan_ref, $b, $today, "Commision", $trans_code);
                } else {
                    $overpayment = $b - $interest - $admin_fee;
                    if ($overpayment > 0) {


                        // check for penalties

                        if ($penalty > 0) {
                            $penalty_balance = $penalty - $overpayment;
                            if ($penalty_balance == 0) {
                                # code...
                                $loan->penalty_amount = 0;
                                $loan->save();
                            } else if ($penalty_balance > 0) {
                                # code...
                                $loan->penalty_amount = $penalty_balance;
                                $loan->save();
                            } else {
                                $loan->penalty_amount = 0;
                                $loan->save();
                                // returns negative value
                                $negative = $penalty_balance * -1;
                                $this->journal_b035_c140($loan_ref, $negative, $today, "Suspense Account", $trans_code);

                                $over = Overpayment::create([
                                    'phone' => $phone,
                                    'loan_ref' => $loan_ref,
                                    'amount' => $negative

                                ]);
                            }
                        } else {
                            $this->journal_b035_c140($loan_ref, $overpayment, $today, "Suspense Account", $trans_code);

                            $over = Overpayment::create([
                                'phone' => $phone,
                                'loan_ref' => $loan_ref,
                                'amount' => $overpayment

                            ]);
                        }
                    }
                    $this->journal_b035_f100($loan_ref, $interest, $today, "Commision2", $trans_code);

                    $this->journal_b035_f200($loan_ref, $admin_fee, $today, "Commision1", $trans_code);
                }
            }
        } else {
            // Partial Payments
            $newamount = ($principle_paid + $amount) - $principle;
            $fh = 0;
            if ($newamount <= $fh) {
                $this->journal_b035_b020($loan_ref, $amount, $today, $narration, $trans_code);
            } else {
                $newprinciple = $principle - $principle_paid;
                $newintrest = $amount - $newprinciple;
                $this->journal_b035_b020($loan_ref, $newprinciple, $today, $narration, $trans_code);

                if ($newintrest <= $interest) {
                    $this->journal_b035_f100($loan_ref, $newintrest, $today, "Commision", $trans_code);
                } else {
                    $jint = $interest - $interest_paid;
                    $Adminf = $admin_fee - $admin_paid;
                    $this->journal_b035_f100($loan_ref, $jint, $today, "Commision", $trans_code);

                    $this->journal_b035_f200($loan_ref, $Adminf, $today, "Commision", $loan_ref);

                    $postive_figure = $balance * -1;

                    if ($penalty > 0) {
                        $penalty_balance = $penalty - $postive_figure;
                        if ($penalty_balance == 0) {
                            # code...
                            $loan->penalty_amount = 0;
                            $loan->save();
                        } else if ($penalty_balance > 0) {
                            # code...
                            $loan->penalty_amount = $penalty_balance;
                            $loan->save();
                        } else {
                            $loan->penalty_amount = 0;
                            $loan->save();
                            // returns negative value
                            $negative = $penalty_balance * -1;
                            $this->journal_b035_c140($loan_ref, $negative, $today, "Suspense Account", $trans_code);

                            $over = Overpayment::create([
                                'phone' => $phone,
                                'loan_ref' => $loan_ref,
                                'amount' => $negative

                            ]);
                        }
                    } else {
                        $this->journal_b035_c140($loan_ref, $postive_figure, $today, "Suspense Account", $trans_code);

                        $over = Overpayment::create([
                            'phone' => $phone,
                            'loan_ref' => $loan_ref,
                            'amount' => $postive_figure

                        ]);
                    }
                }
            }
        }
    }

    public function recreate_schedule(Request $request)
    {
        $attr = $request->validate([
            'loan_ref' => 'required|string|max:255'
        ]);
        $loan_ref = $attr['loan_ref'];
        $loan = Loans::where(['loan_ref' => $loan_ref])->first();

        if ($loan) {
            $repayment_period = $loan->repayment_period;
            $loan_balance = $loan->loan_amount;
            $then = $loan->disbursment_date;

            $times = $repayment_period / 7;
            $schedule_amount = $loan_balance / $times;

            $date = Carbon::parse($then);

            $deletedRows = Schedule::where('loan_ref', $loan_ref)->delete();

            for ($i = 1; $i <= $times; $i++) {

                $schedule_date = $date->addDays(7);

                $sh = Schedule::create([
                    'phone' => $loan->phone,
                    'loan_ref' => $loan->loan_ref,
                    'due_date' => $schedule_date,
                    'amount' => $schedule_amount,
                    'balance' => $schedule_amount,
                ]);
            }

            return $this->successResponse("success", $sh);
        }
    }
    public function web_loans(Request $request, $id, $phone)
    {


        $loans = [];
        $loantypes = LoanTypes::where('active', true)->orderBy('created_at', 'desc')->get();
        if ($id == "pending") {
            $loans = Loans::where(['loan_status' => $id, 'phone' => $phone])->orderBy('created_at', 'desc')->get();
        } else if ($id == "disbursed") {
            $loans =  Loans::where(['loan_status' => 'disbursed', 'phone' => $phone])
                ->orWhere(['loan_status' => 'paid', 'phone' => $phone])
                ->orderBy('created_at', 'desc')
                ->get();
        } else if ($id == "paid") {
            $loans = Loans::where(['loan_status' => $id, 'phone' => $phone])
                ->orderBy('created_at', 'desc')
                ->get();
        } else if ($id == "rejected") {
            $loans = Loans::where(['loan_status' => $id, 'phone' => $phone])
                ->orderBy('created_at', 'desc')
                ->get();
        } else if ($id == "overdue") {
            $date = Carbon::today();
            $loans = Loans::whereDate('repayment_date', '<=', $date)
                ->where(['loan_status' => 'disbursed', 'repayment_status' => false, 'phone' => $phone])
                ->orderBy('created_at', 'desc')
                ->get();
        }


        $data = ([
            'loantypes' => $loantypes,
            'loans' => $loans
        ]);
        return $this->successResponse("success", $data);
    }
}
