<?php

namespace App\Http\Controllers;

use App\FileType;
use App\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class FileTypeController extends Controller
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
        $fileTypes = FileType::search($search_term)->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('filetype.index', [
            'title' => 'File Type',
            'fileTypes' => $fileTypes,
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
        $file_sections=Config::get('constants.file_sections') ;
        return view('filetype.create',
            [
                'title' => 'Add File Type',
                'file_sections' => $file_sections
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
            'file_type' => 'required',
            'file_section' => 'required',
        ],
            [
                'file_type.required' => 'You must enter the file type title!',
                'file_section.required' => 'You must select the file type section!',
            ]
        );
        if($validator->fails()){
            return redirect()->route('file-typecreate')
                ->withInput()
                ->withErrors($validator);
        }
        else{
            //start transaction to save the data
            try{
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $fileType = new FileType();
                $fileType->file_type = $request->file_type;
                $fileType->file_section = $request->file_section;
                $fileType->description = $request->description;
                $fileType->created_by = Auth::user()->id;
                if($fileType->save()){
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
        return redirect()->route('file-typecreate')->with('flash',array('status'=>$status,'mesg' => $mesg));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FileType  $fileType
     * @return \Illuminate\Http\Response
     */
    public function show(FileType $fileType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FileType  $fileType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fileType=FileType::find($id);
        $file_sections=Config::get('constants.file_sections') ;
        return view('filetype.edit', [
            'title' => 'Edit File Type',
            'fileType' => $fileType,
            'file_sections' => $file_sections
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FileType  $fileType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(),[
            'file_type' => 'required',
            'file_section' => 'required',
        ],
            [
                'file_type.required' => 'You must enter the file type title!',
                'file_section.required' => 'You must select the file type section!',
            ]
        );
        if($validator->fails()){
            return redirect()->route('file-typeedit', [$id])
                ->withInput()
                ->withErrors($validator);
        }
        else{
            //start transaction to save the data
            try{
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info
                $fileType=FileType::find($id);
                $fileType->file_type = $request->file_type;
                $fileType->file_section = $request->file_section;
                $fileType->description = $request->description;
                $fileType->updated_by = Auth::id();

                if($fileType->save()){
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
        return redirect()->route('file-typeedit', [$id])->with('flash',array('status'=>$status,'mesg' => $mesg));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FileType  $fileType
     * @return \Illuminate\Http\Response
     */
    public function destroy(FileType $fileType)
    {
        //
    }
}
