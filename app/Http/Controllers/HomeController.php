<?php

namespace App\Http\Controllers;

use App\Http\Traits\CommonTrait;
use App\Models\AccountBalance;
use App\Models\Admins;
use App\Models\BulkStatement;
use App\Models\Buyforme;
use App\Models\ChatRoom;
use App\Models\Branch;
use App\Models\Chats;
use App\Models\Commitment;
use App\Models\CustomerGroup;
use App\Models\Customers;
use App\Models\CustomerSavings;
use App\Models\Disbursement;
use App\Models\Dividend;
use App\Models\FloatStatements;
use App\Models\Reminder;
use App\Models\Guarantor;
use App\Models\JournalEntries;
use App\Models\Loans;
use App\Models\LoanTypes;
use App\Models\LockBuy;
use App\Models\Message;
use App\Models\MessageType;
use App\Models\Mode;
use App\Models\Note;
use App\Models\Option;
use App\Models\Payouts;
use App\Models\Pool;
use App\Models\ProductGroup;
use App\Models\ProfitShare;
use App\Models\Repayments;
use App\Models\Response;
use App\Models\SavingInterest;
use App\Models\Savings;
use App\Models\SavingsProducts;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Share;
use App\Models\Social;
use App\Models\SystemLogs;
use App\Models\Thirdparty;
use App\Models\User;
use App\Models\Welfare;
use App\Models\Withdrawals;
use App\Models\WithdrawalTransaction;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDF;
use Illuminate\Support\Facades\Session;


class HomeController extends Controller
{

    use CommonTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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

