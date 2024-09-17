<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;
    protected $fillable = [
        'status', 'txncd', 'msisdn_id','msisdn_idnum', 'mc','channel','action_by','branch_id'
    ]; 

    public static function selectAllRepayments($branch_id)
    {
        # code...
        return Response::where(['status' => '0','branch_id'=>$branch_id])->orderBy('id', 'DESC')->get();
    }
 
}
