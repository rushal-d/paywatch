<?php

namespace App\Http\Controllers;

use App\FileType;
use App\Helpers\BSDateHelper;
use App\Http\Requests\LeaveRequestRequest;
use App\LeaveRequest;
use App\OrganizationSetup;
use App\Repositories\AuthRepository;
use App\Repositories\CalenderHolidayRepository;
use App\Repositories\LeaveRequestRepository;
use App\StafMainMastModel;
use App\SystemLeaveMastModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveRequestController extends Controller
{

    /**
     * @var LeaveRequestRepository
     */
    protected $leaveRequestRepository, $calenderHolidayRepository;

    public function __construct(LeaveRequestRepository $leaveRequestRepository, CalenderHolidayRepository $calenderHolidayRepository)
    {
        $this->leaveRequestRepository = $leaveRequestRepository;
        $this->calenderHolidayRepository = $calenderHolidayRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $leaves = SystemLeaveMastModel::select('leave_id', 'leave_name')->get();
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : config('constants.records_per_page');
        $search_term = $request->search;
        $isAdmin = AuthRepository::isAdministrator();
        $leaveRequests = LeaveRequest::with('staff', 'leave')->search($search_term)->latest()->paginate($records_per_page);
        $records_per_page_options = config('constants.records_per_page_options');

        return view('leaverequest.index', [
            'isAdmin' => $isAdmin,
            'title' => 'Leave Request',
            'leaveRequests' => $leaveRequests,
            'records_per_page_options' => $records_per_page_options,
            'leaves' => $leaves,
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
        $leavetypes = SystemLeaveMastModel::pluck('leave_name', 'leave_id');

        $staff_central_id = null;
        if (Auth::user()->hasRole('Employee')) {
            $staff_central_id = Auth::user()->staff_central_id;
            $staffs = StafMainMastModel::select('id', 'name_eng', 'FName_Eng', 'main_id')->where('id', $staff_central_id)->get();
        } else {
            $staffs = StafMainMastModel::select('id', 'name_eng', 'FName_Eng', 'main_id')->get();

        }
        $organization = OrganizationSetup::first();
        $file_types = FileType::where('file_section', 'leave_request_documents')->get();

        return view('leaverequest.create',
            [
                'staff_central_id' => $staff_central_id,
                'staffs' => $staffs,
                'leavetypes' => $leavetypes,
                'organization' => $organization,
                'file_types' => $file_types,
                'title' => 'Add Leave Request Holiday'
            ]);
    }

    public function search(Request $request)
    {
        $staffs = StafMainMastModel::select('id', 'name_eng')->get();
        $leaves = SystemLeaveMastModel::select('leave_id', 'leave_name')->get();
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : config('constants.records_per_page');
        $search_term = $request->search;
        $model = new LeaveRequest();
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
            $model = $model->whereDate('from_leave_day', '>=', date('Y-m-d', strtotime(BSDateHelper::BsToAd('-', $request->date_from))));
        }
        //check if has Ending date
        if ($request->has('date_to') && !empty($request->date_to)) {
            $model = $model->whereDate('to_leave_day', '<=', date('Y-m-d', strtotime(BSDateHelper::BsToAd('-', $request->date_to))));
        }
        $leaveRequests = $model->search($search_term)->latest()->paginate($records_per_page);
        $records_per_page_options = config('constants.records_per_page_options');
        return view('leaverequest.index', [
            'title' => 'Leave Request',
            'leaveRequests' => $leaveRequests,
            'leaves' => $leaves,
            'staffs' => $staffs,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeaveRequestRequest $request)
    {
        $saveStatus = false;

        $organization = OrganizationSetup::first();
        $input = $request->all();
        if ($organization->organization_structure == 2) {
            $response = $this->calenderHolidayRepository->check_conditions($request->leave_id, $request->staff_central_id, $request->from_leave_day_np, $request->to_leave_day_np, $request->is_half,null,$organization);
            if (!$response['status']) {
                return redirect()->route('calender-holiday-create')->withInput()->with('flash', array('status' => 'error', 'mesg' => $response['message']));
            } else {
                $input['weekend_days'] = $response['weekend_day'];
                $input['public_holidays'] = $response['public_holiday'];
                $input['public_weekend'] = $response['public_holiday_weekend'];
                $input['holiday_days'] = $response['holiday_days'];
            }
        }

        try {
            DB::beginTransaction();

            $leaveRequestSaveStatus = $this->leaveRequestRepository->save($input);

            if ($leaveRequestSaveStatus) {
                $saveStatus = true;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $saveStatus = false;
        }

        if ($saveStatus) {
            DB::commit();
        }

        $status = $saveStatus ? 'success' : 'error';
        $mesg = $saveStatus ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('leaverequest-create')->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\LeaveRequest $leaveRequest
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leaveRequest = LeaveRequest::with(['staff', 'leave', 'leaveRequestFiles'=>function($query){
            $query->with('staffFile');
        }])->where('id', $id)->first();
        $file_types = FileType::where('file_section', 'leave_request_documents')->get();
        return view('leaverequest.show', [
            'title' => 'Leave Request',
            'leaveRequest' => $leaveRequest,
            'file_types' => $file_types,
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\LeaveRequest $leaveRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\LeaveRequest $leaveRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $leaveRequest = LeaveRequest::find($request->id);
            if ($leaveRequest->delete()) {
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

    public function approve(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete

            if ($this->leaveRequestRepository->setApprove($request->id)) {
                $success = true;
            }
            if ($success) {
                echo 'Successfully Approved!';
            } else {
                echo "Error approving!";
            }
        } else {
            echo "Error approving!";
        }
    }

    public function reject(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            if ($this->leaveRequestRepository->setReject($request->id)) {
                $success = true;
            }
            if ($success) {
                echo 'Successfully Rejected!';
            } else {
                echo "Error rejecting!";
            }
        } else {
            echo "Error rejecting!";
        }
    }
}
