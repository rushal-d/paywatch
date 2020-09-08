<?php

namespace App\Http\Controllers;

use App\AlternativeDayShift;
use App\Department;
use App\Helpers\BSDateHelper;
use App\Shift;
use App\StaffShiftHistory;
use App\StafMainMastModel;
use App\SystemJobTypeMastModel;
use App\SystemOfficeMastModel;
use App\SystemPostMastModel;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index(Request $request)
    {

        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $shifts = Shift::query();
        if (!empty($search_term)) {
            $shifts = $shifts->search($search_term);
        }
        if (empty($request->show_all)) {
            $shifts = $shifts->where('active', 1);
        }

        if (!empty($request->branch_id)) {
            $shifts = $shifts->where('branch_id', $request->branch_id);
        }

        $shifts = $shifts->latest()->paginate($records_per_page);
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $records_per_page_options = Config::get('constants.records_per_page_options');

        return view('shifts.index', [
            'title' => 'Shift',
            'shifts' => $shifts,
            'branches' => $branches,
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
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        return view('shifts.create',
            [
                'title' => 'Add Shift',
                'branches' => $branches
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
            'shift_name' => 'required',
            'branch_id' => 'required',
        ],
            [
                'shift_name.required' => 'You must enter the shift name!',
                'branch_id.required' => 'You must enter the branch!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $input = Input::all();
                if ((strtotime($input['punch_in_start']) > strtotime($input['punch_in'])) || strtotime($input['punch_in']) > strtotime($input['punch_in_end'])) {
                    //punch in start is greater than punch in end
                    $status = 'error';
                    $mesg = 'Error Occured! Punch In Start is greater than Punch In End!';
                    return redirect()->back()->withInput()->with('flash', array('status' => $status, 'mesg' => $mesg));
                }

                if ((strtotime($input['punch_out_start']) > strtotime($input['punch_out'])) || strtotime($input['punch_out']) > strtotime($input['punch_out_end'])) {
                    //punch out start is greater than punch out end
                    $status = 'error';
                    $mesg = 'Error Occured! Punch Out Start is greater than Punch Out End!';
                    return redirect()->back()->withInput()->with('flash', array('status' => $status, 'mesg' => $mesg));
                }
                $input['before_punch_in_threshold'] = (int)(strtotime($input['punch_in']) - strtotime($input['punch_in_start'])) / 60;
                $input['after_punch_in_threshold'] = (int)(strtotime($input['punch_in_end']) - strtotime($input['punch_in'])) / 60;

                $input['before_punch_out_threshold'] = (int)(strtotime($input['punch_out']) - strtotime($input['punch_out_start'])) / 60;
                $input['after_punch_out_threshold'] = (int)(strtotime($input['punch_out_end']) - strtotime($input['punch_out'])) / 60;

                /*$input['min_tiffin_out'] = date('H:i', strtotime('+60 minutes', strtotime($input['punch_in_start'])));
                $input['max_tiffin_in'] = date('H:i', strtotime('-60 minutes', strtotime($input['punch_out_end'])));

                $input['min_lunch_out'] = date('H:i', strtotime('+60 minutes', strtotime($input['punch_in_start'])));
                $input['max_lunch_in'] = date('H:i', strtotime('-60 minutes', strtotime($input['punch_out_end'])));*/

                $input['before_tiffin_threshold'] = 0;
                $input['after_tiffin_threshold'] = 0;

                $input['before_lunch_threshold'] = 0;
                $input['after_lunch_threshold'] = 0;

                $input['personal_in_out_duration'] = 0;
                $input['personal_in_out_threshold'] = 0;

                $input['sync'] = Shift::sync;
                $input['created_by'] = Auth::user()->id;
                $status_mesg = Shift::create($input);
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
        return redirect()->route('shift-create')->with('flash', array('status' => $status, 'mesg' => $mesg));


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Shift $shift
     * @return \Illuminate\Http\Response
     */
    public function show(Shift $shift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Shift $shift
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shift = Shift::find($id);
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        return view('shifts.edit',
            [
                'title' => 'Edit Shift',
                'branches' => $branches,
                'shift' => $shift,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Shift $shift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'shift_name' => 'required',
            'branch_id' => 'required',
        ],
            [
                'shift_name.required' => 'You must enter the shift name!',
                'branch_id.required' => 'You must enter the branch!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('shift-index')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $input = Input::all();

                if ((strtotime($input['punch_in_start']) > strtotime($input['punch_in'])) || strtotime($input['punch_in']) > strtotime($input['punch_in_end'])) {
                    //punch in start is greater than punch in end
                    $status = 'error';
                    $mesg = 'Error Occured! Punch In Start is greater than Punch In End!';
                    return redirect()->back()->with('flash', array('status' => $status, 'mesg' => $mesg));
                }

                if ((strtotime($input['punch_out_start']) > strtotime($input['punch_out'])) || strtotime($input['punch_out']) > strtotime($input['punch_out_end'])) {
                    //punch out start is greater than punch out end
                    $status = 'error';
                    $mesg = 'Error Occured! Punch Out Start is greater than Punch Out End!';
                    return redirect()->back()->with('flash', array('status' => $status, 'mesg' => $mesg));
                }
                $input['before_punch_in_threshold'] = (int)(strtotime($input['punch_in']) - strtotime($input['punch_in_start'])) / 60;
                $input['after_punch_in_threshold'] = (int)(strtotime($input['punch_in_end']) - strtotime($input['punch_in'])) / 60;

                $input['before_punch_out_threshold'] = (int)(strtotime($input['punch_out']) - strtotime($input['punch_out_start'])) / 60;
                $input['after_punch_out_threshold'] = (int)(strtotime($input['punch_out_end']) - strtotime($input['punch_out'])) / 60;

                /*$input['min_tiffin_out'] = date('H:i', strtotime('+60 minutes', strtotime($input['punch_in_start'])));
                $input['max_tiffin_in'] = date('H:i', strtotime('-60 minutes', strtotime($input['punch_out_end'])));

                $input['min_lunch_out'] = date('H:i', strtotime('+60 minutes', strtotime($input['punch_in_start'])));
                $input['max_lunch_in'] = date('H:i', strtotime('-60 minutes', strtotime($input['punch_out_end'])));*/

                $input['before_tiffin_threshold'] = 0;
                $input['after_tiffin_threshold'] = 0;

                $input['before_lunch_threshold'] = 0;
                $input['after_lunch_threshold'] = 0;

                $input['personal_in_out_duration'] = 0;
                $input['personal_in_out_threshold'] = 0;
                $input['created_by'] = Auth::user()->id;
                $old_shift = Shift::find($id);
                $old_shift->sync = 1;
                $old_shift->active = 0;
                $old_shift->updated_by = Auth::user()->id;
                $old_shift->save();
                $input['parent_id'] = $id;
                $input['sync'] = Shift::sync;
                $shift = Shift::create($input);
                $all_staff_with_prevuious_shift = StafMainMastModel::where('shift_id', $id)->get();
                foreach ($all_staff_with_prevuious_shift as $staff) {
                    $staff->shift_id = $shift->id;
                    $staff->sync = StafMainMastModel::sync;
                    $staff->save();

                    $previous_staff_history = StaffShiftHistory::where('staff_central_id', $staff->id)->latest()->first();
                    if (!empty($previous_staff_history)) {
                        $previous_staff_history->effective_to = date('Y-m-d');
                        $previous_staff_history->updated_by = Auth::id();
                        $previous_staff_history->save();
                    }
                    $staff_shift_history = new StaffShiftHistory();
                    $staff_shift_history->staff_central_id = $staff->id;
                    $staff_shift_history->effective_from = date('Y-m-d');
                    $staff_shift_history->shift_id = $shift->id;
                    $staff_shift_history->created_by = Auth::user()->id;
                    $staff_shift_history->save();

                    //the old shift id in future date which will be effective on the future date:
                    StaffShiftHistory::whereDate('effective_from', '>=', date('Y-m-d'))->where('shift_id', $id)->update(['shift_id' => $shift->id]);
                    AlternativeDayShift::where('shift_id', $id)->update(['shift_id' => $shift->id]);

                }
                $status_mesg = true;
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
        return redirect()->route('shift-index')->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Shift $shift
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $shift = Shift::with('staff')->find($request->id);
            if ($shift->staff->count() == 0) {
                $shift->active = 0;
                $shift->updated_by = Auth::id();
                if ($shift->save()) {
                    $success = true;
                }
                if ($success) {
                    echo 'Successfully Deleted';
                } else {
                    echo "Error deleting!";
                }
            } else {
                echo "Shift Contains Staff! Please change the shift of Staffs to delete shift";
            }

        } else {
            echo "Error deleting!";
        }

    }

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
                        $shift = Shift::with('staff')->find($id);
                        if ($shift->staff->count() == 0) {
                            $shift->active = 0;
                            $shift->updated_by = Auth::id();
                            $shift->save();
                        }
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

    public function by_branch(Request $request)
    {
        $shifts = Shift::where('branch_id', $request->branch);
        if (!empty($request->noScope)) {
            $shifts = $shifts->withoutGlobalScopes();
        }
        $shifts = $shifts->where('active', 1)->get();
        $change_name = array();
        $i = 0;
        foreach ($shifts as $shift) {
            $change_name[$i]['id'] = $shift->id;
            $change_name[$i]['shift_name'] = '(' . date('h:i a', strtotime($shift->punch_in . '-' . $shift->before_punch_in_threshold . ' minutes')) . '/' . date('h:i a', strtotime($shift->punch_in . '+' . $shift->after_punch_in_threshold . ' minutes')) . ')-(' .
                date('h:i a', strtotime($shift->punch_out . '-' . $shift->before_punch_out_threshold . ' minutes')) . '/' . date('h:i a', strtotime($shift->punch_out . '+' . $shift->after_punch_out_threshold . ' minutes')) . ')';
            $change_name[$i]['original_name'] = $shift->shift_name;
            $change_name[$i]['punch_in'] = date('h:i a', strtotime($shift->punch_in));
            $change_name[$i]['punch_out'] = date('h:i a', strtotime($shift->punch_out));
            $change_name[$i]['active'] = $shift->active;
            $i++;
        }

        return response()->json($change_name);
    }

    public function changeShift()
    {
        $data['branches'] = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $data['job_types'] = SystemJobTypeMastModel::pluck('jobtype_name', 'jobtype_id');
        $data['designations'] = SystemPostMastModel::pluck('post_title', 'post_id');
        $data['departments'] = Department::pluck('department_name', 'id');
        $data['title'] = "Filter Staff";
        return view('shifts.change-shift-index', $data);
    }

    public function changeShiftFilter(Request $request)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'branch_id' => 'required',
        ],
            [
                'branch_id.required' => 'You must select Branch!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        $staffs = new StafMainMastModel();
        if (!empty($request->branch_id)) {
            $staffs = $staffs->where('branch_id', $request->branch_id);
        }
        if (!empty($request->job_type)) {
            $staffs = $staffs->where('jobtype_id', $request->job_type);
        }
        if (!empty($request->designation)) {
            $staffs = $staffs->where('post_id', $request->designation);
        }
        if (!empty($request->shift_id)) {
            $staffs = $staffs->where('shift_id', $request->shift_id);
        }

        if (!empty($request->department)) {
            $staffs = $staffs->where('department', $request->department);
        }

        if (!empty($request->main_ids)) {
            $array = explode(',', $request->main_ids);
            $staffs = $staffs->whereIn('main_id', $array);
        }
        $staffs = $staffs->orderby('main_id')->get();

        $data['staffs'] = $staffs;
        $data['i'] = 1;
        if ($staffs->count() > 100) {
            $data['break_count'] = (ceil($staffs->count() / 3));
        } else {
            $data['break_count'] = $staffs->count();
        }
        $data['branches'] = SystemOfficeMastModel::where('office_id', $request->branch_id)->pluck('office_name', 'office_id');
        $data['job_types'] = SystemJobTypeMastModel::pluck('jobtype_name', 'jobtype_id');
        $data['designations'] = SystemPostMastModel::pluck('post_title', 'post_id');
        $data['departments'] = Department::pluck('department_name', 'id');
        $data['title'] = 'Change Staff Shift';

        $data['shifts'] = Shift::where('branch_id', $request->branch_id)->where('active', 1)->pluck('shift_name', 'id');
        return view('shifts.change-shift-create', $data);
    }

    public function changeShiftStore(Request $request)
    {
        $status_mesg = false;
        $validator = \Validator::make($request->all(), [
            'staff_central_id' => 'required',
            'branch_id' => 'required',
            'shift_id' => 'required',
        ],
            [
                'staff_central_id.required' => 'You must select atleast on Staff!',
                'branch_id.required' => 'You must select the branch!',
                'shift_id.required' => 'You must select the shift!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        } else {
            try {
                DB::beginTransaction();
                $input = Input::all();
                $input['effective_from'] = BSDateHelper::BsToAd('-', $input['effective_from']);
                foreach ($input['staff_central_id'] as $staff_id) {
                    $staff = StafMainMastModel::find($staff_id);
                    if ($staff->shift_id != $request->shift_id) {
                        if ($input['effective_from'] == date('Y-m-d')) {
                            $staff->shift_id = $request->shift_id;
                            $staff->sync = StafMainMastModel::sync;
                            $staff->save();
                        }


                        $previous_staff_history = StaffShiftHistory::where('staff_central_id', $staff->id)->latest()->first();
                        if (!empty($previous_staff_history)) {
                            $previous_staff_history->effective_to = $input['effective_from'];
                            $previous_staff_history->updated_by = Auth::id();
                            $previous_staff_history->save();
                        }


                        $staff_shift_history = new StaffShiftHistory();
                        $staff_shift_history->staff_central_id = $staff->id;
                        $staff_shift_history->effective_from = $input['effective_from'];
                        $staff_shift_history->shift_id = $request->shift_id;
                        $staff_shift_history->created_by = Auth::user()->id;
                        $staff_shift_history->save();
                    }
                }
                $status_mesg = true;
            } catch (\Exception $e) {
                DB::rollback();
                $status_mesg = false;
            }
            if ($status_mesg) {
                DB::commit();
            }

            $status = ($status_mesg) ? 'success' : 'error';
            $mesg = ($status_mesg) ? 'Updated Successfully' : 'Error Occured! Try Again!';
            return redirect()->route('change-shift')->with('flash', array('status' => $status, 'mesg' => $mesg));
        }

    }

    public function shiftImportIndex()
    {
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        return view('shifts.excelimport', [
            'title' => 'Excel Import',
            'branches' => $branches
        ]);
    }

    public function shiftImport(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'branch_id' => 'required',
            'excel_file' => 'required',
        ],
            [
                'branch_id.required' => 'You must select the branch!',
                'excel_file.required' => 'You must select a file!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        } else {
            $path = $request->file('excel_file')->getRealPath();
            $datas = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                $reader->formatDates(false);
            })->all();
            $status_mesg = false;
            try {
                DB::beginTransaction();
                foreach ($datas as $data) {
                    if (strcasecmp($data['punch_in_start'], 'END') == 0) {
                        break;
                    }
//                    dd($data);
                    $punch_in_start = date('H:i:s', strtotime($data['punch_in_start']));
                    $tiffin_lunch_in_start = date('H:i:s', strtotime('+60 Min ' . $data['punch_in_start']));
                    $punch_in_end = date('H:i:s', strtotime($data['punch_in_end']));

                    $before_punch_in_threshold = 0;
                    $after_punch_in_threshold = (strtotime($punch_in_end) - strtotime($punch_in_start)) / 60;
                    $punch_out_start = date('H:i:s', strtotime($data['punch_out_start']));
                    $punch_out_end = date('H:i:s', strtotime($data['punch_out_end']));
                    $after_punch_out_threshold = 0;
                    $before_punch_out_threshold = (strtotime($punch_out_end) - strtotime($punch_out_start)) / 60;
//                    dd(($punch_out_start),($punch_out_end));
//                    dd(strtotime($punch_out_start),strtotime($punch_out_end));
                    $shift_name = $punch_in_start . '/' . $punch_in_end . ' - ' . $punch_out_start . '/' . $punch_out_end;

                    $shift = new Shift();
                    $shift->shift_name = $shift_name;
                    $shift->branch_id = $request->branch_id;
                    $shift->punch_in = $punch_in_start;
                    $shift->before_punch_in_threshold = $before_punch_in_threshold;
                    $shift->after_punch_in_threshold = $after_punch_in_threshold;
                    $shift->punch_out = $punch_out_end;
                    $shift->before_punch_out_threshold = $before_punch_out_threshold;
                    $shift->after_punch_out_threshold = $after_punch_out_threshold;
                    $shift->min_tiffin_out = $tiffin_lunch_in_start;
                    $shift->max_tiffin_in = $punch_out_end;
                    $shift->tiffin_duration = $data['tiffin_duration'];
                    $shift->before_tiffin_threshold = 0;
                    $shift->after_tiffin_threshold = 0;
                    $shift->min_lunch_out = $tiffin_lunch_in_start;
                    $shift->max_lunch_in = $punch_out_end;
                    $shift->lunch_duration = $data['lunch_duration'];
                    $shift->before_lunch_threshold = 0;
                    $shift->after_lunch_threshold = 0;
                    $shift->personal_in_out_duration = 0;
                    $shift->personal_in_out_threshold = 0;
                    $shift->save();
                    $status_mesg = true;
                }
            } catch (\Exception $e) {
                DB::rollBack();
                $status_mesg = false;
            }
            if ($status_mesg) {
                DB::commit();
            }


            $status = ($status_mesg) ? 'success' : 'error';
            $mesg = ($status_mesg) ? 'Data Added Successfully' : 'Error Occured! Try Again!';
            return redirect()->route('shift-index')->with('flash', array('status' => $status, 'mesg' => $mesg));
        }

    }

    public function staffShiftImportIndex()
    {
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        return view('shifts.staff-shift-import-index', [
            'title' => 'Excel Import',
            'branches' => $branches
        ]);
    }

    public function staffShiftImport(Request $request)
    {
        /* try {
             DB::beginTransaction();
             $path = $request->file('excel_file')->getRealPath();
             $datas = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                 $reader->formatDates(false);
             })->all();
             $count = 0;
             foreach ($datas as $data) {
                 $staff = StafMainMastModel::where('main_id', $data['emp_id'])->first();
                 $weekend = null;
                 if (!empty($staff)) {
                     if ($data['sun'] == 1) {
                         $weekend = 7;
                     } elseif ($data['mon'] == 1) {
                         $weekend = 1;
                     } elseif ($data['tue'] == 1) {
                         $weekend = 2;
                     } elseif ($data['wed'] == 1) {
                         $weekend = 3;
                     } elseif ($data['thu'] == 1) {
                         $weekend = 4;
                     } elseif ($data['fri'] == 1) {
                         $weekend = 5;
                     } elseif ($data['sat'] == 1) {
                         $weekend = 6;
                     }
                     if (!empty($weekend)) {
                         $count++;
                         StaffWorkScheduleMastModel::where('staff_central_id', $staff->id)->update(['weekend_day' => $weekend]);
                     }
                 }

                 if (!empty($staff)) {
                     $departmentName = $data['department'];
                     $department = Department::whereRaw('lower(department_name) like (?)', ["{$departmentName}"])->first();
                     if (!$department) {
                         $department = Department::create([
                             'department_name' => $data['department'],
                             'created_by' => auth()->id()
                         ]);
                     }

                     $staff->department = $department->id;
                     $staff->save();
                 }
             }
             echo $count;
         } catch (\Exception $e) {
             dd($e);
             DB::rollBack();
         }
         DB::commit();*/


        /*$validator = \Validator::make($request->all(), [
            'branch_id' => 'required',
            'excel_file' => 'required',
        ],
            [
                'branch_id.required' => 'You must select the branch!',
                'excel_file.required' => 'You must select a file!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        } else {
            $path = $request->file('excel_file')->getRealPath();
            $datas = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                $reader->formatDates(false);
            })->all();
            $status_mesg = false;
            try {
                DB::beginTransaction();
                foreach ($datas as $data) {

                    if (strcasecmp($data['main_id'], 'END') == 0) {
                        break;
                    }
                    $punch_in_start = date('H:i:s', strtotime($data['punch_in_start']));
                    $punch_in_end = date('H:i:s', strtotime($data['punch_in_end']));


                    $punch_out_start = date('H:i:s', strtotime($data['punch_out_start']));
                    $punch_out_end = date('H:i:s', strtotime($data['punch_out_end']));

                    $shift = Shift::where([['branch_id', '=', $request->branch_id], ['active', '=', 1], ['punch_in', '=', $punch_in_start], ['punch_out', '=', $punch_out_start]])
                        ->orWhere([['branch_id', '=', $request->branch_id], ['active', '=', 1], ['punch_in', '=', $punch_in_start], ['punch_out', '=', $punch_out_end]])
                        ->orWhere([['branch_id', '=', $request->branch_id], ['active', '=', 1], ['punch_in', '=', $punch_in_end], ['punch_out', '=', $punch_out_start]])
                        ->orWhere([['branch_id', '=', $request->branch_id], ['active', '=', 1], ['punch_in', '=', $punch_in_end], ['punch_out', '=', $punch_out_end]])->first();
                    if (empty($shift)) {
                        DB::rollBack();
                        return redirect()->route('change-shift')->with('flash', array('status' => 'error', 'mesg' => 'No Shift found of Staff ID: ' . $data['main_id']));
                    }
                    StafMainMastModel::where('branch_id', $request->branch_id)->where('main_id', $data['main_id'])->update(['shift_id' => $shift->id]);
                    $staff = StafMainMastModel::where('branch_id', $request->branch_id)->where('main_id', $data['main_id'])->first();

                    if (!empty($staff)) {
                        $previous_staff_history = StaffShiftHistory::where('staff_central_id', $staff->id)->latest()->first();
                        if (!empty($previous_staff_history)) {
                            $previous_staff_history->effective_to = date('Y-m-d');
                            $previous_staff_history->save();
                        }


                        $staff_shift_history = new StaffShiftHistory();
                        $staff_shift_history->staff_central_id = $staff->id;
                        $staff_shift_history->effective_from = date('Y-m-d', strtotime("+1 day"));
                        $staff_shift_history->shift_id = $shift->id;
                        $staff_shift_history->save();
                        $status_mesg = true;
                    }

                }
            } catch (\Exception $e) {
                dd($e);
                DB::rollBack();
                $status_mesg = false;
            }
            if ($status_mesg) {
                DB::commit();
            }


            $status = ($status_mesg) ? 'success' : 'error';
            $mesg = ($status_mesg) ? 'Data Added Successfully' : 'Error Occured! Try Again!';
            return redirect()->route('change-shift')->with('flash', array('status' => $status, 'mesg' => $mesg));
        }*/

        //code to manage leave balance

        /*  $path = $request->file('excel_file')->getRealPath();
          $datas = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
              $reader->formatDates(false);
          })->all();
          foreach ($datas as $data){
              $staff=StafMainMastModel::where('main_id',$data['main_id'])->first();
              if(!empty($staff)){
                  LeaveBalance::where('staff_central_id',$staff->id)->where('leave_id',7)->update(["balance"=>$data['home_leave']]);
                  LeaveBalance::where('staff_central_id',$staff->id)->where('leave_id',8)->update(["balance"=>$data['sick_leave']]);
                  LeaveBalance::where('staff_central_id',$staff->id)->where('leave_id',12)->update(["balance"=>$data['substitute_leave']]);
              }
          }*/

        $path = $request->file('excel_file')->getRealPath();
        $datas = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
            $reader->formatDates(false);
        })->all();
        foreach ($datas as $data) {
            $staffs = StafMainMastModel::where('staff_central_id', $data['staff_central_id'])->get();
            foreach ($staffs as $staff) {
                if (!empty($data['dob_np'])) {
                    if ($this->validateDate($data['dob_np'])) {
                        $date_of_birth = date_parse_from_format('Y-m-d', $data['dob_np']);
                        $staff->date_birth = $date_of_birth['year'] . '-' . $date_of_birth['month'] . '-' . $date_of_birth['day'];
                        $staff->date_birth_np = BSDateHelper::BsToAd('-', $staff->date_birth);
                    }
                }

                if (!empty($data['bank'])) {
                    $staff->bank_id = 1;
                    $staff->acc_no = $data['account_number'];
                } else {
                    $staff->bank_id = null;
                    $staff->acc_no = null;
                }
                $staff->profund_acc_no = $data['profund_account_number'];
                $staff->save();
            }

        }

    }

    function validateDate($date, $format = 'Y/m/d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    public function shiftVisual(Request $request)
    {
        $branch_id = Auth::user()->branch_id;
        if (!empty($request->branch_id)) {
            $branch_id = $request->branch_id;
        }
        $start_time = '7:00 am';
        $end_time = '11:00 pm';
        $total_duration = strtotime($end_time) - strtotime($start_time);
        $total_duration_in_seconds = $total_duration / 60;
        $data = [];
        $shifts = Shift::withCount('staff')->where('branch_id', $branch_id)->where('active', 1)->orderBy('punch_in')->get();
        $i = 0;
        $colors = ['#845EC2', '#2C73D2', '#00C9A7', '#C34A36', '#4E8397', '#8EED89', '#BC5067', '#4373C1', '#008A62', '#00D3E0'];
        foreach ($shifts as $shift) {
            $data[$i]['shift_name'] = $shift->shift_name;
            $punch_in = $shift->punch_in;
            $punch_out = $shift->punch_out;
            $total_shift_duration = ((strtotime($punch_out) - strtotime($punch_in)) / 60);
            $punchin_margin_duration = ((strtotime($punch_in) - strtotime($start_time)) / 60);
            $data[$i]['duration_percentage'] = ($total_shift_duration / $total_duration_in_seconds) * 100;
            $data[$i]['margin_percentage'] = ($punchin_margin_duration / $total_duration_in_seconds) * 100;
            $data[$i]['staff_count'] = $shift->staff_count;
            $data[$i]['shift_id'] = $shift->id;
            $data[$i]['color'] = $colors[$i % 10];
            $i++;
        }
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');

        return view('shifts.visual', [
            'title' => 'Shift Visual',
            'data' => $data,
            'branches' => $branches
        ]);
    }

}
