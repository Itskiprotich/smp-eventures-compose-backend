<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class FileUpload extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function uploadContentAlt(Request $request)
    {

        $file = $request->file('uploaded_file');
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $this->checkUploadedFileProperties($extension, $fileSize);
        $location = 'uploads';
        $file->move($location, $filename);
        $filepath = public_path($location . "/" . $filename);
    }
    function csvToArray($filename = '', $delimiter = ',')
    {
        // if (!file_exists($filename) || !is_readable($filename))
        //     return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

    public function github_copilot(Request $request)
    {
        # code...
    }
    public function chat_upload(Request $request)
    {
        $file = $request->file('file');
        if ($file) {
            # code...

            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $location = 'uploads';

            $file->move($location, $filename);
            // In case the uploaded file path is to be stored in the database 
            $filepath = public_path($location . "/" . $filename);
            return redirect()->to('chats')->with('success', 'Attachment Uploaded successfully');
        } else {
            return redirect()->to('chats')->with('error', 'Error Uploading the file');
        }
    }

    public function uploadContent(Request $request)
    {
        $file = $request->file('uploaded_file');
        if ($file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize(); //Get size of uploaded file in bytes
            //Check for file extension and size
            $this->checkUploadedFileProperties($extension, $fileSize);
            //Where uploaded file will be stored on the server 
            $location = 'uploads'; //Created an "uploads" folder for that
            // Upload file
            $file->move($location, $filename);
            // In case the uploaded file path is to be stored in the database 
            $filepath = public_path($location . "/" . $filename);
            // Reading file
            $file = fopen($filepath, "r");
            $customerArr = $this->csvToArray($filepath);
            // dd($customerArr);



            for ($i = 0; $i < count($customerArr); $i++) {
                $member = Customers::latest()->first();
                if ($member) {
                    $add = $member->id;
                } else {
                    $add = 0;
                }
                foreach ($customerArr as $single) {
                    $id = $single['ID'];
                    $firstname = $single['FIRSTNAME'];
                    $lastname = $single['LASTNAME'];
                    $phone = $single['PHONE'];
                    $email = $single['EMAIL'];
                    $national = $single['NATIONAL'];
                    $gender = $single['GENDER'];
                    $loanlimit = $single['LOANLIMIT'];
                    $mem = $add + 1;

                    $fcm = Customers::updateOrCreate(
                        ['email' =>  $email, 'phone' =>  $phone],
                        [
                            'phone' =>  $phone,
                            'firstname' =>  $firstname,
                            'lastname' => $lastname,
                            'phone' => $phone,
                            'email' => $email,
                            'national_id' => $national,
                            'gender' => $gender,
                            'devicename' => 'Upload',
                            'type' => 'Upload',
                            'loanlimit' => $loanlimit,
                            'membership_no' => "J" . $mem . "K",
                            'device_id' => rand(1000, 10000),
                            'password' => Hash::make(rand(1000, 10000))
                        ],
                    );
                }
            }
            return redirect()->to('/customer/pending')->with('success', 'Customer List uploaded successfully!');
        } else {
            //no file was uploaded
            return response()->json([
                'message' => "No file was uploaded"
            ]);
        }
    }
    public function checkUploadedFileProperties($extension, $fileSize)
    {
        $valid_extension = array("csv", "xlsx"); //Only want csv and excel files
        $maxFileSize = 2097152; // Uploaded file size limit is 2mb
        if (in_array(strtolower($extension), $valid_extension)) {
            if ($fileSize <= $maxFileSize) {
            } else {
                throw new \Exception('No file was uploaded', Response::HTTP_REQUEST_ENTITY_TOO_LARGE); //413 error
            }
        } else {
            throw new \Exception('Invalid file extension', Response::HTTP_UNSUPPORTED_MEDIA_TYPE); //415 error
        }
    }
    public function sendEmail($email, $name)
    {
        $data = array(
            'email' => $email,
            'name' => $name,
            'subject' => 'Welcome Message',
        );
        Mail::send('welcomeEmail', $data, function ($message) use ($data) {
            $message->from('welcome@myapp.com');
            $message->to($data['email']);
            $message->subject($data['subject']);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
