<?php

namespace App\Http\Controllers;

use App\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page') ;
        $search_term = $request->search;
        $sections = Section::search($search_term)->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('section.index', [
            'title' => 'Section',
            'sections' => $sections,
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
        return view('section.create',
            [
                'title' => 'Add Section'
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(),[
            'section_name' => 'required',
        ],
            [
                'section_name.required' => 'You must enter the section name!',
            ]
        );
        if($validator->fails()){
            return redirect()->route('sectioncreate')
                ->withInput()
                ->withErrors($validator);
        }
        else{
            //start transaction to save the data
            try{
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $section = new Section();
                $section->section_name = $request->section_name;
                $section->description = $request->description;
                $section->created_by = Auth::user()->id;
                if($section->save()){
                    $status_mesg = true;
                }
            }
            catch (Exception $e){
                DB::rollback();
                $status_mesg = false;
            }
        }

        if($status_mesg){
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('sectioncreate')->with('flash',array('status'=>$status,'mesg' => $mesg));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $section=Section::find($id);
        return view('section.edit', [
            'title' => 'Edit Section',
            'section' => $section
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(),[
            'section_name' => 'required',
        ],
            [
                'section_name.required' => 'You must enter the section name!',
            ]
        );
        if($validator->fails()){
            return redirect()->route('sectionedit', [$id])
                ->withInput()
                ->withErrors($validator);
        }
        else{
            //start transaction to save the data
            try{
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info
                $section=Section::find($id);
                $section->section_name = $request->section_name;
                $section->description = $request->description;
                $section->updated_by = Auth::id();

                if($section->save()){
                    $status_mesg = true;
                }
            }
            catch (Exception $e){
                DB::rollback();
                $status_mesg = false;
            }
        }

        if($status_mesg){
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Updated Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('sectionedit', [$section->id])->with('flash',array('status'=>$status,'mesg' => $mesg));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(!empty($request->id)){
            //only soft delete
            $section = Section::find($request->id);
            $section->deleted_by=Auth::id();
            $section->save();
            if($section->delete()){
                $success = true;
            }
            if($success){
                echo 'Successfully Deleted';
            }
            else{
                echo "Error deleting!";
            }
        }
        else{
            echo "Error deleting!";
        }
    }

    public function destroySelected(Request $request)
    {
        $status_mesg = false;
        if(!empty($request->ids)){
            $ids = $request->ids;
            //only soft delete
            try{
                //start transaction to prevent unsuccessful deletion
                $exception = DB::transaction(function() use ($ids) {
                    foreach ($ids as $id) {
                        $section = Section::find($id);
                        $section->deleted_by=Auth::id();
                        $section->save();
                        $section->delete();
                    }
                });
                $status_mesg = is_null($exception) ? true : $exception;
            }
            catch(Exception $e) {
                $status_mesg = false;
            }
        }
        $mesg = ($status_mesg) ? 'Successfully Deleted' : 'Error deleting!';
        echo $mesg;
    }
}