    public function generateOTP()
    {

        $characters = '123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 5; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function add()
    {
        return view('customers.add');
    }
    public function exit()
    {
        Session::flush();

        Auth::logout();

        return redirect('login');
    }
    public function switch()
    {
        $branch_id = session('branch_id');
        if (is_null($branch_id)) {
            return redirect()->to('/branches/select');
        }
        if ($branch_id == 1) {
            session(['branch_id' => 2]);
        } else {
            session(['branch_id' => 1]);
        }
        return redirect()->route('home');
    }
    public function account()
    {
        $user = Auth::user();
        $data['data'] = Admins::where('email', $user->email)->first();
        $logs['logs'] = SystemLogs::all();

        return view('admins.account')->with($data)->with($logs);
    }

    public function invoice()
    {
        $url = "https://invoices.pharmacyboardkenya.org/token";


        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "APPID: c4ca4238a0b923820dcc509a6f75849b",
            "APIKEY: YzM4ZWRhMTMwNzViMGJjZDJiMGVkNjkzOWRlNzFmMDhkZTA2YTUzNA=="
        );

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        curl_close($curl);

        $result_arr = json_decode($response, true);

        $session_token = $result_arr['session_token'];

        /* 
        Generate your invoice and store in your DB
*/

        $invoice_total = 1000;

        $invoice_url = "https://invoices.pharmacyboardkenya.org/invoice";


        $invoice_total = $invoice_total * 0.0075;


        $curl = curl_init($invoice_url);
        curl_setopt($curl, CURLOPT_URL, $invoice_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $data = array(
            'payment_type' => 'Clinical_Trials', // Types are issued e.g. Clinical_Trials
            'amount_due' => $invoice_total, // from your invoice
            'user_id' => 1, // from PRIMS login
            'session_token' => $session_token // from above
        );

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $resp = curl_exec($curl);
        curl_close($curl);

        $result_invoiceid = json_decode($resp, true);

        // Do stuff with feedback e.g. getting invoice id that can be used to test payment from eCitizen like below

        $invoice_id = $result_invoiceid['invoice_id'];

        $raw_id = base64_encode($invoice_id);

        //example of payment details query https://prims.pharmacyboardkenya.org/scripts/get_status?invoice=264526

        return   $paymentdetails = json_decode("https://prims.pharmacyboardkenya.org/scripts/get_status?invoice=$invoice_id", true);


        $ecitizeniframe = "https://payments.ecitizen.go.ke/PaymentAPI/iframev2.1.php";


        $curl = curl_init($ecitizeniframe);
        curl_setopt($curl, CURLOPT_URL, $ecitizeniframe);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $data = array(
            'secureHash' => $paymentdetails["secureHash"],
            'apiClientID' => 42,
            'serviceID' => $paymentdetails["ecitizen_service_id"],
            'notificationURL' => 'https://practice.pharmacyboardkenya.org/ipn?id=' . $raw_id,
            'callBackURLOnSuccess' => 'https://practice.pharmacyboardkenya.org/callback?id=' . $raw_id,
            'billRefNumber' => $paymentdetails["billRefNumber"],
            'currency' => $paymentdetails["currency"],
            'amountExpected' => $paymentdetails["amountExpected"],
            'billDesc' => $paymentdetails["billDesc"],
            'pictureURL' => $paymentdetails["pictureURL"],
            'clientName' => $paymentdetails["clientName"],
            'clientEmail' => $paymentdetails["clientEmail"],
            'clientMSISDN' => $paymentdetails["clientMSISDN"],
            'clientIDNumber' => $paymentdetails["clientIDNumber"],
        );

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $resp = curl_exec($curl);
        echo $resp;
        curl_close($curl);


        echo '<h2><a href="https://prims.pharmacyboardkenya.org/crunch?type=ecitizen_invoice&id=' . $raw_id . '">Download Invoice</a></h2>'; // Official PPB Invoice

        /*
        Create your own invoice if you desire
  */

        echo 'your own invoice';
    }
    public function changer(Request $request)
    {
        $attr = $request->validate([
            'password' => 'required|string|max:255|min:6',
            'confirm' => 'required|string|max:255|min:6'
        ]);
        $password = $attr['password'];
        $confirm = $attr['confirm'];

        if ($password != $confirm) {

            return redirect()->to('account')->with('error', 'Password Does not Match!!');
        }

        $auth = Auth::user();

        $user = User::where(['email' => $auth->email])->first();
        $user->password = Hash::make($password);
        $user->save();

        $admin = Admins::where(['email' => $auth->email,])->first();
        if ($admin) {
            $admin->password = Hash::make($password);
            $admin->save();
        }

        return redirect()->to('exit')->with('success', 'Password Updated Successfully');
    }
    public function index()
    {
        $user = Auth::user();
        $message = "";

        $mytime = Carbon::now();

        $admin = Admins::where('email', $user->email)->first();
        $branch_id = session('branch_id');
        if (is_null($branch_id)) {
            return redirect()->to('/branches/select');
        }
        //     $otp = $this->generateOTP();

        //     $message =  "Your One Time Password is {$otp}";


        //     $admin->ot_time = $mytime;
        //     $admin->otp = $otp;
        //     $admin->save();
        //     $result = (new EmailController)->new_otp_email($admin, $admin->email, $message);
        //     return redirect()->to('/otp');
        // }
        // $last_otp_time = $admin->ot_time;

        // $result = $mytime->gt($last_otp_time);
        // if ($result) {
        //     $otp = $this->generateOTP();
        //     $message =  "Your One Time Password is {$otp}";
        //     $admin->ot_time = $mytime;
        //     $admin->otp = $otp;
        //     $admin->save();
        //     $result = (new EmailController)->new_otp_email($admin, $admin->email, $message);
        //     return redirect()->to('/otp');
        // }

        $today = today();
        $dates = [];
        $repay = [];
        $disburse = [];
        $depos = [];
        $withs = [];
        $interest = [];
        $interestWithdraw = [];
        $admin = [];
        $interest_month = 0;
        $tulipo = Carbon::createFromDate($today->year, $today->month)->format('d');
        DB::table('temp__chartsof_accs')->delete();

        for ($i = 1; $i <= $tulipo; ++$i) {
            $leo = Carbon::createFromDate($today->year, $today->month, $i)->format('d');
            $two = Carbon::createFromDate($today->year, $today->month, $i)->format('m');
            $three = Carbon::createFromDate($today->year, $today->month, $i)->format('Y');
            $dates[] = $leo;
            $repay[] = Repayments::whereDay('created_at', $leo)->whereMonth('created_at', $two)->whereYear('created_at', $three)->where(['branch_id' => $branch_id])->sum('paid_amount');
            $disburse[] = Disbursement::whereDay('created_at', $leo)->whereMonth('created_at', $two)->whereYear('created_at', $three)->where(['branch_id' => $branch_id])->sum('amount');
            $depos[] = Savings::whereDay('created_at', $leo)->where(['branch_id' => $branch_id])->sum('amount');
            $withs[] = WithdrawalTransaction::whereDay('created_at', $leo)->where(['branch_id' => $branch_id])->sum('amount');
            $int = Loans::whereDay('clear_date', $leo)->whereMonth('clear_date', $two)->whereYear('clear_date', $three)
                ->where(['branch_id' => $branch_id])->sum('interest');
            $divwith = Dividend::whereDay('created_at', $leo)->whereMonth('created_at', $two)->whereYear('created_at', $three)
                // ->where(['branch_id' => $branch_id])
                ->where(['paid' => true])
                ->sum('available');
            $divwith = $divwith * -1;
            $interest[] = $int;
            $interestWithdraw[] = $divwith;
            $int_admin = Loans::whereDay('clear_date', $leo)->whereMonth('clear_date', $two)->whereYear('clear_date', $three)
                ->where(['branch_id' => $branch_id])->sum('admin_fee');
            $admin[] = $int_admin;
            $interest_month += $int;
        }


        // return $interestWithdraw;
        $approved_customers = Customers::where(['status' => 'Approved', 'branch_id' => $branch_id])->count();
        $total_repayments = Repayments::where(['branch_id' => $branch_id])->sum('paid_amount');
        $disbursed_principal_disbursed = Loans::where(['loan_status' => 'disbursed', 'branch_id' => $branch_id])->sum('principle');
        $disbursed_principal_paid = Loans::where(['loan_status' => 'paid', 'branch_id' => $branch_id])->sum('principle');
        $disbursed_principal = $disbursed_principal_disbursed + $disbursed_principal_paid;
        $unpaid_loans = Loans::where(['repayment_status' => false, 'loan_status' => 'disbursed', 'branch_id' => $branch_id])->sum('loan_balance');
        $all_customers = Customers::where(['branch_id' => $branch_id])->count();
        $total_savings = CustomerSavings::where(['branch_id' => $branch_id])->sum('amount');
        $disbursed_interest_disbursed = Loans::where(['loan_status' => 'disbursed', 'branch_id' => $branch_id])->sum('interest');
        $disbursed_interest_paid = Loans::where(['loan_status' => 'paid', 'branch_id' => $branch_id])->sum('interest');
        $disbursed_interest = $disbursed_interest_disbursed + $disbursed_interest_paid;


        $all_male = Customers::where(['gender' => 'Male', 'branch_id' => $branch_id])->count();
        $all_female = Customers::where(['gender' => 'Female', 'branch_id' => $branch_id])->count();

        $disbursed_admin_fee_disbursed = Loans::where(['loan_status' => 'disbursed', 'branch_id' => $branch_id])->sum('admin_fee');
        $disbursed_admin_fee_paid = Loans::where(['loan_status' => 'paid', 'branch_id' => $branch_id])->sum('admin_fee');
        $disbursed_admin_fee = $disbursed_admin_fee_disbursed + $disbursed_admin_fee_paid;

        $all_unpaid = Loans::where(['repayment_status' => false, 'loan_status' => 'disbursed', 'branch_id' => $branch_id])->count();
        $all_paid = Loans::where(['loan_status' => 'paid', 'branch_id' => $branch_id])->count();
        $all_disbursed_disbursed = Loans::where(['loan_status' => 'disbursed', 'branch_id' => $branch_id])->count();
        $all_disbursed_paid = Loans::where(['loan_status' => 'paid', 'branch_id' => $branch_id])->count();
        $all_disbursed = $all_disbursed_disbursed + $all_disbursed_paid;

        $all_partially = Loans::whereRaw('loans.loan_balance < loans.loan_amount')->where(['loan_status' => 'disbursed', 'branch_id' => $branch_id])->count();
        $penalties = Loans::where(['branch_id' => $branch_id])->sum('penalty_amount');

        $leo = Carbon::today();
        $all_overdue  = Loans::whereDate('repayment_date', '<=', $leo)->where(['loan_status' => 'disbursed', 'repayment_status' => false, 'branch_id' => $branch_id])->count();

        $total_b4m = Pool::all()->sum('amount');
        $total_welfare = CustomerSavings::where(['branch_id' => $branch_id])->sum('welfare');
        $total_shares = CustomerSavings::where(['branch_id' => $branch_id])->sum('share_capital');
        $grand_total = $total_b4m + $total_savings + $total_welfare + $total_shares + $total_repayments;


        $system_balance = 0;
        $paybill_balance = 0;
        $balances_account = AccountBalance::where(['status' => true, 'branch_id' => $branch_id])->first();
        if ($balances_account) {

            $system_balance = $balances_account->bulk;
            $paybill_balance = $balances_account->paybill;
        }
        // Outstanding Principal
        $outstanding_unpaid_principal = 0;
        $total_loans = $all_disbursed;
        $i = 0;
        $fully_paid = Loans::where(['loan_status' => 'paid', 'branch_id' => $branch_id])->count();
        $unpaid_loans_outstanding = Loans::where(['repayment_status' => false, 'loan_status' => 'disbursed', 'branch_id' => $branch_id])->get();
        if ($unpaid_loans_outstanding) {

            $total_paid = 0;
            $total_principal_unpaid = 0;

            $total_principal = 0;
            $total_balance_oustanding = 0;


            foreach ($unpaid_loans_outstanding as $single) {
                $i++;
                $total_balance_oustanding += $single->loan_balance;

                $current_principal = $single->principle;
                $sum_paid_oustanding = Repayments::where(['loan_ref' => $single->loan_ref, 'branch_id' => $branch_id])->sum('paid_amount');

                $unpaid = $current_principal - $sum_paid_oustanding;

                $total_paid += $sum_paid_oustanding;
                $total_principal += $current_principal;

                if ($unpaid > 0) {
                    $outstanding_unpaid_principal += $unpaid;
                }
            }
        }

        $savingsproducts = SavingsProducts::select(DB::raw('savings_products.*, ifnull(SUM(savings.amount),0) as revenue'))
            ->leftJoin('savings', 'savings.product', '=', 'savings_products.product_code')
            ->where(['savings_products.branch_id' => $branch_id])
            ->groupBy('savings_products.id', 'savings_products.product_code', 'savings_products.product_name', 'savings_products.duration', 'savings_products.min_limit', 'savings_products.max_limit', 'savings_products.interest_rate', 'savings_products.admin_fee', 'savings_products.active', 'savings_products.created_at', 'savings_products.updated_at')
            ->get();

        $billings = ProfitShare::select(DB::raw('year, month,MAX(id) as max_id, SUM(earnings) AS total_earnings'))
            ->groupBy('year', 'month')
            ->orderBy('max_id', 'desc')
            ->get();

        // return $billings;

        $current_timestamp = Carbon::today();
        $one = $current_timestamp->format('Y');
        $two = $current_timestamp->format('m');
        $three = $current_timestamp->format('d');

        $disburse_year = Disbursement::whereYear('created_at', $one)->where(['branch_id' => $branch_id])->sum('amount');
        $disburse_month = Disbursement::whereMonth('created_at', $two)->whereYear('created_at', $one)->where(['branch_id' => $branch_id])->sum('amount');
        $disburse_today = Disbursement::whereDay('created_at', $three)->whereMonth('created_at', $two)->whereYear('created_at', $one)->where(['branch_id' => $branch_id])->sum('amount');

        $w_all = Dividend::whereYear('created_at', $one)/*->where(['branch_id' => $branch_id])*/->sum('available');
        $w_year = Dividend::whereYear('created_at', $one)/*->where(['branch_id' => $branch_id])*/->sum('available');
        $w_month = Dividend::whereMonth('created_at', $two)->whereYear('created_at', $one)/*->where(['branch_id' => $branch_id])*/->sum('available');
        $w_today = Dividend::whereDay('created_at', $three)->whereMonth('created_at', $two)->whereYear('created_at', $one)/*->where(['branch_id' => $branch_id])*/->sum('available');


        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 days'));
        $today_due = Loans::dueToday();
        $tommorow_due = Loans::dueToday();


        $paid_interest = Loans::where(['loan_status' => 'paid'])->sum('interest');

        // get sum of earnings from profitshare where phone is not system 

        $paid_investor = ProfitShare::where(['status' => true])->where('phone', '!=', 'system')->sum('earnings');
        $unpaid_investor = ProfitShare::where(['status' => false])->where('phone', '!=', 'system')->sum('earnings');

        $available = Dividend::where(['paid' => false])->sum('available');

        $branch_name = "";
        $br = Branch::where(['id' => $branch_id])->first();
        if ($br) {
            $branch_name = $br->name;
        }
        $current = 0;
        $available = 0;
        $savings = CustomerSavings::join('customers', 'customer_savings.phone', '=', 'customers.phone')
            ->where(['customer_savings.branch_id' => $branch_id])
            ->orderBy('customer_savings.created_at', 'desc')
            ->get(['customer_savings.*', 'customers.firstname', 'customers.lastname']);

        foreach ($savings as $saving) {
            $av = Dividend::where(['paid' => false, 'phone' => $saving->phone])->sum('available');
            $c = Dividend::where(['iswithdrawal' => false, 'phone' => $saving->phone])->sum('amount');
            $available += $av;
            $current += $c;
        }
        $collection_today = self::calculate_recollection_today();
        $collection_month = self::calculate_recollection_this_month();
        $collection_year = self::calculate_recollection_this_year();
        $collection_all = self::calculate_all_recollections();

        $data = ([
            'collection_today' => $collection_today,
            'collection_month' => $collection_month,
            'collection_year' => $collection_year,
            'collection_all' => $collection_all,
            'branch_name' => $branch_name,
            'unpaid_investor' => number_format($unpaid_investor, 0, '.', ','),
            'paid_interest' => number_format($paid_interest, 0, '.', ','),
            'paid_investor' => number_format($paid_investor, 0, '.', ','),
            'today' => $today_due,
            'tomorrow' => $tommorow_due,
            'matured' => $all_overdue,
            'past' => $all_overdue,
            'available' => number_format($available, 0, '.', ','),
            'disburse_today' =>  number_format($disburse_today, 0, '.', ','),
            'disburse_month' =>  number_format($disburse_month, 0, '.', ','),
            'disburse_year' =>  number_format($disburse_year, 0, '.', ','),
            'current' =>  number_format($this->nothing_less_than_zero($current), 0, '.', ','),
            'available' =>  number_format($this->nothing_less_than_zero($available), 0, '.', ','),
            'w_today' =>  number_format($this->nothing_less_than_zero($w_today), 0, '.', ','),
            'w_month' =>  number_format($this->nothing_less_than_zero($w_month), 0, '.', ','),
            'w_year' =>  number_format($this->nothing_less_than_zero($w_year), 0, '.', ','),
            'w_all' =>  number_format($this->nothing_less_than_zero($w_all), 0, '.', ','),
            'paybill_balance' => number_format($paybill_balance, 0, '.', ','),
            'system_balance' =>  number_format($system_balance, 0, '.', ','),
            'open_loans' => $i,
            'total_loans' => $total_loans,
            'fully_paid' => $fully_paid,
            'outstanding_unpaid_principal' => number_format($outstanding_unpaid_principal, 0, '.', ','),
            'approved_customers' => $approved_customers,
            'total_repayments' => number_format($total_repayments, 0, '.', ','),
            'disbursed_principal' => number_format($disbursed_principal, 0, '.', ','),
            'unpaid_loans' => number_format($unpaid_loans, 0, '.', ','),
            'all_customers' => $all_customers,
            'disbursed_interest' => number_format($disbursed_interest, 0, '.', ','),
            'disbursed_admin_fee' => number_format($disbursed_admin_fee, 0, '.', ','),
            'total_savings' => number_format($total_savings, 0, '.', ','),
            'penalties' => number_format($penalties, 0, '.', ','),
            'dates' => json_encode(array_values($dates)),
            'repay' => json_encode(array_values($repay)),
            'disburse' => json_encode(array_values($disburse)),
            'depos' => json_encode(array_values($depos)),
            'withs' => json_encode(array_values($withs)),
            'interest' => json_encode(array_values($interest)),
            'interestWithdraw' => json_encode(array_values($interestWithdraw)),
            'admin' => json_encode(array_values($admin)),
            'male' => $all_male,
            'female' => $all_female,
            'all_unpaid' => $all_unpaid,
            'all_paid' => $all_paid,
            'all_disbursed' =>  number_format($all_disbursed, 0, '.', ','),
            'all_overdue' =>  number_format($all_overdue, 0, '.', ','),
            'all_partially' =>  number_format($all_partially, 0, '.', ','),
            'total_b4m' => number_format($total_b4m, 0, '.', ','),
            'total_welfare' => number_format($total_welfare, 0, '.', ','),
            'total_shares' => number_format($total_shares, 0, '.', ','),
            'grand_total' => number_format($grand_total, 0, '.', ','),
            'interest_month' => number_format($interest_month, 0, '.', ','),
            'savingsproducts' => $savingsproducts,
            'billings' => $billings


        ]);
        $data['data'] = $data;
        return view('home', $data);
    }

    public function loans_repayments_monthly()
    {

       
        $repayments = Repayments::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(paid_amount) as total_paid')
            ->groupBy('year', 'month')
            ->orderBy('year','DESC')
            ->orderBy('month','DESC')
            ->get();

            $data=[];
        foreach ($repayments as $repayment) {
            $year = $repayment->year;
            $monthName = Carbon::createFromDate($year, $repayment->month, 1)->format('F'); // Get the full month name
            $totalPaid = $repayment->total_paid;

            $data[]=array(
                'month'=>"{$monthName} {$year}",
                'amount'=>number_format($totalPaid, 0, '.', ',')
            );
            // echo "Year: $year, Month: $monthName, Total Paid: $totalPaid\n";
        }
        $data['data'] = $data;
        return view('loans.collections', $data);
    }
    public function calculate_all_recollections()
    {
        $branch_id = session('branch_id');
        $total = Repayments::where(['branch_id' => $branch_id])->sum('paid_amount');
        return  number_format($total, 0, '.', ',');
    }

    public function calculate_recollection_today()
    {
        $today = Carbon::now();
        $currentDay = Carbon::createFromDate($today->year, $today->month, $today->day)->format('d');
        $currentMonth = Carbon::createFromDate($today->year, $today->month, 1)->format('m');
        $currentYear = Carbon::createFromDate($today->year, $today->month, $today->day)->format('Y');
        $branch_id = session('branch_id');
        $total = Repayments::whereDay('created_at', $currentDay)->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->where(['branch_id' => $branch_id])->sum('paid_amount');
        return  number_format($total, 0, '.', ',');
    }
    public function calculate_recollection_this_month()
    {
        $today = Carbon::now();
        $currentMonth = Carbon::createFromDate($today->year, $today->month, 1)->format('m');
        $currentYear = Carbon::createFromDate($today->year, $today->month, $today->day)->format('Y');
        $branch_id = session('branch_id');
        $total = Repayments::whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->where(['branch_id' => $branch_id])->sum('paid_amount');
        return  number_format($total, 0, '.', ',');
    }
    public function calculate_recollection_this_year()
    {
        $today = Carbon::now();
        $currentYear = Carbon::createFromDate($today->year, $today->month, $today->day)->format('Y');
        $branch_id = session('branch_id');
        $total = Repayments::whereYear('created_at', $currentYear)->where(['branch_id' => $branch_id])->sum('paid_amount');
        return  number_format($total, 0, '.', ',');
    }

    public function nothing_less_than_zero($value)
    {
        if ($value < 0) {
            $value = $value * -1;
        }
        return $value;
    }
    public function checkotp(Request $request)
    {

        $attr = $request->validate([
            'otp' => 'required|string|max:255'
        ]);
        $otp = $attr['otp'];

        $mytime = Carbon::now();

        $user = Auth::user();
        $admin = Admins::where(['email' => $user->email, 'otp' => $otp])->first();
        if ($admin) {

            $admin->ot_time = $mytime->addHours(3);
            $admin->otp = $otp;
            $admin->save();

            return redirect()->to('home');
        } else {

            return redirect()->to('otp')->with('error', 'Invalid OTP');
        }
    }

    public function otp_page()
    {
        $user = Auth::user();
        $admin['admin'] = Admins::where('email', $user->email)->first();
        return view('admins.otp')->with($admin);
    }


    public function pending_customers()
    {
        $branch_id = $this->check_current_branch();
        $data['customers'] = Customers::where(['status' => 'Pending', 'branch_id' => $branch_id])->orderBy('created_at', 'desc')->get();
        return view('customers.pending', $data);
    }
    public function all()
    {
        $branch_id = $this->check_current_branch();
        $data['customers'] = Customers::where(['status' => 'Approved', 'branch_id' => $branch_id])->orderBy('created_at', 'desc')->get();
        return view('customers.approved', $data);
    }

    public function view_customer($id)
    {



        $customer = Customers::where('id', $id)->first();

        // Customer Savings
        $save = CustomerSavings::where(['phone' => $customer->phone])->first();

        // Pending
        $pending = Loans::where(['phone' => $customer->phone, 'loan_status' => 'pending'])->orderBy('created_at', 'desc')->get();
        // Approved
        $approved = Loans::where(['phone' => $customer->phone, 'loan_status' => 'disbursed'])->orderBy('created_at', 'desc')->get();
        // Paid
        $paid = Loans::where(['phone' => $customer->phone, 'loan_status' => 'paid'])->orderBy('created_at', 'desc')->get();
        // Rejected
        $rejected = Loans::where(['phone' => $customer->phone, 'loan_status' => 'rejected'])->orderBy('created_at', 'desc')->get();
        // Overdue 
        $date = Carbon::today();
        $overdue = Loans::whereDate('repayment_date', '<=', $date)->where(['loan_status' => 'disbursed', 'repayment_status' => false, 'phone' => $customer->phone])->orderBy('created_at', 'desc')->get();

        $savings = Savings::join('savings_products', 'savings_products.product_code', '=', 'savings.product')
            ->where(['savings.phone' => $customer->phone])
            ->orderBy('savings.id', 'desc')
            ->get(['savings.*', 'savings_products.*', 'savings.created_at as timestamp']);


        $withdrawals['withdrawals'] =  Savings::join('savings_products', 'savings_products.product_code', '=', 'savings.product')->where(['savings.phone' => $customer->phone, 'savings.withdrawal' => true])->orderBy('savings.id', 'desc')->get(['savings.*', 'savings_products.*']);
        $welfare = Welfare::where('phone', $id)->orderBy('created_at', 'desc')->get();

        $loantypes = LoanTypes::where('active', true)->orderBy('created_at', 'desc')->get();
        $products = SavingsProducts::all();
        $pwith = WithdrawalTransaction::join('customers', 'withdrawal_transactions.phone', '=', 'customers.phone')
            ->where(['withdrawal_transactions.status' => false, 'withdrawal_transactions.deleted' => false, 'withdrawal_transactions.phone' => $customer->phone])->get(['withdrawal_transactions.*', 'customers.firstname', 'customers.lastname']);

        $awith = WithdrawalTransaction::join('customers', 'withdrawal_transactions.phone', '=', 'customers.phone')
            ->where(['withdrawal_transactions.status' => true, 'withdrawal_transactions.deleted' => false, 'withdrawal_transactions.phone' => $customer->phone])->get(['withdrawal_transactions.*', 'customers.firstname', 'customers.lastname']);

        $products = SavingsProducts::all();
        $current_products   = SavingsProducts::select(DB::raw('savings_products.*, ifnull(SUM(savings.amount),0) as revenue'))
            ->leftJoin('savings', 'savings.product', '=', 'savings_products.product_code')
            ->where(['savings.phone' => $customer->phone])
            ->groupBy(['savings_products.id', 'savings_products.product_code', 'savings_products.product_name', 'savings_products.duration', 'savings_products.max_limit', 'savings_products.min_limit', 'savings_products.interest_rate', 'savings_products.admin_fee', 'savings_products.active', 'savings_products.created_at', 'savings_products.updated_at'])
            ->get();
        $groups = ProductGroup::all();

        $others = Guarantor::where('guarantor', $customer->phone)->orderBy('created_at', 'desc')->get();
        $ids = [];
        if ($others) {
            $ids = $others->pluck('phone');
        }
        // return $ids;

        $assigned_groups = [];

        $grps = CustomerGroup::where(['customers_id' => $id])->orderBy('created_at', 'desc')->get();
        if ($grps) {
            foreach ($grps as $one) {
                $one_grp = ProductGroup::where(['id' => $one->product_group_id])->first();
                $assigned_groups[] = [
                    'id' => $one->id,
                    'title' => $one_grp->title,
                    'description' => $one_grp->description,
                    'created_at' => $one->created_at,

                ];
            }
        }
        $guaranteed = Loans::whereIn('loan_ref', $ids)->orderBy('created_at', 'desc')->get();

        $data['data'] = ([
            'guaranteed' => $guaranteed,
            'pending' => $pending,
            'customer' => $customer,
            'approved' => $approved,
            'paid' => $paid,
            'rejected' => $rejected,
            'overdue' => $overdue,
            'savings' => $savings,
            'welfare' => $welfare,
            'save' =>  $save,
            'loantypes' => $loantypes,
            'products' => $products,
            'pwith' => $pwith,
            'awith' => $awith,
            'products' => $products,
            'groups' => $groups,
            'assigned_groups' => $assigned_groups,
            'current_products' => $current_products,

        ]);
        // return $data;
        return view('customers.view', $data);
    }
    public function edit_customer($id)
    {

        $data['data'] = Customers::where('id', $id)->first();
        return view('customers.edit')->with($data);
    }
    public function update_customer(Request $request, $id)
    {
        $automatic = $request->automatic === 'true' ? true : false;

        $user = Auth::user();
        $customer = Customers::where('id', $id)->first();
        if ($customer) {
            $customer->loanlimit = $request->loanlimit;
            $customer->automatic = $automatic;
            $customer->action_by = $user->name;
            $customer->approved_by = $user->name;
            $customer->status = $request->status;
            $customer->save();
        }
        return redirect()->to('/customer/pending')->with('success', 'Update successful!');
    }

    public function update_correct_customer(Request $request, $id)
    {

        $automatic = $request->automatic === 'true' ? true : false;

        $user = Auth::user();
        $customer = Customers::where('id', $id)->first();
        if ($customer) {
            $customer->gender = $request->gender;
            $customer->national_id = $request->national;
            $customer->firstname = $request->firstname;
            $customer->lastname = $request->lastname;
            $customer->action_by = $user->name;
            $customer->approved_by = $user->name;
            $customer->email = $request->email;
            $customer->save();
        }
        return redirect()->to('/customer/pending')->with('success', 'Update successful!');
    }

    public function online_access($id)
    {
        # code...
        $customer = Customers::where('id', $id)->first();
        if ($customer) {
            try {
                $name = $customer->firstname . " " . $customer->lastname;
                $randomString = '';
                $characters = '123456789';
                $charactersLength = strlen($characters);
                for ($i = 0; $i < 6; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                $password = $randomString;
                DB::connection('online_mysql')->beginTransaction(); 
               $user= User::on('online_mysql')->updateOrCreate(
                    ['email' => $customer->email],
                    [
                        'name' => $name,
                        'icon' => 'logo.jpeg',
                        'phone' => $customer->phone,
                        'password' => Hash::make($password),
                    ]
                );
            
                DB::connection('online_mysql')->commit(); 

                $customer->online_access = true;
                $customer->save();
                $message = "You have been granted access to the online version of SMP App, kindly use {$customer->email} and {$password} to Log on to the Jetpack Compose Web App. Access link https://web-smp.imeja.co.ke";
                $result = (new EmailController)->online_access_email($customer, $message);
// return $user;
                return redirect()->to('/customer/view/' . $id)->with('success', "Online access granted! to {$name}");
            } catch (\Exception $e) {
                DB::connection('online_mysql')->rollBack();

                return redirect()->to('/customer/view/' . $id)->with('error', 'Whoops! Exception thrown, please try again [Tip: Account exists]' . $e->getMessage());
            }
        } else {
            return redirect()->to('/customer/view/' . $id)->with('error', 'Whoops! Exception thrown, please try again');
        }
    }

    public function assign_group(Request $request, $id)
    {
        # code...
        $attr = $request->validate([
            'group_id' => 'required|string|max:255'
        ]);
        $group_id = $attr['group_id'];
        $customer = Customers::where('id', $id)->first();
        $date = Carbon::today();
        if ($customer) {
            $grp = CustomerGroup::updateOrCreate(
                ['customers_id' => $customer->id, 'product_group_id' => $group_id],
                ['created_at' => $date]
            );


            return redirect()->to('/customer/view/' . $id)->with('success', 'Group added successfully');
        } else {
            return redirect()->to('/customer/view/' . $id)->with('error', 'Whoops! Exception thrown, please try again');
        }
    }

    public function bulk_online_access()
    {
        # code...
        $customers = Customers::where(['online_access' => false])
            ->orderBy('created_at', 'DESC')
            ->take(50)
            ->get();
        if ($customers) {
            foreach ($customers as $customer) {
                try {
                    $name = $customer->firstname . " " . $customer->lastname;
                    $randomString = '';
                    $characters = '123456789';
                    $charactersLength = strlen($characters);
                    for ($i = 0; $i < 6; $i++) {
                        $randomString .= $characters[rand(0, $charactersLength - 1)];
                    }
                    $password = $randomString;
                    DB::connection('online_mysql')->beginTransaction();
                    User::updateOrCreate(
                        ['email' => $customer->email],
                        [
                            'name' => $name,
                            'icon' => 'logo.jpeg',
                            'phone' => $customer->phone,
                            'password' => bcrypt($password),
                        ]
                    );
                    DB::connection('online_mysql')->commit();

                    $customer->online_access = true;
                    $customer->save();
                    $message = "You have been granted access to the online version of SMP App, kindly use {$customer->email} and {$password} to Log on to the Jetpack Compose Web App. Access link https://web-smp.imeja.co.ke";
                    $result = (new EmailController)->online_access_email($customer, $message);

                    // return redirect()->to('settings')->with('success', 'Online access granted!');
                } catch (\Exception $e) {
                    DB::connection('online_mysql')->rollBack();

                    return redirect()->to('settings')->with('error', 'Whoops! Exception thrown, please try again [Tip: Account exists]' . $e->getMessage());
                }
            }
            return redirect()->to('settings')->with('success', 'Online access granted!');
        } else {
            return redirect()->to('settings')->with('error', 'Whoops! Exception thrown, please try again');
        }
    }
    public function alerts_on($id = null)
    {
        # code...
        $customer = Customers::where('phone', $id)->first();
        $message = "Activated";
        if ($customer) {
            $alerts_enabled = $customer->alerts_enabled;
            $customer->alerts_enabled = !$alerts_enabled;
            $customer->save();
            if ($alerts_enabled) {
                $message = "Deactivated";
            }
        }
        return redirect()->to('/customer/view/' . $customer->id)->with('success', 'Alerts have been ' . $message . ' Succesfully');
    }
    public function reset_pin($id)
    {

        $characters = '123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $password = $randomString;

        $customer = Customers::where('phone', $id)->first();
        if ($customer) {
            $customer->password = Hash::make($password);
            $customer->save();

            $message = "Your new PIN has been updated, kindly use {$password} to Log on to the Jetpack Compose Mobile App";
            $result = (new EmailController)->reset_password_email($customer, $message);
        }
        return redirect()->to('/customer/edit/' . $customer->id)->with('success', 'Pin Reset Successful!');
    }
    public function register(Request $request)
    {
        $attr = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email',
            'national' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'confirm' => 'required|string|min:6'
        ]);
        $phone = $attr['phone'];
        $phone = preg_replace("/^0/", "254", $phone);

        if (Customers::where('phone', '=', $phone)->exists()) {

            return redirect()->back()->withInput()->with('error', 'A User with the same phone number already exist!');
            // return redirect()->to('/customer/add')->with();
        }
        if (Customers::where('email', '=', $attr['email'])->exists()) {

            return redirect()->to('/customer/add')->withInput()->with('error', 'A User with the same email address already exist');
        }

        $name = $attr['firstname'] . " " . $attr['lastname'];
        $member = Customers::latest()->first();

        if ($member) {
            $add = $member->id + 1;
        } else {
            $add = 1;
        }

        $branch_id = $this->check_current_branch();
        $customer = Customers::create([
            'firstname' => $attr['firstname'],
            'lastname' => $attr['lastname'],
            'phone' => $phone,
            'devicename' => "USSD",
            'device_id' => "USSD",
            'type' => "Upload",
            'national_id' => $attr['national'],
            'gender' => $attr['gender'],
            'membership_no' => "S" . $add . "E",
            'branch_id' => $branch_id,
            'password' => Hash::make($attr['password']),
            'email' => $attr['email'],
        ]);
        if ($customer) {
            # code...
            $cs = CustomerSavings::updateOrCreate(
                ['phone' =>   $phone],
                ['branch_id' => $branch_id, 'amount' => 0, 'name' => $name, 'share_capital' => 0, 'welfare' => 0]
            );
        }
        return redirect()->to('/customer/add')->with('success', 'Customer Added successfully!');
    }

