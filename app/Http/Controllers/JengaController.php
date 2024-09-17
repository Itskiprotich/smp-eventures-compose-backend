<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class JengaController extends Controller

{

    // Generating Random Strings
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

    // Generating Random Numbers
    public function generateRandomNumbers($length = 25)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    // Generate Access Token
    
    public function generateToken(Request $request)
    {
        $username="4940759945";
        $password="poba4YEDfS4LUd3GzzVrLMBjVozR79xV";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://uat.jengahq.io/identity/v2/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "username=".$username."&password=".$password,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic N2RuOUVSdkc5OGFOUjd0OEdpdWVMTEl4Q0ZRVExMM286d2dtSERVMTR3RGw3dEhPQw==",
                "Content-Type: application/x-www-form-urlencoded",
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    // Live Access Token
    
    public function liveToken()
    {
        
        $username="4940759945";
        $password="poba4YEDfS4LUd3GzzVrLMBjVozR79xV";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://uat.jengahq.io/identity/v2/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "username=".$username."&password=".$password,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic N2RuOUVSdkc5OGFOUjd0OEdpdWVMTEl4Q0ZRVExMM286d2dtSERVMTR3RGw3dEhPQw==",
                "Content-Type: application/x-www-form-urlencoded",
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $server_output = (array) json_decode($response);
        json_encode($server_output);

        return $access_token = ($server_output['access_token']);

    }

    // Check Account Balance
    public function checkBalance(Request $request)
    {

        $countryCode="KE";
        $accountId="1100161816677";
        $date=Carbon::rawParse('now')->format('Y-m-d');
        $plainText = $countryCode.$accountId;

        $fp = fopen("privatekey.pem", "r");
        $privateKey = fread($fp, 8192);
        fclose($fp);
        $token = $this->liveToken();
        openssl_sign($plainText, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        $curl = curl_init(); 

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://uat.jengahq.io/account/v2/accounts/balances/{$countryCode}/{$accountId}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 50,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "cache-control: no-cache",
                "Content-Type: application/json",
                "signature: " . base64_encode($signature),
            ),
        ));
        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return  $err;
        } else {
            return $result;
        }

    }
    //   Make Airtime Purchase
    public function buyAirtime(Request $request)
    {
        $attr = $request->validate([
            'amount' => 'required|numeric',
            'phone'    => 'required|numeric|digits:10', 
            'telco' => 'required',
        ]);
                 
        $mobileNumber= $request->phone; 
        $amount=$request->amount; 
        $telco=$request->telco; 
        $reference=$this->generateRandomString(12);

        $countryCode="KE";
        $accountId="4940759945";
        
        $date=Carbon::rawParse('now')->format('Y-m-d');
        $plainText = $accountId.$telco.$amount.$reference;

        $fp = fopen("privatekey.pem", "r");
        $privateKey = fread($fp, 8192);
        fclose($fp);
        $token = $this->liveToken();
        openssl_sign($plainText, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        $fields = array (
            'customer' => array ( 
                "countryCode" => $countryCode,
                "mobileNumber" => $mobileNumber, 
            ),
            'airtime' => array (
                    "amount" => $amount,
                    "telco" => $telco,
                    "reference" => $reference, 
            )
        );
        $curl = curl_init(); 
        $data_string= json_encode($fields);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://uat.jengahq.io/transaction/v2/airtime",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 50,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "cache-control: no-cache",
                "Content-Type: application/json",
                "signature: " . base64_encode($signature),
            ),
        ));
        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return  $err;
        } else {
            return $result;
        }

    }

    // Get list of all billers
    public function getAllBillers(Request $request)
    {

        $countryCode="KE";
        $accountNumber="1100161816677";
        $date=Carbon::rawParse('now')->format('Y-m-d');
        $plainText = $countryCode.$accountNumber;

        $fp = fopen("privatekey.pem", "r");
        $privateKey = fread($fp, 8192);
        fclose($fp);
        $token = $this->liveToken();
        openssl_sign($plainText, $signature, $privateKey, OPENSSL_ALGO_SHA256);
       
        $countryCode="KE";
        $mobileNumber="0764555372";
        $amount="10";
        $telco="Equitel";
        $reference=$this->generateRandomString(12);

        $fields = array (
            'customer' => array ( 
                "countryCode" => $countryCode,
                "mobileNumber" => $mobileNumber, 
            ),
            'airtime' => array (
                    "amount" => $amount,
                    "telco" => $telco,
                    "reference" => $reference, 
            )
        );
        
        $curl = curl_init(); 

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://uat.jengahq.io/transaction/v2/billers?per_page=20&page=1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 50,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => json_encode($fields),
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "cache-control: no-cache",
                "Content-Type: application/json",
                "signature: " . base64_encode($signature),
            ),
        ));
        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return  $err;
        } else {
            return $result;
        }

    }
    
    // Make Bill Payment
    public function billPayments(Request $request)
    {
 
        $countryCode="KE";
        $currency="KES";
        $amount="10";
        $billerCode="320320"; 
        $reference=$this->generateRandomNumbers(12);
        $name="Kiprotich Japheth";
        $account="101704";
        $mobileNumber="0764555372";
        $partnerId="1100161816677"; 
        $remarks ="Payment for Bills";
    
        $plainText = $billerCode.$amount.$reference.$partnerId;

        $fp = fopen("privatekey.pem", "r");
        $privateKey = fread($fp, 8192);
        fclose($fp);
        $token = $this->liveToken();
        openssl_sign($plainText, $signature, $privateKey, OPENSSL_ALGO_SHA256);
       
        

        $fields = array (
            'biller' => array ( 
                "billerCode" => $billerCode,
                "countryCode" => $countryCode, 
            ),
            'bill' => array (
                    "amount" => $amount,
                    "currency" => $currency,
                    "reference" => $account, 
            ),
            'payer' => array (
                    "name" => $name,
                    "account" => $account,
                    "reference" => $reference, 
                    "mobileNumber" => $mobileNumber, 
            ),
            'partnerId'=>$partnerId,
            'remarks'=>$remarks

        );

        $data_string= json_encode($fields);

 
        
        $curl = curl_init(); 

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://uat.jengahq.io/transaction/v2/bills/pay",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "cache-control: no-cache",
                "Content-Type: application/json",
                "signature: " . base64_encode($signature),
            ),
        ));
        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return  $err;
        } else {
            return $result;
        }

    }

    // Transact Within Equity
    
    public function withinEquity(Request $request)
    {
 
        $countryCode="KE";
        $currencyCode="KES";
        $amount="10"; 
        $reference=$this->generateRandomString(12);

        $fromName="Kiprotich Japheth";
        $fromaccountNumber="1234567890"; 

        $toName="Ian Murithi";
        $toaccountNumber="1234567890";

        $destinationtype="bank";
        $transfertype="InternalFundsTransfer";

        $description="Sending money from one account to another "; 
        $date=Carbon::rawParse('now')->format('Y-m-d');

        $plainText = $fromaccountNumber.$amount.$currencyCode.$reference;
 

        $fp = fopen("privatekey.pem", "r");
        $privateKey = fread($fp, 8192);
        fclose($fp);
        $token = $this->liveToken();
        openssl_sign($plainText, $signature, $privateKey, OPENSSL_ALGO_SHA256);
       
        

        $fields = array (
            'source' => array ( 
                "name" => $fromName,
                "countryCode" => $countryCode, 
                "accountNumber" => $fromaccountNumber, 
            ),
            'destination' => array (
                    "type" => $destinationtype,
                    "countryCode" => $countryCode,
                    "name" => $toName, 
                    "accountNumber" => $toaccountNumber, 
            ),
            'transfer' => array (
                    "type" => $transfertype,
                    "amount" => $amount,
                    "reference" => $reference, 
                    "currencyCode" => $currencyCode, 
                    "date" => $date, 
                    "description" => $description, 

            )

        );

       

        $data_string= json_encode($fields);

 
        
        $curl = curl_init(); 

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://uat.jengahq.io/transaction/v2/remittance",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 50,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "cache-control: no-cache",
                "Content-Type: application/json",
                "signature: " . base64_encode($signature),
            ),
        ));
        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return  $err;
        } else {
            return $result;
        }

    }

    // Transact From Equity to Mobile
        
    public function equitytoMobile(Request $request)
    {
 
        $countryCode="KE";
        $currencyCode="KES";
        $amount="10"; 
        $reference=$this->generateRandomString(12);

        $fromName="Kiprotich Japheth";
        $fromaccountNumber="1234567890"; 

        $toName="Ian Murithi";
        $toaccountNumber="1234567890";

        $destinationtype="mobile";
        $transfertype="MobileWallet";

        $description="Sending money from one account to another "; 
        $date=Carbon::rawParse('now')->format('Y-m-d');
        
        // Airtel & Mpesa
        $plainText = $amount.$currencyCode.$reference.$fromaccountNumber;

        // Equitel
        $plainText2 = $fromaccountNumber.$amount.$currencyCode.$reference;
        
 

        $fp = fopen("privatekey.pem", "r");
        $privateKey = fread($fp, 8192);
        fclose($fp);
        $token = $this->liveToken();
        openssl_sign($plainText, $signature, $privateKey, OPENSSL_ALGO_SHA256);
       
        

        $fields = array (
            'source' => array ( 
                "name" => $fromName,
                "countryCode" => $countryCode, 
                "accountNumber" => $fromaccountNumber, 
            ),
            'destination' => array (
                    "type" => $destinationtype,
                    "countryCode" => $countryCode,
                    "name" => $toName, 
                    "accountNumber" => $toaccountNumber, 
            ),
            'transfer' => array (
                    "type" => $transfertype,
                    "amount" => $amount,
                    "reference" => $reference, 
                    "currencyCode" => $currencyCode, 
                    "date" => $date, 
                    "description" => $description, 

            )

        );

       

        $data_string= json_encode($fields);

 
        
        $curl = curl_init(); 

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://uat.jengahq.io/transaction/v2/remittance",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 50,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "cache-control: no-cache",
                "Content-Type: application/json",
                "signature: " . base64_encode($signature),
            ),
        ));
        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return  $err;
        } else {
            return $result;
        }

    }

    // Pesalink to User Bank Account

    public function pesalinkBank(Request $request)
    {
 
        $countryCode="KE";
        $currencyCode="KES";
        $amount="10"; 
        $reference=$this->generateRandomString(12);

        $fromName="Kiprotich Japheth";
        $fromaccountNumber="1234567890"; 

        $toName="Ian Murithi";
        $toaccountNumber="1234567890";

        $destinationtype="bank";
        $transfertype="PesaLink";

        $description="Sending money from one account to another "; 
        $date=Carbon::rawParse('now')->format('Y-m-d');
        
        // Airtel & Mpesa
        $plainText = $amount.$currencyCode.$reference.$fromaccountNumber;

        // Equitel
        $plainText2 = $fromaccountNumber.$amount.$currencyCode.$reference;
        
 

        $fp = fopen("privatekey.pem", "r");
        $privateKey = fread($fp, 8192);
        fclose($fp);
        $token = $this->liveToken();
        openssl_sign($plainText, $signature, $privateKey, OPENSSL_ALGO_SHA256);
       
        

        $fields = array (
            'source' => array ( 
                "name" => $fromName,
                "countryCode" => $countryCode, 
                "accountNumber" => $fromaccountNumber, 
            ),
            'destination' => array (
                    "type" => $destinationtype,
                    "countryCode" => $countryCode,
                    "name" => $toName, 
                    "accountNumber" => $toaccountNumber, 
            ),
            'transfer' => array (
                    "type" => $transfertype,
                    "amount" => $amount,
                    "reference" => $reference, 
                    "currencyCode" => $currencyCode, 
                    "date" => $date, 
                    "description" => $description, 

            )

        );

       

        $data_string= json_encode($fields);

 
        
        $curl = curl_init(); 

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://uat.jengahq.io/transaction/v2/remittance",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 50,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "cache-control: no-cache",
                "Content-Type: application/json",
                "signature: " . base64_encode($signature),
            ),
        ));
        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return  $err;
        } else {
            return $result;
        }

    }
    
    // Pesalink to User Mobile
    
    public function pesalinkMobile(Request $request)
    {
 
        $countryCode="KE";
        $currencyCode="KES";
        $amount="10"; 
        $reference=$this->generateRandomString(12);

        $fromName="Kiprotich Japheth";
        $fromaccountNumber="1234567890"; 

        $toName="Ian Murithi";
        $toaccountNumber="1234567890";

        $destinationtype="mobile";
        $transfertype="PesaLink";

        $description="Sending money from one account to another "; 
        $date=Carbon::rawParse('now')->format('Y-m-d');
        
        // Airtel & Mpesa
        $plainText = $amount.$currencyCode.$reference.$fromaccountNumber;

        // Equitel
        $plainText2 = $fromaccountNumber.$amount.$currencyCode.$reference;
        
 

        $fp = fopen("privatekey.pem", "r");
        $privateKey = fread($fp, 8192);
        fclose($fp);
        $token = $this->liveToken();
        openssl_sign($plainText, $signature, $privateKey, OPENSSL_ALGO_SHA256);
       
        

        $fields = array (
            'source' => array ( 
                "name" => $fromName,
                "countryCode" => $countryCode, 
                "accountNumber" => $fromaccountNumber, 
            ),
            'destination' => array (
                    "type" => $destinationtype,
                    "countryCode" => $countryCode,
                    "name" => $toName, 
                    "accountNumber" => $toaccountNumber, 
            ),
            'transfer' => array (
                    "type" => $transfertype,
                    "amount" => $amount,
                    "reference" => $reference, 
                    "currencyCode" => $currencyCode, 
                    "date" => $date, 
                    "description" => $description, 

            )

        );

       

        $data_string= json_encode($fields);

 
        
        $curl = curl_init(); 

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://uat.jengahq.io/transaction/v2/remittance",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 50,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "cache-control: no-cache",
                "Content-Type: application/json",
                "signature: " . base64_encode($signature),
            ),
        ));
        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return  $err;
        } else {
            return $result;
        }

    }


}
