<?php

namespace App\Http\Controllers;

use App\FileType;
use App\Helpers\BSDateHelper;
use App\StaffFileModel;
use App\StafMainMastModel;
use App\TrainingDetail;
use App\TrainingDetailFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrainingDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($staff_central_id)
    {
        $trainings = TrainingDetail::where('staff_central_id', $staff_central_id)->get();
        $staffmain = StafMainMastModel::with('branch')->where('id', $staff_central_id)->first();
        return view('staffmain.training.index', [
            'title' => 'Staff Training',
            'trainings' => $trainings,
            'staffmain' => $staffmain,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($staff_central_id)
    {
        $staffmain = StafMainMastModel::with('branch')->where('id', $staff_central_id)->first();
        $file_types = FileType::where('file_section', 'training_documents')->get();
        return view('staffmain.training.create', [
            'title' => 'Training Create',
            'staffmain' => $staffmain,
            'file_types' => $file_types,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $staff_central_id)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'training_organization_name' => 'required',
            'training_title' => 'required'
        ],
            [
                'training_organization_name.required' => 'Organization Name is required',
                'training_title.required' => 'Training Title is required',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('training-detail-create', [$staff_central_id])
                ->withInput()
                ->withErrors($validator);
        } else {
            try {
                DB::beginTransaction();
                $training = new TrainingDetail();
                $training->staff_central_id = $staff_central_id;
                $training->training_organization_name = $request->training_organization_name;
                $training->training_title = $request->training_title;
                $training->training_category = $request->training_category;
                $training->training_start_date_np = $request->training_start_date_np;
                $training->training_start_date = !empty($request->training_start_date_np) ? BSDateHelper::BsToAd('-', $request->training_start_date_np) : null;
                $training->training_end_date_np = $request->training_end_date_np;
                $training->training_end_date = !empty($request->training_end_date_np) ? BSDateHelper::BsToAd('-', $request->training_end_date_np) : null;
                $training->result = $request->result;
                $training->training_main_subject = $request->training_main_subject;
                $training->training_description = $request->training_description;
                $training->created_by = Auth::id();
                $status_mesg = $training->save();

                if (!empty($request->upload)) {
                    foreach ($request->upload as $staff_file_id) {
                        $training_detail_file = new TrainingDetailFile();
                        $training_detail_file->training_detail_id = $training->id;
                        $training_detail_file->staff_file_id = $staff_file_id;
                        $training_detail_file->save();
                    }
                }
            } catch (\Exception $e) {
                DB::rollBack();
                $status_mesg = false;
            }
            if ($status_mesg) {
                DB::commit();
            }
        }

        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('training-detail-create', [$staff_central_id])->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\TrainingDetail $trainingDetail
     * @return \Illuminate\Http\Response
     */
    public function show(TrainingDetail $trainingDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\TrainingDetail $trainingDetail
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $training = TrainingDetail::with(['staff', 'trainingDetailFiles' => function ($query) {
            $query->with('staffFile');
        }])->where('id', $id)->first();

        $file_types = FileType::where('file_section', 'training_documents')->get();
        $staffmain = $training->staff;
        return view('staffmain.training.edit', [
            'title' => 'Training Update',
            'staffmain' => $staffmain,
            'training' => $training,
            'file_types' => $file_types,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\TrainingDetail $trainingDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'training_organization_name' => 'required',
            'training_title' => 'required'
        ],
            [
                'training_organization_name.required' => 'Organization Name is required',
                'training_title.required' => 'Training Title is required',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('training-detail-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            try {
                DB::beginTransaction();
                $training = TrainingDetail::find($id);
                $training->training_organization_name = $request->training_organization_name;
                $training->training_title = $request->training_title;
                $training->training_category = $request->training_category;
                $training->training_start_date_np = $request->training_start_date_np;
                $training->training_start_date = !empty($request->training_start_date_np) ? BSDateHelper::BsToAd('-', $request->training_start_date_np) : null;
                $training->training_end_date_np = $request->training_end_date_np;
                $training->training_end_date = !empty($request->training_end_date_np) ? BSDateHelper::BsToAd('-', $request->training_end_date_np) : null;
                $training->result = $request->result;
                $training->training_main_subject = $request->training_main_subject;
                $training->training_description = $request->training_description;
                $training->created_by = Auth::id();
                $status_mesg = $training->save();
                TrainingDetailFile::where('training_detail_id', $training->id)->delete();
                if (!empty($request->upload)) {
                    foreach ($request->upload as $staff_file_id) {
                        $training_detail_file = new TrainingDetailFile();
                        $training_detail_file->training_detail_id = $training->id;
                        $training_detail_file->staff_file_id = $staff_file_id;
                        $training_detail_file->save();
                    }
                }
            } catch (\Exception $e) {
                DB::rollBack();
            }
            if ($status_mesg) {
                DB::commit();
            }
        }

        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Updated Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('training-detail-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\TrainingDetail $trainingDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();
            if (!empty($request->id)) {
                $training = TrainingDetail::find($request->id);
                $training->deleted_by = Auth::id();
                $training->save();
                $success = $training->delete();
                if ($success) {
                    DB::commit();
                    echo 'Successfully Deleted';
                } else {
                    echo "Error deleting!";
                }
            } else {
                echo "Error deleting!";
            }
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error deleting!";
        }

    }
}
