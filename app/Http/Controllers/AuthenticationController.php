<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{

    public function responseJson($message, $statusCode, $data, $isSuccess = true) {
        if($isSuccess)
            return response()->json([
                "message" => $message,
                "data" => $data,
                "success" => true,
                "code" => $statusCode
            ], $statusCode);

        return response()->json([
            "message" => $message,
            "success" => false,
            "code" => $statusCode
        ], $statusCode);
    }

    public function successResponse($message, $data) {
        return $this->responseJson($message, 200, $data);
    }

    public function errorResponse($message) {
        return $this->responseJson($message, 400, null, false);
    }

    //this method adds new users
    public function createAccount(Request $request)
    {
        $attr = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'usertype' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
        ]);

       
        $firstname = $attr['firstname'];
        $lastname = $attr['lastname'];
        $phone = $attr['phone'];
        $usertype = $attr['usertype'];
        $status = $attr['status'] === 'true' ? true : false;
        $email = $attr['email'];
        $name = $firstname . " " . $lastname;
        $pass = $attr['password'];
         
        $user = User::create([
            'name' => $name, 
            'password' => bcrypt($attr['password']),
            'email' => $attr['email'],
        ]);

        $admin = Admins::create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' =>  $pass,
            'phone' => $phone,
            'usertype' => $usertype,
            'status' => $status
        ]);
        $token=$user->createToken('tokens')->plainTextToken;

        return $this->successResponse("success", $token);
        
    }
    //use this method to signin users
    public function signin(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6',
        ]);

        if (!Auth::attempt($attr)) { 
            
            return $this->errorResponse("Credentials not match");
        }       
        
        $user = Auth::user();
        // $token = $user->createToken('tokens')->plainTextToken;
        $data=([
            'success'=>true,
            'code'=>200,
            'message'=>'success',
            'id'=>$user->id,
            'name'=>$user->name,
            'email'=>$user->email,
            'phone'=>$user->phone,
            'updated_at'=>$user->updated_at,
            'created_at'=>$user->created_at,
            // 'access_token'=>$token,
            
        ]);
        return $this->successResponse("success", $data);
    }

    public function reset(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|', 
        ]);

        $characters = '123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $password = $randomString;

        $user = User::where(['email' =>  $attr['email']])->first();
        $user->password = Hash::make($password);
        $user->save();

        $admin = Admins::where(['email' =>  $attr['email']])->first();
        if ($admin) {
            $admin->password = Hash::make($password);
            $admin->save();

            $message = "Your new PIN has been updated, kindly use {$password} to Log on to the SMP EVentures Admin Portal";
            $result = (new EmailController)->reset_password_email($admin, $message);

        }
        return $this->successResponse("success", $admin);

    }

    // this method signs out users by removing tokens
    public function logout()
    {
        // auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked',
        ];
    }
}
