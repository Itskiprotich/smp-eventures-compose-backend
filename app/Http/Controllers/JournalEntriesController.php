<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ChartsofAcc;
use App\Models\Customers;
use App\Models\JournalEntries;
use App\Models\Loans;
use App\Models\RunningBalances;
use App\Models\Temp_ChartsofAcc;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalEntriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $categories['categories'] = Category::all();
        $charts['charts'] = ChartsofAcc::all();
        return view('reports.index')->with($charts)->with($categories);
    }
    public function balance_reports()
    {
        $customers['customers'] = Customers::where('status', 'Approved')->orderBy('created_at', 'desc')->get();
        return view('reports.balance')->with($customers);
    }
    public function income_reports()
    {
        $charts['charts'] = Temp_ChartsofAcc::all();
        return view('reports.income')->with($charts);
    }

    public function consolidated_reports()
    {
        $loans['loans'] = Loans::where('loan_status', '=', 'pending')->orderBy('created_at', 'desc')->get();

        return view('reports.consolidated')->with($loans);
    }
    public function ledgers_reports()
    {
        $customers['customers'] = Customers::where('status', 'Approved')->orderBy('created_at', 'desc')->get();
        return view('reports.ledgers')->with($customers);
    }

    public function trial_reports()
    {
        $customers['customers'] = Customers::where('status', 'Approved')->orderBy('created_at', 'desc')->get();
        return view('reports.trial')->with($customers);
    }
    public function monthly_reports()
    {


        $dates = [];
        for ($i = 0; $i <= 5; $i++) {
            $now = Carbon::now()->subMonths($i);
            $response = ([
                'bigname' => $now->startOfMonth()->format('F'),
                'smallname' => $now->startOfMonth()->format('m'),
                'year' => $now->startOfMonth()->format('Y'),
                'name' => $now->startOfMonth()->format('M'),
                'first' => $now->startOfMonth()->format('d'),
                'last' =>  $now->endOfMonth()->format('d')
            ]);

            $dates[] = json_encode($response);
        }
        $netai = $dates[0];
        $yummy = json_decode($netai);

        $akenge = $dates[1];
        $akengeyummy = json_decode($akenge);

        $aeng = $dates[2];
        $aengyummy = json_decode($aeng);

        $somok = $dates[3];
        $somokyummy = json_decode($somok);

        $agwan = $dates[4];
        $agwanyummy = json_decode($agwan);

        $mut = $dates[5];
        $mutyummy = json_decode($mut);

        $month_one = $yummy->first . "-" . $yummy->last . " " . $yummy->name;
        $month_two = $akengeyummy->first . "-" . $akengeyummy->last . " " . $akengeyummy->name;
        $month_three = $aengyummy->first . "-" . $aengyummy->last . " " . $aengyummy->name;
        $month_four = $somokyummy->first . "-" . $somokyummy->last . " " . $somokyummy->name;
        $month_five = $agwanyummy->first . "-" . $agwanyummy->last . " " . $agwanyummy->name;
        $month_six = $mutyummy->first . "-" . $mutyummy->last . " " . $mutyummy->name;

        // Opening Balances
        $month =  $yummy->bigname . " " . $yummy->year;
        $month1 =  $akengeyummy->bigname . " " . $akengeyummy->year;
        $month2 =  $aengyummy->bigname . " " . $aengyummy->year;
        $month3 =  $somokyummy->bigname . " " . $somokyummy->year;
        $month4 =  $agwanyummy->bigname . " " . $agwanyummy->year;
        $month5 =  $mutyummy->bigname . " " . $mutyummy->year;

        $opening_one = RunningBalances::where('month', $month)->sum('amount');
        $opening_two = RunningBalances::where('month', $month1)->sum('amount');
        $opening_three = RunningBalances::where('month', $month2)->sum('amount');
        $opening_four = RunningBalances::where('month', $month3)->sum('amount');
        $opening_five = RunningBalances::where('month', $month4)->sum('amount');
        $opening_six = RunningBalances::where('month', $month5)->sum('amount');

        // Receipts

        $receipts_one = "0";
        $receipts_two = "0";
        $receipts_three = "0";
        $receipts_four = "0";
        $receipts_five = "0";
        $receipts_six = "0";

        // Loan Principal Repayments

        $loan_principal_repayments_one = Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $yummy->smallname)->whereYear('clear_date', $yummy->year)->sum('principle');
        $loan_principal_repayments_two = Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $akengeyummy->smallname)->whereYear('clear_date', $akengeyummy->year)->sum('principle');
        $loan_principal_repayments_three = Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $aengyummy->smallname)->whereYear('clear_date', $aengyummy->year)->sum('principle');
        $loan_principal_repayments_four = Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $somokyummy->smallname)->whereYear('clear_date', $somokyummy->year)->sum('principle');
        $loan_principal_repayments_five = Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $agwanyummy->smallname)->whereYear('clear_date', $agwanyummy->year)->sum('principle');
        $loan_principal_repayments_six = Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $mutyummy->smallname)->whereYear('clear_date', $mutyummy->year)->sum('principle');

        // Loan Interest Repayments

        $loan_interest_repayments_one =  Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $yummy->smallname)->whereYear('clear_date', $yummy->year)->sum('interest');
        $loan_interest_repayments_two =  Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $akengeyummy->smallname)->whereYear('clear_date', $akengeyummy->year)->sum('interest');
        $loan_interest_repayments_three =  Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $aengyummy->smallname)->whereYear('clear_date', $aengyummy->year)->sum('interest');
        $loan_interest_repayments_four = Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $somokyummy->smallname)->whereYear('clear_date', $somokyummy->year)->sum('interest');
        $loan_interest_repayments_five =  Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $agwanyummy->smallname)->whereYear('clear_date', $agwanyummy->year)->sum('interest');
        $loan_interest_repayments_six = Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $mutyummy->smallname)->whereYear('clear_date', $mutyummy->year)->sum('interest');

        // Loan Penalty Repayments
        $loan_penalty_repayments_one = Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $yummy->smallname)->whereYear('clear_date', $yummy->year)->sum('penalty_amount');
        $loan_penalty_repayments_two =  Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $akengeyummy->smallname)->whereYear('clear_date', $akengeyummy->year)->sum('penalty_amount');
        $loan_penalty_repayments_three = Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $aengyummy->smallname)->whereYear('clear_date', $aengyummy->year)->sum('penalty_amount');
        $loan_penalty_repayments_four = Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $somokyummy->smallname)->whereYear('clear_date', $somokyummy->year)->sum('penalty_amount');
        $loan_penalty_repayments_five =  Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $agwanyummy->smallname)->whereYear('clear_date', $agwanyummy->year)->sum('penalty_amount');
        $loan_penalty_repayments_six = Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $mutyummy->smallname)->whereYear('clear_date', $mutyummy->year)->sum('penalty_amount');


        // Loan Fees Repayments (Non-Deductable)
        $loan_fees_non_deduct_one = Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $yummy->smallname)->whereYear('clear_date', $yummy->year)->sum('admin_fee');
        $loan_fees_non_deduct_two =  Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $akengeyummy->smallname)->whereYear('clear_date', $akengeyummy->year)->sum('admin_fee');
        $loan_fees_non_deduct_three =  Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $aengyummy->smallname)->whereYear('clear_date', $aengyummy->year)->sum('admin_fee');
        $loan_fees_non_deduct_four = Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $somokyummy->smallname)->whereYear('clear_date', $somokyummy->year)->sum('admin_fee');
        $loan_fees_non_deduct_five =  Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $agwanyummy->smallname)->whereYear('clear_date', $agwanyummy->year)->sum('admin_fee');
        $loan_fees_non_deduct_six =  Loans::where(['loan_status' => 'paid'])->whereMonth('clear_date', $mutyummy->smallname)->whereYear('clear_date', $mutyummy->year)->sum('admin_fee');


        // Loan Fees Repayments
        $loan_fees_deduct_one = "0";
        $loan_fees_deduct_two = "0";
        $loan_fees_deduct_three = "0";
        $loan_fees_deduct_four = "0";
        $loan_fees_deduct_five = "0";
        $loan_fees_deduct_six = "0";

        // Investor Account Deposits
        $investor_deposits_one = "0";
        $investor_deposits_two = "0";
        $investor_deposits_three = "0";
        $investor_deposits_four = "0";
        $investor_deposits_five = "0";
        $investor_deposits_six = "0";

        // Total Receipts (A)
        $total_receipts_a_one = $loan_principal_repayments_one + $loan_interest_repayments_one + $loan_penalty_repayments_one + $loan_fees_non_deduct_one;

        $total_receipts_a_two =  $loan_principal_repayments_two + $loan_interest_repayments_two + $loan_penalty_repayments_two + $loan_fees_non_deduct_two;

        $total_receipts_a_three =  $loan_principal_repayments_three +
            $loan_interest_repayments_three + $loan_penalty_repayments_three + $loan_fees_non_deduct_three;

        $total_receipts_a_four =  $loan_principal_repayments_four + $loan_interest_repayments_four + $loan_penalty_repayments_four + $loan_fees_non_deduct_four;

        $total_receipts_a_five =  $loan_principal_repayments_five + $loan_interest_repayments_five + $loan_penalty_repayments_five + $loan_fees_non_deduct_five;

        $total_receipts_a_six =  $loan_principal_repayments_six + $loan_interest_repayments_six + $loan_penalty_repayments_six + $loan_fees_non_deduct_six;

        // Expenses
        $expenses_one = JournalEntries::where(['debit_account' => 'J005'])->whereMonth('trans_date', $yummy->smallname)->whereYear('trans_date', $yummy->year)->sum('amount');
        $expenses_two = JournalEntries::where(['debit_account' => 'J005'])->whereMonth('trans_date', $akengeyummy->smallname)->whereYear('trans_date', $akengeyummy->year)->sum('amount');
        $expenses_three = JournalEntries::where(['debit_account' => 'J005'])->whereMonth('trans_date', $aengyummy->smallname)->whereYear('trans_date', $aengyummy->year)->sum('amount');
        $expenses_four = JournalEntries::where(['debit_account' => 'J005'])->whereMonth('trans_date', $somokyummy->smallname)->whereYear('trans_date', $somokyummy->year)->sum('amount');
        $expenses_five = JournalEntries::where(['debit_account' => 'J005'])->whereMonth('trans_date', $agwanyummy->smallname)->whereYear('trans_date', $agwanyummy->year)->sum('amount');
        $expenses_six = JournalEntries::where(['debit_account' => 'J005'])->whereMonth('trans_date', $mutyummy->smallname)->whereYear('trans_date', $mutyummy->year)->sum('amount');

        // Payroll
        $payroll_one = "0";
        $payroll_two = "0";
        $payroll_three = "0";
        $payroll_four = "0";
        $payroll_five = "0";
        $payroll_six = "0";

        // Investor Account Withdrawals
        $withdrawals_one = "0";
        $withdrawals_two = "0";
        $withdrawals_three = "0";
        $withdrawals_four = "0";
        $withdrawals_five = "0";
        $withdrawals_six = "0";


        // Loan Principal Disbursements

        $prin_one = Loans::where(['loan_status' => 'disbursed'])->orWhere('loan_status', '=', 'paid')->whereMonth('disbursment_date', $yummy->smallname)->whereYear('disbursment_date', $yummy->year)->sum('principle');

        $prin_two = Loans::where(['loan_status' => 'disbursed'])->orWhere('loan_status', '=', 'paid')->whereMonth('disbursment_date', $akengeyummy->smallname)->whereYear('disbursment_date', $akengeyummy->year)->sum('principle');

        $prin_three = Loans::where(['loan_status' => 'disbursed'])->orWhere('loan_status', '=', 'paid')->whereMonth('disbursment_date', $aengyummy->smallname)->whereYear('disbursment_date', $aengyummy->year)->sum('principle');

        $prin_four = Loans::where(['loan_status' => 'disbursed'])->orWhere('loan_status', '=', 'paid')->whereMonth('disbursment_date', $somokyummy->smallname)->whereYear('disbursment_date', $somokyummy->year)->sum('principle');

        $prin_five = Loans::where(['loan_status' => 'disbursed'])->orWhere('loan_status', '=', 'paid')->whereMonth('disbursment_date', $agwanyummy->smallname)->whereYear('disbursment_date', $agwanyummy->year)->sum('principle');

        $prin_six = Loans::where(['loan_status' => 'disbursed'])->orWhere('loan_status', '=', 'paid')->whereMonth('disbursment_date', $mutyummy->smallname)->whereYear('disbursment_date', $mutyummy->year)->sum('principle');


        // Total Payments (B)
        $total_payments_b_one = $prin_one + $expenses_one;
        $total_payments_b_two = $prin_two + $expenses_two;
        $total_payments_b_three = $prin_three + $expenses_three;
        $total_payments_b_four = $prin_four + $expenses_four;
        $total_payments_b_five = $prin_five + $expenses_five;
        $total_payments_b_six = $prin_six + $expenses_six;

        // Cash Balance (O) + (A) - (B)
        $cash_balance_one = ($opening_one + $total_receipts_a_one) - $total_payments_b_one;
        $cash_balance_two = ($opening_two + $total_receipts_a_two) - $total_payments_b_two;
        $cash_balance_three = ($opening_three + $total_receipts_a_three) - $total_payments_b_three;
        $cash_balance_four = ($opening_four + $total_receipts_a_four) - $total_payments_b_four;
        $cash_balance_five = ($opening_five + $total_receipts_a_five) - $total_payments_b_five;
        $cash_balance_six = ($opening_six + $total_receipts_a_six) - $total_payments_b_six;




        $data['data'] = ([

            'prin_one' => number_format($prin_one, 0, '.', ','),
            'prin_two' => number_format($prin_two, 0, '.', ','),
            'prin_three' => number_format($prin_three, 0, '.', ','),
            'prin_four' => number_format($prin_four, 0, '.', ','),
            'prin_five' => number_format($prin_five, 0, '.', ','),
            'prin_six' => number_format($prin_six, 0, '.', ','),

            'cash_balance_one' => number_format($cash_balance_one, 0, '.', ','),
            'cash_balance_two' => number_format($cash_balance_two, 0, '.', ','),
            'cash_balance_three' => number_format($cash_balance_three, 0, '.', ','),
            'cash_balance_four' => number_format($cash_balance_four, 0, '.', ','),
            'cash_balance_five' => number_format($cash_balance_five, 0, '.', ','),
            'cash_balance_six' => number_format($cash_balance_six, 0, '.', ','),

            'total_payments_b_one' => number_format($total_payments_b_one, 0, '.', ','),
            'total_payments_b_two' => number_format($total_payments_b_two, 0, '.', ','),
            'total_payments_b_three' => number_format($total_payments_b_three, 0, '.', ','),
            'total_payments_b_four' => number_format($total_payments_b_four, 0, '.', ','),
            'total_payments_b_five' => number_format($total_payments_b_five, 0, '.', ','),
            'total_payments_b_six' => number_format($total_payments_b_six, 0, '.', ','),
            'withdrawals_one' => number_format($withdrawals_one, 0, '.', ','),
            'withdrawals_two' => number_format($withdrawals_two, 0, '.', ','),
            'withdrawals_three' => number_format($withdrawals_three, 0, '.', ','),
            'withdrawals_four' => number_format($withdrawals_four, 0, '.', ','),
            'withdrawals_five' => number_format($withdrawals_five, 0, '.', ','),
            'withdrawals_six' => number_format($withdrawals_six, 0, '.', ','),
            'payroll_one' => number_format($payroll_one, 0, '.', ','),
            'payroll_two' => number_format($payroll_two, 0, '.', ','),
            'payroll_three' => number_format($payroll_three, 0, '.', ','),
            'payroll_four' => number_format($payroll_four, 0, '.', ','),
            'payroll_five' => number_format($payroll_five, 0, '.', ','),
            'payroll_six' => number_format($payroll_six, 0, '.', ','),
            'expenses_one' => number_format($expenses_one, 0, '.', ','),
            'expenses_two' => number_format($expenses_two, 0, '.', ','),
            'expenses_three' => number_format($expenses_three, 0, '.', ','),
            'expenses_four' => number_format($expenses_four, 0, '.', ','),
            'expenses_five' => number_format($expenses_five, 0, '.', ','),
            'expenses_six' => number_format($expenses_six, 0, '.', ','),
            'total_receipts_a_one' => number_format($total_receipts_a_one, 0, '.', ','),
            'total_receipts_a_two' => number_format($total_receipts_a_two, 0, '.', ','),
            'total_receipts_a_three' => number_format($total_receipts_a_three, 0, '.', ','),
            'total_receipts_a_four' => number_format($total_receipts_a_four, 0, '.', ','),
            'total_receipts_a_five' => number_format($total_receipts_a_five, 0, '.', ','),
            'total_receipts_a_six' => number_format($total_receipts_a_six, 0, '.', ','),
            'investor_deposits_one' => number_format($investor_deposits_one, 0, '.', ','),
            'investor_deposits_two' => number_format($investor_deposits_two, 0, '.', ','),
            'investor_deposits_three' => number_format($investor_deposits_three, 0, '.', ','),
            'investor_deposits_four' => number_format($investor_deposits_four, 0, '.', ','),
            'investor_deposits_five' => number_format($investor_deposits_five, 0, '.', ','),
            'investor_deposits_six' => number_format($investor_deposits_six, 0, '.', ','),
            'loan_fees_non_deduct_one' => number_format($loan_fees_non_deduct_one, 0, '.', ','),
            'loan_fees_non_deduct_two' => number_format($loan_fees_non_deduct_two, 0, '.', ','),
            'loan_fees_non_deduct_three' => number_format($loan_fees_non_deduct_three, 0, '.', ','),
            'loan_fees_non_deduct_four' => number_format($loan_fees_non_deduct_four, 0, '.', ','),
            'loan_fees_non_deduct_five' => number_format($loan_fees_non_deduct_five, 0, '.', ','),
            'loan_fees_non_deduct_six' => number_format($loan_fees_non_deduct_six, 0, '.', ','),
            'loan_fees_deduct_one' => number_format($loan_fees_deduct_one, 0, '.', ','),
            'loan_fees_deduct_two' => number_format($loan_fees_deduct_two, 0, '.', ','),
            'loan_fees_deduct_three' => number_format($loan_fees_deduct_three, 0, '.', ','),
            'loan_fees_deduct_four' => number_format($loan_fees_deduct_four, 0, '.', ','),
            'loan_fees_deduct_five' => number_format($loan_fees_deduct_five, 0, '.', ','),
            'loan_fees_deduct_six' => number_format($loan_fees_deduct_six, 0, '.', ','),
            'loan_penalty_repayments_one' => number_format($loan_penalty_repayments_one, 0, '.', ','),
            'loan_penalty_repayments_two' => number_format($loan_penalty_repayments_two, 0, '.', ','),
            'loan_penalty_repayments_three' => number_format($loan_penalty_repayments_three, 0, '.', ','),
            'loan_penalty_repayments_four' => number_format($loan_penalty_repayments_four, 0, '.', ','),
            'loan_penalty_repayments_five' => number_format($loan_penalty_repayments_five, 0, '.', ','),
            'loan_penalty_repayments_six' => number_format($loan_penalty_repayments_six, 0, '.', ','),
            'loan_interest_repayments_one' => number_format($loan_interest_repayments_one, 0, '.', ','),
            'loan_interest_repayments_two' => number_format($loan_interest_repayments_two, 0, '.', ','),
            'loan_interest_repayments_three' => number_format($loan_interest_repayments_three, 0, '.', ','),
            'loan_interest_repayments_four' => number_format($loan_interest_repayments_four, 0, '.', ','),
            'loan_interest_repayments_five' => number_format($loan_interest_repayments_five, 0, '.', ','),
            'loan_interest_repayments_six' => number_format($loan_interest_repayments_six, 0, '.', ','),
            'loan_principal_repayments_one' => number_format($loan_principal_repayments_one, 0, '.', ','),
            'loan_principal_repayments_two' => number_format($loan_principal_repayments_two, 0, '.', ','),
            'loan_principal_repayments_three' => number_format($loan_principal_repayments_three, 0, '.', ','),
            'loan_principal_repayments_four' => number_format($loan_principal_repayments_four, 0, '.', ','),
            'loan_principal_repayments_five' => number_format($loan_principal_repayments_five, 0, '.', ','),
            'loan_principal_repayments_six' => number_format($loan_principal_repayments_six, 0, '.', ','),
            'month_one' => $month_one,
            'month_two' => $month_two,
            'month_three' => $month_three,
            'month_four' => $month_four,
            'month_five' => $month_five,
            'month_six' => $month_six,
            'opening_one' => number_format($opening_one, 0, '.', ','),
            'opening_two' => number_format($opening_two, 0, '.', ','),
            'opening_three' => number_format($opening_three, 0, '.', ','),
            'opening_four' => number_format($opening_four, 0, '.', ','),
            'opening_five' => number_format($opening_five, 0, '.', ','),
            'opening_six' => number_format($opening_six, 0, '.', ','),
            'receipts_one' =>  number_format($receipts_one, 0, '.', ','),
            'receipts_two' =>  number_format($receipts_two, 0, '.', ','),
            'receipts_three' =>  number_format($receipts_three, 0, '.', ','),
            'receipts_four' =>  number_format($receipts_four, 0, '.', ','),
            'receipts_five' =>  number_format($receipts_five, 0, '.', ','),
            'receipts_six' =>  number_format($receipts_six, 0, '.', ','),
        ]);
        return view('reports.monthly')->with($data);
    }
    public function accumulated_reports()
    {

        $loan_principal_repayment = Loans::where('loan_status', '=', 'paid')->sum('principle');
        $loan_interest_repayment = Loans::where('loan_status', '=', 'paid')->sum('interest');
        $loan_fees_processing = Loans::where('loan_status', '=', 'paid')->sum('admin_fee');
        $loan_penalty_repayment = Loans::where('loan_status', '=', 'paid')->sum('penalty_amount');

        $total_receipts_a = $loan_principal_repayment + $loan_interest_repayment + $loan_fees_processing + $loan_penalty_repayment;

        $loan_fees_deductable = 0;
        $loan_investor_deposits = 0;

        // Expenses
        $expenses = JournalEntries::where(['debit_account' => 'J005'])->sum('amount');;
        $payroll = 0;
        $investor_withdrawals = 0;

        $total_payment_b = $expenses + $payroll + $investor_withdrawals;
        $total_payment_a_b = $total_receipts_a - $total_payment_b;

        $data['data'] = ([
            'loan_principal_repayment' =>  number_format($loan_principal_repayment, 0, '.', ','),
            'total_receipts_a' =>  number_format($total_receipts_a, 0, '.', ','),
            'loan_investor_deposits' =>  number_format($loan_investor_deposits, 0, '.', ','),
            'loan_fees_deductable' =>  number_format($loan_fees_deductable, 0, '.', ','),
            'loan_fees_processing' =>  number_format($loan_fees_processing, 0, '.', ','),
            'loan_penalty_repayment' =>  number_format($loan_penalty_repayment, 0, '.', ','),
            'loan_interest_repayment' =>  number_format($loan_interest_repayment, 0, '.', ','),
            'expenses' =>  number_format($expenses, 0, '.', ','),
            'payroll' =>  number_format($payroll, 0, '.', ','),
            'investor_withdrawals' =>  number_format($investor_withdrawals, 0, '.', ','),
            'total_payment_b' =>  number_format($total_payment_b, 0, '.', ','),
            'total_payment_a_b' =>  number_format($total_payment_a_b, 0, '.', ','),

        ]);
        return view('reports.accumulated')->with($data);
    }
    public function filter_accumulated_reports(Request $request)
    {

        $attr = $request->validate([
            'startdate' => 'required|string|max:255',
            'enddate' => 'required|string|max:255',
        ]);

        $start_date = Carbon::parse($request->startdate)->toDateTimeString();

        $end_date = Carbon::parse($request->enddate)->toDateTimeString();


        $loan_principal_repayment = Loans::where('loan_status', '=', 'paid')->whereBetween('created_at', [$start_date, $end_date])->sum('principle');
        $loan_interest_repayment = Loans::where('loan_status', '=', 'paid')->whereBetween('created_at', [$start_date, $end_date])->sum('interest');
        $loan_fees_processing = Loans::where('loan_status', '=', 'paid')->whereBetween('created_at', [$start_date, $end_date])->sum('admin_fee');
        $loan_penalty_repayment = Loans::where('loan_status', '=', 'paid')->whereBetween('created_at', [$start_date, $end_date])->sum('penalty_amount');

        $total_receipts_a = $loan_principal_repayment + $loan_interest_repayment + $loan_fees_processing + $loan_penalty_repayment;

        $loan_fees_deductable = 0;
        $loan_investor_deposits = 0;

        // Expenses
        $expenses = JournalEntries::where(['debit_account' => 'J005'])->whereBetween('created_at', [$start_date, $end_date])->sum('amount');;
        $payroll = 0;
        $investor_withdrawals = 0;

        $total_payment_b = $expenses + $payroll + $investor_withdrawals;
        $total_payment_a_b = $total_receipts_a - $total_payment_b;

        $data['data'] = ([
            'loan_principal_repayment' =>  number_format($loan_principal_repayment, 0, '.', ','),
            'total_receipts_a' =>  number_format($total_receipts_a, 0, '.', ','),
            'loan_investor_deposits' =>  number_format($loan_investor_deposits, 0, '.', ','),
            'loan_fees_deductable' =>  number_format($loan_fees_deductable, 0, '.', ','),
            'loan_fees_processing' =>  number_format($loan_fees_processing, 0, '.', ','),
            'loan_penalty_repayment' =>  number_format($loan_penalty_repayment, 0, '.', ','),
            'loan_interest_repayment' =>  number_format($loan_interest_repayment, 0, '.', ','),
            'expenses' =>  number_format($expenses, 0, '.', ','),
            'payroll' =>  number_format($payroll, 0, '.', ','),
            'investor_withdrawals' =>  number_format($investor_withdrawals, 0, '.', ','),
            'total_payment_b' =>  number_format($total_payment_b, 0, '.', ','),
            'total_payment_a_b' =>  number_format($total_payment_a_b, 0, '.', ','),

        ]);
        return view('reports.accumulated')->with($data);
    }
    public function view_category()
    {
        $categories['categories'] = Category::all();
        return view('reports.categories')->with($categories);
    }

    public function add_category(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $name = $request->name;
        $category = Category::create([
            'name' => $name,
        ]);

        $categories['categories'] = Category::all();
        return redirect()->back()->with($categories);
    }
    public function filter_balance(Request $request)
    {
        $attr = $request->validate([
            'startdate' => 'required|string|max:255',
            'enddate' => 'required|string|max:255',
        ]);

        $expense = Category::where('name', "Expense")->first()->id;
        $income = Category::where('name', "Income")->first()->id;


        return redirect()->back()->with('message', 'IT WORKS!');
    }

    public function filter_consolidated(Request $request)
    {
        $attr = $request->validate([
            'start_date' => 'required|string|max:255',
            'end_date' => 'required|string|max:255',
            'loan_status' => 'required|string|max:255',
        ]);

        $start_date = Carbon::parse($request->start_date)
            ->toDateTimeString();

        $end_date = Carbon::parse($request->end_date)
            ->toDateTimeString();

        $loan_status = $request->loan_status;
        $loans['loans'] = [];

        if ($loan_status == "Pending") {
            $loans['loans'] = Loans::where('loan_status', '=', 'pending')->whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'desc')->get();
        } else if ($loan_status == "Disbursed") {
            $loans['loans'] = Loans::where('loan_status', '=', 'disbursed')->orWhere('loan_status', '=', 'paid')->whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'desc')->get();
        } else if ($loan_status == "Paid") {
            $loans['loans'] = Loans::where('loan_status', '=', 'paid')->whereBetween('clear_date', [$start_date, $end_date])->orderBy('created_at', 'desc')->get();
        } else if ($loan_status == "Unpaid") {
            $loans['loans'] = Loans::where('loan_status', '=', 'disbursed')->whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'desc')->get();
        } else if ($loan_status == "Overdue") {
            $loans['loans'] = Loans::where('loan_status', '=', 'disbursed')->whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'desc')->get();
        } else if ($loan_status == "Goodloans") {
            $loans['loans'] = Loans::where('loan_status', '=', 'paid')->whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'desc')->get();
        }

        return view('reports.consolidated')->with($loans);
    }
    public function filter_income(Request $request)
    {
        $attr = $request->validate([
            'startdate' => 'required|string|max:255',
            'enddate' => 'required|string|max:255',
        ]);

        $start_date = Carbon::parse($request->startdate)->toDateTimeString();

        $end_date = Carbon::parse($request->enddate)->toDateTimeString();
        DB::table('temp__chartsof_accs')->delete();

        $s1  = JournalEntries::where(['credit_account' => 'F100'])->orWhere(['credit_account' => 'F200'])->whereBetween('created_at', [$start_date, $end_date])->get();

        foreach ($s1 as $s) {
            $chart_name = ChartsofAcc::where('account_no', $s->credit_account)->first()->chart_name;

            $temp = Temp_ChartsofAcc::create([
                'account_type' => $s->credit_account,
                'account_code' => $s->credit_account,
                'account_name' => $chart_name,
                'amount_cr' => $s->amount,
                'amount_dr' => $s->amount,
            ]);
        }

        $charts['charts'] = Temp_ChartsofAcc::all();
        return redirect()->to('/reports/income')->with($charts);
    }
    public function add_account(Request $request)
    {
        $attr = $request->validate([
            'number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);
        $chart_name = $attr['name'];
        $account_no = $attr['number'];
        $product_id = 1;
        $category_id = $attr['category'];
        $sub_category_id = 1;
        $account = ChartsofAcc::create([
            'chart_name' => $chart_name,
            'account_no' => $account_no,
            'product_id' => $product_id,
            'category_id' => $category_id,
            'sub_category_id' => $sub_category_id,
        ]);

        $categories['categories'] = Category::all();
        $charts['charts'] = ChartsofAcc::all();

        return redirect()->back()->with($categories)->with($charts);
    }
    public function all_installment()
    {
        $dataSet = [];
        // $now = Carbon::rawParse('now')->format('Y-m-d');
        // $date = Carbon::createFromFormat('Y-m-d', $now);

        // $all['all'] = Schedule::where(['status'=>'unpaid','due_date'=>])->orderBy('created_at', 'desc')->get();
        // foreach($all as $one){

        //     $dataSet[] = [
        //         'name' => $name,
        //         'age' => $age,
        //         'bmi' => $bmi,
        //         'status' => $status
        //     ];
        // }

        return view('installment.index')->with($dataSet);
    }
    public function today_installment()
    {
        return view('installment.today');
    }

    public function arrears_installment()
    {
        return view('installment.arrears');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
