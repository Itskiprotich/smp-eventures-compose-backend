<?php

namespace App\Http\Controllers;

use App\Http\Traits\CommonTrait;
use App\Models\Customers;
use App\Models\Kopo;
use Illuminate\Http\Request;

class KopoController extends Controller
{
    use CommonTrait;


    public function generate_token(Request $request)
    {

        $url = "https://api.kopokopo.com/oauth/token";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $curl_post_data = [
            "client_id" => "T4vpEc3NVc8WjlOUOAQxtYYDkyjvZScObbUpNkUOWKo",
            "client_secret" => "wkbmmeRyxtJ_vjQEG9vEH71IgpKwCNDWEMytAdqEzj8",
            "grant_type" => "client_credentials"
        ];
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);
        $response = curl_exec($curl);

        curl_close($curl);

        $imeja = json_decode($response);
        return  $sid = $imeja->access_token;
    }


    public function initiate_stk(Request $request)
    {
        $phone = "254724743788";
        $amount = "1";

        $url = "https://api.kopokopo.com/oauth/token";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $curl_post_data = [
            "client_id" => "T4vpEc3NVc8WjlOUOAQxtYYDkyjvZScObbUpNkUOWKo",
            "client_secret" => "wkbmmeRyxtJ_vjQEG9vEH71IgpKwCNDWEMytAdqEzj8",
            "grant_type" => "client_credentials"
        ];
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $response = curl_exec($curl);

        curl_close($curl);
        $imeja = json_decode($response);
        $sid = $imeja->access_token;

        $res = $this->complete_process($sid, $phone, $amount);

        return $res;
    }

    public function initiate_payments($phone, $amount)
    {


        $url = "https://api.kopokopo.com/oauth/token";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $curl_post_data = [
            "client_id" => "T4vpEc3NVc8WjlOUOAQxtYYDkyjvZScObbUpNkUOWKo",
            "client_secret" => "wkbmmeRyxtJ_vjQEG9vEH71IgpKwCNDWEMytAdqEzj8",
            "grant_type" => "client_credentials"
        ];
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $response = curl_exec($curl);

        curl_close($curl);
        $imeja = json_decode($response);
        $sid = $imeja->access_token;

        $res = $this->complete_process($sid, $phone, $amount);

        return $res;
    }
    public function complete_process($token, $phone, $amount)
    {

        $customer = Customers::where('phone', $phone)->first();
        $url = "https://api.kopokopo.com/api/v1/incoming_payments";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'Authorization: Bearer ' . $token
        ));
        $curl_post_data = [
            "payment_channel" => "M-PESA",
            "till_number" => "K748653",
            "subscriber" => [
                "first_name" => $customer->firstname,
                "last_name" => $customer->lastname,
                "phone_number" => $phone,
                "email" => $customer->email
            ],
            "amount" => [
                "currency" => "KES",
                "value" => $amount
            ],
            "metadata" => [
                "something" => "",
                "something_else" => "Something else"
            ],
            "_links" => [
                "callback_url" => "https://smp.imeja.co.ke/api/kopo/response"
            ]

        ];
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);
        // get curl error
        $error = curl_error($curl);

        $data = Kopo::create([
            'phone' => $phone,
            'amount' => $amount,
            'initiator' => $curl_response,
        ]);
        //
        curl_close($curl);

        //return $curl_response and $error in array
        $response=[
            'curl_response'=>$curl_response,
            'error'=>$error
        ];
        return $response;

    }

    public function response(Request $request)
    {
        $content = json_decode($request->getContent());
        $file = fopen("kopo.txt", "w"); //url fopen should be allowed for this to occur
        if (fwrite($file,  json_encode($content)) === FALSE) {
            fwrite("Error: no data written", "\n");
        }

        fwrite($file, "\r\n");
        fclose($file);

        $json_data = file_get_contents('kopo.txt');
        $callbackData = json_decode($json_data);
        $status = $callbackData->data->attributes->status;
        if ($status == "Success") {
            # code...
            $event = $callbackData->data->attributes->event;
            $reference = $event->resource->reference;
            $sender_phone_number = $event->resource->sender_phone_number;
            $msisdn_id = preg_replace('/[^0-9\.]/', '', $sender_phone_number);
            $msisdn_idnum = preg_replace('/[^0-9\.]/', '', $sender_phone_number);
            $mc = $event->resource->amount;
            $channel = $event->resource->system;
            $res = (new IPayController)->ipaycallback_kopo($status, $reference, $msisdn_id, $msisdn_idnum, $mc, $channel);
        }
    }
}