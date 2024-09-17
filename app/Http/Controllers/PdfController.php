<?php

namespace App\Http\Controllers;

use App\Models\Guarantor;
use App\Models\Loans;
use App\Models\Note;
use App\Models\Repayments;
use App\Models\Schedule;
use Illuminate\Http\Request;
use PDF;

class PdfController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function download_loan($id)
    {
        # code...
        $data['data'] = Loans::where('loan_ref', $id)->first();
        $data['schedules'] = Schedule::where('loan_ref', $id)->orderBy('id', 'desc')->get();
        $data['repayments'] = Repayments::where('loan_ref', $id)->orderBy('id', 'desc')->get();
        $data['guarantors'] = Guarantor::join('customers', 'guarantors.guarantor', '=', 'customers.phone')->where(['guarantors.phone' => $id])->orderBy('guarantors.id', 'desc')->get(['guarantors.*', 'customers.firstname', 'customers.phone as phonenumber', 'customers.lastname']);
        $data['notes'] = Note::where('loan_ref', $id)->orderBy('id', 'desc')->get();
        $pdf = PDF::loadView('downloads.loans', $data);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream('loan.pdf');
    }

    public function graphs()
    {
        # code...
        return view('graphs.index');
    }
    public function graphsPdf()
    {
        $data = [
            'foo' => 'bar'
        ];
        $pdf = PDF::loadView('graphs.index', $data);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream('document.pdf');
    }
}
