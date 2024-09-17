<?php

namespace App\Http\Controllers;

use App\Models\Mode;
use App\Models\Response;
use Illuminate\Http\Request;

class TinyController extends Controller
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

    public function tiny_sample(Request $request)
    {
        $phone = "254724743788";
        $amount = "10";
        $result_desc = "PAYMENT SUCCESSFUL";
        $trans_code = "CONFIRMED";
        $result_code = "0";

        $params = "phone=" . $phone . "&amount=" . $amount . "&result_desc=" . $result_desc . "&trans_code=" . $trans_code . "&result_code=" . $result_code;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://smp.imeja.co.ke/api/receivables/tiny_external",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic N2RuOUVSdkc5OGFOUjd0OEdpdWVMTEl4Q0ZRVExMM286d2dtSERVMTR3RGw3dEhPQw==",
                "Content-Type: application/x-www-form-urlencoded",
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    public function tiny_external(Request $request)
    {

        $attr = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|integer|between:1,100000',
            'result_desc' => 'required|string',
            'trans_code' => 'required|string',
            'result_code' => 'required|string',
        ]);

        $amount = $attr['amount'];
        $result_code = $attr['result_code'];
        $trans_code = $attr['trans_code'];
        $phone = $attr['phone'];
        $result_desc = $attr['result_desc'];

        $res = Response::create([
            'status' => $result_code,
            'txncd' => $trans_code,
            'msisdn_id' => $phone,
            'msisdn_idnum' => $result_desc,
            'mc' => $amount,
            'channel' => "MPESA",
        ]);

        if ($result_code == 0) {

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

            return "Success";
        } else {
            return "Error";
        }
    }
    public function tiny_callback(Request $request)
    {
        $stkCallbackResponse = $request->getContent();
        // $stkCallbackResponse = file_get_contents('php://input');
        $logFile = "config.json";
        $log = fopen($logFile, "a");
        fwrite($log, $stkCallbackResponse);
        fclose($log);

        $callbackContent = json_decode($stkCallbackResponse);

        $ResultCode = $callbackContent->Body->stkCallback->ResultCode;
        $ResultDesc = $callbackContent->Body->stkCallback->ResultDesc;
        $CheckoutRequestID = $callbackContent->Body->stkCallback->CheckoutRequestID;
        $amount = $callbackContent->Body->stkCallback->CallbackMetadata->Item[0]->Value;
        $trans_code = $callbackContent->Body->stkCallback->CallbackMetadata->Item[1]->Value;
        $phone = $callbackContent->Body->stkCallback->CallbackMetadata->Item[4]->Value;

        $res = Response::create([
            'status' => $ResultCode,
            'txncd' => $trans_code,
            'msisdn_id' => $phone,
            'msisdn_idnum' => $ResultDesc,
            'mc' => $amount,
            'channel' => "MPESA",
        ]);

        if ($ResultCode == 0) {

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

            return "Success";
        } else {
            return "Error";
        }
    }
    //
    public function make_payment($phone, $amount)
    {
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
    }
}
