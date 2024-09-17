<?php

namespace App\Http\Traits;

use App\Models\Student;
use Carbon\Carbon;

trait CommonTrait
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

    public function short_code()
    {
        return 7417384;
    }

    public function short_code_till()
    {
        return 9379253;
    }

    public function generate_access_password()
    {
        $lipa_time = Carbon::rawParse('now')->format('YmdHms');
        $passkey = "7bb498f78f60b2b480d3673c55930209bc933d563be4e1373dd90c44c43a66ea";
        $BusinessShortCode = $this->short_code();
        $timestamp = $lipa_time;
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode . $passkey . $timestamp);
        return $lipa_na_mpesa_password;
    }

    public function generate_access_token()
    {

        $consumer_key = "FSyzccXourmCibz6OLN7rJGK4uBNQmyd";
        $consumer_secret = "cGrncZodSBOifaqc";
        $credentials = base64_encode($consumer_key . ":" . $consumer_secret);
        $url = "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic " . $credentials));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        $access_token = json_decode($curl_response);
        return $access_token->access_token;
    }
    public function generate_file_name($file)
    {
        $filename =  $file->getClientOriginalName();
        $location = 'uploads';

        $file->move($location, $filename);
        $profile = public_path($location . "/" . $filename);
        $profile = substr($profile, strrpos($profile, '/') + 1);

        return $profile;
    }
    public function check_current_branch()
    {
        $branch_id = session('branch_id');
        if (is_null($branch_id)) {
            return redirect()->to('/branches/select');
        }
        return $branch_id;
    }
}
