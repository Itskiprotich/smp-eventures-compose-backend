<?php

namespace App\Http\Controllers;

use App\Http\Traits\CommonTrait;
use App\Models\Admins;
use App\Models\Customers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    use CommonTrait;

    function sanitize_my_email($field)
    {
        $field = filter_var($field, FILTER_SANITIZE_EMAIL);
        if (filter_var($field, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    public function php_information()
    {
        echo phpinfo();
    }
    public function test_email_local(Request $request)

    {
        $customer = Customers::where(['phone' => '254724743788'])->first();
        $info = "Test Email";
        $data = array(
            'customer' => $customer,
            'loan' => $customer,
            'info' => $info,
        );
        Mail::send('mail', $data, function ($message) {
            $sender = env('MAIL_USERNAME', 'APP_NAME');
            $app_name = env('APP_NAME', 'APP_NAME');

            $message->to('kiprotich.japheth19@gmail.com', 'SMP EVentures')
            ->subject('Database Backup');
            // $message->cc(['smp.eventure@gmail.com']);
            $message->from($sender, $app_name);
        });

        $data = ([
            'code' => 200,
            'message' => "Email Sent with attachment. Check your inbox.",

        ]);

        return $this->successResponse("success", $data);
    }

    public function chat_email($chat)
    {
        $customer = Customers::where(['phone' => "254790888247"])->first();
        $info = "You have a new Message::\n\n{$chat->message} From:\n{$chat->phone}";

        $data = array(
            'customer' => $customer,
            'loan' => $customer,
            'info' => $info,
        );
        $isTest = true;
        Mail::send('mail', $data, function ($message) {
            $sender = env('MAIL_USERNAME', 'APP_NAME');
            $app_name = env('APP_NAME', 'APP_NAME');

            $isTest = env('APP_DEBUG') == 'true' ? true : false;
            if ($isTest) {

                $message->to('kiprotich.japheth19@gmail.com', 'SMP EVentures')->subject('New Chat Message');
            } else {
                // $message->to('misiatipeter@gmail.com', 'SMP EVentures')->subject('New Chat Message');
            }
            $message->from($sender, $app_name);
        });

        return  "Email Sent, Check your inbox. $isTest";
    }

    public function topup_email($loan, $info)
    {
        $customer = Customers::where(['phone' => $loan->phone])->first();

        session(['email' => $customer->email]);
        $data = array(
            'customer' => $customer,
            'loan' => $customer,
            'info' => $info,
        );
        $isTest = true;
        Mail::send('mail', $data, function ($message) {
            $sender = env('MAIL_USERNAME', 'APP_NAME');
            $app_name = env('APP_NAME', 'APP_NAME');
            $email = session('email');

            $message->to($email, 'SMP EVentures')->subject('Loan Topup');

            $message->from($sender, $app_name);
        });

        return  "Email Sent, Check your inbox. $isTest";
    }
    public function chat_email_to_user($chat)
    {
        $customer = Customers::where(['phone' => $chat->phone])->first();
        $info = $chat->message;

        session(['email' => $customer->email]);
        $data = array(
            'customer' => $customer,
            'loan' => $customer,
            'info' => $info,
        );
        $isTest = true;
        Mail::send('mail', $data, function ($message) {
            $sender = env('MAIL_USERNAME', 'APP_NAME');
            $app_name = env('APP_NAME', 'APP_NAME');
            $email = session('email');

            $message->to($email, 'SMP EVentures')->subject('New Chat Message');

            $message->from($sender, $app_name);
        });

        return  "Email Sent, Check your inbox. $isTest";
    }

    public function chat_email_attach_user($chat, $file_name)
    {
        $customer = Customers::where(['phone' => $chat->phone])->first();
        $info = "Please Download the following attachment for your action";

        session(['file_name' => $file_name]);
        session(['email' => $customer->email]);
        $data = array(
            'customer' => $customer,
            'loan' => $customer,
            'info' => $info,
        );
        $isTest = true;
        Mail::send('mail', $data, function ($message) {
            $sender = env('MAIL_USERNAME', 'APP_NAME');
            $app_name = env('APP_NAME', 'APP_NAME');
            $email = session('email');
            $file_name = session('file_name');
            $message->attach($file_name);
            $message->to($email, 'SMP EVentures')->subject('New Chat Message');

            $message->from($sender, $app_name);
        });

        return  "Email Sent, Check your inbox. $isTest";
    }

    public function basic_email()
    {
        $customer = Customers::where(['phone' => '254724743788'])->first();

        $email = $customer->email;
        $header = "From: SMP Eventures <smp.imeja@gmail.com>";
        $subject = "Loan Repayment";
        $message = "Welcome to SMP Eventures";

        $url = 'https://codewavesystems.com/hook/email.php';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $curl_post_data = [
            'to' => $email,
            'head' => $header,
            'subject' => $subject,
            'message' => $message,
        ];
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);
        $response = curl_exec($curl);

        curl_close($curl);



        return $this->successResponse("success", $response);
    }
    public function html_email()
    {
        $data = array('name' => "Kiprotich");
        // Mail::send('mail', $data, function ($message) {

        //     $sender = env('MAIL_USERNAME', 'APP_NAME');
        //     $app_name = env('APP_NAME', 'APP_NAME');
        //     $message->to('kiprotich.japheth19@gmail.com', 'Tutorials Point')->subject('Laravel HTML Testing Mail');
        //     $message->from($sender, $app_name);
        // });

        // $data = ([
        //     'code' => 200,
        //     'message' => "HTML Email Sent. Check your inbox.",

        // ]);

        return $this->successResponse("success", $data);
    }

    public function general_email_function($data, $info, $title)
    {
        try {
            $email = session('email');
            // get the customer by email address
            $cust = Customers::where(['email' => $email])->first();
            if ($cust) {
                $alerts_enabled = $cust->alerts_enabled;
                if ($alerts_enabled) {
                    session(['title' => $title]);
                    Mail::send('mail', $data, function ($message) {
                        $sender = env('MAIL_USERNAME', 'APP_NAME');
                        $app_name = env('APP_NAME', 'APP_NAME');

                        $title = session('title');
                        $email = session('email');
                        $message->to($email, 'SMP EVentures')->subject($title);
                        // $message->cc(['smp.eventure@gmail.com']);
                        $message->from($sender, $app_name);
                    });
                }
            }
        } catch (Exception $e) {
        }
    }
    public function customer_otp_email($customer, $info)
    {
        session(['email' => $customer->email]);
        $data = array(
            'customer' => $customer,
            'loan' => $customer,
            'info' => $info,
        );
        $title = "OTP";
        session(['title' => $title]);
        Mail::send('mail', $data, function ($message) {
            $sender = env('MAIL_USERNAME', 'APP_NAME');
            $app_name = env('APP_NAME', 'APP_NAME');

            $title = session('title');
            $email = session('email');
            $message->to($email, 'SMP EVentures')->subject($title);

            $message->from($sender, $app_name);
        });
        // $this->general_email_function($info, $title);
    }
    public function attachment_email($file_name, $customer, $info)

    {
        session(['file_name' => $file_name]);
        $data = array(
            'customer' => $customer,
            'loan' => $customer,
            'info' => $info,
        );
        Mail::send('mail', $data, function ($message) {
            $sender = env('MAIL_USERNAME', 'APP_NAME');
            $app_name = env('APP_NAME', 'APP_NAME');
            $file_name = session('file_name');
            $message->to('kiprotich.japheth19@gmail.com', 'SMP EVentures')->subject('Database Backup');
            $message->attach($file_name);
            $message->from($sender, $app_name);
        });

        $data = ([
            'code' => 200,
            'message' => "Email Sent with attachment. Check your inbox.",

        ]);
    }
    public function send_reminder($customer, $info)
    {
        session(['email' => $customer->email]);
        session(['name' => $customer->email]);
        $data = array(
            'customer' => $customer,
            'loan' => $customer,
            'info' => $info,
        );
        $title = "Loan Reminder";
        $this->general_email_function($data, $info, $title);
    }
    public function send_reminder_message($customer, $info, $title)
    {
        session(['email' => $customer->email]);
        session(['name' => $customer->email]);
        $data = array(
            'customer' => $customer,
            'loan' => $customer,
            'info' => $info,
        );

        $this->general_email_function($data, $info, $title);
    }
    public function send_savings_reminder($customer, $info)
    {
        session(['email' => $customer->email]);
        $data = array(
            'customer' => $customer,
            'loan' => $customer,
            'info' => $info,
        );

        $title = "Savings Reminder";
        $this->general_email_function($data, $info, $title);
    }

    public function send_rollover($customer, $info)
    {
        session(['email' => $customer->email]);
        $data = array(
            'customer' => $customer,
            'loan' => $customer,
            'info' => $info,
        );

        $title = "Loan Penalty";
        $this->general_email_function($data, $info, $title);
    }
    public function reset_password_email($customer, $info)
    {
        session(['email' => $customer->email]);
        $data = array(
            'customer' => $customer,
            'loan' => $customer,
            'info' => $info,
        );
        $title = "Password Reset";
        session(['title' => $title]);
        Mail::send('mail', $data, function ($message) {
            $sender = env('MAIL_USERNAME', 'APP_NAME');
            $app_name = env('APP_NAME', 'APP_NAME');

            $title = session('title');
            $email = session('email');
            $message->to($email, 'SMP EVentures')->subject($title);
            // $message->cc(['smp.eventure@gmail.com']);
            $message->from($sender, $app_name);
        });
        // $this->general_email_function($data, $info, $title);
    }
    public function online_access_email($customer, $info)
    {
        session(['email' => $customer->email]);
        $data = array(
            'customer' => $customer,
            'loan' => $customer,
            'info' => $info,
        );
        $title = "Online Access";
        session(['title' => $title]);
        // Mail::send('mail', $data, function ($message) {
        //     $sender = env('MAIL_USERNAME', 'APP_NAME');
        //     $app_name = env('APP_NAME', 'APP_NAME');

        //     $title = session('title');
        //     $email = session('email');
        //     $message->to($email, 'SMP EVentures')->subject($title);
        //     $message->cc(['smp.eventure@gmail.com']);
        //     $message->from($sender, $app_name);
        // });
        $this->general_email_function($data, $info, $title);
    }

    public function repayment_email($customer, $loan, $info)
    {
        session(['email' => $customer->email]);
        $data = array(
            'customer' => $customer,
            'loan' => $loan,
            'info' => $info,
        );


        $title = "Loan Repayment";
        $this->general_email_function($data, $info, $title);
    }
    public function disbursement_email($customer, $loan, $info)
    {
        session(['email' => $customer->email]);
        $data = array(
            'customer' => $customer,
            'loan' => $loan,
            'info' => $info,
        );

        $title = "Loan Disbursement";
        $this->general_email_function($data, $info, $title);
    }

    public function cash_deposit($customer, $saving, $info)
    {
        session(['email' => $customer->email]);
        $data = array(
            'customer' => $customer,
            'loan' => $saving,
            'info' => $info,
        );


        $title = "Cash Deposit";
        $this->general_email_function($data, $info, $title);
    }
    public function new_branch_email($info)
    {
        $email = "misiatipeter@gmail.com";
        session(['email' => $email]);
        $customer = Customers::where(['email' => $email])->first();
        $data = array(
            'customer' => $customer,
            'email' => $customer->email,
            'info' => $info,
        );


        $title = "Branch Details";
        $this->general_email_function($data, $info, $title);
    }
    public function new_customer_email($customer, $info)
    {

        session(['email' => 'misiatipeter@gmail.com']);
        $data = array(
            'customer' => $customer,
            'email' => $customer->email,
            'info' => $info,
        );


        $title = "Customer Registration";
        $this->general_email_function($data, $info, $title);
    }
    public function new_application_email($customer, $info, $title)
    {
        $emails = ['misiatipeter@gmail.com'];
        $admins = Admins::where(['alerts_on' => true])->get();
        if ($admins) {
            foreach ($admins as $ad) {
                $emails[] = $ad->email;
            }
        }
        $emails = array_unique($emails);
        foreach ($emails as $email) {

            $customer = Admins::where(['email' => $email])->first();
            session(['email' => $email]);
            $data = array(
                'customer' => $customer,
                'email' => $email,
                'info' => $info,
            );
            $this->general_email_function($data, $info, $title);
        }
    }

    public function new_admin_email($customer, $email, $info)
    {
        session(['email' => $email]);
        $data = array(
            'customer' => $customer,
            'email' => $email,
            'info' => $info,
        );

        $title = "Admin Account";
        $this->general_email_function($data, $info, $title);
    }

    public function new_otp_email($customer, $email, $info)
    {
        session(['email' => $email]);
        $data = array(
            'customer' => $customer,
            'email' => $email,
            'info' => $info,
        );

        $title = "Admin OTP";
        $this->general_email_function($data, $info, $title);
    }
    public function student_email($customer, $email, $info, $title)
    {
        session(['email' => $email]);
        $data = array(
            'customer' => $customer,
            'email' => $email,
            'info' => $info,
        );

        $this->general_email_function($data, $info, $title);
    }
    public function test_email($customer, $email, $info)
    {
        session(['email' => $email]);
        session(['content' => $email]);
        $data = array(
            'customer' => $customer,
            'email' => $email,
            'info' => $info,
            'play_url' => 'https:wwww.imeja',
            'ios_url' => '#',
            'pinterest_url' => '#',
            'twitter_url' => '#',
            'instagram_url' => '#',
            'facebook_url' => '#',
            'download_link' => '#',
            'top_logo' => "{{ asset('images/Email-18_Intro.png') }}"
        );
        Mail::send('emails/payment', $data, function ($message) {

            $sender = env('MAIL_USERNAME', 'APP_NAME');
            $app_name = env('APP_NAME', 'APP_NAME');
            $email = session('email');
            $content = session('content');
            $filepath = "payment.pdf";

            $file = fopen($filepath, "w"); //url fopen should be allowed for this to occur
            if (fwrite($file,  json_encode($content)) === FALSE) {
                fwrite("Error: no data written", "\n");
            }

            fwrite($file, "\r\n");
            fclose($file);

            // $filepath = public_path($location . "/" . $filename);
            $message->to($email, 'SMP EVentures')->subject('Cash Payment');
            // $message->attach($filepath);
            $message->from($sender, "SMP EVentures");
        });
    }

    public function new_admin_message_email($customer, $email, $info)
    {
        session(['email' => $email]);
        $data = array(
            'customer' => $customer,
            'email' => $email,
            'info' => $info,
        );
        $title = "Admin Message";
        $this->general_email_function($data, $info, $title);
    }
}
