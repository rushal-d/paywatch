<?php

namespace App\Http\Controllers;

use App\Religion;
use App\Http\Requests\ReligionRequest;
use App\Repositories\CasteRepository;
use App\Repositories\ReligionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ReligionController extends Controller
{
    /**
     * @var ReligionRepository
     */
    private $religionRepository;

    /**
     * ReligionController constructor.
     * @param ReligionRepository $religionRepository
     */
    public function __construct(ReligionRepository $religionRepository)
    {
        $this->religionRepository = $religionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $religions = Religion::search($search_term)->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('religion.index', [
            'title' => 'Religion',
            'religions' => $religions,
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
        return view('religion.create',
            [
                'title' => 'Add Religion'
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReligionRequest $request)
    {
        $status_mesg = false;

        //start transaction to save the data
        try {
            //start transaction for rolling back if some problem occurs
            DB::beginTransaction();
            $input = $request->all();
            $input['created_by'] = Auth::id();
            if (Religion::create($input)) {
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
        return redirect()->route('religioncreate')->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Religion $religion
     * @return \Illuminate\Http\Response
     */
    public function show(Religion $religion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Religion $religion
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $religion = Religion::find($id);
        return view('religion.edit', [
            'title' => 'Edit Religion',
            'religion' => $religion
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Religion $religion
     * @return \Illuminate\Http\Response
     */
    public function update(ReligionRequest $request, $id)
    {
        $status_mesg = false;

        try {
            DB::beginTransaction();
            $religion = Religion::find($id);
            $religion->religion_name = $request->religion_name;
            $religion->description = $request->description;
            $religion->updated_by = Auth::id();
            if ($religion->save()) {
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
        return redirect()->route('religionedit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Religion $religion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $religion = Religion::find($request->id);
            $religion->deleted_by = Auth::id();
            $religion->save();
            if ($religion->delete()) {
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
