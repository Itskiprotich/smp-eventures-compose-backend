<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Loans;
use App\Models\PenaltyTracker;
use App\Models\Reminder;
use App\Models\Savings;
use App\Models\SavingsProducts;
use App\Models\Schedule;
use App\Models\SystemLogs;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
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



    public function loans_due_next_week()
    {

        $date = Carbon::today();
        $date = $date->addDays(7);
        $date = $date->toDateString();

        $loans = Loans::whereDate('repayment_date', '=', $date)->where(['loan_status' => 'disbursed', 'repayment_status' => false, 'paused' => false])->orderBy('created_at', 'desc')->get();

        foreach ($loans as $one) {
            $message = "Phone number {$one->phone } has a loan of {$one->loan_balance} that is due next week on {$date}.";

            $fcm = Reminder::updateOrCreate(
                ['phone' =>  $one->phone, 'loan_ref' => $one->loan_ref],
                ['message' => $message, 'status' => false, 'type' => '20']
            );
        }
    }
    public function send_loans_due_next_week(Request $request)
    {
        $logs = Reminder::where(['status' => false, 'type' => '20'])->get();
        if ($logs) {
            foreach ($logs as $log) {
                $customer = Customers::where(['phone' => "254724743788"])->first();
                $message = $log->message;

                $result = (new EmailController)->send_reminder_message($customer, $message,"Loans Due Next Week");
                $log->status = true;
                $log->delete();
            }
        }

        return "Success";
    }

    public function loans_due_today()
    {
    }
    public function loans_overdue_till_one_month()
    {
    }
    public function test_mail(Request $request)
    {

        $customer = Customers::where(['phone' => '254724743788'])->first();
        $message = "This is a test email";

        $result = (new EmailController)->test_email($customer, $customer->email, $message);

        return $this->successResponse("success", $customer);
    }

    public function manual_penalty(Request $request)
    {
        $attr = $request->validate([
            'loan_ref' => 'required|string|max:255'
        ]);
        $loan_ref = $attr['loan_ref'];
        $date = Carbon::today();
        $loan = Loans::whereDate('penalty_date', '<=', $date)->where(['loan_ref' => $loan_ref, 'loan_status' => 'disbursed', 'repayment_status' => false])->first();
        if ($loan) {

            $loan_balance = $loan->loan_balance;
            $rate_applied = $loan->rate_applied;
            $exist = $loan->penalty_amount;

            $current = $loan_balance * $rate_applied;

            $penalty_date =  (new Carbon($loan->penalty_date))->addDays($loan->repayment_period);

            $total_penalty = $exist + $current;
            $total_balance = $loan_balance + $current;

            $loan->penalty_date = $penalty_date;
            $loan->penalty_amount = $total_penalty;
            $loan->loan_balance = $total_balance;
            $loan->save();
        }
        return "Success";
    }


    public function half_rate(Request $request)
    {
        // get overdue loans

        $date = Carbon::today();
        $loans = Loans::whereDate('penalty_date', '<=', $date)->where(['loan_status' => 'disbursed', 'repayment_status' => false, 'paused' => false])->orderBy('created_at', 'desc')->get();

        if ($loans) {

            foreach ($loans as $single) {
                $loan_balance = $single->loan_balance;
                $rate_applied = $single->rate_applied;


                $half_rate = $rate_applied * 0.5;
                $penalty_date =  (new Carbon($single->penalty_date))->addDays(28);

                $exist = $single->penalty_amount;
                $current = $loan_balance * $half_rate;

                $total_penalty = $exist + $current;
                $total_balance = $loan_balance + $current;

                $single->penalty_date = $penalty_date;
                $single->penalty_amount = $total_penalty;
                $single->loan_balance = $total_balance;
                $single->save();

                PenaltyTracker::create([
                    'loan_ref' => $single->loan_ref,
                    'amount' => $current,
                    'current' => $exist,
                    'balance' => $total_balance
                ]);

                $message = "Your loan has been rolled over by KES {$current}. Please pay the balance of KES {$total_balance} to avoid further penalties";
                $logs = SystemLogs::create([
                    'phone' => $single->phone,
                    'title' => "Loan Penalty",
                    'body' => $message,
                ]);
                $customer = Customers::where(['phone' => $single->phone])->first();

                // $result = (new EmailController)->send_rollover($customer, $message);
            }
        }
        return "Success";
    }



    public function apply_penalty(Request $request)
    {
        $date = Carbon::today();
        $overdue = Loans::whereDate('penalty_date', '<=', $date)->where(['loan_status' => 'disbursed', 'repayment_status' => false])->orderBy('created_at', 'desc')->get();
        $penalty_amount = 0;
        if ($overdue) {
            foreach ($overdue as $single) {

                $penalty_date =  (new Carbon($single->penalty_date))->addDays($single->repayment_period);
                $end = $single->principle;
                $max = $end * 2;
                $loan_balance = $single->loan_balance;
                $rate_applied = $single->rate_applied;
                $exist = $single->penalty_amount;
                $current = $loan_balance * $rate_applied;

                $next = $loan_balance + $current;
                if ($next > $max) {
                    if ($loan_balance == $max) {
                        # code...
                        $penalty_amount = 0;
                    } else {
                        $penalty_amount = $max - $end;
                    } //
                } else {

                    $penalty_amount = $next;
                }
                $total_penalty = $exist + $penalty_amount;
                $total_balance = $loan_balance + $penalty_amount;

                $single->penalty_date = $penalty_date;
                $single->penalty_amount = $total_penalty;
                $single->loan_balance = $total_balance;
                $single->save();

                $customer = Customers::where(['phone' => $single->phone])->first();
                $message = "Your loan has been rolled over by KES {$current}. Please pay the balance of KES {$total_balance} to avoid further penalties";

                $result = (new EmailController)->send_rollover($customer, $message);
            }
            return "Success";
        } else {
            return "Success";
        }
    }
    //
    public function log_reminder(Request $request)
    {
        // $unpaid = Loans::where(['repayment_status' => false, 'loan_status' => 'disbursed'])->get();

        $today = today();
        $d = Carbon::createFromDate($today->year, $today->month)->format('d');
        $m = Carbon::createFromDate($today->year, $today->month)->format('m');
        $y = Carbon::createFromDate($today->year, $today->month)->format('Y');
        // Schedule for today
        $todays_schedules = Schedule::whereDay('due_date', $d)
            ->whereMonth('due_date', '=', $m)
            ->whereYear('due_date', '=', $y)
            ->get();

        $data = [];
        foreach ($todays_schedules as $schedule) {

            $one =  Loans::where(['repayment_status' => false, 'loan_ref' => $schedule->loan_ref, 'loan_status' => 'disbursed'])->first();
            if ($one) {
                $message = (new MessageController)->loan_remainder_message($one);

                $fcm = Reminder::updateOrCreate(
                    ['phone' =>  $one->phone, 'loan_ref' => $one->loan_ref],
                    ['message' => $message, 'status' => false, 'type' => '1']
                );
            }
            $data[] = $one;
        }

        return "Success";
    }

    public function daily_payment()
    {
        # code...
        $data = [];

        $customers = Customers::where('status', 'Approved')->orderBy('created_at', 'desc')->get();
        foreach ($customers as $one) {
            $product = SavingsProducts::where(['product_code' => 'lYFMmU5WupvK'])->first();
            $sum_total = Savings::where(['phone' => $one->phone, 'product' => 'lYFMmU5WupvK'])->sum('amount');
            // get day of the year 
            $date = Carbon::now();
            $year = $date->format('Y');

            $mwanzo = '01/01/' . $year;
            $mwisho = $date->addDays(1)->format('m/d/Y');


            $start_date = Carbon::parse($mwanzo); //->toDateTimeString();
            $end_date = Carbon::parse($mwisho); //->toDateTimeString(); 

            $diff_in_days = $start_date->diffInDays($end_date);
            $message = "Kindly make a deposit of KES {$diff_in_days} for the product {$product->product_name}. Your current total amount is KES {$sum_total}. Thank you for using SMP App";

            $result = (new EmailController)->send_savings_reminder($one, $message);

            $data[] = [
                'phone' => $one->phone,
                'total' => $sum_total,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'diff_in_days' => $diff_in_days,
                'message' => $message,
            ];
        }

        return "Success";
    }
    public function log_savings_reminder(Request $request)
    {
        $products = SavingsProducts::join('savings', 'savings_products.product_code', '=', 'savings.product')
            ->get(['savings_products.*', 'savings.*']);
        foreach ($products as $one) {
            $paybill = env('PAYBILL', 'xxxxxx');
            $sum_total = Savings::where(['phone' => $one->phone, 'product' => $one->product_code])->sum('amount');
            $amount = $one->max_limit - $sum_total;
            if ($amount > 0) {

                $message = (new MessageController)->log_savings_reminder($one, $amount, $sum_total);
                $fcm = Reminder::updateOrCreate(
                    ['phone' =>  $one->phone, 'loan_ref' => $one->product_code],
                    ['message' => $message, 'status' => false, 'type' => '2']
                );
            }
        }

        return "Success";
    }

    public function send_reminder(Request $request)
    {
        $logs = Reminder::where(['status' => false, 'type' => '1'])->get();
        if ($logs) {
            foreach ($logs as $log) {
                $customer = Customers::where(['phone' => $log->phone])->first();
                $message = $log->message;

                $result = (new EmailController)->send_reminder($customer, $message);
                $log->status = true;
                $log->delete();
            }
        }

        return "Success";
    }
    public function send_savings_reminder(Request $request)
    {
        $logs = Reminder::where(['status' => false, 'type' => '2'])->get();
        if ($logs) {
            foreach ($logs as $log) {
                $customer = Customers::where(['phone' => $log->phone])->first();
                $message = $log->message;

                $result = (new EmailController)->send_savings_reminder($customer, $message);
                $log->status = true;
                $log->delete();
            }
        }

        return "Success";
    }

    public function generate_overdue_reminder(Request $request)
    {

        $date = Carbon::today();
        $loans = Loans::whereDate('repayment_date', '<=', $date)
            ->whereDate('reminder_date', '<=', $date)
            ->where(['loan_status' => 'disbursed', 'repayment_status' => false])->orderBy('created_at', 'desc')->get();
        foreach ($loans as $one) {
            $message = "You have an overdue loan of KES {$one->loan_balance} which was due on {$one->repayment_date}. Kindly clear your loan today to keep enjoying service from SMP Eventures";
            // add 7 days to today then update reminder_date with resultant date
            $currentDate = Carbon::now();
            $reminderDate = $currentDate->copy()->addDays(7);
            $one->reminder_date = $reminderDate;
            $one->save();
            $fcm = Reminder::updateOrCreate(
                ['phone' =>  $one->phone, 'loan_ref' => $one->loan_ref],
                ['message' => $message, 'status' => false, 'type' => '1']
            );
        }
    }
}
