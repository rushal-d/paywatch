<?php

namespace App\Http\Controllers;

use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\LeaveBalance;
use App\OrganizationSetup;
use App\StafMainMastModel;
use App\SystemLeaveMastModel;
use App\SystemOfficeMastModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;

class LeaveBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $organization = OrganizationSetup::first();
        $staffs = new StafMainMastModel();
        $staffs = $staffs->with(['leaveBalance', 'jobposition' => function ($query) {
            $query->orderBy('order');
        }]);
        if (strcasecmp($organization->organization_code, 'bbsm') == 0) {
            $staffs = $staffs->whereIn('staff_type', [0, 1]);
        }
        $staffs = $staffs->paginate($records_per_page);
        $leaves = SystemLeaveMastModel::select('leave_id', 'leave_name')->get();
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        if ($request->export == 1) {
            $data = [
                'title' => 'Leave Balance',
                'staffs' => $staffs,
                'leaves' => $leaves,
                'records_per_page_options' => $records_per_page_options,
                'records_per_page' => $records_per_page,
                'branches' => $branches
            ];
            \Excel::create('Leave Balance', function ($excel) use ($data) {
                $excel->sheet('Leave Balance', function ($sheet) use ($data) {
                    $sheet->loadView('leavebalance.index-table', $data);
                });
            })->download('xlsx');
        }
        return view('leavebalance.index', [
            'title' => 'Leave Balance',
            'staffs' => $staffs,
            'leaves' => $leaves,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'branches' => $branches
        ]);
    }


    public function search(Request $request)
    {
        $organization = OrganizationSetup::first();
        $staffs = new StafMainMastModel();
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');

        if (strcasecmp($organization->organization_code, 'bbsm') == 0) {
            $staffs = $staffs->whereIn('staff_type', [0, 1]);
        }

        if ($request->has('staff_central_id') && !empty($request->staff_central_id)) {
            $staffs = $staffs->where('id', $request->staff_central_id);
        }
        if (!empty($request->branch_id)) {
            $staffs = $staffs->where('branch_id', $request->branch_id);
        }
        $staffs = $staffs->with('leaveBalance')->orderBy('name_eng', 'ASC')->paginate($records_per_page);
        $leaves = SystemLeaveMastModel::select('leave_id', 'leave_name')->where('initial_setup', 1)->get();
        $search_term = $request->search;
        $model = new LeaveBalance();

        //check if has leave from filter
        if ($request->has('leave_id') && !empty($request->leave_id)) {
            $model = $model->whereHas('leave', function ($query) use ($request) {
                $query->where('leave_id', $request->leave_id);
            });
        }
        //check if has Staff
        if ($request->has('staff_central_id') && !empty($request->staff_central_id)) {
            $model = $model->whereHas('staff', function ($query) use ($request) {
                $query->where('id', $request->staff_central_id);
            });
        }
        //check if has starting date
        if ($request->has('date_from') && !empty($request->date_from)) {
            $model = $model->whereDate('date', '>=', date('Y-m-d', strtotime(BSDateHelper::BsToAd('-', $request->date_from))));
        }
        //check if has Ending date
        if ($request->has('date_to') && !empty($request->date_to)) {
            $model = $model->whereDate('date', '<=', date('Y-m-d', strtotime(BSDateHelper::BsToAd('-', $request->date_to))));
        }

        $balances = $model->search($search_term)->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $staff_lists = StafMainMastModel::select('main_id', 'id', 'name_eng')->get();
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        return view('leavebalance.index', [
            'title' => 'All Leave Balance',
            'balances' => $balances,
            'leaves' => $leaves,
            'staffs' => $staffs,
            'staff_lists' => $staff_lists,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'branches' => $branches,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fiscalyear = FiscalYearModel::IsActiveFiscalYear()->pluck('fiscal_code', 'id');
        $status_options = Config::get('constants.status_options');
        $staffs = StafMainMastModel::with('branch')->select('id', 'name_eng', 'FName_Eng', 'main_id', 'staff_central_id', 'branch_id')->take(15)->get();
        $leavetypes = SystemLeaveMastModel::pluck('leave_name', 'leave_id');
        $organization = OrganizationSetup::first();
        return view('leavebalance.create',
            [
                'title' => 'Add Leave Balance',
                'status_options' => $status_options,
                'fiscalyear' => $fiscalyear,
                'staffs' => $staffs,
                'leavetypes' => $leavetypes,
                'organization' => $organization,
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
        $validator = \Validator::make($request->all(), [
            'staff_central_id' => 'required',
            'leave_id' => 'required',
            'date_np' => 'required',
            'date' => 'required',
            'fy_id' => 'required',
            'description' => 'required',
            'consumption' => 'required',
            'earned' => 'required',
            'balance' => 'required',
        ],
            [
                'staff_central_id.required' => 'You must select a staff!',
                'leave_id.required' => 'You must select a leave type!',
                'date_np.required' => 'You must select date!',
                'date.required' => 'You must select date!',
                'fy_id.required' => 'You must select a fiscal year!',
                'description.required' => 'You must enter a description!',
                'consumption.required' => 'You must enter consumption!',
                'earned.required' => 'You must enter earned!',
                'balance.required' => 'You must enter balance!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('leavebalance-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $leavebalance = new LeaveBalance();
                $leavebalance->staff_central_id = $request->staff_central_id;
                $leavebalance->leave_id = $request->leave_id;
                $leavebalance->date_np = $request->date_np;
                $leavebalance->date = $request->date;
                $leavebalance->fy_id = $request->fy_id;
                $leavebalance->description = $request->description;
                $leavebalance->consumption = $request->consumption;
                $leavebalance->earned = $request->earned;
                $leavebalance->balance = $request->balance;
                $leavebalance->authorized_by = \Auth::user()->id;
                if ($leavebalance->save()) {
                    $status_mesg = true;
                }
            } catch (Exception $e) {
                DB::rollback();
                $status_mesg = false;
            }
        }

        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('leavebalance-create')->with('flash', array('status' => $status, 'mesg' => $mesg));

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
        $fiscalyear = FiscalYearModel::pluck('fiscal_code', 'id');
        $leavetypes = SystemLeaveMastModel::pluck('leave_name', 'leave_id');
        $balance = LeaveBalance::find($id);
        $status_options = Config::get('constants.status_options');
        return view('leavebalance.edit', [
            'title' => 'Edit Leave Balance',
            'balance' => $balance,
            'fiscalyear' => $fiscalyear,
            'status_options' => $status_options,
            'leavetypes' => $leavetypes

        ]);
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
        $validator = \Validator::make($request->all(), [
            'staff_central_id' => 'required',
            'leave_id' => 'required',
            'date_np' => 'required',
            'date' => 'required',
            'fy_id' => 'required',
            'description' => 'required',
            'consumption' => 'required',
            'earned' => 'required',
            'balance' => 'required',
        ],
            [
                'staff_central_id.required' => 'You must select a staff!',
                'leave_id.required' => 'You must select a leave type!',
                'date_np.required' => 'You must select date!',
                'date.required' => 'You must select date!',
                'fy_id.required' => 'You must select a fiscal year!',
                'description.required' => 'You must enter a description!',
                'consumption.required' => 'You must enter consumption!',
                'earned.required' => 'You must enter earned!',
                'balance.required' => 'You must enter balance!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('leavebalance-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $leavebalance = LeaveBalance::find($id);
                $leavebalance->staff_central_id = $request->staff_central_id;
                $leavebalance->leave_id = $request->leave_id;
                $leavebalance->date_np = $request->date_np;
                $leavebalance->date = $request->date;
                $leavebalance->fy_id = $request->fy_id;
                $leavebalance->description = $request->description;
                $leavebalance->consumption = $request->consumption;
                $leavebalance->earned = $request->earned;
                $leavebalance->balance = $request->balance;
                $leavebalance->authorized_by = \Auth::user()->id;
                if ($leavebalance->save()) {
                    $status_mesg = true;
                }
            } catch (Exception $e) {
                DB::rollback();
                $status_mesg = false;
            }
        }

        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Updated Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('leavebalance-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $holiday = LeaveBalance::find($request->id);
            if ($holiday->delete()) {
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

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroySelected(Request $request)
    {
        $status_mesg = false;
        if (!empty($request->ids)) {
            $ids = $request->ids;
            //only soft delete
            try {
                //start transaction to prevent unsuccessful deletion
                $exception = DB::transaction(function () use ($ids) {
                    foreach ($ids as $id) {
                        $holiday = LeaveBalance::find($id);
                        $holiday->delete();
                    }
                });
                $status_mesg = is_null($exception) ? true : $exception;
            } catch (Exception $e) {
                $status_mesg = false;
            }
        }
        $mesg = ($status_mesg) ? 'Successfully Deleted' : 'Error deleting!';
        echo $mesg;
    }

    public function getDetails(Request $request)
    {
        $balance = LeaveBalance::where('leave_id', $request->leave_id)->where('staff_central_id', $request->staff_central_id)->get()->last();
        return response()->json([
            'balance' => $balance
        ]);
    }

    public function importLeaveBalance(Request $request)
    {
        $status_mesg = false;
        $path = $request->file('excel_file');
        $datas = Excel::formatDates(true)->load($path, function ($reader) {
        })->all();
        $fiscal_years = FiscalYearModel::get();
        $fiscal_year = $date = $date_np = null;
        try {
            DB::beginTransaction();
            foreach ($datas as $data) {
                if (strcasecmp($data['cid'], "END") != 0) {
                    $leaveBalance = new LeaveBalance();
                    $staff_id = null;
                    foreach ($data as $key => $record) {
                        $leave_id = null;
                        switch ($key) {
                            case "cid":
                                $staffmain = StafMainMastModel::where('staff_central_id', $record)->first();
                                if (!empty($staffmain)) {
                                    $staff_id = $staffmain->id;
                                }
                                break;
                            case "name":
                                break;
                            case "date":
                                $date = date('Y-m-d', strtotime($record));
                                $date_np = BSDateHelper::AdToBs('-', $date);
                                $fiscal_year = $fiscal_years->where('fiscal_start_date', '<=', $date)->where('fiscal_end_date', '>=', $date)->last()->id ?? $fiscal_years->where('fiscal_status', 1)->first()->id ?? 4;

                                break;
                            default:
                                $leave = SystemLeaveMastModel::where('leave_code', 'like', '%' . $key . '%')->first();
                                if (!empty($leave)) {
                                    $leave_id = $leave->leave_id;
                                    $leaveBalance->balance = $record ?? 0;
                                }

                                if (!empty($leave_id) && !empty($staff_id)) {
                                    $leaveBalance->staff_central_id = $staff_id;
                                    $leaveBalance->leave_id = $leave_id;
                                    $leaveBalance->description = "Opening Balance";
                                    $leaveBalance->consumption = 0;
                                    $leaveBalance->earned = 0;
                                    $leaveBalance->authorized_by = Auth::id();
                                    $leaveBalance->fy_id = $fiscal_year;
                                    $leaveBalance->date = $date;
                                    $leaveBalance->date_np = $date_np;
                                    $leaveBalance->save();
                                    $status_mesg = $leaveBalance = new LeaveBalance();
                                }
                                break;
                        }
                    }
                }


            }
        } catch (\Exception $e) {
            $status_mesg = false;
            DB::rollBack();
        }
        if ($status_mesg) {
            DB::commit();
        }

        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Updated Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('leavebalance-index')->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

}
