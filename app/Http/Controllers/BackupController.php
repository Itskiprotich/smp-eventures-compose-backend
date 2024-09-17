<?php

namespace App\Http\Controllers;

use App\Http\Traits\CommonTrait;
use App\Models\Customers;
use App\Models\Loans;
use App\Models\LoanTypes;
use App\Models\Schedule;
use Carbon\Carbon; 
use Illuminate\Http\Request; 
use Spatie\DbDumper\Databases\MySql;

class BackupController extends Controller
{
    use CommonTrait;
    public function create_cool_backup(Request $request)
    {
        $databaseName=env("DB_DATABASE","irmikete_broshere");
        $userName=env("DB_USERNAME","irmikete_broshere");
        $password=env("DB_PASSWORD","QJvs&zN!-h56");

        $file_name = 'database_backup_on_' . date('y-m-d') . '.sql';

        MySql::create()
            ->setDbName($databaseName)
            ->setUserName($userName)
            ->setPassword($password)
            ->dumpToFile($file_name);

            $today= date('y-m-d');

            $info="Please find attached copy of the database backup for {$today}";
            $customer=Customers::where(['phone'=>'254724743788'])->first();

            $result=(new EmailController)->attachment_email($file_name,$customer,$info);
    }
 

    public function reset_dates(Request $request)
    {
        $loans=Loans::all();
        $present = [];
        foreach($loans as $loan){
            $loan_type = LoanTypes::where(['loan_code' => $loan->loan_code])->first();

            $borrow_date=$loan->created_at;
            $loan_amount=$loan->loan_amount;

            $duration=$loan_type->duration; 
            $date = Carbon::parse($borrow_date);
            $repay_date = $date->addDays($duration);        
            $penalty_date =  (new Carbon($repay_date))->addDays(2);

            // save loans

            $loan->repayment_date= $repay_date;
            $loan->repayment_period=$duration;
            $loan->penalty_date= $penalty_date;
            $loan->save();

            // clear schedules
            $deletedRows = Schedule::where('loan_ref', $loan->loan_ref)->delete();
            $times = $duration / 7;
            $schedule_amount = $loan_amount / $times;
            $then=(new Carbon($borrow_date));
            for ($i = 1; $i <= $times; $i++) {

                $schedule_date = $then->addDays(7);

                $loan = Schedule::create([
                    'phone' => $loan->phone,
                    'loan_ref' => $loan->loan_ref,
                    'due_date' => $schedule_date,
                    'amount' => $schedule_amount,
                    'balance' => $schedule_amount,
                ]);
            }

            $present[] = ([
                'loan_code' => $loan_type->loan_code, 
                'duration' => $duration, 
                'borrow_date'=>$borrow_date,
                'repay_date'=>$repay_date,
                'penalty_date'=>$penalty_date,
                'loan_amount'=>$loan_amount
            ]);
        }

        return $this->successResponse("success", $present);
    }
}
