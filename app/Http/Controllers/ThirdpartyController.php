<?php

namespace App\Http\Controllers;

use App\Http\Traits\CommonTrait;
use App\Models\Customers;
use App\Models\FloatStatements;
use App\Models\SystemLogs;
use App\Models\Thirdparty;
use App\Models\Withdrawals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThirdpartyController extends Controller
{
    //

    use CommonTrait;

    public function __construct()
    {
        $this->middleware('auth');
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
    public function transfer_shares(Request $request)
    {
        $attr = $request->validate([
            'original' => 'required|string',
            'destination' => 'required|string',
            'amount' => 'required|integer|between:1,100000',
            'note' => 'required|string'
        ]);
        $original = $attr['original'];
        $destination = $attr['destination'];
        $amount = $attr['amount'];

        // return $data = [
        //     'original' => $original,
        //     'destination' => $destination,
        // ];

        if ($original === $destination) {
            return redirect()->to('/investment')->with('error', 'Operation not permitted');
        }

        $exists = Thirdparty::where(['phone' => $original])->exists();
        if (!$exists) {
            return redirect()->to('/investment')->with('error', 'Investor Does not Exists, please try again');
        }
        $exists2 = Thirdparty::where(['phone' => $destination])->exists();
        if (!$exists2) {
            return redirect()->to('/investment')->with('error', 'Investor Does not Exists, please try again');
        }
        $org = Thirdparty::where(['phone' => $original])->first();
        $des = Thirdparty::where(['phone' => $destination])->first();
        $org_float_balance = $org->float_balance;
        $des_float_balance = $des->float_balance;

        if ($amount > $org_float_balance) {
            return redirect()->to('/investment')->with('error', 'Insufficient Funds, please try again');
        }

        $new_org = $org_float_balance - $amount;
        $new_des = $des_float_balance + $amount;

        $org->float_balance = $new_org;
        $org->save();

        $des->float_balance = $new_des;
        $des->save();
        $user = Auth::user();

        $logs = SystemLogs::create([
            'phone' => $user->email,
            'title' => "Investment Shares Transfer",
            'body' => "Shares transfer of KES {$amount} from Investor {$original} to Investor {$destination}. New Balances are Org KES: {$new_org} and Des KES: {$new_des} "
        ]);

        return redirect()->to('/investment')->with('success', "Successfull transfer of KES {$amount} from {$original} to {$destination}");
    }
    public function add_investor(Request $request)
    {
        $attr = $request->validate([
            'phone' => 'required|string|max:255'
        ]);
        $phone = $attr['phone'];

        $cust = Customers::where(['phone' => $phone])->first();
        if ($cust) {
            $exits = Thirdparty::where(['phone' => $phone])->exists();
            if ($exits) {
                return redirect()->to('/investment')->with('success', 'Investor Already Exists');
            }

            $branch_id = $this->check_current_branch();

            $tp = Thirdparty::create(['branch_id'=>$branch_id,'firstname' => $cust->firstname, 'lastname' => $cust->lastname, 'phone' => $phone, 'email_address' => $cust->email]);

            return redirect()->to('/investment')->with('success', 'Investor Added Successfully');
        } else {
            return redirect()->to('/investment')->with('error', 'Experienced Problems Saving Data!!');
        }
    }

    public function withdrawal_reject($id)
    {
        $inv = Withdrawals::where(['reference' => $id])->first();
        if ($inv) {
            $inv->narration = 'rejected';
            $inv->status = true;
            $inv->save();
        }

        return redirect()->to('/investment/pending')->with('success', 'Withdrawal Rejected Successfully');
    }
    public function withdrawal_approve($id)
    {

        $user = Auth::user();
        $inv = Withdrawals::where(['reference' => $id])->first();
        if ($inv) {
            $action_by = $inv->action_by;
            if ($action_by == $user->name) {

                return redirect()->to('/investment/pending')->with('error', 'You are not allowed!!');
            }

            //get the amount 
            $amount = $inv->amount;
            //get the phone number
            $phone = $inv->phone;

            //get the float balance from thirdparty table
            $float = Thirdparty::where(['phone' => $phone])->first();
            if ($float) {
                $float_balance = $float->float_balance;
                if ($float_balance < $amount) {
                    return redirect()->to('/investment/pending')->with('error', 'Insufficient Balance!!');
                }
                $float->float_balance = $float_balance - $amount;
                $float->save();
            }

            $inv->narration = 'approved';
            $inv->status = true;
            $inv->save();
        }

        return redirect()->to('/investment/approved')->with('success', 'Withdrawal Approved Successfully');
    }
    public function withdrawal_investor(Request $request, $id)
    {

        $attr = $request->validate([
            'source' => 'required|string|max:255',
            'amount' => 'required|string|max:255'
        ]);
        $amount = $attr['amount'];
        $source = $attr['source'];

        $is_float = $source === 'true' ? true : false;

        $cust = Thirdparty::where(['phone' => $id])->first();

        $user = Auth::user();

        if ($cust) {
            $float_balance = $cust->float_balance;
            $interest_balance = $cust->interest_balance;


            $pending = Withdrawals::where(['phone' => $id, 'status' => false])->exists();
            if ($pending) {
                # code...
                return redirect()->to('/investment/view/' . $id)->with('werror', 'A pending transaction exists!!');
            }

            if ($is_float) {
                $bal = $float_balance - $amount;
                if ($bal < 0) {

                    return redirect()->to('/investment/view/' . $id)->with('werror', 'Insufficient Float Balance!!');
                } else {
                    // process request

                    $withdraw = Withdrawals::create([
                        'phone' => $id,
                        'reference' => $this->generateRandomString(12),
                        'action_by' => $user->name,
                        'amount' => $amount,
                        'balance' => $bal,
                        'narration' => 'pending',
                        'mode' => $is_float,
                        'status' => false

                    ]);


                    return redirect()->to('/investment/view/' . $id)->with('wsuccess', 'Float withdrawal Innitiated successfully!!');
                }
            } else {
                $bal_int = $interest_balance - $amount;
                if ($bal_int < 0) {

                    return redirect()->to('/investment/view/' . $id)->with('werror', 'Insufficient Interest Balance!!');
                } else {
                    // process request

                    $withdrwa = Withdrawals::create([
                        'phone' => $id,
                        'reference' => $this->generateRandomString(12),
                        'action_by' => $user->name,
                        'amount' => $amount,
                        'balance' => $bal_int,
                        'narration' => 'pending',
                        'mode' => $is_float,
                        'status' => false

                    ]);

                    return redirect()->to('/investment/view/' . $id)->with('wsuccess', 'Interest withdrawal Innitiated successfully!!');
                }
            }
        } else {
            return redirect()->to('/investment/view/' . $id)->with('werror', 'Please check the account details!!');
        }
    }

    public function deposit_investor(Request $request, $id)
    {

        $attr = $request->validate([
            'amount' => 'required|integer|between:1,100000',
            'reference' => 'required|string|max:255',
            'narration' => 'required|string|max:255'
        ]);
        $amount = $attr['amount'];
        $reference = $attr['reference'];
        $narration = $attr['narration'];

        $exists = FloatStatements::where(['reference' => $reference])->exists();
        if ($exists) {

            return redirect()->to('/investment/view/' . $id)->with('error', 'Please check the Reference!!');
        }

        $cust = Thirdparty::where(['phone' => $id])->first();
        if ($cust) {
            $float_balance = $cust->float_balance;
            $total = $float_balance + $amount;

            $cust->float_balance = $total;
            $cust->save();

            $state = FloatStatements::create([
                'phone' => $id,
                'reference' => $reference,
                'description' => $narration,
                'amount' => $amount,
                'total' => $total
            ]);

            return redirect()->to('/investment/view/' . $id)->with('success', 'Cash Deposit Successful!!');
        } else {

            return redirect()->to('/investment/view/' . $id)->with('error', 'Experienced Problems Saving Data!!');
        }
    }
}
