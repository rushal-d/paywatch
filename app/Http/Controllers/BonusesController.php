<?php

namespace App\Http\Controllers;

use App\Bonuses;
use App\FiscalYearModel;
use App\StafMainMastModel;
use App\SystemOfficeMastModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BonusesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bonuses = Bonuses::with('staff', 'fiscal_year', 'branch')->orderBy('staff_central_id', 'asc');
        $fiscal_year = FiscalYearModel::pluck('fiscal_code', 'id');
        $staffs = StafMainMastModel::select('id', 'name_eng', 'FName_Eng', 'main_id')->get();
        $branch = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $records_per_page_options = config('constants.records_per_page_options');

        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : config('constants.records_per_page');

        if (!empty($request->staff_central_id)) {
            $bonuses = $bonuses->where('staff_central_id', $request->staff_central_id);
        }
        if (!empty($request->fiscal_year_id)) {
            $bonuses = $bonuses->where('fiscal_year_id', $request->fiscal_year_id);
        }
        if (!empty($request->date)) {
            $bonuses = $bonuses->whereDate('received_date', $request->date);
        }
        if (!empty($request->branch_id)) {
            $bonuses = $bonuses->where('branch_id', $request->branch_id);
        }
        $bonuses = $bonuses->paginate($records_per_page);
        return view('bonuses.index', ['bonuses' => $bonuses,
            'title' => 'List of Bonus recieved',
            'staffs' => $staffs, 'fiscal_year' => $fiscal_year, 'branch' => $branch, 'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $title = "Create a bonus";
        $fiscal_year = FiscalYearModel::pluck('fiscal_code', 'id');
        $branch = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $staff_central_id = null;
        $staffs = StafMainMastModel::select('id', 'name_eng', 'FName_Eng', 'main_id')->get();

        return view('bonuses.create', ['title' => $title, 'staffs' => $staffs, 'staff_central_id' => $staff_central_id, 'fiscal_year' => $fiscal_year,
                                            'branch' => $branch]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'staff_central_id' => 'required',
            'fiscal_year_id' => 'required',
//            'branch_id' => 'required',
            'received_date'=>  'required',
            'received_date_np' => 'required',
            'received_amount' => 'required'
        ],
            [
                'staff_central_id.required' => 'You must select one!',
                'fiscal_year_id.required' => 'You must select one!',
//                'branch_id.required' => 'You must select one!',
                'received_date.required' => 'You must enter the date!',
                'received_date_np.required' => 'You must enter the date!',
                'received_amount.required' => 'You must enter the amount!',
            ]
        );
        if($validator->fails()){
            return redirect()->route('bonuses.create')
                ->withInput()
                ->withErrors($validator);
        }else{
            //start transaction to save the date
            try {
                DB::beginTransaction();
            $previousBonus = Bonuses::where('received_date', '=', $request->received_date)
                ->where('fiscal_year_id', '=', $request->fiscal_year_id)
                ->where('staff_central_id', $request->staff_central_id)
                ->first();
            $branch_auto = StafMainMastModel::where('id', $request->staff_central_id)->first();
            if (!empty($previousBonus)) {
                $previousBonus->update(['received_amount' => $request->received_amount]);
                return redirect()->route('bonuses.index');
            }else {
                $bonus = new Bonuses();
                $bonus->staff_central_id = $request->staff_central_id;
                $bonus->fiscal_year_id = $request->fiscal_year_id;
                $bonus->branch_id = $branch_auto->branch_id;
                $bonus->received_date = $request->received_date;
                $bonus->received_date_np = $request->received_date_np;
                $bonus->received_amount = $request->received_amount;
                $bonus->created_by = Auth::user()->id;
                if ($bonus->save()) {
                    $status_mesg = true;
                }
            }
                }catch (Exception $e){
                    DB::rollback();
                    $status_mesg = false;
            }
        }
        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('bonuses.index')->with('flash', array('status' => $status, 'mesg' => $mesg));
    }


    public function listAll(Request $request)
    {
        $branch_id = $request->branch_id;
        $fiscal_year_id = $request->fiscal_year_id;
        $received_date = $request->received_date;
        $received_date_np = $request->received_date_np;
        $branch = SystemOfficeMastModel::where('office_id', $request->branch_id)->first();
        $fiscal_year = FiscalYearModel::where('id', $request->fiscal_year_id)->first();
        $previousBonuses = Bonuses::where('received_date', '=', $request->received_date)
            ->where('fiscal_year_id', '=', $request->fiscal_year_id)
            ->get();
//        dd($previousBonus->count());
        $staffs = StafMainMastModel::with('branch')->where('branch_id', $request->branch_id)->get();
        return view('bonuses.listall', ['title' => 'List of all the staffs', 'staffs' => $staffs, 'fiscal_year' => $fiscal_year,
            'branch' => $branch, 'received_date' => $received_date, 'received_date_np' => $received_date_np,
            'previousBonuses' => $previousBonuses, 'fiscal_year_id' => $fiscal_year_id,
            'branch_id' => $branch_id]);
    }

    public function bulkInsert(Request $request)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'fiscal_year_id' => 'required',
            'received_date' => 'required',
            'received_date_np' => 'required',
            'branch_id' => 'required',
            'bonuses.*' => 'required'
        ],
            [
                'fiscal_year_id.required' => 'You must select one!',
                'branch_id.required' => 'You must select one!',
                'received_date.required' => 'You must enter the date!',
                'received_date_np.required' => 'You must enter the date!',
                'bonuses.*' => 'You must select one',
            ]
        );
        if($validator->fails()){
            return redirect()->route('bonuses.index')
                ->withInput()
                ->withErrors($validator);
        }else {
            try {
                DB::beginTransaction();
                $bonusArray = [];
                foreach ($request->bonuses as $bonus) {
                    if (empty($bonus['received_amount']) || $bonus['received_amount'] <= 0) {
                        continue;
                    }
                    $previousBonus = Bonuses::where('received_date', '=', $request->received_date)
                        ->where('fiscal_year_id', '=', $request->fiscal_year_id)
                        ->where('staff_central_id', $bonus['staff_central_id'])
                        ->first();
                    if (!empty($previousBonus)) {
                        $previousBonus->received_amount = $bonus['received_amount'];
                        $previousBonus->save();
                        continue;
                    }
                    $tempInsertRow['fiscal_year_id'] = $request->fiscal_year_id;
                    $tempInsertRow['staff_central_id'] = $bonus['staff_central_id'];
                    $tempInsertRow['branch_id'] = $request->branch_id;
                    $tempInsertRow['received_amount'] = $bonus['received_amount'];
                    $tempInsertRow['received_date'] = $request->received_date;
                    $tempInsertRow['received_date_np'] = $request->received_date_np;
                    $tempInsertRow['created_by'] = Auth::user()->id;
                    $tempInsertRow['created_at'] = Carbon::now();
                    $bonusArray[] = $tempInsertRow;
                }
                Bonuses::insert($bonusArray);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollback();
            }
        }
