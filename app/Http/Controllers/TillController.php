<?php

namespace App\Http\Controllers;

use App\Http\Traits\CommonTrait;
use App\Models\AccountBalance;
use App\Models\Mode;
use App\Models\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TillController extends Controller
{
    use CommonTrait;

    public function initiate_stk_push(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'amount' => 'required|string|max:255'
        ]);
        $phone = $attr['phone'];
        $amount = $attr['amount'];
        $account = $phone;


        $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $this->generate_access_token()));
        $curl_post_data = [
            'BusinessShortCode' => $this->short_code(),
            'Password' => $this->generate_access_password(),
            'Timestamp' => Carbon::rawParse('now')->format('YmdHms'),
            'TransactionType' => 'CustomerBuyGoodsOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => $this->short_code_till(),
            'PhoneNumber' => $phone,
            //  'CallBackURL' => 'https://urgentcoursewriters.com/hook/hook3.php',
            'CallBackURL' => 'https://kinjatech.com/wp-email.php',
            'AccountReference' => $account,
            'TransactionDesc' => "Testing stk push"
        ];
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);


        return $this->successResponse("success", $curl_response);
    }
    public function initiate_stk_payment($phone, $amount)
    {
        $account = $phone;


        $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $this->generate_access_token()));
        $curl_post_data = [
            'BusinessShortCode' => $this->short_code(),
            'Password' => $this->generate_access_password(),
            'Timestamp' => Carbon::rawParse('now')->format('YmdHms'),
            'TransactionType' => 'CustomerBuyGoodsOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => $this->short_code_till(),
            'PhoneNumber' => $phone,
            'CallBackURL' => 'https://urgentcoursewriters.com/hook/hook3.php',
            //'CallBackURL' => 'https://kinjatech.com/wp-email.php',
            'AccountReference' => $account,
            'TransactionDesc' => "Testing stk push"
        ];
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);


        return $this->successResponse("success", $curl_response);
    }
    public function safaricom_callback(Request $request)
    {

        $attr = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|integer|between:1,100000',
            'result_desc' => 'required|string',
            'trans_code' => 'required|string',
            'result_code' => 'required|string',
        ]);

        $mc = $attr['amount'];
        $status = $attr['result_code'];
        $txncd = $attr['trans_code'];
        $msisdn_id = $attr['phone'];
        $msisdn_idnum = $attr['result_desc'];
        $channel = "MPESA TILL";

        $exists = Response::where(['txncd' => $txncd])->exists();
        if ($exists) {
            $response = ([
                'proceed' => 0,
                'message' => 'Transaction Code Already Exists'
            ]);

            return $this->successResponse("success", $response);
        }


        $res = Response::create([
            'status' => $status,
            'txncd' => $txncd,
            'msisdn_id' => $msisdn_id,
            'msisdn_idnum' => $msisdn_idnum,
            'mc' => $mc,
            'channel' => $channel,
        ]);

        if ($status == '0') {

            $phone = $msisdn_id;
            $amount = $mc;
            $trans_code = $txncd;
            $response = [];

            // update paybill balance::
            $system_balance = 0;
            $balances_account = AccountBalance::where(['status' => true])->first();
            if ($balances_account) {
                $system_balance = $balances_account->paybill + $amount;
                $balances_account->paybill = $system_balance;
                $balances_account->save();
            } else {
                $fcm = AccountBalance::updateOrCreate(
                    ['status' => true],
                    ['paybill' => $amount]
                );
            }

            $where = Mode::where('phone', $phone)->first();
            if ($where) {
                $mode = $where->mode;
                if ($mode == '1') {
                    # code... 
                    $response = ([
                        'proceed' => 0,
                        'message' => 'Loan Payment Successfull'
                    ]);
                    $result = (new LoansController)->pay_loan_callback($phone, $amount, $trans_code);
                }
                if ($mode == '2') {
                    # code...
                    $response = ([
                        'proceed' => 0,
                        'message' => 'Savings Deposit Successfull'
                    ]);
                    $result = (new SavingsController)->savings_callback($phone, $amount, $trans_code, $where->description);
                }
                if ($mode == '3') {
                    # code...
                    $response = ([
                        'proceed' => 0,
                        'message' => 'Welfare Deposit Successfull'
                    ]);
                    $result = (new SavingsController)->welfare_callback($phone, $amount, $trans_code);
                }
                if ($mode == '4') {
                    # code...
                    $response = ([
                        'proceed' => 0,
                        'message' => 'Shares Deposit Successfull'
                    ]);
                    $result = (new SavingsController)->shares_callbacl($phone, $amount, $trans_code);
                }

                if ($mode == '5') {
                    # code...
                    $response = ([
                        'proceed' => 0,
                        'message' => 'Shares Deposit Successfull'
                    ]);
                    $result = (new B4MController)->callback_contribute($where->reference, $phone, $trans_code, $amount);
                }



                return $this->successResponse("success", $response);
            } else {
                return "No Payment Record Available";
            }
        } else {
            return "Request failed";
        }
    }

    public function check_till_balance(Request $request)
    {
        $url = 'https://api.safaricom.co.ke/mpesa/accountbalance/v1/query';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $this->generate_access_token()));
        $curl_post_data = [
            'Initiator' => "SMP WEB",
            'SecurityCredential' => $this->generate_access_password(),
            'Timestamp' => Carbon::rawParse('now')->format('YmdHms'),
            'CommandID' => 'AccountBalance',
            'PartyA' => 7417384,
            'Remarks' => "Account Balance Enquiry",
            'IdentifierType' => "4",
            'QueueTimeOutURL' => "https://kinjatech.com/wp-email1.php",
            'ResultURL' => "https://kinjatech.com/wp-email1.php"
        ];

        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);


        return $this->successResponse("success", $curl_response);
    }
}
