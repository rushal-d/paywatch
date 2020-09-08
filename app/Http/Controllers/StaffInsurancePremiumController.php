<?php

namespace App\Http\Controllers;

use App\FiscalYearModel;
use App\StaffInsurancePremium;
use App\StafMainMastModel;
use App\SystemOfficeMastModel;
use Carbon\Carbon;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffInsurancePremiumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $staffInsurances = StaffInsurancePremium::with('staff', 'fiscal_year', 'branch')->orderBy('staff_central_id', 'asc');

        $records_per_page_options = config('constants.records_per_page_options');
        $fiscal_year = FiscalYearModel::pluck('fiscal_code', 'id');
        $staffs = StafMainMastModel::select('id', 'name_eng', 'FName_Eng', 'main_id')->get();
        $branch = SystemOfficeMastModel::pluck('office_name', 'office_id');

        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : config('constants.records_per_page');

        if(!empty($request->staff_central_id)) {
            $staffInsurances = $staffInsurances->where('staff_central_id', $request->staff_central_id);
        }
        if(!empty($request->fiscal_year_id)){
            $staffInsurances = $staffInsurances->where('fiscal_year_id', $request->fiscal_year_id);
        }
        if(!empty($request->branch_id)){
            $staffInsurances = $staffInsurances->where('branch_id', $request->branch_id);
        }
        $staffInsurances = $staffInsurances->paginate($records_per_page);
        return view('staffinsurancepremium.index', [
                        'title' => 'Staff Insurance Premium', 'staffInsurances' => $staffInsurances,
                        'records_per_page_options' => $records_per_page_options, 'records_per_page' => $records_per_page,
                        'fiscal_year' => $fiscal_year, 'staffs' => $staffs, 'branch' => $branch]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $staffs = StafMainMastModel::select('id', 'name_eng', 'FName_Eng', 'main_id')->get();
        $fiscal_year = FiscalYearModel::pluck('fiscal_code', 'id');
        $branch = SystemOfficeMastModel::pluck('office_name', 'office_id');
        return view('staffinsurancepremium.create', [
            'title' => "Enter Staff Insurance Premium",
            'staffs' => $staffs,
            'fiscal_year' => $fiscal_year,
            'branch' => $branch
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
        $validator = \Validator::make($request->all(), [
            'staff_central_id' => 'required',
//            'branch_id' => 'required',
            'fiscal_year_id' => 'required',
            'premium_amount' => 'required'
        ],
            [
                'staff_central_id.required' => 'You must select one!',
//                'branch_id.required' => 'You must select one!',
                'fiscal_year_id.required' => 'You must select one!',
                'premium_amount.required' => 'You must enter the amount!'
            ]
        );
        if($validator->fails()){
            return redirect()->route('staff-insurance-premium-create')
                ->withInput()
                ->withErrors($validator);
        }else{
            try{
                DB::beginTransaction();
                $previousPremium = StaffInsurancePremium::where('fiscal_year_id', $request->fiscal_year_id)
                    ->where('staff_central_id', $request->staff_central_id)
                    ->first();
                $branch_auto = StafMainMastModel::where('id', $request->staff_central_id)->first();
                if(empty($previousPremium)){
                    $premium = new StaffInsurancePremium();
                    $premium->staff_central_id = $request->staff_central_id;
                    $premium->branch_id = $branch_auto->branch_id;
                    $premium->fiscal_year_id = $request->fiscal_year_id;
                    $premium->premium_amount = $request->premium_amount;
                    $premium->created_by = Auth::user()->id;
                    if ($premium->save()){
                        $status_mesg = true;
                    }
                }else{
                    $status_mesg = false;
                }
            }catch(Exception $e){
                DB::rollBack();
                $status_mesg = false;
            }
        }
        if ($status_mesg){
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Duplicate Record Found!';
        return redirect()->route('staff-insurance-premium-index')->with('flash', array('status' => $status, 'mesg' => $mesg));
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $premium = StaffInsurancePremium::where('id', $id)->first();
        $fiscal_year = FiscalYearModel::pluck('fiscal_code', 'id');
        $branch = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $staff_central_id = null;
        if (Auth::user()->hasRole('Employee')) {
            $staff_central_id = Auth::user()->staff_central_id;
            $staffs = StafMainMastModel::select('id', 'name_eng', 'FName_Eng', 'main_id')->where('id', $staff_central_id)->get();
        } else {
            $staffs = StafMainMastModel::select('id', 'name_eng', 'FName_Eng', 'main_id')->get();
        }
        return view('staffinsurancepremium.edit', [
           'title' => "Edit",
           'premium' => $premium,
           'fiscal_year' => $fiscal_year,
            'branch' => $branch,
            'staff_central_id' => $staff_central_id,
            'staffs' => $staffs
        ]);
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
        $status_mesg = false;
        $premium = StaffInsurancePremium::where('id', $id)->first();
        $previousPremium = StaffInsurancePremium::where('fiscal_year_id', $request->fiscal_year_id)
            ->where('staff_central_id', $request->staff_central_id)
            ->where('id', '<>', $premium->id)
            ->first();
        if(empty($previousPremium)){
            $premium->staff_central_id = $request->staff_central_id;
            $premium->branch_id = $request->branch_id;
            $premium->fiscal_year_id = $request->fiscal_year_id;
            $premium->premium_amount = $request->premium_amount;
            $premium->updated_by = Auth::user()->id;
            if($premium->save()){
                $status_mesg = true;
            }
        }else{
            $status_mesg = false;
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Updated Successfully' : 'Error Occured! Check for duplicate records!';

        return redirect()->route('staff-insurance-premium-index')->with('flash', ['status' => $status, 'mesg' => $mesg]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //soft delete
        if(!empty($request->id)){
            $premium = StaffInsurancePremium::find($request->id);
            $premium->deleted_by = Auth::user()->id;
            $premium->deleted_at = Carbon::now();
            if($premium->save()){
                $success = true;
            }
            if($success){
                echo 'Successfully Deleted';
            }else{
                echo 'Error Deleting';
            }
        }else{
            echo 'Error Deleting';
        }
    }

    public function deleteSelected(Request $request)
    {
        $status_mesg = false;
        if(!empty($request->ids)){
            $deletedBy = auth()->id();
            $staffInsurances = StaffInsurancePremium::whereIn('id', $request->ids)->get();
            //only soft delete
            try{
                $exception = DB::transaction(function () use ($staffInsurances, $deletedBy){
                    foreach($staffInsurances as $insurance){
                        $insurance->deleted_by = $deletedBy;
                        $insurance->deleted_at = Carbon::now();
                        $insurance->save();
                    }
                });
                $status_mesg = is_null($exception) ? true : $exception;
            }catch(Exception $e){
                $status_mesg = false;
            }
        }
        $mesg = ($status_mesg) ? 'Successfully Deleted' : 'Error Deleting';
        echo $mesg;
    }
}
