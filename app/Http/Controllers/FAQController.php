<?php

namespace App\Http\Controllers;

use App\Http\Traits\CommonTrait;
use App\Models\Faq;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    //
    use CommonTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function list_faqs()
    {
        # code... 
        $data['faqs'] = Faq::where('deleted', false)->get();
        return view('faqs.index', $data);
    }

    public function add_faq(Request $request)
    {
        # code...
        $attr = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string'
        ]);
        $question = $attr['question'];
        $answer = $attr['answer'];

        $faq = Faq::create([
            'question'=>$question,
            'answer'=>$answer,
        ]);
        if($faq){

            return redirect()->route('list_faqs')->with('success', 'FAQ added successfully');
        }else{

            return redirect()->route('list_faqs')->with('error', 'Whoops!, Experienced problems, please try again later!!');
        }
    }
}
