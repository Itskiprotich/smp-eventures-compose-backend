<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{


    public function membership_fee_msg($phone, $amount)
    {
        $customer = Customers::where(['phone' => $phone])->first();
        $premsg = "";
        $message = Message::where(['type' => '1'])->first();
        if ($message) {
            $org = $message->message;
            $premsg = str_replace("%AM", $amount, $org);
        } else {
            $premsg = "Your Membership Fee payment of KES {$amount} has been received. Thank you for using Broshere Mobile App";
        }

        return $premsg;
    }

    public function savings_deposits_msg($phone, $amount, $total)
    {
        $customer = Customers::where(['phone' => $phone])->first();
        $premsg = "";
        $message = Message::where(['type' => '2'])->first();
        if ($message) {
            $org = $message->message;
            $premsg = str_replace("%AM", $amount, $org);
            $premsg = str_replace("%TA", $total, $premsg);
        } else {
            $premsg = "Your Savings payment of KES {$amount} has been received. Your tatal savings are now {$total}. Thank you for using Broshere Mobile App";
        }

        return $premsg;
    }
    public function welfare_deposits_msg($phone, $amount, $total)
    {
        $customer = Customers::where(['phone' => $phone])->first();
        $premsg = "";
        $message = Message::where(['type' => '3'])->first();
        if ($message) {
            $org = $message->message;
            $premsg = str_replace("%AM", $amount, $org);
            $premsg = str_replace("%TA", $total, $premsg);
        } else {
            $premsg = "Your Welfare payment of KES {$amount} has been received. Your tatal welfare are now {$total}. Thank you for using Broshere Mobile App";
        }

        return $premsg;
    }
    public function shares_deposits_msg($phone, $amount, $total)
    {
        $customer = Customers::where(['phone' => $phone])->first();
        $premsg = "";
        $message = Message::where(['type' => '4'])->first();
        if ($message) {
            $org = $message->message;
            $premsg = str_replace("%AM", $amount, $org);
            $premsg = str_replace("%TA", $total, $premsg);
        } else {
            $premsg = "Your Shares payment of KES {$amount} has been received. Your tatal shares are now {$total}. Thank you for using Broshere Mobile App";
        }

        return $premsg;
    }
    public function cleared_loan_repayment_msg($phone, $amount)
    {
        $customer = Customers::where(['phone' => $phone])->first();
        $premsg = "";
        $message = Message::where(['type' => '5'])->first();
        if ($message) {
            $org = $message->message;
            $premsg = str_replace("%AM", $amount, $org);
        } else {
            $premsg = "Your loan payment of KES {$amount} has been received, your loan has been cleared. Thank you for using Broshere Mobile App";
        }

        return $premsg;
    }

    public function overpayment_msg($phone, $amount, $overpayment)
    {

        $customer = Customers::where(['phone' => $phone])->first();
        $premsg = "";
        $message = Message::where(['type' => '6'])->first();
        if ($message) {
            $org = $message->message;
            $premsg = str_replace("%AM", $amount, $org);
            $premsg = str_replace("%OVP", $overpayment, $premsg);
        } else {
            $premsg = "Your loan payment of KES {$amount} has exceeded by KES {$overpayment} and will be recovered in your next loan application. Thank you for using Broshere Mobile App";
        }

        return $premsg;
    }

    public function partial_loan_repayment_msg($phone, $amount, $balance)
    {

        $customer = Customers::where(['phone' => $phone])->first();
        $premsg = "";
        $message = Message::where(['type' => '7'])->first();
        if ($message) {
            $org = $message->message;
            $premsg = str_replace("%AM", $amount, $org);
            $premsg = str_replace("%LB", $balance, $premsg);
        } else {
            $premsg = "Your loan payment of KES {$amount} has been received, your loan balance is KES  {$balance}. Thank you for using Broshere Mobile App";
        }

        return $premsg;
    }

    public function loan_remainder_message($loan)
    {

        $premsg = "";
        $message = Message::where(['type' => '8'])->first();
        if ($message) {
            $org = $message->message;
            $premsg = str_replace("%AM", $loan->loan_balance, $org);
            $premsg = str_replace("%LB", $loan->loan_balance, $premsg);
            $premsg = str_replace("%FN", $loan->loan_balance, $premsg);
            $premsg = str_replace("%RD", $loan->repayment_date, $premsg);
        } else {
            // $date=date("d-m-Y",strtotime($loan->repayment_date));
            $premsg = "Your SMP Eventures Loan of KES {$loan->loan_balance} is due on {$loan->repayment_date}. Please pay on time to increase your credit score";
        }

        return $premsg;
    }

    public function log_savings_reminder($loan, $amount, $sum_total)
    {

        $premsg = "";
        $message = Message::where(['type' => '9'])->first();
        if ($message) {
            $org = $message->message;
            $premsg = str_replace("%AM", $amount, $org);
            $premsg = str_replace("%SP", $loan->product_name, $premsg);
            $premsg = str_replace("%TA", $loan->sum_total, $premsg);
        } else {
            $premsg = "Please deposit your SMP Eventures Savings Balance for the product {$loan->product_name} of KES {$amount}. Your current Savings is KES {$sum_total}.";
        }

        return $premsg;
    }
}