    // Loan Types Module

    public function register_loantype(Request $request)
    {
        $attr = $request->validate([
            'loan_name' => 'required|string|max:255',
            'duration' => 'required|string|max:11',
            'min_limit' => 'required|string|max:255',
            'max_limit' => 'required|string|max:255',
            'interest_rate' => 'required|string|max:255',
            'admin_fee' => 'required|string|max:255',
        ]);
        $admin = $attr['admin_fee'];
        $inter = $attr['interest_rate'] / 100;

        $loan_type = LoanTypes::create([
            'loan_name' => $attr['loan_name'],
            'duration' => $attr['duration'],
            'loan_code' => $this->generateRandomString(12),
            'min_limit' => $attr['min_limit'],
            'max_limit' => $attr['max_limit'],
            'interest_rate' => $inter,
            'admin_fee' => $admin
        ]);
        return redirect()->to('/loantypes')->with('success', 'Loan Type Added successfully!');
        // return redirect()->to('/loantypes')->with('success', 'Please Contact Administrator');
    }


    public function record_message(Request $request)
    {

        $attr = $request->validate([
            'type' => 'required|string|max:255',
            'message' => 'required|string|max:255'
        ]);
        $type = $attr['type'];
        $message = $attr['message'];

        $options = Message::updateOrCreate(
            ['type' =>  $type],
            ['message' => $message]
        );

        return redirect()->to('/messages')->with('success', 'Message Added successfully!');
    }
    public function view_messages()
    {
        $options['options'] = Option::all();
        $types['types'] = MessageType::all();
        $messages['messages'] = Message::join('message_types', 'message_types.id', '=', 'messages.type')->orderBy('messages.id', 'desc')->get(['messages.*', 'message_types.type as mode']);

        return view('settings.index')->with($options)->with($messages)->with($types);
    }


    public function view_loantypes()
    {

        $loantypes['loantypes'] = LoanTypes::where('active', true)->orderBy('created_at', 'desc')->get();

        return view('loantypes.index')->with($loantypes);
    }
    public function add_loantypes()
    {

        return view('loantypes.add');
    }
    public function edit_loantype($id)
    {
        $loantype['loantype'] = LoanTypes::where('loan_code', $id)->first();
        return view('loantypes.edit')->with($loantype);
    }

    public function single_loantype($id)
    {
        $loantype['loantype'] = LoanTypes::where('loan_code', $id)->first();
        return view('loantypes.view')->with($loantype);
    }
    // Loans module


