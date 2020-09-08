<?php

namespace App\Http\Controllers;

use App\Caste;
use App\Http\Requests\CasteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CasteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $castes = Caste::search($search_term)->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('caste.index', [
            'title' => 'Caste',
            'castes' => $castes,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('caste.create',
            [
                'title' => 'Add Caste'
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status_mesg = false;

        //start transaction to save the data
        try {
            //start transaction for rolling back if some problem occurs
            DB::beginTransaction();
            $input = $request->all();
            $input['created_by'] = Auth::id();
            if (Caste::create($input)) {
                $status_mesg = true;
            }
        } catch (Exception $e) {
            DB::rollback();
            $status_mesg = false;
        }

        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('castecreate')->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Caste $caste
     * @return \Illuminate\Http\Response
     */
    public function show(Caste $caste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Caste $caste
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $caste = Caste::find($id);
        return view('caste.edit', [
            'title' => 'Edit Caste',
            'caste' => $caste
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Caste $caste
     * @return \Illuminate\Http\Response
     */
    public function update(CasteRequest $request, $id)
    {
        $status_mesg = false;

        try {
            DB::beginTransaction();
            $caste = Caste::find($id);
            $caste->caste_name = $request->caste_name;
            $caste->description = $request->description;
            $caste->updated_by = Auth::id();
            if ($caste->save()) {
                $status_mesg = true;
            }
        } catch (Exception $e) {
            DB::rollback();
            $status_mesg = false;
        }

        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Updated Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('casteedit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Caste $caste
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $caste = Caste::find($request->id);
            $caste->deleted_by = Auth::id();
            $caste->save();
            if ($caste->delete()) {
                $success = true;
            }
            if ($success) {
                echo 'Successfully Deleted';
            } else {
                echo "Error deleting!";
            }
        } else {
            echo "Error deleting!";
        }
    }
}
