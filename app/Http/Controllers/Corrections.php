<?php

namespace App\Http\Controllers;

use App\Models\Loans;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Corrections extends Controller
{
    public function recreate_schedule(Request $request)
    {
        $attr = $request->validate([
            'loan_ref' => 'required|string|max:255'
        ]);

        $loan_ref = $attr['loan_ref'];

        $loan = Loans::where(['loan_ref' => $loan_ref])->first();

        $loan_duration = $loan->repayment_period;
        $deletedRows = Schedule::where('loan_ref', $loan_ref)->delete();

        $total_balance = $loan->loan_balance;
        $times = $loan_duration / 7;
        $schedule_amount = $total_balance / $times;
        $now = Carbon::rawParse('now')->format('Y-m-d');
        $date = Carbon::createFromFormat('Y-m-d', $now);

        $phone = $loan->phone;
        $principle = $loan->principle;

        for ($i = 1; $i <= $times; $i++) {

            $schedule_date = $date->addDays(7);

            $loan = Schedule::create([
                'phone' => $phone,
                'loan_ref' => $loan_ref,
                'due_date' => $schedule_date,
                'amount' => $schedule_amount,
                'balance' => $schedule_amount,
            ]);
        }
    }

}