    public function update_loantype(Request $request)
    {
        $attr = $request->validate([
            'loan_code' => 'required|string|max:255',
            'loan_name' => 'required|string|max:255',
            'duration' => 'required|string|max:11',
            'min_limit' => 'required|string|max:255',
            'max_limit' => 'required|string|max:255',
            'interest_rate' => 'required|string|max:255',
            'admin_fee' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);
        $admin = $attr['admin_fee'];
        $inter = $attr['interest_rate'] / 100;
        $loan_code = $attr['loan_code'];
        $status = $attr['status'] === 'true' ? true : false;

        $loan_types = LoanTypes::where(['loan_code' => $loan_code])->first();
        $loan_types->loan_name = $attr['loan_name'];
        // $loan_types->duration = $attr['duration'];
        $loan_types->min_limit = $attr['min_limit'];
        $loan_types->max_limit = $attr['max_limit'];
        $loan_types->interest_rate = $inter;
        $loan_types->admin_fee = $admin;
        $loan_types->active = $status;
        $loan_types->save();

        return redirect()->to('/loantypes')->with('success', 'Loan Type Updated successfully!');


        // return redirect()->to('/loantypes')->with('success', 'Please contact Administrator');
    }




    public function pending_loans()
    {
        $branch_id = $this->check_current_branch();

        $data['customers'] = Customers::where(['status' => 'Approved', 'branch_id' => $branch_id])->orderBy('created_at', 'desc')->get();
        $loantypes = LoanTypes::where(['active' => true,])->orderBy('created_at', 'desc')->get();
        $loans = Loans::where(['loan_status' => 'pending', 'branch_id' => $branch_id])->orderBy('created_at', 'desc')->get();

        $data['loans'] = $loans;
        $data['loantypes'] = $loantypes;
        return view('loans.index', $data);
    }

    public function approved_loans()
    {
        $branch_id = $this->check_current_branch();

        $disbursedLoans = Loans::where(['loan_status' => 'disbursed', 'branch_id' => $branch_id])
            ->orderBy('created_at', 'desc')
            ->get();

        $paidLoans = Loans::where(['loan_status' => 'paid', 'branch_id' => $branch_id])
            ->orderBy('created_at', 'desc')
            ->get();

        $loans['loans'] = $disbursedLoans->concat($paidLoans);

        return view('loans.approved')->with($loans);
    }
    public function disbursed_loans()
    {

        $branch_id = $this->check_current_branch();
        $data['loans'] = Loans::where(['loan_status' => 'disbursed', 'branch_id' => $branch_id])->orderBy('created_at', 'desc')->get();

        return view('loans.approved', $data);
    }

    public function loans_today()
    {

        $branch_id = $this->check_current_branch();
        $today = date('Y-m-d');
        $data['loans'] = Loans::where('phone', '!=', null)
            ->where(['loan_status' => 'disbursed', 'repayment_status' => false, 'branch_id' => $branch_id])
            ->where(DB::raw('CAST(repayment_date as date)'), '=', $today)
            ->latest('id')
            ->get();
        return view('loans.approved', $data);
    }
    public function loans_next_week()
    {
        $branch_id = $this->check_current_branch();
        $today = date('Y-m-d');
        $start_day = date('Y-m-d', strtotime($today . '+7 day'));
        $end_day = date('Y-m-d', strtotime($start_day . '+7 day'));
        $data['loans'] = Loans::where('phone', '!=', null)->where(['loan_status' => 'disbursed', 'repayment_status' => false, 'branch_id' => $branch_id])->where(DB::raw('CAST(repayment_date as date)'), '>=', $start_day)->where(DB::raw('CAST(repayment_date as date)'), '<=', $end_day)->latest('id')->get();
        return view('loans.approved', $data);
    }
    public function paid_loans()
    {
        $branch_id = $this->check_current_branch();

        $data['loans'] = Loans::where(['loan_status' => 'paid', 'branch_id' => $branch_id])->orderBy('created_at', 'desc')->get();

        return view('loans.paid', $data);
    }
    public function clear_loan_penalty($loan)
    {


        $current_penalty_amount = $loan->penalty_amount;
        $current_loan_balance = $loan->loan_balance;

        if ($current_penalty_amount > 0) {

            //Loan penalty removed
            $new_loan_balance = $current_loan_balance - $current_penalty_amount;

            $penalty_date =  (new Carbon($loan->penalty_date))->addDays($loan->repayment_period);
            if ($new_loan_balance <= 0) {
                // mark it as cleared loan
                $loan->loan_balance = 0;
                $loan->loan_status = "paid";
                $loan->penalty_amount = 0;
                $loan->save();
            } else {
                $loan->loan_balance = $new_loan_balance;
                $loan->penalty_amount = 0;
                $loan->penalty_date = $penalty_date;
                $loan->save();
            }
            if ($new_loan_balance < 0) {
                $new_loan_balance = 0;
            }

            $logs = SystemLogs::create([
                'phone' => $loan->loan_ref,
                'title' => "Loan Penalty Reset",
                'body' => "Loan Penalty of KES {$current_penalty_amount} has been cleared from a balance of KES {$current_loan_balance} with amount of KES {$current_penalty_amount} to a new balance of KES {$new_loan_balance}"
            ]);
        }
    }
    public function overdue_loans()
    {
        $branch_id = $this->check_current_branch();
        $date = Carbon::today();
        $loans = Loans::whereDate('repayment_date', '<=', $date)->where(['loan_status' => 'disbursed', 'repayment_status' => false, 'branch_id' => $branch_id])->orderBy('created_at', 'desc')->get();

        foreach ($loans as $loan) {
            $repaymentDate = Carbon::parse($loan->repayment_date);
            $currentDate = Carbon::now();
            $maxDays = $currentDate->diffInDays($repaymentDate);
            $randomDays = mt_rand(0, $maxDays);
            $diff = $currentDate->copy()->subDays($randomDays);

            // only update if reminder_date is null 
            if ($loan->reminder_date === null) {
                $loan->reminder_date = $diff;
                $loan->save();
            }

            //Handle clearing of balances if penalty
            // $cleared = $this->clear_loan_penalty($loan);
        }
        $data['loans'] = $loans;
        return view('loans.overdue', $data);
    }
    public function rejected_loans()
    {
        $branch_id = $this->check_current_branch();
        $data['loans'] = Loans::where(['loan_status' => 'rejected', 'branch_id' => $branch_id])->orderBy('created_at', 'desc')->get();

        return view('loans.rejected', $data);
    }

    public function edit_loans($id)
    {
        $all['all'] = Customers::all();
        $data['data'] = Loans::where('loan_ref', $id)->first();
        $schedules['schedules'] = Schedule::where('loan_ref', $id)->orderBy('created_at', 'desc')->get();

        $guarantors['guarantors'] = Guarantor::join('customers', 'guarantors.guarantor', '=', 'customers.phone')->where(['guarantors.phone' => $id])->orderBy('guarantors.id', 'desc')->get(['guarantors.*', 'customers.firstname', 'customers.phone as phonenumber', 'customers.lastname']);
        return view('loans.edit')->with($data)->with($schedules)->with($all)->with($guarantors);
    }

    public function assign_loan(Request $request, $id)
    {
        # code... 
        $guarantor = $request->guarantor;
        if (Guarantor::where(['phone' => $id, 'guarantor' => $guarantor])->exists()) {

            return redirect()->to('/loans/edit/' . $id)->with('error', 'Guarantor already added');
        }
        $gr = Guarantor::create([
            'phone' => $id,
            'guarantor' => $guarantor,
            'status' => true,

        ]);
        return redirect()->to('/loans/edit/' . $id)->with('success', 'Guarantor Added');
    }
    public function view_loans($id)
    {
        $data['loantypes'] = LoanTypes::where('active', true)->orderBy('id', 'desc')->get();
        $data['data'] = Loans::where('loan_ref', $id)->first();
        $data['schedules'] = Schedule::where('loan_ref', $id)->orderBy('id', 'desc')->get();
        $data['repayments'] = Repayments::where('loan_ref', $id)->orderBy('id', 'desc')->get();
        $data['top_logs'] = SystemLogs::where(['phone' => $id])->whereNotIn('title', ['Waive Loan'])->orderBy('id', 'desc')->get();
        $data['waive_logs'] = SystemLogs::where(['phone' => $id])->whereIn('title', ['Waive Loan'])->orderBy('id', 'desc')->get();
        $data['guarantors'] = Guarantor::join('customers', 'guarantors.guarantor', '=', 'customers.phone')->where(['guarantors.phone' => $id])->orderBy('guarantors.id', 'desc')->get(['guarantors.*', 'customers.firstname', 'customers.phone as phonenumber', 'customers.lastname']);
        $data['notes'] = Note::where('loan_ref', $id)->orderBy('id', 'desc')->get();

        return view('loans.view', $data);
    }
    public function reject_loans($id)
    {
        $loan = Loans::where('loan_ref', $id)->first();
        $loan->loan_status = 'rejected';
        $loan->save();

        return redirect()->to('/loans/rejected')->with('success', 'Loan Successfully Rejected');
    }
    public function action_loan($id)
    {
        # code...

        $branch_id = $this->check_current_branch();
        $loan = Loans::where(['loan_ref' => $id])->first();
        $loan_ref = $loan->loan_ref;
        $loan_duration = $loan->repayment_period;
        // check for float balance
        $system_balance = 0;
        $balances_account = AccountBalance::where(['status' => true, 'branch_id' => $branch_id])->first();
        if ($balances_account) {
            $system_balance = $balances_account->bulk;
        }
        if ($system_balance < $loan->principle) {

            return redirect()->to('/loans')->with('error', 'Insufficient Float Balance, Please Top Up your account');
        }



        if (Guarantor::where(['phone' => $id])->exists()) {
            $deletedRows = Schedule::where('loan_ref', $loan_ref)->delete();

            $total_balance = $loan->loan_balance;
            $times = $loan_duration / 7;
            $schedule_amount = $total_balance / $times;
            $now = Carbon::rawParse('now')->format('Y-m-d');
            $date = Carbon::createFromFormat('Y-m-d', $now);

            $loan->loan_status = "disbursed";
            $loan->save();

            $rem = $system_balance - $loan->principle;
            if ($rem < 0) {
                $rem = 0;
            }
            $balances_account->bulk = $rem;
            $balances_account->save();

            $add = BulkStatement::create([
                'reference' => $this->generateRandomString(12),
                'action_by' => $loan->phone,
                'approved_by' => $loan->phone,
                'amount' => $loan->principle,
                'balance' => $rem,
                'branch_id' => $branch_id,
                'narration' => "Loan Disbursment of {$loan->principle} to {$loan->phone}",
                'status' => true
            ]);

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
            $send_money = (new LoansController)->handleAutomatic($loan_ref, $branch_id);

            return redirect()->to('/loans')->with('success', 'Disbursement Processed successfully!');
        } else {

            return redirect()->to('/loans/edit/' . $id)->with('error', 'Please Add a Guarantor First!');
        }
    }

    public function topup_loan(Request $request, $loan_ref)
    {
        $attr = $request->validate([
            'amount' => 'required|integer|between:1,250000',
            'duration' => 'required|string|max:255',
            'reason' => 'required|string'
        ]);

        $amount = $attr['amount'];
        $duration = $attr['duration'];
        $reason = $attr['reason'];

        $branch_id = $this->check_current_branch();
        // check for float balance
        $system_balance = 0;
        $balances_account = AccountBalance::where(['status' => true, 'branch_id' => $branch_id])->first();
        if ($balances_account) {
            $system_balance = $balances_account->bulk;
        }
        if ($system_balance < $amount) {

            return redirect()->to('/loans/view/' . $loan_ref)->with('error', 'Insufficient Float Balance, Please Top Up your account!');
        }


        $user = Auth::user();
        $loan = Loans::where(['loan_ref' => $loan_ref])->first();
        if ($loan) {

            $current_balance = $loan->loan_balance;

            $new_interest = $loan->rate_applied * $amount;
            $new_principal = $loan->principle + $amount;

            $total_interest = $loan->interest + $new_interest;

            $new_loan_amount = $loan->loan_amount + $amount + $new_interest;
            $new_loan_balance = $loan->loan_balance + $amount + $new_interest;



            $penalty_date = date('Y-m-d', strtotime("+ {$duration} days", strtotime($loan->penalty_date)));
            $repay_date = date('Y-m-d', strtotime("+ {$duration} days", strtotime($loan->repayment_date)));
            $loan->loan_balance = $new_loan_balance;
            $loan->penalty_date = $penalty_date;
            $loan->repayment_date = $repay_date;
            $loan->principle = $new_principal;
            $loan->interest = $total_interest;
            $loan->loan_amount = $new_loan_amount;
            $loan->save();


            $user = Auth::user();
            $logs = SystemLogs::create([
                'phone' => $loan_ref,
                'title' => "Loan Edited by {$user->name}",
                'body' => "Loan Topup from a balance of KES {$current_balance} with amount of {$amount} to Total of {$new_loan_balance}\n\nReason: {$reason}"
            ]);
            $logs1 = SystemLogs::create([
                'phone' => $loan->phone,
                'title' => "Loan Edited by {$user->name}",
                'body' => "Loan Topup from a balance of KES {$current_balance} with amount of {$amount} to Total of {$new_loan_balance}"
            ]);
            $message = "We have successfully toped up your loan with KES {$amount}. Your new balance is KES {$new_loan_balance}. Payment due on {$repay_date}. Thank you for using our services.";
            // $results = (new EmailController)->topup_email($loan, $message);
            return redirect()->to('/loans/view/' . $loan_ref)->with('success', 'Cash Topup Successfull!');
        } else {

            return redirect()->to('/loans/view/' . $loan_ref)->with('error', 'Request cannot be processed!');
        }
    }

    public function add_note(Request $request, $id)
    {
        # code...
        $user = Auth::user();
        $nt = Note::create([
            'loan_ref' => $id,
            'description' => $request->description,
            'officer' => $user->name
        ]);
        return redirect()->to('/loans/view/' . $id)->with('success', 'Note Update successfully!');
    }

    public function add_pause(Request $request, $id)
    {
        # code...
        $user = Auth::user();
        $loan = Loans::where(['loan_ref' => $id])->first();
        if ($loan) {
            $loan->paused = true;
            $loan->save();
            $logs = SystemLogs::create([
                'phone' => $loan->loan_ref,
                'title' => "Loan Paused by {$user->name}",
                'body' => "Loan Penalty Paused by {$user->name} for the loan with reference {$id}\n\nUser description: {$request->description}"
            ]);

            return redirect()->to('/loans/view/' . $id)->with('success', 'Pause Update successfully!');
        }
    }
    public function activate_penalty(Request $request, $id)
    {
        # code... get request with name description 

        $user = Auth::user();
        $loan = Loans::where(['loan_ref' => $id])->first();
        if ($loan) {
            $now = Carbon::rawParse('now')->format('Y-m-d');
            $date = Carbon::createFromFormat('Y-m-d', $now);
            $today = Carbon::today();

            $loan->paused = false;
            $loan->penalty_date = $today;
            $loan->save();
            $logs = SystemLogs::create([
                'phone' => $loan->phone,
                'title' => "Penalty Activation",
                'body' => "Loan Penalty re-activated for loan with reference number {$id} by {$user->name}\n\n\nUser Description: {$request->description}. Balance is KES {$loan->loan_balance} and penalty date is {$date}"
            ]);

            return redirect()->to('/loans/view/' . $id)->with('success', 'Penalty Activated successfully!');
        }
    }

    public function generate_statement(Request $request, $id)
    {
        $user = Auth::user();
        $loan = Loans::where(['loan_ref' => $id])->first();
        if ($loan) {

            $data['schedules'] = Schedule::where('loan_ref', $id)->orderBy('id', 'desc')->get();
            $data['repayments'] = Repayments::where('loan_ref', $id)->orderBy('id', 'desc')->get();
            $data['reminders'] = Reminder::where('loan_ref', $id)->orderBy('id', 'desc')->get();
            $data['data'] = $loan;
            $pdf = PDF::loadView('downloads.loans', $data);
            $file = "{$loan->customer_name}-{$loan->phone}.pdf";
            return $pdf->download($file);
        } else {
            return redirect()->to('/loans/view/' . $id)->with('error', 'Failed to generate statement, please try again later!!');
        }
    }


    public function generate_savings_statement(Request $request, $phone, $id)
    {
        $user = Auth::user();
        $customer = Customers::where('phone', $phone)->first();
        if ($customer) {
            $available = Dividend::where(['paid' => false, 'phone' => $phone])->sum('available');
            $current = Dividend::where(['iswithdrawal' => false, 'phone' => $phone])->sum('amount');

            $data['savings'] =  Savings::join('savings_products', 'savings_products.product_code', '=', 'savings.product')
                ->where(['savings.phone' => $phone])
                ->orderBy('savings.created_at', 'desc')
                ->get(['savings.*', 'savings_products.*', 'savings.created_at as saved']);

            $data['withdrawals'] =  Savings::join('savings_products', 'savings_products.product_code', '=', 'savings.product')
                ->where(['savings.phone' => $phone, 'savings.withdrawal' => true])
                ->orderBy('savings.id', 'desc')->get(['savings.*', 'savings_products.*', 'savings.created_at as tolewa']);

            $data['savingsproducts'] = SavingsProducts::select(DB::raw('savings_products.*, ifnull(SUM(savings.amount),0) as revenue'))
                ->leftJoin('savings', 'savings.product', '=', 'savings_products.product_code')
                ->groupBy('savings_products.id', 'savings_products.product_code', 'savings_products.product_name', 'savings_products.duration', 'savings_products.min_limit', 'savings_products.max_limit', 'savings_products.interest_rate', 'savings_products.admin_fee', 'savings_products.active', 'savings_products.created_at', 'savings_products.updated_at')
                ->where(['savings.phone' => $phone])
                ->get();
            $total_savings = Savings::where(['phone' => $phone])->sum('amount');

            if ($available < 0) {
                $available = 0;
            }
            $fcm = Payouts::updateOrCreate(
                ['phone' =>  $customer->phone],
                ['amount' => $available]
            );
            $data['data'] = ([
                'firstname' => $customer->firstname,
                'lastname' => $customer->lastname,
                'name' => "{$customer->firstname} {$customer->lastname}",
                'phone' => $customer->phone,
                'membership_no' => $customer->membership_no,
                'total_savings' => $total_savings,
                'available' => $available,
                'current' => $current,
                'reference' => $customer->phone
            ]);
            $pdf = PDF::loadView('downloads.savings', $data);
            $file = "{$customer->firstname} {$customer->lastname}-{$phone}.pdf";
            return $pdf->download($file);
        } else {
            return redirect()->to('/customer/view/' . $id)->with('error', 'Failed to generate statement, please try again later!!');
        }
    }

    public function correct_loan(Request $request)
    {
        $attr = $request->validate([
            'loan_ref' => 'required|string|max:255',
            'reason' => 'required|string|max:255',
            'amount' => 'required|integer|between:1,100000',
            'loan_code' => 'required|string|max:255'
        ]);
        $user = Auth::user();
        $principle = $attr['amount'];
        $loan_ref = $attr['loan_ref'];
        $reason = $attr['reason'];
        $loan_code = $attr['loan_code'];

        $loan = Loans::where(['loan_ref' => $loan_ref])->first();
        if ($loan) {


            $loan_type_available = LoanTypes::where(['loan_code' => $loan_code])->first();

            if ($loan_type_available) {

                $admin_fee = $loan_type_available->admin_fee;
                $interest = $loan_type_available->interest_rate * $principle;
                $loan_amount = $principle + $admin_fee + $interest;
                $loan_balance = $loan_amount;
                $disbursment_date = $loan->disbursment_date;
                $repayment_period = $loan_type_available->duration;


                $repayment_date = date('Y-m-d ', strtotime($disbursment_date . ' + ' . $repayment_period . ' days'));
                $penalty_date = date('Y-m-d', strtotime($repayment_date . ' + 2 days'));


                $loan->principle = $principle;
                $loan->loan_disbursed = $principle;
                $loan->rate_applied = $loan_type_available->interest_rate;
                $loan->admin_fee = $admin_fee;
                $loan->interest = $interest;
                $loan->loan_amount = $loan_amount;
                $loan->loan_balance = $loan_balance;
                $loan->repayment_period = $repayment_period;
                $loan->disbursment_date = $disbursment_date;
                $loan->repayment_date = $repayment_date;
                $loan->penalty_date = $penalty_date;
                $loan->approved_by = $user->name;
                $loan->actioned_by = $user->name;
                $loan->save();

                $deletedRows = Schedule::where('loan_ref', $loan_ref)->delete();
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

                $user = Auth::user();
                $logs = SystemLogs::create([
                    'phone' => $loan_ref,
                    'title' => "Loan Edited by {$user->name}",
                    'body' => $reason
                ]);
            } else {

                return redirect()->to('/loans/view/' . $loan_ref)->with('error', 'Request cannot be processed!');
            }
        }


        return redirect()->to('/loans/view/' . $loan_ref)->with('success', 'Disbursement Update successfully!');
    }

    public function waive_loan(Request $request, $loan_ref)
    {
        $attr = $request->validate([
            'reason' => 'required|string|max:255',
            'amount' => 'required|integer|between:1,100000',
        ]);

        $amount = $attr['amount'];
        $reason = $attr['reason'];

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


                $logs = SystemLogs::create([
                    'phone' => $loan_ref,
                    'title' => "Waive Loan",
                    'body' => "Loan Waived from a balance of KES {$loan_balance} with amount of {$amount} to Total of {$end}\n\nReason: {$reason}"

                ]);
            }
        }

