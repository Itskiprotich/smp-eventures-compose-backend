<?php

namespace App\Http\Controllers;

use App\Models\Buyforme;
use App\Models\Commitment;
use App\Models\LockBuy;
use App\Models\Mode;
use App\Models\Pool;
use Illuminate\Http\Request;

class B4MController extends Controller
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
                "data" => $data,
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

    public function pledges_b4m(Request $request)
    {
        $attr = $request->validate([
            'owner' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
        ]);
        $owner = $attr['owner'];
        $phone = $attr['phone'];
        $previous = LockBuy::where(['owner' => $owner, 'phone' => $phone])->first();
        if ($previous) {
            $data = ([
                'borrow' => 1,
                'message' => $previous->balance
            ]);
            return $this->successResponse("success", $data);
        } else {
            $data = ([
                'borrow' => 0,
                'message' => 0
            ]);
            return $this->successResponse("success", $data);
        }
    }
    public function qualification(Request $request)
    {
        $attr = $request->validate([
            'reference' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
        ]);
        $reference = $attr['reference'];
        $phone = $attr['phone'];
        $exist = Buyforme::where(['phone' => $phone])->exists();
        if ($exist) {
            $data = ([
                'proceed' => 1,
                'message' => 'Welcome'
            ]);
            return $this->successResponse("success", $data);
        } else {
            $data = ([
                'proceed' => 0,
                'message' => 'Please contact the administrator'
            ]);
            return $this->successResponse("success", $data);
        }
    }

    public function contribute(Request $request)
    {
        $attr = $request->validate([
            'reference' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'trans_code' => 'required|string|max:255',
            'amount' => 'required|integer|between:1,100000',
        ]);


        $amount = $attr['amount'];
        $phone = $attr['phone'];
        $reference = $attr['reference'];
        $account = $phone;
        if ($request->has('account')) {
            $account = $request->account;
        }
        $mode = Mode::updateOrCreate(
            ['phone' =>  $phone],
            ['description' => 'Payment for Buy 4 Me','account'=>$account, 'amount' => $amount, 'mode' => '5', 'reference' => $reference]
        );


        $result = (new IPayController)->make_payment($phone, $amount);
        $data = ([
            'borrow' => 1,
            'message' => 'Payment successfull, Please wait for STK Push'
        ]);
        return $this->successResponse("success", $data);
    }

    public function callback_contribute($reference, $phone, $trans_code, $amount)
    {

        $exist = Pool::where(['reference' => $reference, 'is_closed' => false])->exists();
        if ($exist) {
            $contributor = Buyforme::where(['phone' => $phone])->first();
            if ($contributor) {
                $current = Pool::where(['reference' => $reference])->first();
                if ($current) {
                    $balance = $current->balance;
                    $diff = $balance - $amount;

                    $org = $contributor->amount;
                    $newamount = $org + $amount;

                    $contributor->amount = $newamount;
                    $contributor->save();

                    if ($diff > 0) {
                        $current->balance = $diff;
                        $current->save();
                    } else {
                        $current->balance = 0;
                        $current->is_closed = true;
                        $current->save();
                    }
                    $alr = Commitment::where(['phone' => $phone, 'reference' => $reference])->first();
                    if ($alr) {
                        $t_amount = $alr->amount + $amount;
                    } else {
                        $t_amount = $amount;
                    }
                    $commit = Commitment::updateOrCreate(
                        ['phone' => $phone, 'reference' => $reference],
                        ['trans_code' => $trans_code, 'amount' => $t_amount],
                    );

                    $data = ([
                        'proceed' => 0,
                        'message' => 'Pool Deposit successful!'
                    ]);
                    return $this->successResponse("success", $data);
                } else {

                    $data = ([
                        'proceed' => 1,
                        'message' => 'Wait for Account Approval'
                    ]);
                    return $this->successResponse("success", $data);
                }
            } else {

                $data = ([
                    'proceed' => 1,
                    'message' => 'Wait for Account Approval'
                ]);
                return $this->successResponse("success", $data);
            }
        }


        $data = ([
            'proceed' => 1,
            'message' => 'Pool Already closed please try another pool'
        ]);
        return $this->successResponse("success", $data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Pool::join('customers', 'pools.phone', '=', 'customers.phone')->where('pools.is_closed', false)->get(['pools.*', 'customers.firstname', 'customers.lastname']);
        return $this->successResponse("success", $data);
    }
    public function approved_b4m($id)
    {
        $data = Pool::join('customers', 'pools.phone', '=', 'customers.phone')
            ->join('commitments', 'pools.reference', '=', 'commitments.reference')
            ->where(['pools.is_closed' => true, 'commitments.phone' => $id])->get(['pools.*', 'customers.firstname', 'customers.lastname', 'commitments.amount as pledge',]);
        return $this->successResponse("success", $data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
