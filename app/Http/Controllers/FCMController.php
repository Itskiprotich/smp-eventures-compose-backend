<?php

namespace App\Http\Controllers;

use App\Models\FCMTokens;
use App\Models\Notification;
use Illuminate\Http\Request;

class FCMController extends Controller
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


    public function index()
    {
        //
    }

    public function store(Request $request)
    {

        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'fcm_token' => 'required|string|max:255'
        ]);
        $fcm = FCMTokens::updateOrCreate(
            ['phone' =>  request('phone')],
            ['fcm_token' => request('fcm_token')]
        );

        if ($fcm) {
            return $this->successResponse("success", $fcm);
        } else {
            return $this->errorResponse("Failed to Add Token");
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
    public function send_message_admin($phone, $title, $body)
    {
        $noty = Notification::create([
            'phone' => $phone,
            'title' => $title,
            'body' => $body,
        ]);
        $url = 'https://fcm.googleapis.com/fcm/send';

        $user = FCMTokens::where(['phone' => $phone])->first();
        $device_id = $user->fcm_token;

        $api_key = 'AAAAR08BZM0:APA91bGsDdX3D0l25gRo_mdMpkjMLcSIK1pj-L2v6Qd4bI3vra0eupbIsbxRXupPmtI4unPVAmh_5aAuKIkzedvqo89FfYtOuBY0DUhZ-gSgaz3Yb6z2Ck-5VSn8N0KFe9E-8Itx_eEv';

        $fields = array(
            'registration_ids' => array(
                $device_id
            ),
            'data' => array(
                "message" => $body
            )
        );

        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $api_key
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
    }
    public function send_message(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:255'
        ]);
        $phone = $request->phone;
        $title = $request->title;
        $body = $request->body;

        $noty = Notification::create([
            'phone' => $phone,
            'title' => $title,
            'body' => $body,
        ]);




        $url = 'https://fcm.googleapis.com/fcm/send';

        $user = FCMTokens::where(['phone' => $phone])->first();
        $device_id = $user->fcm_token;

        $api_key = 'AAAAR08BZM0:APA91bGsDdX3D0l25gRo_mdMpkjMLcSIK1pj-L2v6Qd4bI3vra0eupbIsbxRXupPmtI4unPVAmh_5aAuKIkzedvqo89FfYtOuBY0DUhZ-gSgaz3Yb6z2Ck-5VSn8N0KFe9E-8Itx_eEv';

        $fields = array(
            'registration_ids' => array(
                $device_id
            ),
            'data' => array(
                "message" => $body
            )
        );

        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $api_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $this->successResponse("success", $result);
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