        return redirect()->to('/loans/view/' . $loan_ref)->with('success', 'Disbursement Waived successfully!');
    }
    public function update_manual_loan(Request $request)
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
            $loanlimit = $customer->loanlimit;

            if ($principle > $loanlimit) {
                return redirect()->to('/loans')->with('error', 'Maximum Limit Exceeded');
            }

            $branch_id = $this->check_current_branch();
            $data = (new LoansController)->store_manual_loans($customer, $phone, $loan_code, $principle, $startdate, $branch_id);
            return redirect()->to('/loans')->with('success', $data);
        } else {
            return redirect()->to('/loans')->with('error', 'Customer Does not Exist!!');
        }
    }
    public function update_manual_loan_single(Request $request, $id)
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
            $loanlimit = $customer->loanlimit;

            if ($principle > $loanlimit) {
                return redirect()->to('/customer/view/' . $id)->with('error', 'Maximum Limit Exceeded');
            }
            $branch_id = $this->check_current_branch();
            $data = (new LoansController)->store_manual_loans($customer, $phone, $loan_code, $principle, $startdate, $branch_id);
            return redirect()->to('/customer/view/' . $id)->with('success', $data);
        } else {
            return redirect()->to('/customer/view/' . $id)->with('error', 'Customer Does not Exist!!');
        }
    }


    public function update_loan(Request $request)
    {
        $loan_ref = $request->reference;
        $loan_duration = $request->loan_duration;

        $loan = Loans::where(['loan_ref' => $loan_ref])->first();

        // check for float balance
        $system_balance = 0;
        $balances_account = AccountBalance::where(['status' => true])->first();
        if ($balances_account) {
            $system_balance = $balances_account->bulk;
        }
        if ($system_balance < $loan->principle) {

            return redirect()->to('/loans')->with('error', 'Insufficient Float Balance, Please Top Up your account');
        }
        $deletedRows = Schedule::where('loan_ref', $loan_ref)->delete();

        $total_balance = $loan->loan_balance;
        $times = $loan_duration / 7;
        $schedule_amount = $total_balance / $times;
        $now = Carbon::rawParse('now')->format('Y-m-d');
        $date = Carbon::createFromFormat('Y-m-d', $now);

        $loan->loan_status = "disbursed";

        $disbursment_date = Carbon::now();
        $now = Carbon::rawParse('now')->format('Y-m-d');
        $date = Carbon::createFromFormat('Y-m-d', $now);
        $repayment_date = $date->addDays($loan->repayment_period);
        $penalty_date = $repayment_date->addDays(2);

        $loan->disbursment_date = $disbursment_date;
        $loan->repayment_date = $repayment_date;
        $loan->penalty_date = $penalty_date;

        $loan->save();

        $rem = $system_balance - $loan->principle;
        if ($rem < 0) {
            $rem = 0;
        }
        $balances_account->bulk = $rem;
        $balances_account->save();

        $add = BulkStatement::create([
            'reference' => $this->generateRandomString(12),
            'action_by' => $loan->phone,
            'approved_by' => $loan->phone,
            'amount' => $loan->principle,
            'balance' => $rem,
            'narration' => "Loan Disbursment of {$loan->principle} to {$loan->phone}",
            'status' => true
        ]);

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
        $branch_id = $this->check_current_branch();
        $send_money = (new LoansController)->handleAutomatic($loan_ref, $branch_id);

        return redirect()->to('/loans')->with('success', 'Disbursement Processed successfully!');
    }

    public function loans_repayments()
    {

        $branch_id = $this->check_current_branch();
        $data['loans'] = Loans::where(['loan_status' => 'disbursed', 'branch_id' => $branch_id])->orderBy('created_at', 'DESC')->get();

        return view('repayments.index', $data);
    }
    public function payments()
    {

        $branch_id = $this->check_current_branch();
        $data['response'] = Response::selectAllRepayments($branch_id);
        $data['customers'] = Customers::where(['status' => 'Approved', 'branch_id' => $branch_id])->orderBy('created_at', 'desc')->get();
        return view('repayments.index', $data);
    }
    public function manual_savings(Request $request)
    {

        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'amount' => 'required|integer|between:1,100000',
            'product' => 'required|string|max:255',
        ]);
        $phone = $attr['phone'];
        $trans_code = $attr['code'];
        $amount = $attr['amount'];
        $product = $attr['product'];
        if (Response::where(['txncd' => $trans_code])->exists()) {
            return redirect()->to('/savings')->with('error', 'Transaction Code Already Exists !!');
        }

        $branch_id = $this->check_current_branch();
        if (Customers::where('phone', '=', $phone)->exists()) {

            $res = Response::create([
                'status' => 0,
                'txncd' => $trans_code,
                'msisdn_id' => $phone,
                'msisdn_idnum' => 'Manual Repayment',
                'mc' => $amount,
                'branch_id' => $branch_id,
                'channel' => "MPESA",
            ]);
            $result = (new SavingsController)->savings_callback($phone, $amount, $trans_code, $product);
            return redirect()->to('/savings')->with('success', 'Cash deposit successfull !!');
        } else {
            return redirect()->to('/savings')->with('error', 'Phone Number not registered!!');
        }
    }
    public function manual_user_savings(Request $request, $id)
    {

        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'amount' => 'required|integer|between:1,100000',
            'product' => 'required|string|max:255',
        ]);
        $phone = $attr['phone'];
        $trans_code = $attr['code'];
        $amount = $attr['amount'];
        $product = $attr['product'];
        if (Response::where(['txncd' => $trans_code])->exists()) {
            return redirect()->to('/customer/view/' . $id)->with('error', 'Transaction Code Already Exists !!');
        }
        if (Customers::where('phone', '=', $phone)->exists()) {
            $user = Auth::user()->name;
            $res = Response::create([
                'status' => 0,
                'txncd' => $trans_code,
                'msisdn_id' => $phone,
                'msisdn_idnum' => 'Manual Repayment',
                'mc' => $amount,
                'channel' => "MPESA",
                'action_by' => $user
            ]);
            $result = (new SavingsController)->savings_callback($phone, $amount, $trans_code, $product);
            return redirect()->to('/customer/view/' . $id)->with('success', 'Cash deposit successfull !!');
        } else {
            return redirect()->to('/customer/view/.$id')->with('error', 'Phone Number not registered!!');
        }
    }

    public function record(Request $request)
    {

        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'amount' => 'required|integer|between:1,100000',
            'type' => 'required|string|max:255',
        ]);
        $phone = $attr['phone'];
        $trans_code = $attr['code'];
        $amount = $attr['amount'];
        $type = $attr['type'];

        $branch_id = $this->check_current_branch();
        if (Response::where(['txncd' => $trans_code])->exists()) {
            return redirect()->to('/payments')->with('error', 'Transaction Code Already Exists !!');
        }
        if (Customers::where('phone', '=', $phone)->exists()) {
            $user = Auth::user()->name;
            $res = Response::create([
                'status' => 0,
                'txncd' => $trans_code,
                'msisdn_id' => $phone,
                'msisdn_idnum' => 'Manual Repayment',
                'mc' => $amount,
                'branch_id' => $branch_id,
                'channel' => "MPESA",
                'action_by' => $user
            ]);

            $where = Mode::where('phone', $phone)->first();
            if ($type == '1') {
                $result = (new LoansController)->pay_loan_callback($phone, $amount, $trans_code, $branch_id);
            }
            if ($type == '2') {
                # code... 

                $result = (new SavingsController)->savings_callback($phone, $amount, $trans_code, $where->description);
            }
            if ($type == '3') {
                # code... 
                $result = (new SavingsController)->welfare_callback($phone, $amount, $trans_code);
            }
            if ($type == '4') {
                # code... 
                $result = (new SavingsController)->shares_callbacl($phone, $amount, $trans_code);
            }
            if ($type == '5') {
                # code... 
                $result = (new B4MController)->callback_contribute($where->reference, $phone, $trans_code, $amount);
            }
            return redirect()->to('/payments')->with('success', 'Cash deposit successfull !!');
        } else {
            return redirect()->to('/payments')->with('error', 'Phone Number not registered!!');
        }
    }

    public function savings_repayments()
    {

        $savings['savings'] = CustomerSavings::join('customers', 'customer_savings.phone', '=', 'customers.phone')->orderBy('customer_savings.created_at', 'DESC')->get(['customer_savings.*', 'customers.firstname', 'customers.lastname']);
        return view('repayments.savings')->with($savings);
    }
    public function view_savings_repayments($id)
    {
        $data['data'] = Customers::where('phone', $id)->first();
        $savings['savings'] =  Savings::join('savings_products', 'savings_products.product_code', '=', 'savings.product')->where(['savings.phone' => $id])->orderBy('savings.id', 'desc')->get(['savings.*', 'savings_products.*']);

        return view('repayments.savingsview')->with($data)->with($savings);
    }

    public function view_repayments($id)
    {

        $data['data'] = Loans::where('loan_ref', $id)->first();
        $schedules['schedules'] = Schedule::where('loan_ref', $id)->orderBy('created_at', 'desc')->get();
        $repayments['repayments'] = Repayments::where('loan_ref', $id)->orderBy('created_at', 'desc')->get();
        return view('repayments.view')->with($data)->with($schedules)->with($repayments);;
    }

    public function update_savings_repayments(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'trans_code' => 'required|string|max:255',
            'amount' => 'required|integer|between:1,100000',
        ]);
        $phone = $attr['phone'];
        $amount = $attr['amount'];
        $trans_code = $attr['trans_code'];

        $mode = Mode::where('phone', $phone)->first();
        if ($mode) {
            $result = (new SavingsController)->savings_callback($phone, $amount, $trans_code, $mode->description);

            return redirect()->to('/repayments/savings')->with('success', 'Customer Savings updated !!');
        } else {

            $product = SavingsProducts::where('id', 1)->first();
            $result = (new SavingsController)->savings_callback($phone, $amount, $trans_code, $product->product_code);
            return redirect()->to('/repayments/savings')->with('success', 'Customer Savings updated !!');
        }
    }

    public function update_repayments(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'trans_code' => 'required|string|max:255',
            'amount' => 'required|integer|between:1,100000',
        ]);
        $phone = $attr['phone'];
        $amount = $attr['amount'];
        $trans_code = $attr['trans_code'];


        $unpaid = Loans::where(['phone' => $phone, 'repayment_status' => false, 'loan_status' => 'disbursed'])->first();
        if ($unpaid) {

            $exist = Repayments::where(['reference' => $trans_code])->exists();
            if ($exist) {

                return redirect()->to('repayments/view/' . $unpaid->loan_ref)->with('error', 'Transaction Reference Already Exists!!');
            }

            $balance = $unpaid->loan_balance;
            $remainder = $balance - $amount;
            $now = Carbon::rawParse('now')->format('Y-m-d');
            $reference = $this->generateRandomString(12);

            $result = (new LoansController)->handleReports($unpaid->loan_ref, $amount, $trans_code);

            if ($remainder > 0) {
                $unpaid->loan_balance = $remainder;
                $unpaid->save();
                $payment = Repayments::create([
                    'phone' => $attr['phone'],
                    'loan_ref' => $unpaid->loan_ref,
                    'date_paid' => $now,
                    'initiator' => $attr['phone'],
                    'reference' => $trans_code,
                    'paid_amount' => $attr['amount'],
                    'balance' => $remainder,

                ]);
            } else {
                $unpaid->loan_balance = 0;
                $unpaid->repayment_status = true;
                $unpaid->loan_status = 'paid';
                $unpaid->clear_date = $now;
                $unpaid->save();

                $repay = Repayments::create([
                    'phone' => $attr['phone'],
                    'loan_ref' => $unpaid->loan_ref,
                    'date_paid' => $now,
                    'initiator' => $attr['phone'],
                    'reference' => $trans_code,
                    'paid_amount' => $balance,
                    'balance' => 0,

                ]);
            }
        }
        return redirect()->to('/repayments');
    }


    // Savings Module

    public function product_add(Request $request)
    {

        $branch_id = $this->check_current_branch();
        $attr = $request->validate([
            'loan_name' => 'required|string|max:255',
            'duration' => 'required|string|max:11',
            'min_limit' => 'required|string|max:255',
            'max_limit' => 'required|string|max:255',
        ]);
        $loan_type = SavingsProducts::create([
            'product_name' => $attr['loan_name'],
            'duration' => $attr['duration'],
            'product_code' => $this->generateRandomString(12),
            'min_limit' => $attr['min_limit'],
            'branch_id' => $branch_id,
            'max_limit' => $attr['max_limit'],
        ]);
        return redirect()->to('/savings/products')->with('success', 'Product Added successfully!');
    }
    public function product_update(Request $request, $id)
    {
        $attr = $request->validate([
            'product_name' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'duration' => 'required|string|max:11',
            'min_limit' => 'required|string|max:255',
            'max_limit' => 'required|string|max:255',
        ]);
        $product_code = $id;
        $product_name = $attr['product_name'];
        $status = $attr['status'] === 'true' ? true : false;
        $duration = $attr['duration'];
        $min_limit = $attr['min_limit'];
        $max_limit = $attr['max_limit'];

        $current = SavingsProducts::where(['product_code' => $product_code])->first();
        if ($current) {
            # code...
            $current->product_name = $product_name;
            $current->active = $status;
            $current->duration = $duration;
            $current->min_limit = $min_limit;
            $current->max_limit = $max_limit;
            $current->save();
        }
        return redirect()->to('/savings/products')->with('success', 'Product updated successfully!');
    }

    public function products()
    {

        $branch_id = $this->check_current_branch();
        $products = SavingsProducts::all(); //where(['branch_id' => $branch_id])->get();
        if ($products) {
            foreach ($products as $single) {
                $group = "N/A";
                $group_parent = ProductGroup::where('id', $single->product_group_id)->first();
                if ($group_parent) {
                    $group = $group_parent->title;
                }
                $single['parent_group'] = $group;
                //get the product group
            }
        }
        $data['products'] = $products;
        $data['groups'] = ProductGroup::all(); //where(['branch_id' => $branch_id])->get();
        return view('savings.products', $data);
    }


    public function savings_per_product($id)
    {
        # code...

        $branch_id = $this->check_current_branch();
        $data['products'] = SavingsProducts::where(['product_code' => $id, 'branch_id' => $branch_id])->get();
        // $savings = CustomerSavings::join('customers', 'customer_savings.phone', '=', 'customers.phone')
        //     ->orderBy('customer_savings.created_at', 'desc')
        //     ->get(['customer_savings.*', 'customers.firstname', 'customers.lastname']);

        //for each savings get sum of dividends where paid = false and append to savings as interest
        // foreach ($savings as $saving) {
        //     $interest = Dividend::where(['phone' => $saving->phone, 'paid' => false])->sum('amount');
        //     $saving->interest = $interest;
        // }

        //   $savingsproducts['savingsproducts'] = SavingsProducts::select(DB::raw('savings_products.*, ifnull(SUM(savings.amount),0) as revenue'))
        // ->leftJoin('savings', 'savings.product', '=', 'savings_products.product_code')
        // ->groupBy('savings_products.id', 'savings_products.product_code', 'savings_products.product_name', 'savings_products.duration', 'savings_products.min_limit', 'savings_products.max_limit', 'savings_products.interest_rate', 'savings_products.admin_fee', 'savings_products.active', 'savings_products.created_at', 'savings_products.updated_at')
        // ->get();

        // $savings = DB::table('savings')
        //     ->leftJoin('savings_products', 'savings_products.product_code', '=', 'savings.product')
        //     ->leftJoin('customer_savings', 'customer_savings.phone', '=', 'savings.phone')
        //     ->select(DB::raw('SUM(savings.amount) AS amount,savings.phone,customer_savings.name', 'savings.created_at'))->groupBy('savings.phone', 'customer_savings.name')->get();

        $savings = Savings::select(DB::raw('SUM(amount) as amount,phone'))
            ->groupBy("phone")
            ->where(['product' => $id, 'branch_id' => $branch_id])
            // ->havingRaw('SUM(amount) > 0')
            ->get();

        foreach ($savings as $save) {
            //get name from the customer_Savings table
            // $customer = CustomerSavings::where(['phone' => $save->phone, 'branch_id' => $branch_id])->first();
            $customer = CustomerSavings::where(['phone' => $save->phone])->first();
            if ($customer) {
                $name = $customer->name;
                $created = $customer->created_at;
            } else {
                $name = "N/A";
                $created = "N/A";
            }
            $save['name'] = $name;
            $save['created'] = $created;
        }

        $data['savings'] = $savings;
        return view('savings.perproduct', $data);
    }

    public function savings_per_group($id)
    {
        # code...
        // get the savings 

        $group = ProductGroup::where('id', $id)->first();
        $products = SavingsProducts::where('product_group_id', $id)->get();
        // get the products ids
        $ids = [0];

        if ($products) {
            $ids = $products->pluck('product_code');
        }

        $savings = Savings::select(DB::raw('SUM(amount) as amount,phone,product'))
            ->groupBy("phone", 'product')
            ->whereIn('product', $ids)
            // ->havingRaw('SUM(amount) > 0')
            ->get();

        foreach ($savings as $save) {
            //get name from the customer_Savings table
            $customer = CustomerSavings::where(['phone' => $save->phone])->first();
            $prod = SavingsProducts::where(['product_code' => $save->product])->first();
            if ($customer) {
                $name = $customer->name;
                $created = $customer->created_at;
            } else {
                $name = "N/A";
                $created = "N/A";
            }
            $save['name'] = $name;
            $save['created'] = $created;
            $save['group_name'] = $group->title;
            $save['product_name'] = $prod->product_name;
        }

        $data['savings'] = $savings;
        $data['products'] = $products;
        return view('savings.pergroup', $data);
    }

    // GROUPS

    public function remove_group($id, $group_id)
    {
        # code... 
        // return $group_id;
        $customer = Customers::where('id', $id)->first();
        $date = Carbon::today();
        if ($customer) {
            $grp = CustomerGroup::find($group_id);
            if ($grp) {
                $grp->delete();
            }
            return redirect()->to('/customer/view/' . $id)->with('success', 'Group removed successfully');
        } else {
            return redirect()->to('/customer/view/' . $id)->with('error', 'Whoops! Exception thrown, please try again');
        }
    }
    public function assign_product(Request $request)
    {
        # code...
        $attr = $request->validate([
            'product_code' => 'required|string|max:255',
            'group_id' => 'required|string',
        ]);
        $product_code = $attr['product_code'];
        $group_id = $attr['group_id'];
        $current = SavingsProducts::where(['product_code' => $product_code])->first();
        if ($current) {
            $current->product_group_id = $group_id;
            $current->save();
            return redirect()->to('/savings/products')->with('success', 'Group assigned successfully!');
        }

        return redirect()->to('/savings/products')->with('error', 'Whoops!, experienced problems updating the records. Please try again later');
    }

    public function add_groups(Request $request)
    {
        # code...
        $attr = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        $title = $attr['title'];
        $description = $attr['description'];

        $group = ProductGroup::updateOrCreate(
            ['title' => $title],
            ['description' => $description]
        );
        if ($group) {
            $user = Auth::user()->name;
            $logs = SystemLogs::create([
                'phone' => $user,
                'title' => "Saving Group",
                'body' => "A new saving group with the name {$title} has been created by {$user} "
            ]);
            return redirect()->to('/savings/products')->with('gsuccess', 'Product Group addedd successfully!');
        }
        return redirect()->to('/savings/products')->with('gerror', 'Whoops! experienced problems with your request. Please try again');
    }
    public function all_savings()
    {

        $branch_id = $this->check_current_branch();
        $data['customers'] = Customers::where(['status' => 'Approved', 'branch_id' => $branch_id])->orderBy('created_at', 'desc')->get();
        $data['products'] = SavingsProducts::all(); //where(['branch_id' => $branch_id])->get();
        $savings = CustomerSavings::join('customers', 'customer_savings.phone', '=', 'customers.phone')
            ->where(['customer_savings.branch_id' => $branch_id])
            ->orderBy('customer_savings.created_at', 'desc')
            ->get(['customer_savings.*', 'customers.firstname', 'customers.lastname']);

        //for each savings get sum of dividends where paid = false and append to savings as interest
        foreach ($savings as $saving) {
            $interest = Dividend::where(['phone' => $saving->phone, 'iswithdrawal' => false])->sum('amount');
            if ($interest < 0) {
                $interest = 0;
            }
            $saving->interest = $interest;
        }

        $data['savings'] = $savings;
        return view('savings.index', $data);
    }

    public function welfare_members()
    {
        $branch_id = $this->check_current_branch();
        $data['welfare'] = CustomerSavings::join('customers', 'customer_savings.phone', '=', 'customers.phone')
            ->where(['customer_savings.branch_id' => $branch_id])
            ->orderBy('customer_savings.created_at', 'desc')
            ->get(['customer_savings.*', 'customers.firstname', 'customers.lastname']);
        return view('welfare.index', $data);
    }

    public function shares_members()
    {
        $branch_id = $this->check_current_branch();
        $data['shares'] = CustomerSavings::join('customers', 'customer_savings.phone', '=', 'customers.phone')
            ->where(['customer_savings.branch_id' => $branch_id])
            ->orderBy('customer_savings.created_at', 'desc')
            ->get(['customer_savings.*', 'customers.firstname', 'customers.lastname']);
        return view('shares.index', $data);
    }

    public function view_shares($id)
    {
        $data['data'] = Customers::where('phone', $id)->first();
        $shares['shares'] = Share::where('phone', $id)->orderBy('created_at', 'desc')->get();

        return view('shares.view')->with($data)->with($shares);
    }
    public function add_shares(Request $request)
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

            $welfare = new Share();
            $phone = $attr['phone'];
            $amount = $attr['amount'];
            $ref = $this->generateRandomString(12);
            $total_savings = 0;


            if ($savings_history) {
                $sum_total = Share::where('phone', '=', $phone)->sum('amount');

                $total_savings = $sum_total + $attr['amount'];

                $welfare->phone = $phone;
                $welfare->reference = $ref;
                $welfare->amount = $attr['amount'];
                $welfare->total = $total_savings;
                $welfare->save();
            } else {
                $total_savings = $attr['amount'];

                $welfare->phone = $phone;
                $welfare->reference = $this->generateRandomString(12);
                $welfare->amount = $attr['amount'];
                $welfare->total = $attr['amount'];
                $welfare->save();
            }

            $fcm = CustomerSavings::updateOrCreate(
                ['phone' =>  $phone],
                ['share_capital' => $total_savings, 'name' => $customer->firstname . " " . $customer->lastname],
            );
            $narration = "Shares contribution payment for {$phone}";
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
                'credit_account' => "SC100",
                'trans_date' => $today,
                'narration' => $narration,
                'loan_type' => "Contribution",
                'payment_ref' => $attr['payment_ref'],
                'name' => $name,
                'phone' => $phone,

            ]);

            $info = "Thank you, your deposit of KES. {$amount} has been received and Your Shares are now Ksh. {$total_savings}";

            $result = (new EmailController)->cash_deposit($customer, $welfare, $info);
            return redirect()->to('/shares')->with('success', 'Cash Deposit success!');
        } else {
            return redirect()->to('/shares')->with('error', 'Experienced problems updating!');
        }
    }



    public function pending_withdrawals()
    {

        $branch_id = $this->check_current_branch();
        $data['customers'] = Customers::where(['branch_id' => $branch_id])->get();
        $data['products'] = SavingsProducts::all(); //where(['branch_id' => $branch_id])->get();
        $withdrawal  = WithdrawalTransaction::join('customers', 'withdrawal_transactions.phone', '=', 'customers.phone')
            ->where(['withdrawal_transactions.status' => false, 'withdrawal_transactions.deleted' => false, 'withdrawal_transactions.branch_id' => $branch_id])->get(['withdrawal_transactions.*', 'customers.firstname', 'customers.lastname']);
        if ($withdrawal) {
            foreach ($withdrawal as $single) {
                $name = "";
                $product = SavingsProducts::where(['product_code' => $single->trans_id])->first();
                if ($product) {
                    $name = $product->product_name;
                }
                $single['product_name'] = $name;
            }
        }
        $data['withdrawal'] = $withdrawal;
        return view('savings.withdrawal', $data);
    }

    public function approved_withdrawals()
    {

        $branch_id = $this->check_current_branch();
        $withdrawal = WithdrawalTransaction::join('customers', 'withdrawal_transactions.phone', '=', 'customers.phone')
            ->where(['withdrawal_transactions.status' => true, 'withdrawal_transactions.deleted' => false, 'withdrawal_transactions.branch_id' => $branch_id])
            ->get(['withdrawal_transactions.*', 'customers.firstname', 'customers.lastname']);

        if ($withdrawal) {
            foreach ($withdrawal as $single) {
                $name = "";
                $product = SavingsProducts::where(['product_code' => $single->trans_id])->first();
                if ($product) {
                    $name = $product->product_name;
                }
                $single['product_name'] = $name;
            }
        }

        $data['withdrawal'] = $withdrawal;
        return view('savings.approvedwithdrawal', $data);
    }

    public function view_withdrawal($id)
    {
        $data['data'] = WithdrawalTransaction::join('savings_products', 'withdrawal_transactions.trans_id', '=', 'savings_products.product_code')->where('withdrawal_transactions.reference', $id)->first();

        if (!$data['data']) {
            return back()->withError("Saving Product Not Found");
        }
        if (empty($data['data'])) {

            return back()->withError("Saving Product Not Found");
        }
        if (is_null($data['data'])) {

            return back()->withError("Saving Product Not Found");
        }


        return view('savings.single')->with($data);
    }
    public function process_withdrawal(Request $request, $id)
    {
        $data = WithdrawalTransaction::where('reference', $id)->first();
        if ($data) {

            $user = CustomerSavings::where('phone', '=', $data->phone)->first();

            if ($user->amount > $data->amount) {
                $balance = $user->amount - $data->amount;
                $user->amount = $balance;
                $user->save();

                // search specific saving product
                $reference = $this->generateRandomString(12);

                $saving = new Savings();

                $saving->phone = $user->phone;
                $saving->reference = $reference;
                $saving->amount = ($data->amount) * -1;
                $saving->total = $balance;
                $saving->product = $data->trans_id;
                $saving->save();

                $branch_id = $this->check_current_branch();
                $data->result_code = 0;
                $data->response = "Success";
                $data->callback_response = "Transaction Processed successfully";
                $data->status = true;
                $data->branch_id = $branch_id;
                $data->save();


                // Record in Journal Entries
                $narration = "Withdrawal of Customer Savings KES {$data->amount}.";

                $today = Carbon::now();

                $loop = JournalEntries::create([
                    'reference' => $user->phone,
                    'amount' => $data->amount,
                    'debit_account' => "S200",
                    'credit_account' => "B035",
                    'trans_date' => $today,
                    'narration' => $narration,
                    'loan_type' => $data->trans_id,
                    'payment_ref' => $reference,
                    'name' => $user->name,
                    'phone' => $user->phone,

                ]);

                return redirect()->to('/savings/awithdrawals')->with('success', 'Withdrawal Processed successfully!');
            } else {
                return redirect()->to('/savings/withdrawal/' . $id)->with('error', 'Insufficient Funds');
            }
        } else {
            return redirect()->to('/savings/withdrawal/' . $id)->with('error', 'Experienced problems!!');
        }
    }

    public function view_product($id)
    {
        $branch_id = $this->check_current_branch();
        $data['data'] = SavingsProducts::where(['product_code' => $id, 'branch_id' => $branch_id])->first();
        return view('savings.viewproduct', $data);
    }

    public function approve_pay(Request $request, $id)
    {
        // return redirect()->to('/savings/view/' . $id)->with('error', 'Coming soon');
        $attr = $request->validate([
            'amount' => 'required|integer|between:1,100000',
        ]);
        $payout = Payouts::where('phone', $id)->first();
        if ($payout) {
            $maximum = $payout->amount;
            $cur = $request->amount;

            if ($cur > $maximum) {
                return redirect()->to('/savings/view/' . $id)->with('error', 'Insufficient balance, please try again!');
            }

            $date = Carbon::now();
            $year = $date->format('Y');
            $div = Dividend::where(['phone' => $id, 'paid' => false])->first();
            if ($div) {
                $total_amount = $div->amount;
                $active_amount = $div->available;
                $bal = $active_amount - $cur;
                if ($bal >= 0) {
                    $div->available = $bal;
                    $div->save();

                    $div = new Dividend();
                    $div->phone = $id;
                    $div->amount = $total_amount;
                    $div->paid = true;
                    $div->iswithdrawal = true;
                    $div->year = $year;
                    $div->available = $cur * -1;
                    $div->reference = $this->generateRandomString(12);
                    $div->save();
                }
            }


            // update all as paid
            $divs = Dividend::where(['phone' => $id])->orderBy('id', 'DESC')->get();
            foreach ($divs as $div) {
                $div->paid = true;
                $div->save();
            }

            //create a entry for balance if any

            $bal = $maximum - $cur;

            if ($bal > 0) {
                $div = new Dividend();
                $div->phone = $id;
                $div->amount = 0;
                $div->paid = false;
                $div->iswithdrawal = false;
                $div->year = $year;
                $div->available = $bal;
                $div->reference = $this->generateRandomString(12);
                $div->save();
            }

            // $all = (new SavingsController)->earn_interest();
            // $alt = (new SavingsController)->earn_active_interest();

            $logs = SystemLogs::create([
                'phone' => $id,
                'title' => "Interest Withdrawal",
                'body' => "Interest withdrawal of KES {$cur}. Available balance KES {$bal}"
            ]);

            return redirect()->to('/savings/view/' . $id)->with('success', 'Cash withdraw was successfully');
        }
        return redirect()->to('/savings/view/' . $id)->with('error', 'Experienced problems updating!');
    }

    public function view_savings($id)
    {

        $customer = Customers::where('phone', $id)->first();
        $available = Dividend::where(['paid' => false, 'phone' => $id])->sum('available');
        $current = Dividend::where(['iswithdrawal' => false, 'phone' => $id])->sum('amount');

        $data['savings'] =  Savings::join('savings_products', 'savings_products.product_code', '=', 'savings.product')
            ->where(['savings.phone' => $id])
            ->orderBy('savings.created_at', 'desc')
            ->get(['savings.*', 'savings_products.*', 'savings.created_at as saved']);

        $data['withdrawals'] =  Savings::join('savings_products', 'savings_products.product_code', '=', 'savings.product')
            ->where(['savings.phone' => $id, 'savings.withdrawal' => true])
            ->orderBy('savings.id', 'desc')->get(['savings.*', 'savings_products.*', 'savings.created_at as tolewa']);

        $data['savingsproducts'] = SavingsProducts::select(DB::raw('savings_products.*, ifnull(SUM(savings.amount),0) as revenue'))
            ->leftJoin('savings', 'savings.product', '=', 'savings_products.product_code')
            ->groupBy('savings_products.id', 'savings_products.product_code', 'savings_products.product_name', 'savings_products.duration', 'savings_products.min_limit', 'savings_products.max_limit', 'savings_products.interest_rate', 'savings_products.admin_fee', 'savings_products.active', 'savings_products.created_at', 'savings_products.updated_at')
            ->where(['savings.phone' => $id])
            ->get();
        $total_savings = Savings::where(['phone' => $id])->sum('amount');

        if ($available < 0) {
            $available = 0;
        }
        if ($current < 0) {
            $current = 0;
        }
        $fcm = Payouts::updateOrCreate(
            ['phone' =>  $customer->phone],
            ['amount' => $available]
        );
        $data['interes'] = Dividend::where(['paid' => true, 'iswithdrawal' => true, 'phone' => $id])->orderBy('created_at', 'DESC')->get();
        $data['data'] = ([
            'id' => $customer->id,
            'firstname' => $customer->firstname,
            'lastname' => $customer->lastname,
            'phone' => $customer->phone,
            'membership_no' => $customer->membership_no,
            'total_savings' => $total_savings,
            'available' => $available,
            'current' => $current,
            'reference' => $customer->phone

        ]);
        return view('savings.view', $data);
    }

    public function initiate_manual(Request $request)
    {

        $attr = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|integer|between:1,100000',
            'product' => 'required|string',
        ]);
        $phone = $attr['phone'];
        $amount = $attr['amount'];
        $product_code = $attr['product'];

        $customer = Customers::where('phone', $phone)->first();
        if ($customer->status == "Pending") {

            return redirect()->to('/customer/edit/' . $customer->id)->with('error', 'Operation not permitted, Please approve the client first!');
        }

        $user = CustomerSavings::where('phone', '=', $phone)->first();
        if ($user) {

            if ($user->amount >= $amount) {
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
                        return redirect()->to('/savings/pwithdrawals')->with('error', 'Insufficient Funds, Maximum allowed is KES ' . $max_amount);
                    }

                    // check if pending exists::
                    $pen = WithdrawalTransaction::where(['phone' => $phone, 'status' => false, 'deleted' => false])->first();

                    if ($pen) {

                        return redirect()->to('/savings/pwithdrawals')->with('error', 'You have a pending transaction');
                    }
                    $trans_id = $this->generateRandomString(12);
                    $over = WithdrawalTransaction::create([
                        'reference' => $trans_id,
                        'amount' => $amount,
                        'trans_id' => $product_code,
                        'phone' => $phone,
                        'branch_id' => $user->branch_id
                    ]);


                    return redirect()->to('/savings/pwithdrawals')->with('success', 'Savings withdrawal Successfull');
                } else {

                    return redirect()->to('/savings/pwithdrawals')->with('error', 'Insufficient Funds, Customer does not have the specific loan product');
                }
            } else {

                return redirect()->to('/savings/pwithdrawals')->with('error', 'Insufficient Funds, Please make some deposits');
            }
        } else {
            return redirect()->to('/savings/pwithdrawals')->with('error', 'Insufficient Funds, Please make some deposits');
        }
    }

    public function initiate_user_manual(Request $request, $id)
    {

        $attr = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|integer|between:1,100000',
            'product' => 'required|string',
        ]);
        $phone = $attr['phone'];
        $amount = $attr['amount'];
        $product_code = $attr['product'];

        $user = CustomerSavings::where('phone', '=', $phone)->first();
        if ($user) {

            if ($user->amount >= $amount) {
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
                        return redirect()->to('/customer/view/' . $id)->with('error', 'Insufficient Funds, Maximum allowed is KES ' . $max_amount);
                    }

                    // check if pending exists::
                    $pen = WithdrawalTransaction::where(['phone' => $phone, 'status' => false, 'deleted' => false])->first();

                    if ($pen) {

                        return redirect()->to('/customer/view/' . $id)->with('error', 'You have a pending transaction');
                    }
                    $trans_id = $this->generateRandomString(12);
                    $over = WithdrawalTransaction::create([
                        'reference' => $trans_id,
                        'amount' => $amount,
                        'trans_id' => $product_code,
                        'phone' => $phone,

                    ]);


                    return redirect()->to('/customer/view/' . $id)->with('success', 'Savings withdrawal Successfull');
                } else {

                    return redirect()->to('/customer/view/' . $id)->with('error', 'Insufficient Funds, Customer does not have the specific loan product');
                }
            } else {

                return redirect()->to('/customer/view/' . $id)->with('error', 'Insufficient Funds, Please make some deposits');
            }
        } else {
            return redirect()->to('/customer/view/' . $id)->with('error', 'Insufficient Funds, Please make some deposits');
        }
    }


    public function view_welfare($id)
    {
        $data['data'] = Customers::where('phone', $id)->first();
        $welfare['welfare'] = Welfare::where('phone', $id)->orderBy('created_at', 'desc')->get();

        return view('welfare.view')->with($data)->with($welfare);
    }

    public function add_welfare(Request $request)
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

            $welfare = new Welfare();
            $phone = $attr['phone'];
            $amount = $attr['amount'];
            $ref = $this->generateRandomString(12);
            $total_savings = 0;


            if ($savings_history) {
                $sum_total = Welfare::where('phone', '=', $phone)->sum('amount');

                $total_savings = $sum_total + $attr['amount'];

                $welfare->phone = $phone;
                $welfare->reference = $ref;
                $welfare->amount = $attr['amount'];
                $welfare->total = $total_savings;
                $welfare->save();
            } else {
                $total_savings = $attr['amount'];

                $welfare->phone = $phone;
                $welfare->reference = $this->generateRandomString(12);
                $welfare->amount = $attr['amount'];
                $welfare->total = $attr['amount'];
                $welfare->save();
            }

            $fcm = CustomerSavings::updateOrCreate(
                ['phone' =>  $phone],
                ['welfare' => $total_savings, 'name' => $customer->firstname . " " . $customer->lastname],
            );
            $narration = "Welfare contribution payment for {$phone}";
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
                'credit_account' => "W200",
                'trans_date' => $today,
                'narration' => $narration,
                'loan_type' => "Contribution",
                'payment_ref' => $attr['payment_ref'],
                'name' => $name,
                'phone' => $phone,

            ]);

            $info = "Thank you, your deposit of KES. {$amount} has been received and Your welfare are now Ksh. {$total_savings}";

            $result = (new EmailController)->cash_deposit($customer, $welfare, $info);
            return redirect()->to('/welfare')->with('success', 'Cash Deposit success!');
        } else {
            return redirect()->to('/welfare')->with('error', 'Experienced problems updating!');
        }
    }

    // Investment
    public function all_investment()
    {

        $branch_id = $this->check_current_branch();
        $data['customers'] = Customers::where(['branch_id' => $branch_id])->get();
        $investment = Thirdparty::where(['status' => true, 'branch_id' => $branch_id])->orderBy('created_at', 'desc')->get();
        foreach ($investment as $key => $value) { //get sum of unpaid earnings from profit_share table where status is false 
            $investment[$key]['interest_balance'] = ProfitShare::where(['phone' => $value->phone, 'status' => false])->sum('earnings');
        }
        $data['investment'] = $investment;
        return view('investment.index', $data);
    }
    public function view_investment($id)
    {
        // $data['data']   = Thirdparty::select(DB::raw('thirdparties.*, ifnull(SUM(profit_shares.earnings),0) as interest_balance'))
        //     ->leftJoin('profit_shares', 'profit_shares.phone', '=', 'thirdparties.phone')
        //     ->where(['thirdparties.phone' => $id])
        //     ->where(['profit_shares.status' => false])
        //     ->groupBy('thirdparties.id')
        //     ->first();
        $data = Thirdparty::select(DB::raw('thirdparties.*, ifnull(SUM(profit_shares.earnings), 0) as interest_balance'))
            ->leftJoin('profit_shares', function ($join) {
                $join->on('profit_shares.phone', '=', 'thirdparties.phone')
                    ->where('profit_shares.status', false)
                    ->orWhereNull('profit_shares.status');
            })
            ->where('thirdparties.phone', $id)
            ->groupBy('thirdparties.id')
            ->first();

        $data['savings'] = FloatStatements::where('phone', $id)->orderBy('created_at', 'desc')->get();
        $data['data'] = $data;
        return view('investment.view', $data);
    }

    public function pending_investment()
    {
        $branch_id = $this->check_current_branch();
        $data['withdrawal'] = Withdrawals::join('thirdparties', 'withdrawals.phone', '=', 'thirdparties.phone')
            ->where(['withdrawals.status' => false, 'withdrawals.narration' => 'pending', 'withdrawals.branch_id' => $branch_id])
            ->get(['withdrawals.*', 'thirdparties.firstname', 'thirdparties.lastname']);

        return view('investment.pwith', $data);
    }
    public function approved_investment()
    {
        $branch_id = $this->check_current_branch();
        $withdrawal['withdrawal'] = Withdrawals::join('thirdparties', 'withdrawals.phone', '=', 'thirdparties.phone')
            ->where(['withdrawals.status' => true, 'withdrawals.narration' => 'approved', 'withdrawals.branch_id' => $branch_id])
            ->get(['withdrawals.*', 'thirdparties.firstname', 'thirdparties.lastname']);

        return view('investment.awith')->with($withdrawal);
    }


    public function view_chats()
    {
        $customers['customers'] = Customers::all();
        $chats['chats'] = ChatRoom::orderBy('updated_at', 'desc')->get();;
        return view('chats.index')->with($chats)->with($customers);
    }

    public function single_chat($id)
    {
        $chats['chats'] = Chats::where(['phone' => $id,])->orderBy('created_at', 'asc')->get();
        return view('chats.view')->with($chats);
    }

    public function compose(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'message' => 'required|string|max:255'
        ]);
        $phone = $request->phone;
        $message = $request->message;
        $room = ChatRoom::updateOrCreate(
            ['phone' => $phone],
            ['message' => $message, 'is_admin' => "1"]
        );
        $chat = Chats::create([
            'phone' => $phone,
            'message' => $message,
            'is_admin' => "1"
        ]);
        $result = (new FCMController)->send_message_admin($phone, "Admin Request", $message);

        return redirect()->to('/chats')->with('success', 'Message sent Successfully');
    }

    public function upload_compose(Request $request, $id)
    {

        $file = $request->file('attachment');

        # code...

        // $filename =  Carbon::now() . ".png";
        $filename =  $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $location = 'uploads';

        $file->move($location, $filename);
        // In case the uploaded file path is to be stored in the database 
        $message = public_path($location . "/" . $filename);


        $message = str_replace("/home/irmikete/", "https://", $message);

        $room = ChatRoom::updateOrCreate(
            ['phone' => $id],
            ['message' => $message, 'is_admin' => "1"]
        );
        $chat = Chats::create([
            'phone' => $id,
            'message' => $message,
            'is_admin' => "1"
        ]);
        $customer = Customers::where(['phone' => $id])->first();
        $result = (new EmailController)->chat_email_attach_user($chat, $message);
        if ($customer) {
            $email = $customer->email;

            // $results = (new EmailController)->new_admin_message_email($customer, $email, $message);
        }

        return redirect()->to('/chats/view/' . $id)->with('success', 'Attatchment sent Successfully');
    }


    public function add_chat(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'message' => 'required|string|max:255'
        ]);
        $phone = $request->phone;
        $message = $request->message;
        $room = ChatRoom::updateOrCreate(
            ['phone' => $phone],
            ['message' => $message, 'is_admin' => "1"]
        );
        $chat = Chats::create([
            'phone' => $phone,
            'message' => $message,
            'is_admin' => "1"
        ]);
        $result = (new EmailController)->chat_email_to_user($chat);

        return redirect()->to('/chats/view/' . $phone)->with('success', 'Attatchment sent Successfully');
    }

    public function password_changer(Request $request, $id)
    {

        $attr = $request->validate([
            'password' => 'required|string|max:255',
            'confirm' => 'required|string|max:255'
        ]);
        $password = $attr['password'];
        $confirm = $attr['confirm'];

        if ($password != $confirm) {
            return redirect()->to('/admin')->with('error', 'Password does not match!!');
        }
        $admin = Admins::where('id', $id)->first();
        if ($admin) {
            $admin->password = Hash::make($password);
            $admin->save();

            $user = User::where(['email' => $admin->email])->first();
            if ($user) {
                $user->password = Hash::make($password);
                $user->save();
            }

            return redirect()->to('/admin')->with('success', 'Password Updated Successfully');
        } else {

            return redirect()->to('/admin')->with('error', 'Failed to update password');
        }
    }

    // Admins Module

    public function add_admin(Request $request)
    {
        $attr = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'usertype' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
        ]);
        $firstname = $attr['firstname'];
        $lastname = $attr['lastname'];
        $phone = $attr['phone'];
        $usertype = $attr['usertype'];
        $status = $attr['status'] === 'true' ? true : false;
        $email = $attr['email'];
        $name = $firstname . " " . $lastname;
        $rand = rand(100000, 1000000);
        $pass = Hash::make($rand);

        $admin = Admins::create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' =>  $pass,
            'phone' => $phone,
            'usertype' => $usertype,
            'status' => $status
        ]);

        if ($admin) {
            $user = User::create([
                'name' => $name,
                'password' => $pass,
                'email' => $attr['email'],
            ]);
        }
        $message = "Welcome to Jetpack Compose. your account login details are Email: {$email} and Password: {$rand}";

        $result = (new EmailController)->new_admin_email($admin, $email, $message);
        return redirect()->to('/admin')->with('success', 'Admin Added successfully');
    }
    public function update_admin(Request $request, $id)
    {
        $attr = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'usertype' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'email' => 'required|string|email',
        ]);
        $alerts_on = $request->alerts_on;
        $firstname = $attr['firstname'];
        $lastname = $attr['lastname'];
        $phone = $attr['phone'];
        $usertype = $attr['usertype'];
        $status = $attr['status'] === 'true' ? true : false;
        $email = $attr['email'];
        $name = $firstname . " " . $lastname;

        $admin = Admins::find($id);
        $original_email = $admin->email;
        if ($alerts_on) {
            $admin->alerts_on = $alerts_on;
        } else {
            $admin->alerts_on = false;
        }
        $admin->firstname = $firstname;
        $admin->lastname = $lastname;
        $admin->email = $email;
        $admin->phone = $phone;
        $admin->usertype = $usertype;
        $admin->status = $status;
        $admin->save();

        //get user by original email

        $user = User::where(['email' => $original_email])->first();
        if ($user) {
            $user->email = $email;
            $user->name = $name;
            $user->save();
        }

        return redirect()->to('/admin')->with('success', 'Admin Updated successfully');
    }

    public function view_admins()
    {

        $branch_id = $this->check_current_branch();
        $data['admins'] = Admins::all(); //where(['branch_id' => $branch_id])->get();
        return view('admins.index', $data);
    }
    public function view_admin($id)
    {
        $branch_id = $this->check_current_branch();
        $data['data'] = Admins::where('id', $id)->first();
        $data['admins'] = Admins::all(); //where(['branch_id' => $branch_id])->get();
        return view('admins.view', $data);
    }


    public function edit_admin($id)
    {

        $data['data'] = Customers::where('id', $id)->first();
        return view('admin.edit')->with($data);
    }
    public function export_admin()
    {
        # code...

        $data = [
            'title' => 'Welcome to Tutsmake.com',
            'date' => date('m/d/Y')
        ];

        // $pdf = DomPDFPDF::loadView('testPDF', $data);

        // return $pdf->download('tutsmake.pdf');
    }



    // But for Me
    public function add_b4m_member(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255',
        ]);
        $phone = $attr['phone'];
        $fcm = Buyforme::updateOrCreate(
            ['phone' =>  $phone],
            []
        );

        return redirect()->to('/b4m')->with('success', 'Member Added successfully!');
    }

    public function b4m_members()
    {

        $customers['customers'] = Customers::where('status', 'Approved')->orderBy('created_at', 'desc')->get();
        $buyforme['buyforme'] = Buyforme::join('customers', 'buyformes.phone', '=', 'customers.phone')->get(['buyformes.*', 'customers.firstname', 'customers.lastname']);
        return view('buyforme.index')->with($buyforme)->with($customers);
    }

    public function open_b4m()
    {
        $buyforme['buyforme'] = Buyforme::join('customers', 'buyformes.phone', '=', 'customers.phone')->get(['buyformes.*', 'customers.firstname', 'customers.lastname']);
        $pools['pools'] = Pool::join('customers', 'pools.phone', '=', 'customers.phone')->where('pools.is_closed', false)->get(['pools.*', 'customers.firstname', 'customers.lastname']);
        return view('buyforme.open')->with($pools)->with($buyforme);
    }

    public function closed_b4m()
    {
        $pools['pools'] = Pool::join('customers', 'pools.phone', '=', 'customers.phone')->where('pools.is_closed', true)->get(['pools.*', 'customers.firstname', 'customers.lastname']);
        return view('buyforme.closed')->with($pools);
    }
    public function recent_b4m()
    {
        $lock['lock'] = LockBuy::join('customers', 'lock_buys.phone', '=', 'customers.phone')->get(['lock_buys.*', 'customers.firstname', 'customers.lastname']);
        return view('buyforme.recent')->with($lock);
    }
    public function view_b4m($id)
    {

        $commitment['commitment'] = Commitment::where('reference', $id)->orderBy('created_at', 'desc')->get();
        $data['data'] = Pool::join('customers', 'pools.phone', '=', 'customers.phone')->where('pools.reference', $id)->first(['pools.*', 'customers.firstname', 'customers.lastname']);
        return view('buyforme.view')->with($data)->with($commitment);
    }

    public function commit_b4m($id)
    {
        $buyforme['buyforme'] = Buyforme::join('customers', 'buyformes.phone', '=', 'customers.phone')->get(['buyformes.*', 'customers.firstname', 'customers.lastname']);
        $commitment['commitment'] = Commitment::where('reference', $id)->orderBy('created_at', 'desc')->get();
        $data['data'] = Pool::join('customers', 'pools.phone', '=', 'customers.phone')->where('pools.reference', $id)->first(['pools.*', 'customers.firstname', 'customers.lastname']);
        return view('buyforme.edit')->with($data)->with($commitment)->with($buyforme);
    }


    public function lock_b4m(Request $request)
    {

        $attr = $request->validate([
            'reference' => 'required|string|max:255',
        ]);
        $reference = $attr['reference'];
        $exist = Pool::where(['reference' => $reference,])->exists();

        if ($exist) {
            $pool = Pool::where(['reference' => $reference,])->first();
            $balance = $pool->balance;
            $owner = $pool->phone;

            if ($balance > 0) {
                return redirect()->to('/b4m/view/' . $reference)->with('error', 'You cannot closed this pool at the moment!!');
            }
            $all = Commitment::where(['reference' => $reference])->get();
            foreach ($all as $al) {
                $lock = LockBuy::updateOrCreate(
                    ['phone' => $al->phone, 'owner' => $owner],
                    ['reference' => $reference, 'amount' => $al->amount, 'balance' => $al->amount]
                );
            }
        } else {

            return redirect()->to('/b4m/view/' . $reference)->with('error', 'Please try again later!!');
        }

        return redirect()->to('/b4m/closed')->with('success', 'Pool Closed Successfully!!');
    }
    public function contribute_b4m(Request $request)
    {
        $attr = $request->validate([
            'reference' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'trans_code' => 'required|string|max:255',
            'amount' => 'required|integer|between:1,100000',
        ]);
        $reference = $attr['reference'];
        $phone = $attr['phone'];
        $amount = $attr['amount'];
        $trans_code = $attr['trans_code'];

        $exist = Pool::where(['reference' => $reference, 'is_closed' => false])->exists();
        if ($exist) {
            $contributor = Buyforme::where(['phone' => $phone])->first();
            if ($contributor) {
                $current = Pool::where(['reference' => $reference])->first();
                if ($current) {
                    $owner = $current->phone;

                    $balance = $current->balance;
                    $diff = $balance - $amount;

                    $org = $contributor->amount;
                    $newamount = $org + $amount;

                    $contributor->amount = $newamount;
                    $contributor->save();

                    if ($diff > 0) {
                        $current->balance = $diff;
                        $current->save();
                    } else {
                        $current->balance = 0;
                        $current->is_closed = true;
                        $current->save();
                    }
                    $alr = Commitment::where(['phone' => $phone, 'reference' => $reference])->first();
                    if ($alr) {
                        $t_amount = $alr->amount + $amount;
                    } else {
                        $t_amount = $amount;
                    }
                    $commit = Commitment::updateOrCreate(
                        ['phone' => $phone, 'reference' => $reference],
                        ['trans_code' => $trans_code, 'amount' => $t_amount]
                    );
                    $previous = LockBuy::where(['owner' => $phone, 'phone' => $owner])->first();
                    if ($previous) {
                        $lastbalance = $previous->balance;
                        $next = $lastbalance - $t_amount;
                        $end = 0;
                        if ($next > 0) {
                            $end = $next;
                        } else {
                            $end = 0;
                        }
                        $previous->balance = $end;
                        $previous->save();
                    }
                    return redirect()->to('/b4m/commit/' . $reference)->with('success', 'Pool Deposit Successfull');
                } else {
                    return redirect()->to('/b4m/commit/' . $reference)->with('error', 'Wait for Account Approval');
                }
            } else {
                return redirect()->to('/b4m/commit/' . $reference)->with('error', 'Wait for Account Approval');
            }
        }
        return redirect()->to('/b4m/commit/' . $reference)->with('error', 'Pool Already closed please try another pool');
    }

    public function create_pool(Request $request)
    {

        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'amount' => 'required|integer|between:1,100000',
            'description' => 'required|string|max:255',
        ]);
        $phone = $attr['phone'];
        $amount = $attr['amount'];
        $description = $attr['description'];

        $exist = Pool::where(['phone' => $phone, 'is_closed' => false])->exists();
        if ($exist) {
            return redirect()->to('/b4m/open')->with('error', 'Member has Existing Pool!');
        }
        $pool = Pool::create(['reference' => $this->generateRandomString(12), 'phone' => $phone, 'amount' => $amount, 'balance' => $amount, 'description' => $description]);

        return redirect()->to('/b4m/open')->with('success', 'Pool Created successfully!');
    }

    public function view_settings()
    {

        $data['customers'] = Customers::where(['status' => 'Approved', 'online_access' => false])->orderBy('created_at', 'desc')->get();
        return view('settings.sync', $data);
    }

    // Billing

    public function view_billing()
    {


        $prev_month_date = Carbon::now()->startOfMonth()->subMonth(1);

        $prev_year = $prev_month_date->format('Y');
        $prev_month = $prev_month_date->format('F');


        // $unpaid['unpaid'] = ProfitShare::where(['status' => false])->whereYear('created_at', '=', $prev_year)
        //     ->whereMonth('created_at', '=', $prev_month)->get();

        $data['tobepaid'] = ProfitShare::where(['status' => false, 'year' => $prev_year, 'month' => $prev_month])
            ->orderBy('created_at', 'desc')
            ->get();
        $data['unpaid'] = ProfitShare::where(['status' => false])
            ->orderBy('created_at', 'desc')
            ->get();
        $data['paid'] = ProfitShare::where(['status' => true])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('billing.index', $data);
    }

    public function pay_all_bills()
    {

        $unpaid = ProfitShare::where(['status' => false])->get();

        foreach ($unpaid as $un) {

            $reference = "{$un->year}-{$un->month}";
            $amount = $un->earnings;
            $phone = $un->phone;
            $narration = "Bill Payment of KES {$amount} at a rate of {$un->ratio} for total amount of KES {$un->float_balance} ";

            if ($phone == 'system') {

                $done =    $this->approve_paid_bill($reference, $amount, $narration, "System Float", "System Bulk");

                $balances_account = AccountBalance::where(['status' => true])->first();
                if ($balances_account) {
                    $system_balance = $balances_account->paybill + $amount;
                    $balances_account->paybill = $system_balance;
                    $balances_account->save();
                }
            } elseif ($phone == 'developer') {

                $un->status = true;
                $un->save();
                $done =    $this->approve_paid_bill($reference, $amount, $narration, "Developer", "Developer");
            } else {

                $customer = Customers::where(['phone' => $phone])->first();
                $name = "{$customer->firstname} $customer->lastname}";
                $un->status = true;
                $un->save();
                $done = $this->approve_paid_bill($reference, $amount, $narration, $name, $phone);
            }
        }

        return redirect()->to('/billing')->with('success', 'All Bills Paid Successfully!');
    }

    public function pay_billing_old(Request $request)
    {
        $attr = $request->validate([
            'bill' => 'required|string|max:255',
        ]);
        $id = $attr['bill'];

        $bill = ProfitShare::where(['id' => $id])->first();
        if ($bill) {

            $reference = "{$bill->year}-{$bill->month}";
            $amount = $bill->earnings;
            $phone = $bill->phone;
            $narration = "Bill Payment of KES {$amount} at a rate of {$bill->ratio} for total amount of KES {$bill->float_balance} ";

            // System Consider it paid and add to bulk::::
            if ($phone == 'system') {
                $bill->status = true;
                $bill->save();
                $done =    $this->approve_paid_bill($reference, $amount, $narration, "System Float", "System Bulk");

                $balances_account = AccountBalance::where(['status' => true])->first();
                if ($balances_account) {
                    $system_balance = $balances_account->paybill + $amount;
                    $balances_account->paybill = $system_balance;
                    $balances_account->save();
                }

                return redirect()->to('/billing')->with('success', 'Amount added to Bulk');
            } elseif ($phone == 'developer') {

                $bill->status = true;
                $bill->save();
                $done =    $this->approve_paid_bill($reference, $amount, $narration, "Developer", "Developer");


                return redirect()->to('/billing')->with('success', 'Bill Payment successfully processed');
            }

            // Admin Fee

            elseif ($phone == 'developer(admin_fee)') {

                $bill->status = true;
                $bill->save();
                $done =    $this->approve_paid_bill($reference, $amount, $narration, "Developer Admin ", "Developer Admin");


                return redirect()->to('/billing')->with('success', 'Bill Payment successfully processed');
            } elseif ($phone == '254791729957(admin_fee)') {

                $bill->status = true;
                $bill->save();
                $done =    $this->approve_paid_bill($reference, $amount, $narration, "254791729957(admin_fee)", "254791729957(admin_fee)");


                return redirect()->to('/billing')->with('success', 'Bill Payment successfully processed');
            }


            // End of Admin  Fee Transaction


            else {

                $customer = Customers::where(['phone' => $phone])->first();
                $name = "{$customer->firstname} $customer->lastname}";
                $bill->status = true;
                $bill->save();
                $done = $this->approve_paid_bill($reference, $amount, $narration, $name, $phone);

                return redirect()->to('/billing')->with('success', 'Bill Payment successfully processed');
            }
        }
        return redirect()->to('/billing')->with('error', 'Bill payment could not be completed, please contact system administrator');
    }
    public function pay_billing(Request $request, $id)
    {

        $bill = ProfitShare::where(['id' => $id])->first();
        if ($bill) {

            $current = Carbon::now();
            $year = $current->format('Y');
            $month = $current->format('F');

            // check if the bill is for the current month and year return error
            if ($bill->year == $year && $bill->month == $month) {

                return redirect()->to('/billing')->with('error', 'Please approve the transaction after end month');
            }

            $reference = "{$bill->year}-{$bill->month}";
            $amount = $bill->earnings;
            $phone = $bill->phone;
            $narration = "Bill Payment of KES {$amount} at a rate of {$bill->ratio} for total amount of KES {$bill->float_balance} ";

            // System Consider it paid and add to bulk::::
            if ($phone == 'system') {
                $bill->status = true;
                $bill->save();
                $done =    $this->approve_paid_bill($reference, $amount, $narration, "System Float", "System Bulk");

                $balances_account = AccountBalance::where(['status' => true])->first();
                if ($balances_account) {
                    $system_balance = $balances_account->paybill + $amount;
                    $balances_account->paybill = $system_balance;
                    $balances_account->save();
                }

                return redirect()->to('/billing')->with('success', 'Amount added to Bulk');
            } elseif ($phone == 'developer') {

                $bill->status = true;
                $bill->save();
                $done =    $this->approve_paid_bill($reference, $amount, $narration, "Developer", "Developer");


                return redirect()->to('/billing')->with('success', 'Bill Payment successfully processed');
            }


            // Admin Fee

            elseif ($phone == 'developer(admin_fee)') {

                $bill->status = true;
                $bill->save();
                $done =    $this->approve_paid_bill($reference, $amount, $narration, "Developer Admin ", "Developer Admin");


                return redirect()->to('/billing')->with('success', 'Bill Payment successfully processed');
            } elseif ($phone == '254791729957(admin_fee)') {

                $bill->status = true;
                $bill->save();
                $done =    $this->approve_paid_bill($reference, $amount, $narration, "254791729957(admin_fee)", "254791729957(admin_fee)");


                return redirect()->to('/billing')->with('success', 'Bill Payment successfully processed');
            }


            // End of Admin  Fee Transaction
            else {

                $customer = Customers::where(['phone' => $phone])->first();
                $name = "{$customer->firstname} $customer->lastname}";
                $bill->status = true;
                $bill->save();
                $done = $this->approve_paid_bill($reference, $amount, $narration, $name, $phone);

                return redirect()->to('/billing')->with('success', 'Bill Payment successfully processed');
            }
        }
        return redirect()->to('/billing')->with('error', 'Bill payment could not be completed, please contact system administrator');
    }

    public function approve_paid_bill($reference, $amount, $narration, $name, $phone)
    {


        $now = Carbon::rawParse('now')->format('Y-m-d');
        $today = Carbon::createFromFormat('Y-m-d', $now);

        $loop = JournalEntries::create([
            'reference' => $reference,
            'amount' => $amount,
            'debit_account' => "B020",
            'credit_account' => "B110",
            'trans_date' => $today,
            'narration' => $narration,
            'loan_type' => "Bill Payment",
            'payment_ref' => $this->generateRandomString(12),
            'name' => $name,
            'phone' => $phone,

        ]);

        $logs = SystemLogs::create([
            'phone' => $phone,
            'title' => "Bill Payment",
            'body' => $narration
        ]);
        return $loop;
    }

    public function approve_withdrawal(Request $request, $id)
    {
        # code...
        $data = WithdrawalTransaction::where('reference', $id)->first();
        if ($data) {
            $trans_id = $data->trans_id;
            $phone = $data->phone;
            $amount = $data->amount;


            $user = CustomerSavings::where('phone', '=', $data->phone)->first();

            $customer = Customers::where('phone', $data->phone)->first();
            if ($customer->status == "Pending") {

                return redirect()->to('/customer/view/' . $customer->id)->with('error', 'Operation not permitted, Please approve the client first!');
            }


            $user = CustomerSavings::where('phone', '=', $data->phone)->first();

            if ($user->amount >= $data->amount) {
                $balance = $user->amount - $data->amount;
                $user->amount = $balance;
                $user->save();

                $data->result_code = 0;
                $data->response = "Success";
                $data->callback_response = "Transaction Processed successfully";
                $data->status = true;
                $data->save();

                // record the withdrawal

                Savings::create([
                    'reference' => $this->generateRandomString(12),
                    'phone' => $phone,
                    'product' => $trans_id,
                    'amount' => $amount * -1,
                    'total' => $balance,
                    'withdrawal' => true,
                    'branch_id' => $user->branch_id,
                ]);


                return redirect()->to('/savings/awithdrawals')->with('success', 'Withdrawal Processed successfully!');
            } else {
                return redirect()->to('/savings/pwithdrawals')->with('error', 'Insufficient Funds');
            }
        } else {
            return redirect()->to('/savings/pwithdrawals')->with('error', 'Experienced problems!!');
        }
    }
    public function reject_withdrawal(Request $request, $id)
    {
        # code...
        $data = WithdrawalTransaction::where('reference', $id)->first();
        if ($data) {
            $data->deleted = true;
            $data->save();

            return redirect()->to('/savings/pwithdrawals')->with('success', 'Withdrawal Rejected successfully!');
        } else {
            return redirect()->to('/savings/pwithdrawals')->with('error', 'Experienced problems!!');
        }
    }
    public function approve_user_withdrawal(Request $request, $id, $sasa)
    {
        # code...
        $data = WithdrawalTransaction::where('reference', $id)->first();
        if ($data) {
            $trans_id = $data->trans_id;
            $phone = $data->phone;
            $amount = $data->amount;

            $user = CustomerSavings::where('phone', '=', $data->phone)->first();

            $customer = Customers::where('phone', $data->phone)->first();
            if ($customer->status == "Pending") {

                return redirect()->to('/customer/view/' . $sasa)->with('error', 'Operation not permitted, Please approve the client first!');
            }


            if ($user->amount >= $data->amount) {
                $balance = $user->amount - $data->amount;
                $user->amount = $balance;
                $user->save();

                $data->result_code = 0;
                $data->response = "Success";
                $data->callback_response = "Transaction Processed successfully";
                $data->status = true;
                $data->save();

                // record the withdrawal

                Savings::create([
                    'reference' => $this->generateRandomString(12),
                    'phone' => $phone,
                    'product' => $trans_id,
                    'amount' => $amount * -1,
                    'total' => $balance,
                    'withdrawal' => true
                ]);


                return redirect()->to('/customer/view/' . $sasa)->with('success', 'Withdrawal Processed successfully!');
            } else {
                return redirect()->to('/customer/view/' . $sasa)->with('error', 'Insufficient Funds');
            }
        } else {
            return redirect()->to('/customer/view/' . $sasa)->with('error', 'Experienced problems!!');
        }
    }
    public function reject_user_withdrawal(Request $request, $id, $user)
    {
        # code...
        $data = WithdrawalTransaction::where('reference', $id)->first();
        if ($data) {
            $data->deleted = true;
            $data->save();

            return redirect()->to('/customer/view/' . $user)->with('success', 'Withdrawal Rejected successfully!');
        } else {
            return redirect()->to('/customer/view/' . $user)->with('error', 'Experienced problems!!');
        }
    }
    public function add_variables()
    {
        $facebook = "#";
        $youtube = "#";
        $instagram = "#";
        $twitter = "#";

        $social_pg = Social::where(['active' => true])->first();
        if ($social_pg) {
            $facebook = $social_pg->facebook;
            $youtube = $social_pg->youtube;
            $instagram = $social_pg->instagram;
            $twitter = $social_pg->twitter;
        }

        $socials = [
            'facebook' => $facebook,
            'youtube' => $youtube,
            'instagram' => $instagram,
            'twitter' => $twitter

        ];
        $data['settings'] = Setting::all();
        $data['social'] = $socials;
        $data['setting'] = Setting::where(['status' => true])->first();
        // return $data;
        return view('admins.settings', $data);
    }

    public function socials(Request $request)
    {
        # code...
        $attr = $request->validate([
            'facebook' => 'required|string|max:255',
            'youtube' => 'required|string|max:255',
            'instagram' => 'required|string|max:255',
            'twitter' => 'required|string|max:255',
        ]);
        $facebook = $attr['facebook'];
        $youtube = $attr['youtube'];
        $instagram = $attr['instagram'];
        $twitter = $attr['twitter'];

        $st = Social::updateOrCreate(
            ['active' =>   true],
            [
                'facebook' => $facebook, 'youtube' => $youtube,
                'instagram' => $instagram, 'twitter' => $twitter,

            ]
        );

        return redirect()->to('/settings/variables')->with('success', 'Update Successfull');
    }

    public function new_variable(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'days' => 'required|string|max:255',
            'saving_rate' => 'required|string|max:255',
            'system_rate' => 'required|string|max:255',
            'developer_rate' => 'required|string|max:255',
            'investor_rate' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);
        $email = $attr['email'];
        $code = $attr['code'];
        $days = $attr['days'];
        $saving_rate = $attr['saving_rate'] / 100;
        $system_rate = $attr['system_rate'] / 100;
        $developer_rate = $attr['developer_rate'] / 100;
        $investor_rate = $attr['investor_rate'] / 100;
        $status = $attr['status'] === 'true' ? true : false;

        $st = Setting::updateOrCreate(
            ['status' =>   $status],
            ['admin_email' => $email, 'code' => $code, 'days' => $days, 'saving_rate' => $saving_rate, 'system_rate' => $system_rate, 'developer_rate' => $developer_rate, 'investor_rate' => $investor_rate]
        );
        $payload = [
            'admin_email' => $email, 'code' => $code, 'days' => $days, 'saving_rate' => $saving_rate, 'system_rate' => $system_rate, 'developer_rate' => $developer_rate, 'investor_rate' => $investor_rate
        ];
        // convert payload to 
        $user = Auth::user();
        $payload = json_encode($payload);
        $logs = SystemLogs::create([
            'phone' => $user->email,
            'title' => "System Variables Update",
            'body' => "Change of System variable by $user->name"
        ]);
        return redirect()->to('/settings/variables')->with('success', 'Update Successfull');
    }

    public function phone_update(Request $request)
    {
        # code...
        $attr = $request->validate([
            'current_phone' => 'required|string|min:10|max:255',
            'update_phone' => 'required|string|min:10|max:255'
        ]);
        $current_phone = $attr['current_phone'];
        $update_phone = $attr['update_phone'];

        $phone = preg_replace("/^0/", "254", $current_phone);
        $update_phone = preg_replace("/^0/", "254", $update_phone);
        $customer = Customers::where('phone', $phone)->first();
        if ($customer) {
            // Check if the user has the Customer savings
            $saver = CustomerSavings::where('phone', $phone)->first();
            if ($saver) {
                $saver->phone = $update_phone;
                $saver->save();
            }
            // check if the user has the savings
            $savings = Savings::where('phone', $phone)->orderBy('created_at', 'desc')->get();
            if ($savings) {
                foreach ($savings as $one) {
                    $one->phone = $update_phone;
                    $one->save();
                }
            }
            // Check for welfare
            $welfares = Welfare::where('phone', $phone)->orderBy('created_at', 'desc')->get();
            if ($welfares) {
                foreach ($welfares as $one) {
                    $one->phone = $update_phone;
                    $one->save();
                }
            }
            // check for loans
            $loans = Loans::where('phone', $phone)->orderBy('created_at', 'desc')->get();
            if ($loans) {
                foreach ($loans as $one) {
                    $one->phone = $update_phone;
                    $one->save();
                }
            }
            // check for logs
            $logs = SystemLogs::where('phone', $phone)->orderBy('created_at', 'desc')->get();
            if ($logs) {
                foreach ($logs as $one) {
                    $one->phone = $update_phone;
                    $one->save();
                }
            }
            $customer->phone = $update_phone;
            $customer->save();

            $user = Auth::user();
            $logs = SystemLogs::create([
                'phone' => $update_phone,
                'title' => "Phone Number Change",
                'body' => "Phone number change has been initiated successfully by {$user->name} from {$phone} to {$update_phone}",
            ]);

            return redirect()->to('/settings/variables')->with('success', 'Update Successfull');
        } else {

            return redirect()->to('/settings/variables')->with('error', 'Whoops! Something went wrong, please try again');
        }
    }
}
