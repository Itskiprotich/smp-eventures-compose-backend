<?php

namespace App\Http\Controllers;

use PDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendEmailController extends Controller
{
    //
    public function index(Request $request)
    {
        $date = date('m');
        $data["email"] = "kiprotich.japheth19@gmail.com";
        $data["client_name"] = "Japheth";
        $data["subject"] = "Imeja Developers - Monthly Billing";

        $pdf = PDF::loadView('emails.test', $data);

        try {
            Mail::send('emails.invoice', $data, function ($message) use ($data, $pdf) {
                $message->to($data["email"], $data["client_name"])
                    ->subject($data["subject"])
                    ->attachData($pdf->output(), "invoice.pdf");
            });
        } catch (Exception $exception) {
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }
        if (Mail::failures()) {
            $this->statusdesc  =   "Error sending mail";
            $this->statuscode  =   "0";
        } else {

            $this->statusdesc  =   "Message sent Succesfully";
            $this->statuscode  =   "1";
        }
        return response()->json(compact('this'));
    }
}
