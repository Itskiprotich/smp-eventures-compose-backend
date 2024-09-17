<?php

namespace App\Http\Controllers;

use App\Http\Traits\CommonTrait;
use App\Models\Loans;
use App\Models\ProfitShare;
use App\Models\Repayments;
use App\Models\Thirdparty;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillingController extends Controller
{

    use CommonTrait;

    public function unpaid_principal(Request $request)
    {
        $outstanding = [];
        $unpaid_loans = Loans::where(['repayment_status' => false, 'loan_status' => 'disbursed'])->get();
        if ($unpaid_loans) {

            $total_paid = 0;
            $total_principal_unpaid = 0;
            $i = 0;
            $total_principal = 0;
            $total_balance = 0;


            foreach ($unpaid_loans as $single) {
                $i++;
                $total_balance += $single->loan_balance;

                $current_principal = $single->principle;
                $sum_paid = Repayments::where(['loan_ref' => $single->loan_ref])->sum('paid_amount');

                $unpaid = $current_principal - $sum_paid;

                $total_paid += $sum_paid;
                $total_principal += $current_principal;

                if ($unpaid > 0) {
                    $total_principal_unpaid += $unpaid;
                }
            }
            $outstanding[] = ([
                'count' => $i,
                'total_paid' => $total_paid,
                'total_principal' => $total_principal,
                'total_balance' => $total_balance,
                'unpaid' => $total_principal_unpaid
            ]);
        }
        return "Success";
    }

    public function generate_admin_fee_billing(Request $request)
    {
        # code...
        $beneficiaries = array(
            'developer(admin_fee)' => 0.2,
            '254791729957(admin_fee)' => 0.3 //frank
        );
        $now = Carbon::now();
        $first =  $now->startOfMonth()->subDay()->toDateString();
        $last = Carbon::now()->endOfMonth()->toDateString();

        $start_date = Carbon::parse($first)->toDateTimeString();
        $end_date = Carbon::parse($last)->toDateTimeString();
        $current = Carbon::now();
        $year = $current->format('Y');
        $month = $current->format('F');
        $total_admin_fee = Loans::where(['loan_status' => 'paid'])->whereBetween('clear_date', [$start_date, $end_date])->sum('admin_fee');

        foreach ($beneficiaries as  $key=>$value) {
          
            $ratio=$value;
            $float_balance= $ratio * $total_admin_fee;
            $name=$key;

            $sys_reserved = ProfitShare::updateOrCreate(
                ['year' => $year, 'month' => $month, 'phone' =>$name],
                ['float_balance' => $float_balance, 'name' => $name, 'ratio' => $ratio, 'earnings' => $float_balance]
    
            );
        }

        return "Success";
    }

    public function generate_billing(Request $request)
    {
        $all = ProfitShare::all();
        foreach ($all as $one) {
            if ($one->phone == "system") {
                $one->name = "System";
            }
            if ($one->phone == "developer") {
                $one->name = "Developer";
            }
            $one->save();
            $jina = Thirdparty::where(['phone' => $one->phone])->first();
            if ($jina) {
                $name = $jina->firstname . " " . $jina->lastname;
                $one->name = $name;
                $one->save();
            }
        }
        $now = Carbon::now(); 
        $first =  $now->startOfMonth()->subDay()->toDateString();
        $last = Carbon::now()->endOfMonth()->toDateString();

        $start_date = Carbon::parse($first)->toDateTimeString();
        $end_date = Carbon::parse($last)->toDateTimeString();

        // return $start_date . " " . $end_date;

        $current = Carbon::now();
        $year = $current->format('Y');
        $month = $current->format('F');

        $total_interest = Loans::where(['loan_status' => 'paid'])->whereBetween('clear_date', [$start_date, $end_date])->sum('interest');

        $developer = 0.2 * $total_interest;
        $reserved = 0.1 * $total_interest;
        $investors_share = 0.7 * $total_interest;

        $total_investment = Thirdparty::where(['status' => true])->sum('float_balance');

        $investors = Thirdparty::all();
        $investor = [];

        $sys_reserved = ProfitShare::updateOrCreate(
            ['year' => $year, 'month' => $month, 'phone' => 'system'],
            ['float_balance' => $reserved, 'name' => 'System Reserved', 'ratio' => 0.1, 'earnings' => $reserved]

        );
        $sys_dev = ProfitShare::updateOrCreate(
            ['year' => $year, 'month' => $month, 'phone' => 'developer'],
            ['float_balance' => $developer, 'name' => 'Developer', 'ratio' => 0.2, 'earnings' => $developer]

        );

        foreach ($investors as $inv) {
            $float_balance = $inv->float_balance;
            $ratio = $float_balance / $total_investment;
            $earnings = $ratio * $investors_share;
            $name = $inv->firstname . " " . $inv->lastname;

            $sys_inv = ProfitShare::updateOrCreate(
                ['year' => $year, 'month' => $month, 'phone' => $inv->phone],
                ['float_balance' => $float_balance, 'name' => $name, 'ratio' => $ratio, 'earnings' => $earnings]

            );
            $investor[] = ([
                'phone' => $inv->phone,
                'float_balance' => $inv->float_balance,
                'ratio' => number_format($ratio, 2),
                'earnings' => number_format($earnings, 0, '.', ','),
            ]);
        }

        $data  = ([

            'year' => $year,
            'month' => $month,
            'total_interest' => number_format($total_interest, 0, '.', ','),
            'developer' => number_format($developer, 0, '.', ','),
            'reserved' => number_format($reserved, 0, '.', ','),
            'investors_share' => number_format($investors_share, 0, '.', ','),
            'total_investment' => number_format($total_investment, 0, '.', ','),
            'investors' => $investor

        ]);

        return $data;
    }
}
