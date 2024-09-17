<?php

namespace App\Http\Controllers;

use App\Models\LoanTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParametersController extends Controller
{
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

    public function addLoantype(Request $request)
    {
        $attr = $request->validate([
            'loan_name' => 'required|string|max:255',
            'duration' => 'required|string|max:11',
            'min_limit' => 'required|string|max:255',
            'max_limit' => 'required|string|max:255',
            'interest_rate' => 'required|string|max:255',
            'admin_fee' => 'required|string|max:255',
        ]);
        $loan_type = LoanTypes::create([
            'loan_name' => $attr['loan_name'],
            'duration' => $attr['duration'],
            'loan_code' => $this->generateRandomString(12),
            'min_limit' => $attr['min_limit'],
            'max_limit' => $attr['max_limit'],
            'interest_rate' => $attr['interest_rate'],
            'admin_fee' => $attr['admin_fee']
        ]);
        if ($loan_type) {

            return $this->successResponse("success", $loan_type);
        } else {
            return $this->errorResponse("Failed to Add Loan Type");
        }
    }
    public function viewLoantype(Request $request)
    {
        $loan_types = DB::table('loan_types')->where('active', '=', true)->orderBy('created_at','DESC')->get();
        if ($loan_types) {

            return $this->successResponse("success", $loan_types);
        } else {
            return $this->errorResponse("Failed to Fetch Loan Type");
        }
    }
    public function store(Request $request)
    { 
      
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
