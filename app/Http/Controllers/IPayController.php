<?php

namespace App\Http\Controllers;

use App\Http\Traits\CommonTrait;
use App\Models\AccountBalance;
use App\Models\Customers;
use App\Models\Mode;
use App\Models\Response;
use Illuminate\Http\Request;

class IPayController extends Controller
{

    use CommonTrait;

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



    public function sample(Request $request)
    {
        # code...
        $content = json_decode($request->getContent());
        $file = fopen("xx.txt", "w"); //url fopen should be allowed for this to occur
        if (fwrite($file,  json_encode($content)) === FALSE) {
            fwrite("Error: no data written", "\n");
        }

        fwrite($file, "\r\n");
        fclose($file);


        $json_data = file_get_contents('xx.txt');
        $callbackData = json_decode($json_data);
        $status = $callbackData->status;

        if ($status == 'aei7p7yrx4ae34') {

            $status = $callbackData->status;
            $txncd = $callbackData->txncd;
            $msisdn_id = $callbackData->msisdn_id;
            $msisdn_idnum = $callbackData->msisdn_idnum;
            $mc = $callbackData->mc;
            $channel = $callbackData->channel;
            // make the api call

            $body = array(
                'status' => $status,
                'txncd' => $txncd,
                'msisdn_id' => $msisdn_id,
                'msisdn_idnum' => $msisdn_idnum,
                'mc' => $mc,
                'channel' => $channel,
            );
            $url = "https://broshere.imejadevelopers.co.ke/api/receivables/external";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);

            curl_close($ch);
            return $response;
        } else {

            return "Failed Transaction";
        }
    }
    public function ipaycallback(Request $request)
    {
        $content = json_decode($request->getContent());
        $file = fopen("asdf.txt", "w"); //url fopen should be allowed for this to occur
        if (fwrite($file,  json_encode($content)) === FALSE) {
            fwrite("Error: no data written", "\n");
        }

        fwrite($file, "\r\n");
        fclose($file);


        $json_data = file_get_contents('asdf.txt');
        $callbackData = json_decode($json_data);
        $status = $callbackData->status;
        $txncd = $callbackData->txncd;
        $msisdn_id = $callbackData->msisdn_id;
        $msisdn_idnum = $callbackData->msisdn_idnum;
        $mc = $callbackData->mc;
        $channel = $callbackData->channel;


        $res = Response::create([
            'status' => $status,
            'txncd' => $txncd,
            'msisdn_id' => $msisdn_id,
            'msisdn_idnum' => $msisdn_idnum,
            'mc' => $mc,
            'channel' => $channel,
        ]);

        if ($status == 'aei7p7yrx4ae34') {

            $phone = $msisdn_idnum;
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
                    $branch_id = "";

                    $customer = Customers::where('phone', $phone)->first();
                    if($customer){
                        $branch_id=$customer->branch_id;
                    }
                    $result = (new LoansController)->pay_loan_callback($phone, $amount, $trans_code,$branch_id);
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

    //
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

    // public function make_payment($phone, $amount)
    // {
    //     return $result = (new TillController)->initiate_stk_payment($phone, $amount);
    // }
    public function make_payment_original($phone, $amount)
    {
        $res = (new KopoController)->initiate_payments($phone, $amount);
        return $res;
    }
    public function make_payment($phone, $amount)
    {
        $res = (new KopoController)->initiate_payments($phone, $amount);
        return $this->ipaycallback_kopo("Success",$this->generateRandomString(12),$phone,$phone,$amount,"MPESA Simulation");
    }
    public function make_payment_ipay($phone, $amount)
    {
        $string = rand(100000, 1000000);
        $customer = Customers::where('phone', $phone)->first();
        if ($customer) {


            $vid = "broshere";
            $hashkey = "VX3JKBEr*9QFkZv7x9ZSGAFwK9F9Edz#";
            $live = 1;
            $oid = "SMP{$string}";
            $inv = "SMP{$string}";
            $tel = $phone;
            $eml = $customer->email;
            $curr = "KES";
            $p1 = "One";
            $p2 = "Two";
            $p3 = "Three";
            $p4 = "Four";
            $cst = "1";
            $crl = "0";
            $autopay = "1";

            $cbk = "https://urgentcoursewriters.com/hook/index_2.php";

            $datastring = $live . $oid . $inv . $amount . $tel . $eml . $vid . $curr . $p1 . $p2 . $p3 . $p4 . $cst . $cbk;

            $hashid = hash_hmac("sha256", $datastring, $hashkey);

            $url = "https://apis.ipayafrica.com/payments/v2/transact";

            $body = "live=" . $live . "&oid=" . $oid . "&inv=" . $inv . "&amount=" . $amount . "&tel=" . $tel . "&eml=" . $eml . "&vid=" . $vid . "&curr=" . $curr . "&p1=" . $p1 . "&p2=" . $p2 . "&p3=" . $p3 . "&p4=" . $p4 . "&cst=" . $cst . "&cbk=" . $cbk . "&crl=" . $crl . "&autopay=" . $autopay . "&hash=" . $hashid;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $body,  CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/x-www-form-urlencoded",
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $imeja = json_decode($response);
            if ($imeja) {

                $sid = $imeja->data->sid;
                $result = $this->complete_request($phone, $amount, $sid);
                # code...
            } else {

                return $this->successResponse("success", $response);
            }
        }
    }

    public function ipaycallback_kopo($status, $txncd, $msisdn_id, $msisdn_idnum, $mc, $channel)
    {


        $exist = Response::where(['txncd' => $txncd])->exists();
        if ($exist) {
            $data = ([
                'code' => 400,
                'message' => "Transaction Code Already Exists",

            ]);
            return $this->successResponse("success", $data);
        }

        $res = Response::create([
            'status' => $status,
            'txncd' => $txncd,
            'msisdn_id' => $msisdn_id,
            'msisdn_idnum' => $msisdn_idnum,
            'mc' => $mc,
            'channel' => $channel,
        ]);

        if ($status == 'Success') {

            $phone = $msisdn_idnum;
            $amount = $mc;
            $trans_code = $txncd;

            $where = Mode::where('phone', $phone)->first();
            if ($where) {
                $mode = $where->mode;
                $phone = $where->account;
                $account = $where->account;
                if ($mode == '1') {
                    # code... 
                    $response = ([
                        'proceed' => 0,
                        'message' => 'Loan Payment Successfull'
                    ]);
                    $branch_id = "";

                    $customer = Customers::where('phone', $phone)->first();
                    if($customer){
                        $branch_id=$customer->branch_id;
                    }
                    $result = (new LoansController)->pay_loan_callback($phone, $amount, $trans_code, $branch_id);


                    return $this->successResponse("success", $response);
                }
                if ($mode == '2') {
                    # code...
                    $response = ([
                        'proceed' => 0,
                        'message' => 'Savings Deposit Successfull'
                    ]);
                    $result = (new SavingsController)->savings_callback($phone, $amount, $trans_code, $where->description);


                    return $this->successResponse("success", $response);
                }
                if ($mode == '3') {
                    # code...
                    $response = ([
                        'proceed' => 0,
                        'message' => 'Welfare Deposit Successfull'
                    ]);
                    $result = (new SavingsController)->welfare_callback($phone, $amount, $trans_code);


                    return $this->successResponse("success", $response);
                }
                if ($mode == '4') {
                    # code...
                    $response = ([
                        'proceed' => 0,
                        'message' => 'Shares Deposit Successfull'
                    ]);
                    $result = (new SavingsController)->shares_callbacl($phone, $amount, $trans_code);


                    return $this->successResponse("success", $response);
                }
                if ($mode == '5') {
                    # code...
                    $response = ([
                        'proceed' => 0,
                        'message' => 'Buy 4 Me Deposit Successfull'
                    ]);
                    $result = (new B4MController)->callback_contribute($where->reference, $phone, $trans_code, $amount);


                    return $this->successResponse("success", $response);
                }
                if ($mode == '6') {
                    # code...
                    $response = ([
                        'proceed' => 0,
                        'message' => 'Welfare Loan Payment Successfull'
                    ]);
                    $result = (new LoansController)->callback_welfare($phone, $amount, $trans_code);

                    return $this->successResponse("success", $response);
                }
                if ($mode == '7') {
                    # code...
                    $response = ([
                        'proceed' => 0,
                        'message' => 'Monthly Contribution Payment Successfull'
                    ]);
                    $result = (new SavingsController)->callback_monthly($phone, $amount, $trans_code);

                    return $this->successResponse("success", $response);
                }
            } else {
                return "No Payment Record Available";
            }
        } else {
            return "Request failed";
        }
    }
    public function web_payment($phone, $amount)
    {
        $string = rand(100000, 1000000);
        $customer = Customers::where('phone', $phone)->first();
        if ($customer) {


            $vid = "broshere";
            $hashkey = "VX3JKBEr*9QFkZv7x9ZSGAFwK9F9Edz#";
            $live = 1;
            $oid = $string;
            $inv = $string;
            $tel = $phone;
            $eml = $customer->email;
            $curr = "KES";
            $p1 = "One";
            $p2 = "Two";
            $p3 = "Three";
            $p4 = "Four";
            $cst = "1";
            $crl = "0";
            $autopay = "1";
            $cbk = "https://urgentcoursewriters.com/hook/index.php";
            // $cbk="https://broshere.imejadevelopers.co.ke/api/receivables/sample";

            $datastring = $live . $oid . $inv . $amount . $tel . $eml . $vid . $curr . $p1 . $p2 . $p3 . $p4 . $cst . $cbk;

            $hashid = hash_hmac("sha256", $datastring, $hashkey);

            $url = "https://apis.ipayafrica.com/payments/v2/transact";

            $body = "live=" . $live . "&oid=" . $oid . "&inv=" . $inv . "&amount=" . $amount . "&tel=" . $tel . "&eml=" . $eml . "&vid=" . $vid . "&curr=" . $curr . "&p1=" . $p1 . "&p2=" . $p2 . "&p3=" . $p3 . "&p4=" . $p4 . "&cst=" . $cst . "&cbk=" . $cbk . "&crl=" . $crl . "&autopay=" . $autopay . "&hash=" . $hashid;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $body,  CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/x-www-form-urlencoded",
                ),
            ));

            return    $response = curl_exec($curl);

            curl_close($curl);
            $imeja = json_decode($response);
            $sid = $imeja->data->sid;
            $result = $this->complete_request($phone, $amount, $sid);

            return $this->successResponse("success", $response);
        }
    }
    public function initiate_payment(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|integer|between:1,100000',
        ]);
        $phone = $attr['phone'];
        $amount = $attr['amount'];
        $string = rand(100000, 1000000);
        $customer = Customers::where('phone', $phone)->first();
        if ($customer) {


            $vid = "broshere";
            $hashkey = "VX3JKBEr*9QFkZv7x9ZSGAFwK9F9Edz#";
            $live = 1;
            $oid = "SMP{$string}";
            $inv = "SMP{$string}";
            $tel = $phone;
            $eml = $customer->email;
            $curr = "KES";
            $p1 = "One";
            $p2 = "Two";
            $p3 = "Three";
            $p4 = "Four";
            $cst = "1";
            $crl = "0";
            $autopay = "1";
            $cbk = "https://urgentcoursewriters.com/hook/index.php";
            // $cbk="https://broshere.imejadevelopers.co.ke/api/receivables/sample";

            $datastring = $live . $oid . $inv . $amount . $tel . $eml . $vid . $curr . $p1 . $p2 . $p3 . $p4 . $cst . $cbk;

            $hashid = hash_hmac("sha256", $datastring, $hashkey);

            $url = "https://apis.ipayafrica.com/payments/v2/transact";

            $body = "live=" . $live . "&oid=" . $oid . "&inv=" . $inv . "&amount=" . $amount . "&tel=" . $tel . "&eml=" . $eml . "&vid=" . $vid . "&curr=" . $curr . "&p1=" . $p1 . "&p2=" . $p2 . "&p3=" . $p3 . "&p4=" . $p4 . "&cst=" . $cst . "&cbk=" . $cbk . "&crl=" . $crl . "&autopay=" . $autopay . "&hash=" . $hashid;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $body,  CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/x-www-form-urlencoded",
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $imeja = json_decode($response);
            $sid = $imeja->data->sid;
            $result = $this->complete_request($phone, $amount, $sid);

            return $this->successResponse("success", $response);
        }
    }

    // Complete iPay Stk Push
    public function complete_request($phone, $amount, $sid)
    {

        $vid = "broshere";
        $hashkey = "VX3JKBEr*9QFkZv7x9ZSGAFwK9F9Edz#";
        $datastring = $phone . $vid . $sid;


        $hashid = hash_hmac("sha256", $datastring, $hashkey);

        $url = "https://apis.ipayafrica.com/payments/v2/transact/push/mpesa";

        $curl_post_data = [
            'vid' => $vid,
            'phone' => $phone,
            'sid' => $sid,
            'amount' => $amount,
            'hash' => $hashid,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "phone=" . $phone . "&sid=" . $sid . "&vid=" . $vid . "&hash=" . $hashid . "&amount=" . $amount,

        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    public function stkPush(Request $request)
    {

        $attr = $request->validate([
            'phone' => 'required|string',
            'sid' => 'required|string',
            'amount' => 'required|integer|between:1,100000',
        ]);
        $phone = $attr['phone'];
        $sid = $attr['sid'];
        $amount = $attr['amount'];

        $vid = "broshere";
        $hashkey = "VX3JKBEr*9QFkZv7x9ZSGAFwK9F9Edz#";
        $datastring = $phone . $vid . $sid;


        $hashid = hash_hmac("sha256", $datastring, $hashkey);

        $url = "https://apis.ipayafrica.com/payments/v2/transact/push/mpesa";

        $curl_post_data = [
            'vid' => $vid,
            'phone' => $phone,
            'sid' => $sid,
            'amount' => $amount,
            'hash' => $hashid,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "phone=" . $phone . "&sid=" . $sid . "&vid=" . $vid . "&hash=" . $hashid . "&amount=" . $amount,

        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
