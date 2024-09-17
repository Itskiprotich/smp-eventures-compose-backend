<?php

namespace App\Http\Controllers;

use App\Http\Traits\CommonTrait;
use App\Models\AccountBalance;
use App\Models\Admins;
use App\Models\BulkStatement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    use CommonTrait;


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add_float(Request $request)
    {

        $attr = $request->validate([
            'amount' => 'required|integer|between:1,1000000',
            'narration' => 'required|string|max:255'
        ]);
        $amount = $attr['amount'];
        $narration = $attr['narration'];

        $user = Auth::user();

        $branch_id = $this->check_current_branch();
        // check if pending
        $pending = BulkStatement::where(['status' => false, 'branch_id' => $branch_id])->exists();
        if ($pending) {
            return redirect()->to('/admin/float')->with('error', 'There is an existing transaction!!');
        }
        $balance = 0;
        $prev = BulkStatement::latest()->first();
        if ($prev) {
            $balance = $prev->balance + $amount;
        } else {
            $balance = $amount;
        }
        $add = BulkStatement::create([
            'reference' => $this->generateRandomString(12),
            'action_by' => $user->name,
            'amount' => $amount,
            'balance' => $balance,
            'narration' => $narration,
            'branch_id' => $branch_id,
            'status' => false
        ]);

        return redirect()->to('/admin/float')->with('success', 'Update successful!');
    }

    public function update_float(Request $request, $id)
    {
        $user = Auth::user();
        $available = BulkStatement::where(['reference' => $id, 'status' => false])->first();
        if ($available) {
            $innitiator = $available->action_by;
            //get admin by email
            $admin = Admins::where(['email' => $user->email])->first();
            $admin_role = $admin->usertype;

            if ($admin_role != "Superadmin") {
                if ($innitiator == $user->name) {
                    return redirect()->to('/admin/float')->with('error', 'Operation not permitted !!');
                }
            }

            // get current bulk amount

            $branch_id = $this->check_current_branch();
            $amount = $available->amount;
            $system_balance = 0;
            $balances_account = AccountBalance::where(['status' => true, 'branch_id' => $branch_id])->first();
            if ($balances_account) {

                $system_balance = $balances_account->bulk;
                $total_balance = $system_balance + $amount;
                $balances_account->bulk = $total_balance;
                $balances_account->save();
            } else {
                $fcm = AccountBalance::updateOrCreate(
                    ['status' => true,'branch_id'=>$branch_id],
                    ['bulk' => $amount]
                );
            }


            $available->approved_by = $user->name;
            $available->status = true;
            $available->save();

            return redirect()->to('/admin/float')->with('success', 'Cash Approval Successfull');
        } else {
            return redirect()->to('/admin/float')->with('error', 'Operation not permitted !!');
        }
    }
    public function view_float()
    {

        $branch_id = $this->check_current_branch();
        $data['pending'] = BulkStatement::where(['status' => false, 'branch_id' => $branch_id])->orderBy('id', 'DESC')->get();
        $data['approved'] = BulkStatement::where(['status' => true, 'branch_id' => $branch_id])->orderBy('id', 'DESC')->get();
        return view('admins.float.index', $data);
    }
}