//        return json_encode('hello');
        return redirect()->route('bonuses.index')->with('success', 'Created Successfully');
    }

    public function filterView(Request $request)
    {
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $fiscalYears = FiscalYearModel::pluck('fiscal_code', 'id');
        return view('bonuses.filter-view', ['title' => 'Bonuses', 'branches' => $branches, 'fiscalYears' => $fiscalYears]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bonus = Bonuses::where('id', $id)->first();
        $fiscal_year = FiscalYearModel::pluck('fiscal_code', 'id');
        $branch = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $staff_central_id = null;
        if (Auth::user()->hasRole('Employee')) {
            $staff_central_id = Auth::user()->staff_central_id;
            $staffs = StafMainMastModel::select('id', 'name_eng', 'FName_Eng', 'main_id')->where('id', $staff_central_id)->get();
        } else {
            $staffs = StafMainMastModel::select('id', 'name_eng', 'FName_Eng', 'main_id')->get();
        }
        return view('bonuses.edit', ['bonus' => $bonus,
            'title' => 'Edit', 'fiscal_year' => $fiscal_year, 'branch' => $branch,
            'staff_central_id' => $staff_central_id, 'staffs' => $staffs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status_mesg = false;
        $bonus = Bonuses::where('id', $id)->first();
        $bonus->staff_central_id = $request->staff_central_id;
        $bonus->fiscal_year_id = $request->fiscal_year_id;
        $bonus->branch_id = $request->branch_id;
        $bonus->received_date = $request->received_date;
        $bonus->received_date_np = $request->received_date_np;
        $bonus->received_amount = $request->received_amount;
        $bonus->updated_by = Auth::user()->id;
        $bonus->save();
        if ($bonus->save()) {
            $status_mesg = true;
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Updated Successfully' : 'Error Occured! Try Again!';

        return redirect()->route('bonuses.edit', $bonus->id)->with('flash', ['status' => $status, 'mesg' => $mesg]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //soft delete
        if (!empty($request->id)) {
            $bonus = Bonuses::find($request->id);
            $bonus->deleted_by = Auth::user()->id;
            $bonus->deleted_at = Carbon::now();
            if ($bonus->save()) {
                $success = true;
            }
            if ($success) {
                echo 'Successfully Deleted';
            } else {
                echo "Error Deleting";
            }
        }else{
            echo "Error Deleting";
        }
    }

    public function deleteSelected(Request $request)
    {
        $status_mesg = false;
        if(!empty($request->ids)){
            $deletedBy = auth()->id();
            $staffBonuses = Bonuses::whereIn('id', $request->ids)->get();
            //only soft delete
            try{
                $exception = DB::transaction(function () use ($staffBonuses, $deletedBy){
                   foreach($staffBonuses as $bonus){
                       $bonus->deleted_by = $deletedBy;
                       $bonus->deleted_at = Carbon::now();
                       $bonus->save();
                   }
                });
                $status_mesg = is_null($exception) ? true : $exception;
            }catch (Exception $e){
                $status_mesg = false;
            }
        }
        $mesg = ($status_mesg) ? 'Successfully Deleted' : 'Error deleting';
        echo $mesg;
    }
}
