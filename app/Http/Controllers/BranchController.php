<?php

namespace App\Http\Controllers;

use App\Http\Traits\CommonTrait;
use App\Models\Admins;
use App\Models\Branch;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{

    use CommonTrait;
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $branches = Branch::all(); //(['active' => true])->get();
        $data['branches'] = $branches;
        return view('branches.index', $data);
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
        $attr = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string|min:6',
        ]);
        $name = $attr['name'];
        $description = $attr['description'];
        $data = Branch::create([
            'name' => $name,
            'description' => $description
        ]);
        return redirect()->route('branch_index');
    }

    function select()
    {
        $user = Auth::user();
        $data['admin'] = Admins::where('email', $user->email)->first();
        $branches = Branch::where(['active' => true])->get();
        $data['branches'] = $branches;
        return view('branches.select', $data);
    }

    public static   function update_branch(Request $request)
    {
        $attr = $request->validate([
            'branch' => 'required|string',
        ]);
        $branch_id = $attr['branch'];
        session(['branch_id' => $branch_id]);

        return redirect()->route('home');
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
        $user = Auth::user();
        $attr = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string|min:6',
        ]);
        $name = $attr['name'];
        $description = $attr['description'];
        $branch = Branch::find($id);
        if ($branch) {
            $branch->name = $name;
            $branch->description = $description;
            $branch->action_by = $user->name;
            $branch->save();

            return redirect()->route('branch_index')->with('success', 'Brach Details updated successfully');
        }
        return redirect()->route('branch_index')->with('error', 'Failed to Update Brach Details');
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
        $user = Auth::user();
        $branch = Branch::find($id);
        if ($branch) {
            $branch->action_by = $user->name;
            $branch->deleted = true;
            $branch->active = false;
            $branch->save();
            return redirect()->route('branch_index')->with('success', 'Brach deativated successfully');
        }
        return redirect()->route('branch_index')->with('error', 'Failed to deactivate Brach');
    }

    public function restore($id)
    {
        $user = Auth::user();
        $branch = Branch::find($id);
        if ($branch) {
            $branch->action_by = $user->name;
            $branch->deleted = false;
            $branch->active = true;
            $branch->save();
            return redirect()->route('branch_index')->with('success', 'Brach deativated successfully');
        }
        return redirect()->route('branch_index')->with('error', 'Failed to deactivate Brach');
    }
    public function recruit($id)
    {
        //
        $user = Auth::user();
        $branch = Branch::find($id);
        if ($branch) {
            $branch->action_by = $user->name;
            $branch->recruit = true;
            $branch->save();

            //get all others except this one

            $branches = Branch::whereNotIn('id', [$id])->get();

            // You can now use the retrieved branches
            foreach ($branches as $br) {
                $br->action_by = $user->name;
                $br->recruit = false;
                $br->save();
            }

            return redirect()->route('branch_index')->with('success', 'Brach recruit details updated successfully');
        }
        return redirect()->route('branch_index')->with('error', 'Failed to update branch details');
    }
}
