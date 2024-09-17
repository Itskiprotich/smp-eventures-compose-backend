<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Loans extends Model
{
    use HasFactory;  
    protected $fillable = [
        'phone', 
        'loan_code',
        'principle', 
        'rate_applied',
        'admin_fee',
        'interest',
        'loan_amount',
        'loan_balance',
        'repayment_period',
        'customer_name', 
        'loan_ref',
        'loan_disbursed',
        'disbursment_date',
        'loan_status',
        'automatic',
        'repayment_date',
        'penalty_date',
        'approved_by',
        'actioned_by','loan_ref','branch_id'
    ];
    public static function countLoans() 
    {
        return Loans::where('phone','!=',null)->where('loan_status','=','pending')->latest('id')->get()->count();
    }

    public static function dueToday()

    {
        $today = date('Y-m-d'); 
        // return Loans::where('phone','!=',null)->where(['loan_status'=>'disbursed', 'repayment_status' => false])->where(DB::raw('CAST(repayment_date as date)'), '>=', $start_day)->where(DB::raw('CAST(repayment_date as date)'), '<=', $end_day)->latest('id')->get()->count();
        return Loans::where('phone', '!=', null)
                ->where('loan_status', 'disbursed')
                ->where('repayment_status', false)
                ->where(DB::raw('CAST(repayment_date as date)'), $today)
                ->latest('id')
                ->get()
                ->count();
    }
    public  static function loans_next_week()
    {
        $branch_id = session('branch_id');
        $today = date('Y-m-d');
        $start_day= date('Y-m-d', strtotime($today . '+7 day'));
        $end_day= date('Y-m-d', strtotime($start_day . '+7 day'));
        $loans=Loans::where('phone','!=',null)->where(['loan_status'=>'disbursed', 'repayment_status' => false,'branch_id' => $branch_id])->where(DB::raw('CAST(repayment_date as date)'), '>=', $start_day)->where(DB::raw('CAST(repayment_date as date)'), '<=', $end_day)->latest('id')->get();
        return $loans;
    }

    public  static function loans_next_week_count()
    {
        $branch_id = session('branch_id');
    
        $today = date('Y-m-d');
        $start_day= date('Y-m-d', strtotime($today . '+7 day'));
        $end_day= date('Y-m-d', strtotime($start_day . '+7 day'));
        $loans=Loans::where('phone','!=',null)->where(['loan_status'=>'disbursed', 'repayment_status' => false,'branch_id' => $branch_id])->where(DB::raw('CAST(repayment_date as date)'), '>=', $start_day)->where(DB::raw('CAST(repayment_date as date)'), '<=', $end_day)->latest('id')->get()
        ->count();
        return $loans;
    }
     
     
    // protected $casts = [
    //     'disbursment_date' => 'date:Y-m-d',
    //     'repayment_date' => 'date:Y-m-d'
    // ];
}
