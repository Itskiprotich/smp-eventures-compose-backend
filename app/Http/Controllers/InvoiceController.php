<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $url = "https://invoices.pharmacyboardkenya.org/token";


        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "APPID: c4ca4238a0b923820dcc509a6f75849b",
            "APIKEY: YzM4ZWRhMTMwNzViMGJjZDJiMGVkNjkzOWRlNzFmMDhkZTA2YTUzNA=="
        );

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        curl_close($curl);

        $result_arr = json_decode($response, true);

         $session_token = $result_arr['session_token'];

        /* 
        Generate your invoice and store in your DB
*/

        $invoice_total = 1000;

        $invoice_url = "https://invoices.pharmacyboardkenya.org/invoice";


        $invoice_total = $invoice_total * 0.0075;


        $curl = curl_init($invoice_url);
        curl_setopt($curl, CURLOPT_URL, $invoice_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $data = array(
            'payment_type' => 'Clinical_Trials', // Types are issued e.g. Clinical_Trials
            'amount_due' => $invoice_total, // from your invoice
            'user_id' => 1, // from PRIMS login
            'session_token' => $session_token // from above
        );

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $resp = curl_exec($curl);
        curl_close($curl);

          $result_invoiceid = json_decode($resp, true);

        // Do stuff with feedback e.g. getting invoice id that can be used to test payment from eCitizen like below

        $invoice_id = "285251";//$result_invoiceid['invoice_id'];

      return  $raw_id = base64_encode($invoice_id);

        //example of payment details query https://prims.pharmacyboardkenya.org/scripts/get_status?invoice=264526

         return   $paymentdetails = json_decode("https://prims.pharmacyboardkenya.org/scripts/get_status?invoice=$invoice_id", true);


        $ecitizeniframe = "https://payments.ecitizen.go.ke/PaymentAPI/iframev2.1.php";


        $curl = curl_init($ecitizeniframe);
        curl_setopt($curl, CURLOPT_URL, $ecitizeniframe);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $data = array(
            'secureHash' => $paymentdetails["secureHash"],
            'apiClientID' => 42,
            'serviceID' => $paymentdetails["ecitizen_service_id"],
            'notificationURL' => 'https://practice.pharmacyboardkenya.org/ipn?id=' . $raw_id,
            'callBackURLOnSuccess' => 'https://practice.pharmacyboardkenya.org/callback?id=' . $raw_id,
            'billRefNumber' => $paymentdetails["billRefNumber"],
            'currency' => $paymentdetails["currency"],
            'amountExpected' => $paymentdetails["amountExpected"],
            'billDesc' => $paymentdetails["billDesc"],
            'pictureURL' => $paymentdetails["pictureURL"],
            'clientName' => $paymentdetails["clientName"],
            'clientEmail' => $paymentdetails["clientEmail"],
            'clientMSISDN' => $paymentdetails["clientMSISDN"],
            'clientIDNumber' => $paymentdetails["clientIDNumber"],
        );

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $resp = curl_exec($curl);
        echo $resp;
        curl_close($curl);


        echo '<h2><a href="https://prims.pharmacyboardkenya.org/crunch?type=ecitizen_invoice&id=' . $raw_id . '">Download Invoice</a></h2>'; // Official PPB Invoice

        /*
        Create your own invoice if you desire
  */

        echo 'your own invoice';
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
