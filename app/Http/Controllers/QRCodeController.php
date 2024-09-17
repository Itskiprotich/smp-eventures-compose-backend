<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $attr = $request->validate([
            'url' => 'required|string',

        ]);

        $url=$attr['url'];
    //    $url= base64_decode($url);
        //
          $qrCode = QrCode::size(150)
        // ->format('png')
        // ->merge(storage_path('app/arms.png')) // Assuming the arms.png file is in the storage/app directory
        ->errorCorrection('M')
        ->generate($url);

    // Convert the image to base64
      $base64Image = base64_encode($qrCode);

    // Prepare API response
    $response = [
        'success' => true,
        'data' => [
            'qr_code' => $base64Image,
            'message' => 'QR code generated successfully',
        ],
    ];

    // Convert the response to JSON and send it
    return response()->json($response);
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
