<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use App\Models\MpesaTransaction;
use Carbon\Carbon;

class MpesaController extends Controller
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
        $consumer_key = "QPgEmexGwGJb9htNLZ8gnGVlj0MOr6Kk";
        $consumer_secret = "ZPiszDit9IZgVcGd";
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
    public function tiny_pesa(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|string'
        ]);
        
        $phone = $attr['phone'];
        $amount = $attr['amount'];

        $account_no = 'SMP Enventure'; // Enter account number optional
        $url = 'https://tinypesa.com/api/v1/express/initialize';
        $data = array(
            'amount' => $amount,
            'msisdn' => $phone,
            'account_no' => $account_no
        );
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
            'ApiKey: sbAE7ECAfqA' // Replace with your api key from server
        );
        $info = http_build_query($data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $info);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $resp = curl_exec($curl);
      return   $msg_resp = json_decode($resp);

        if ($msg_resp->success == 'true') {
            return $this->successResponse("success", $msg_resp);
        }else{
            return $this->errorResponse("Account Not Found");
        }
    }

    public function stkPush(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|string'
        ]);
        if (Customers::where('phone', $attr['phone'])->exists()) {
            $customer = Customers::where('phone', $attr['phone'])->first();

            $phone = $attr['phone'];
            $amount = $attr['amount'];

            $account = $customer->membership_no;
            if ($account == "") {
                $account =  $phone;
            }

            $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $this->generateAccessToken()));
            $curl_post_data = [
                //Fill in the request parameters with valid values
                'BusinessShortCode' => 174379,
                'Password' => $this->lipaNaMpesaPassword(),
                'Timestamp' => Carbon::rawParse('now')->format('YmdHms'),
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $amount,
                'PartyA' => $phone,
                'PartyB' => 174379,
                'PhoneNumber' => $phone,
                'CallBackURL' => 'https://smp.imeja.co.ke/api/mpesa/callback',
                'AccountReference' => $account,
                'TransactionDesc' => "Testing stk push on sandbox"
            ];
            $data_string = json_encode($curl_post_data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            $curl_response = curl_exec($curl);


            return $this->successResponse("success", $curl_response);
        } else {
            return $this->errorResponse("Account Not Found");
        }
    }
    public function store(Request $request)
    {

        $content = json_decode($request->getContent());
        $file = fopen("log.txt", "w"); //url fopen should be allowed for this to occur
        if (fwrite($file,  json_encode($content)) === FALSE) {
            fwrite("Error: no data written", "\n");
        }

        fwrite($file, "\r\n");
        fclose($file);

        $file2 = fopen("alllogs.txt", "a+"); //url fopen should be allowed for this to occur
        if (fwrite($file2,  json_encode($content)) === FALSE) {
            fwrite("Error: no data written", "\n");
        }

        fwrite($file2, "\r\n");
        fclose($file2);

        $json_data = file_get_contents('log.txt');
        $callbackData = json_decode($json_data);
        $resultCode = $callbackData->Body->stkCallback->ResultCode;
        $resultDesc = $callbackData->Body->stkCallback->ResultDesc;

        if ($resultCode == 0) {

            $merchantRequestID = $callbackData->Body->stkCallback->MerchantRequestID;
            $checkoutRequestID = $callbackData->Body->stkCallback->CheckoutRequestID;
            $meta_data = $callbackData->Body->stkCallback->CallbackMetadata;

            $amount = $meta_data->Item[0]->Value;
            $mpesaReceiptNumber = $meta_data->Item[1]->Value;
            $transactionDate = $meta_data->Item[4]->Value;

            $result = [
                "resultDesc" => $resultDesc,
                "resultCode" => $resultCode,
                "merchantRequestID" => $merchantRequestID,
                "checkoutRequestID" => $checkoutRequestID,
                "amount" => $amount,
                "mpesaReceiptNumber" => $mpesaReceiptNumber,
                "transactionDate" => $transactionDate,
            ];
            $mpesa_transaction = new MpesaTransaction();
            $mpesa_transaction->transaction_type = $merchantRequestID;
            $mpesa_transaction->trans_id = $mpesaReceiptNumber;
            $mpesa_transaction->trans_time = $transactionDate;
            $mpesa_transaction->trans_amount = $amount;
            $mpesa_transaction->business_short_code = "1234567890";
            $mpesa_transaction->bill_ref_number = $checkoutRequestID;
            $mpesa_transaction->invoice_number = $merchantRequestID;
            $mpesa_transaction->org_account_balance = 100;
            $mpesa_transaction->third_party_trans_id = $merchantRequestID;
            $mpesa_transaction->msisdn = $merchantRequestID;
            $mpesa_transaction->first_name = $mpesaReceiptNumber;
            $mpesa_transaction->middle_name = $mpesaReceiptNumber;
            $mpesa_transaction->last_name = $mpesaReceiptNumber;
            $mpesa_transaction->save();


            return json_encode($result);
        } else {
            return $resultDesc;
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
}
