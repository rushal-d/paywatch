<?php

namespace App\Http\Controllers;

use App\AllowanceModelMast;
use App\BankMastModel;
use App\Caste;
use App\Department;
use App\District;
use App\Education;
use App\EmployeeStatus;
use App\FetchAttendance;
use App\FileType;
use App\FiscalYearAttendanceSum;
use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\HouseLoanDiffIncome;
use App\HouseLoanModelMast;
use App\LeaveBalance;
use App\OrganizationSetup;
use App\PayrollDetailModel;
use App\Picture;
use App\Religion;
use App\Repositories\DepartmentRepository;
use App\Repositories\FetchAttendanceRepository;
use App\Repositories\FiscalYearRepository;
use App\Repositories\ShiftRepository;
use App\Repositories\StafMainMastRepository;
use App\Repositories\SystemJobTypeMastRepository;
use App\Repositories\SystemOfficeMastRepository;
use App\Repositories\SystemPostMastRepository;
use App\Section;
use App\Shift;
use App\StaffFileModel;
use App\StaffGrade;
use App\StaffInsurancePremium;
use App\StaffNomineeMastModel;
use App\StaffPaymentMast;
use App\StaffSalaryModel;
use App\StaffShiftHistory;
use App\StaffTransferModel;
use App\StaffType;
use App\StaffWorkScheduleMastModel;
use App\StafMainMastModel;
use App\SundryBalance;
use App\SundryTransaction;
use App\SundryTransactionLog;
use App\SundryType;
use App\SystemJobTypeMastModel;
use App\SystemLeaveMastModel;
use App\SystemOfficeMastModel;
use App\SystemPostMastModel;
use App\Traits\AppUtils;
use App\Traits\PayrollStaff;
use App\VehicalLoanModelTrans;
use App\VehicleLoanDiffIncome;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;

class StaffMainMastController extends Controller
{
    use AppUtils, PayrollStaff;

    private $systemJobTypeMastRepository;
    private $fetchAttendanceRepository;
    private $systemPostMastRepository;
    private $shiftRepository;
    private $departmentRepository;
    private $fiscalYearRepository;
    private $systemOfficeMastRepository;
    private $stafMainMastRepository;

    public function __construct(
        DepartmentRepository $departmentRepository,
        SystemOfficeMastRepository $systemOfficeMastRepository,
        FiscalYearRepository $fiscalYearRepository,
        SystemJobTypeMastRepository $systemJobTypeMastRepository,
        FetchAttendanceRepository $fetchAttendanceRepository,
        SystemPostMastRepository $systemPostMastRepository,
        ShiftRepository $shiftRepository,
        StafMainMastRepository $stafMainMastRepository
    )
    {
        $this->middleware("auth");
        $this->departmentRepository = $departmentRepository;
        $this->systemOfficeMastRepository = $systemOfficeMastRepository;
        $this->fiscalYearRepository = $fiscalYearRepository;
        $this->systemJobTypeMastRepository = $systemJobTypeMastRepository;
        $this->fetchAttendanceRepository = $fetchAttendanceRepository;
        $this->systemPostMastRepository = $systemPostMastRepository;
        $this->shiftRepository = $shiftRepository;
        $this->stafMainMastRepository = $stafMainMastRepository;
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
        $weekend_days = Config::get('constants.weekend_days');
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        $departments = $this->departmentRepository->getAllDepartments();
        $shifts = $this->shiftRepository->getAllShiftsByBranch($request->branch_id)->pluck('shift_name', 'id');
        $jobTypes = $this->systemJobTypeMastRepository->getAllJobTypes()->pluck('jobtype_name', 'jobtype_id');
        $designations = $this->systemPostMastRepository->getAllPosts()->pluck('post_title', 'post_id');
        $staff_types = StaffType::pluck('staff_type_title', 'staff_type_code');
        $staffmains = StafMainMastModel::with(['jobposition' => function ($query) {
            $query->select('post_id', 'post_title');
        }, 'jobtype' => function ($query) {
            $query->select('jobtype_id', 'jobtype_name');
        }, 'latestWorkSchedule' => function ($query) {
            $query->select('work_id', 'staff_central_id', 'weekend_day');
        }, 'branch' => function ($query) {
            $query->select('office_id', 'office_name');
        }, 'shift' => function ($query) {
            $query->select('id', 'shift_name');
        }, 'payrollBranch' => function ($query) {
            $query->select('office_id', 'office_name');
        }, 'fingerprint' => function ($query) {
            $query->select('id', 'staff_central_id');
        }]);

        if (isset($request->staff_central_id)) {
            $staffmains->where('id', $request->staff_central_id);
        }

        if (isset($request->job_type_id)) {
            $staffmains->where('jobtype_id', $request->job_type_id);
        }
        if (isset($request->department_id)) {
            $staffmains->where('department', $request->department_id);
        }

        if (isset($request->branch_id)) {
            $staffmains->where('branch_id', $request->branch_id);
        }

        if (isset($request->designation_id)) {
            $staffmains->where('post_id', $request->designation_id);
            $staffmains->where('post_id', $request->designation_id);
        }

        if (isset($request->shift_id)) {
            $staffmains->where('shift_id', $request->shift_id);
        }

        if (isset($request->staff_type)) {
            $staffmains->whereIn('staff_type', $request->staff_type);
        }
        if (!isset($request->show_inactive)) {
            $staffmains->where('staff_status', '=', 1);
        }

        if (isset($request->has_fingerprint)) {
            if ($request->has_fingerprint == 1) {
                $staffmains->whereHas('fingerprint');
            } else {
                $staffmains->doesntHave('fingerprint');
            }
        }

        $staffmains = $staffmains->orderBy('main_id', 'desc')->search($search_term)->paginate($records_per_page);
        $records_per_page_options = Config::get('constants.records_per_page_options');
        return view('staffmain.index', [
            'title' => 'Staff Main',
            'staffmains' => $staffmains,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page,
            'weekend_days' => $weekend_days,
            'branches' => $branches,
            'departments' => $departments,
            'shifts' => $shifts,
            'jobTypes' => $jobTypes,
            'designations' => $designations,
            'staff_types' => $staff_types,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $educations = Education::pluck('edu_description', 'edu_id');
        $districts = District::select('district_id', 'district_name')->orderBy('district_name', 'asc')->groupBy('district_id')->pluck("district_name", "district_id");
        $religions = Religion::pluck('religion_name', 'id');
        $castes = Caste::pluck('caste_name', 'id');
        //get default allowances
        $weekend_days = Config::get('constants.weekend_days');
        $sections = Section::pluck('section_name', 'id');
        $departments = Department::pluck('department_name', 'id');
        $staff_types = StaffType::pluck('staff_type_title', 'staff_type_code');

        $offices = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $posts = SystemPostMastModel::pluck('post_title', 'post_id');
        $new_branch_id = StafMainMastModel::where('branch_id', Auth::user()->branch_id)->where('main_id', '<', 5000)->max('main_id') + 1;
        $job_types = SystemJobTypeMastModel::pluck('jobtype_name', 'jobtype_id');
        $organizationSetup = OrganizationSetup::first();

        //Basic Salary can be displayed only for contract and contract 1
        $jobTypeIdsForDisplayingBasicSalary = [SystemJobTypeMastModel::JOB_TYPE_FOR_CONTRACT, SystemJobTypeMastModel::JOB_TYPE_FOR_CONTRACT_1];

        return view('staffmain.create',
            [
                'title' => 'Add Staff',
                'educations' => $educations,
                'religions' => $religions,
                'castes' => $castes,
                'districts' => $districts,
                'sections' => $sections,
                'departments' => $departments,
                'weekend_days' => $weekend_days,
                'staff_types' => $staff_types,
                'offices' => $offices,
                'posts' => $posts,
                'new_branch_id' => $new_branch_id,
                'job_types' => $job_types,
                'organizationSetup' => $organizationSetup,
                'jobTypeIdsForDisplayingBasicSalary' => $jobTypeIdsForDisplayingBasicSalary
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
            'name_eng' => 'required',
            'shift_id' => 'required',
            'main_id' => 'required',
            'staff_central_id' => 'nullable|unique:staff_main_mast,staff_central_id',
//            'work_start_on_branch_from' => 'required',
            'appo_date_np' => 'required',
            'post_id' => 'required',
            'jobtype_id' => 'required',
        ],
            [
                'name_eng.required' => 'You must enter the Full Name!',
                'shift_id.required' => 'You must enter the Staff Shift!',
                'main_id.required' => 'You must enter the Branch ID!',
                'post_id.required' => 'You must select post of the staff!',
                'staff_central_id.unique' => 'You must enter the unqiue Staff Central ID',
//                'work_start_on_branch_from.required' => 'You must enter the work start on branch from',
                'appo_date_np.required' => 'You must enter the appointment date',
                'jobtype_id.required' => 'You must select job type of the staff!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('staff-main-create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //Validation for appointment date and work start on branch from for staff transfer records

            /* $_appo_date_en = Carbon::parse(BSDateHelper::BsToAd('-', $request->appo_date_np));
             $_work_start_on_branch_from_en = Carbon::parse(BSDateHelper::BsToAd('-', $request->work_start_on_branch_from));

             $diff_appo_date_with_work_start_on_branch_from = $_work_start_on_branch_from_en->diffInDays($_appo_date_en, false);


             if ($diff_appo_date_with_work_start_on_branch_from > 0) {
                 return redirect()->back()->withInput()->withErrors([
                     'You must enter appointment date lesser or equal to work start on branch from'
                 ]);
             }

             if ($diff_appo_date_with_work_start_on_branch_from !== 0 && (empty($request->previous_working_branch_id) || $request->previous_working_branch_id == $request->branch_id)) {
                 return redirect()->back()->withInput()->withErrors([
                     'You must select different previous working branch if staff has worked in another branch. Or appointment date must be same with work start on branch from.'
                 ]);
             }*/


            //start transaction to save the data

            $organizationSetup = OrganizationSetup::first();

            try {
                $checkBranchMainID = StafMainMastModel::where('branch_id', $request->branch_id)->where('main_id', $request->main_id)->exists();
                if ($checkBranchMainID) {
                    return redirect()->back()->withInput()->withErrors(['main_id' => 'Main ID ' . $request->main_id . ' Has already been taken']);
                }
                if (!empty($request->staff_central_id)) {
                    $checkCentralID = StafMainMastModel::where('staff_central_id', $request->staff_central_id)->exists();
                    if ($checkCentralID) {
                        return redirect()->back()->withInput()->with('flash', array('status' => 'error', 'mesg' => 'Staff Centra; ID ' . $request->staff_central_id . ' Has already been taken'));
                    }
                }
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $staffmain = new StafMainMastModel();
                $staffmain->name_eng = $request->name_eng;
                $staffmain->FName_Eng = $request->FName_Eng;
                $staffmain->gfname_eng = $request->gfname_eng;
                $staffmain->spname_eng = $request->spname_eng;
                $staffmain->district_id = $request->show_vdc;
                $staffmain->ward_no = $request->ward_no;
                $staffmain->tole_basti = $request->tole_basti;
                $staffmain->marrid_stat = $request->marrid_stat;
                $staffmain->Gender = $request->Gender;
                $staffmain->edu_id = $request->edu_id;
                $staffmain->post_id = $request->post_id;
                $staffmain->date_birth = $request->date_birth;
                $staffmain->appo_date_np = $request->appo_date_np;
                $staffmain->appo_date = !empty($request->appo_date_np) ? BSDateHelper::BsToAd('-', $request->appo_date_np) : null;
                $staffmain->branch_id = $request->branch_id;
                $staffmain->payroll_branch_id = $request->branch_id;
                $staffmain->shift_id = $request->shift_id;
                $staffmain->staff_type = $request->staff_type;
                $staffmain->caste_id = $request->caste_id;
                $staffmain->religion_id = $request->religion_id;
                $staffmain->staff_status = 1;
                $staffmain->main_id = $request->main_id;
                $staffmain->staff_central_id = $request->staff_central_id;
                $staffmain->jobtype_id = $request->jobtype_id;
                $staffmain->section = $request->section;
                $staffmain->department = $request->department;
                $staffmain->staff_dob = BSDateHelper::BsToAd('-', $request->staff_dob);
                $staffmain->staff_citizen_no = $request->staff_citizen_no;
                $staffmain->staff_citizen_issue_office = $request->staff_citizen_issue_office;
                $staffmain->staff_citizen_issue_date_np = $request->staff_citizen_issue_date_np;
                $staffmain->phone_number = $request->phone_number;
                $staffmain->emergency_phone_number = $request->emergency_phone_number;
                $staffmain->temporary_con_date_np = $request->appo_date_np;
                $staffmain->temporary_con_date = !empty($request->appo_date_np) ? BSDateHelper::BsToAd('-', $request->appo_date_np) : null;
                $staffmain->sync = 1;
                $staffmain->created_by = Auth::user()->id;
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $filename = rand() . time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path() . '/Images/', $filename);
                    $staffmain->image = $filename;
                }
                $status_mesg = $staffmain->save();
                $staff_id = $staffmain->id;

                //now save the staff central id to docs table
                if (!empty($request->upload)) {
                    foreach ($request->upload as $filename) {
                        $staff_file = new StaffFileModel();
                        $staff_file->staff_central_id = $staff_id;
                        $staff_file->file_name = $filename;
                        $staff_file->created_by = Auth::user()->id;
                        $staff_file->url = 'staff/uploads/' . $filename;
                        $staff_file->save();
                    }
                }

                /*staff shift history*/
                if (!empty($request->shift_id)) {
                    $staff_shift_history = new StaffShiftHistory();
                    $staff_shift_history->staff_central_id = $staffmain->id;
                    $staff_shift_history->shift_id = $request->shift_id;
                    $staff_shift_history->created_by = Auth::user()->id;
                    $staff_shift_history->effective_from = BSDateHelper::BsToAd('-', $request->appo_date_np);
                    $staff_shift_history->save();
                }
                if ($staffmain->staff_type == 0 || $staffmain->staff_type == 1) {
                    $staffTransfer = new StaffTransferModel();
                    $staffTransfer->from_date_np = $request->appo_date_np;
                    $staffTransfer->from_date = BSDateHelper::BsToAd('-', $request->appo_date_np);
                    $staffTransfer->staff_central_id = $staffmain->id;
                    $staffTransfer->transfer_date = null;
                    $staffTransfer->transfer_date_np = null;
                    $staffTransfer->office_from = $staffmain->branch_id;
                    $staffTransfer->autho_id = auth()->id();
                    $staffTransfer->save();

                    if (!empty($staffmain->post_id)) {
                        $staffsalary = new StaffSalaryModel();
                        $staffsalary->staff_central_id = $staff_id;
                        $staffsalary->post_id = $staffmain->post_id;

                        $jobType = SystemJobTypeMastModel::find($staffmain->jobtype_id);
                        $post = SystemPostMastModel::find($staffmain->post_id);
                        $staffsalary->basic_salary = $post->basic_salary;
                        if (strcasecmp($organizationSetup->organization_code, 'bbsm') == 0 && (strcasecmp($jobType->jobtype_code, "Con") == 0 || strcasecmp($jobType->jobtype_code, "Con1") == 0)) {
                            $staffsalary->basic_salary = $request->basic_salary ?? $jobType->basic_salary;
                        }
                        $staffsalary->add_salary_amount = 0;
                        $staffmain->total_grade_amount = 0;
                        $staffsalary->total_grade_amount = 0;
                        $staffsalary->add_grade_this_fiscal_year = 0;
                        $staffsalary->salary_effected_date = BSDateHelper::BsToAd('-', $request->appo_date_np);
                        $staffsalary->salary_effected_date_np = $request->appo_date_np;

                        $fiscal_year_id = FiscalYearModel::where('fiscal_start_date', '<=', $staffsalary->salary_effected_date)->where('fiscal_end_date', '>=', $staffsalary->salary_effected_date)->first()->id ?? null;
                        $staffsalary->fiscal_year_id = $fiscal_year_id;
                        $staffsalary->created_by = Auth::user()->id;
                        $status_mesg = $staffsalary->save();
                    }
                }


                $staff_workschedule = new StaffWorkScheduleMastModel();
                $staff_workschedule->staff_central_id = $staff_id;
                $staff_workschedule->work_hour = Config::get('constants.working_hour');
                $staff_workschedule->weekend_day = $request->weekend_day ?? 7;
                $staff_workschedule->effect_day = BSDateHelper::BsToAd('-', $request->appo_date_np);
                $staff_workschedule->effect_date_np = $request->appo_date_np;
                $staff_workschedule->work_status = 'A';
                $staff_workschedule->created_by = Auth::user()->id;
                $status_mesg = $staff_workschedule->save();

            } catch (\Exception $e) {
                DB::rollback();
                $status_mesg = false;
            }
        }

        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';

        if (!$status_mesg) {
            return redirect()->back()->withErrors([$mesg])->withInput();
        }

        return redirect()->route('staff-main-edit', $staffmain->id)->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    public function staffNominee($id)
    {
        $staff_nominee = StaffNomineeMastModel::where('staff_central_id', $id)->latest()->first();
        $staff = StafMainMastModel::with('staffFiles')->where('id', $id)->first();

        $file_types = FileType::where('file_section', 'nominee')->get();

        return view('staffmain.staffnominee', [
            'staff_nominee' => $staff_nominee,
            'staffmain' => $staff,
            'file_types' => $file_types,
            'title' => 'Staff Nominee'
        ]);
    }

    public function staffNomineeStore(Request $request, $id)
    {
        $staff_nominee = new StaffNomineeMastModel();
        $staff_nominee->staff_central_id = $id;
        $staff_nominee->appli_date = BSDateHelper::BsToAd('-', $request->appli_date_np);
        $staff_nominee->appli_date_np = $request->appli_date_np;
        $staff_nominee->relation = $request->relation;
        $staff_nominee->nominee_name = $request->nominee_name;
        $staff_nominee->dob = $request->dob;
        $staff_nominee->citizen_no = $request->citizen_no;
        $staff_nominee->issue_office = $request->issue_office;
        $staff_nominee->issue_date = BSDateHelper::BsToAd('-', $request->issue_date_np);
        $staff_nominee->issue_date_np = $request->issue_date_np;
        $staff_nominee->created_by = Auth::user()->id;
        $status_mesg = $staff_nominee->save();
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->back()->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    public function staffPayment($id)
    {
        $organizationSetup = OrganizationSetup::first();
        $staffmain = StafMainMastModel::find($id);
        $allowances = AllowanceModelMast::all();
        $banks = BankMastModel::pluck('bank_name', 'id');
        return view('staffmain.staffpayment', [
            'title' => 'Staff Payment',
            'staffmain' => $staffmain,
            'allowances' => $allowances,
            'banks' => $banks,
            'organizationSetup' => $organizationSetup
        ]);
    }

    public function staffPaymentStore(Request $request, $id)
    {

        if (!empty($request->acc_no) && empty($request->bank_id)) {
            return redirect()->back()->withInput()->withErrors([
                'Please choose a bank of provided account number.'
            ]);
        }
        if (empty($request->acc_no) && !empty($request->bank_id)) {
            return redirect()->back()->withInput()->withErrors([
                'Please enter bank account number for the selected bank.'
            ]);
        }
        $organizationSetup = OrganizationSetup::first();
        try {
            DB::beginTransaction();
            $status_mesg = false;
            $staffmain = StafMainMastModel::find($id);
            $staffmain->profund_acc_no = $request->profund_acc_no;
            $staffmain->social_security_fund_acc_no = $request->social_security_fund_acc_no;
            $staffmain->pan_number = $request->pan_number;
            $staffmain->bank_id = $request->bank_id;
            $staffmain->acc_no = $request->acc_no;
            $staffmain->deduct_levy = $request->deduct_levy;
            if (strcasecmp($organizationSetup->organization_code, 'bbsm') !== 0) {
                $staffmain->default_cit_deduction_amount = $request->default_cit_deduction_amount;
            }
            $staffmain->updated_by = Auth::id();
            $status_mesg = $staffmain->save();
            foreach ($request->allowance as $key => $allowance) {
                if (isset($allowance['amount']) || isset($allowance['allow'])) {
                    //check if payroll generated for the input date
                    $effective_date_en = BSDateHelper::BsToAd('-', $allowance['effective_date']);
                    $payroll_exists = PayrollDetailModel::where('branch_id', $staffmain->branch_id)->where('from_date', '<=', $effective_date_en)->where('to_date', '>=', $effective_date_en)->exists();
                    if (!$payroll_exists) {
                        $staff_payment = StaffPaymentMast::where('staff_central_id', $id)->where('allow_id', $key)->whereDate('effective_from', $effective_date_en)->orderBy('effective_from', 'desc')->first();
                        $prev_staff_payment = StaffPaymentMast::where('staff_central_id', $id)->where('allow_id', $key)->whereDate('effective_from', '<', $effective_date_en)->orderBy('effective_from', 'desc')->first();
                        if (empty($staff_payment)) {
                            //create new record
                            $staff_payment = new StaffPaymentMast();
                            $staff_payment->created_by = Auth::id();
                        } else {
                            $staff_payment->updated_by = Auth::id();
                        }
                        if (!empty($prev_staff_payment)) {
                            $prev_staff_payment->effective_to = date('Y-m-d', strtotime('-1 day', strtotime($effective_date_en)));
                            $prev_staff_payment->effective_to_np = BSDateHelper::AdToBs('-', $prev_staff_payment->effective_to);
                            $prev_staff_payment->updated_by = Auth::id();
                            $prev_staff_payment->save();

                        }
                        $staff_payment->staff_central_id = $id;
                        $staff_payment->allow_id = $key;
                        if (strcasecmp($organizationSetup->organization_code, 'bbsm') == 0) {
                            if ($key == 1) {
                                $staffmain->dearness_allowance_amount = isset($allowance['allow']) ? ($allowance['amount'] ?? 0) : 0;
                            } elseif ($key == 2) {
                                $staffmain->risk_allowance_amount = isset($allowance['allow']) ? ($allowance['amount'] ?? 0) : 0;
                            } elseif ($key == 3) {
                                $staffmain->special_allowance_amount = isset($allowance['allow']) ? ($allowance['amount'] ?? 0) : 0;
                            } elseif ($key == 4) {
                                $staffmain->special_allowance_2_amount = isset($allowance['allow']) ? ($allowance['amount'] ?? 0) : 0;
                            } elseif ($key == 5) {
                                $staffmain->other_allowance_amount = isset($allowance['allow']) ? ($allowance['amount'] ?? 0) : 0;
                            } elseif ($key == 6) {
                                $staffmain->incentive_amount = isset($allowance['allow']) ? ($allowance['amount'] ?? 0) : 0;
                            } elseif ($key == 7) {
                                $staffmain->outstation_facility_amount = isset($allowance['allow']) ? ($allowance['amount'] ?? 0) : 0;
                            } elseif ($key == 8) {
                                $staffmain->extra_allow = $allowance['allow'] ?? 0;
                            } elseif ($key == 9) {
                                $staffmain->dashain_allow = $allowance['allow'] ?? 0;
                            }
                            $staffmain->save();
                        }
                        $staff_payment->allow = $allowance['allow'] ?? 0;
                        $staff_payment->amount = $allowance['amount'] ?? 0;
                        $staff_payment->effective_from = $effective_date_en;
                        $staff_payment->effective_from_np = $allowance['effective_date'];
                        $status_mesg = $staff_payment->save();
                    } else {
                        DB::rollBack();
                        $status = 'error';
                        $mesg = "Payroll For the date exists! You can not change the payment of the staff on the payroll generated month's data";
                        return redirect()->back()->with('flash', array('status' => $status, 'mesg' => $mesg));
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $status_mesg = false;
        }
        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->back()->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    public function staffPaymentDelete(Request $request)
    {
        if (!empty($request->id)) {
            $staffPayement = StaffPaymentMast::find($request->id);
            $staffPayement->deleted_by = Auth::id();
            $staffPayement->save();
            $success = $staffPayement->delete();
            if ($success) {
                echo 'Successfully Deleted';
            } else {
                echo "Error deleting!";
            }
        } else {
            echo "Error deleting!";
        }
    }

    public function staffJobInformation($id)
    {
        $staffmain = StafMainMastModel::find($id);
        $staffTypes = StaffType::select('staff_type_title', 'id', 'staff_type_code')->get();
        $educations = Education::pluck('edu_description', 'edu_id');
        $posts = SystemPostMastModel::pluck('post_title', 'post_id');
        $jobtypes = SystemJobTypeMastModel::pluck('jobtype_name', 'jobtype_id');
        $permanent_job_type_id = SystemJobTypeMastModel::where('jobtype_code', 'P')->first()->jobtype_id ?? null; // for frontend validation - if selected permanent type permanent date is compulsory
        $offices = SystemOfficeMastModel::pluck('office_name', 'office_id');

        $shifts = Shift::where('branch_id', $staffmain->branch_id)->where('active', 1)->get();
        $sections = Section::pluck('section_name', 'id');
        $departments = Department::pluck('department_name', 'id');
        $organization = OrganizationSetup::first();
        return view('staffmain.jobinformation', [
            'title' => 'Staff Job Information Detail',
            'staffmain' => $staffmain,
            'educations' => $educations,
            'posts' => $posts,
            'jobtypes' => $jobtypes,
            'offices' => $offices,
            'staffTypes' => $staffTypes,
            'sections' => $sections,
            'departments' => $departments,
            'shifts' => $shifts,
            'permanent_job_type_id' => $permanent_job_type_id,
            'organization' => $organization,
        ]);
    }

    public function staffJobInformationStore(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'staff_type' => 'required',
            'jobtype_id' => 'required',
            'appo_date_np' => 'required',
//            'temporary_con_date_np' => 'required',
            'post_id' => 'required',
            'shift_id' => 'required',
            'appo_office' => 'required',
        ],
            [
                'staff_type.required' => 'Select One Staff Type',
                'jobtype_id.required' => 'Select One Job Type',
                'appo_date_np.required' => 'Appointment Date is required',
                'temporary_con_date_np.required' => 'Temp/Contract Date is required',
                'post_id.required' => 'Select One Post/Designation',
                'shift_id.required' => 'Select at least on Shift',
                'appo_office.required' => 'Select an appointment office'
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
        $error = [];
        //validation check permanent date if the job type is permanent
        $job_type = SystemJobTypeMastModel::find($request->jobtype_id);
        if ($job_type->jobtype_code === "P") {
            if (empty($request->permanent_date_np)) {
                $error[] = "Permanent Date is required if job type is Permanent";

            }
        }
        //validation if the appointment date is greater than the temp contraat date
        if(!empty($request->appo_date_np) && !empty($request->temporary_con_date_np)){
            if (strtotime(BSDateHelper::BsToAd('-', $request->appo_date_np)) > strtotime(BSDateHelper::BsToAd('-', $request->temporary_con_date_np))) {
                $error[] = "Appointment Date is greater than Temp/Contract Date";
            }
        }

        if (!empty($request->permanent_date_np) && !empty($request->temporary_con_date_np)) {
            if (strtotime(BSDateHelper::BsToAd('-', $request->permanent_date_np)) < strtotime(BSDateHelper::BsToAd('-', $request->temporary_con_date_np))) {
                $error[] = "Temp/Contract Date is greater than Permanent Date";
            }
        }
        if (count($error) > 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors($error);

        }
        try {
            DB::beginTransaction();
            $staffmain = StafMainMastModel::find($id);
            $staffmain->edu_id = $request->edu_id;
            $previousPostId = $staffmain->post_id;
            $staffmain->post_id = $request->post_id;
            //It store staff type code
            $staffmain->staff_type = $request->staff_type;
            $staffmain->jobtype_id = $request->jobtype_id;
            $staffmain->appo_date_np = $request->appo_date_np;
            if (!empty($staffmain->appo_date_np)) {
                $staffmain->appo_date = BSDateHelper::BsToAd('-', $staffmain->appo_date_np);
            }

            $staffmain->temporary_con_date_np = $request->temporary_con_date_np;
            if (!empty($staffmain->temporary_con_date_np)) {
                $staffmain->temporary_con_date = BSDateHelper::BsToAd('-', $staffmain->temporary_con_date_np);
            }

            $staffmain->permanent_date_np = $request->permanent_date_np;

            if (!empty($staffmain->permanent_date_np)) {
                $staffmain->permanent_date = BSDateHelper::BsToAd('-', $staffmain->permanent_date_np);
            } else {
                $staffmain->permanent_date = null;
            }

            $staffmain->appo_office = $request->appo_office;
            $staffmain->section = $request->section;
            $staffmain->department = $request->department;
            if (!empty($request->manual_attendance_enable)) {
                $staffmain->manual_attendance_enable = $request->manual_attendance_enable;
            } else {
                $staffmain->manual_attendance_enable = 0;
            }
            $staffmain->is_overtime_payable = $request->is_overtime_payable ?? 0;
            $staffmain->is_holding = $request->is_holding ?? 0;
            $staffmain->sync = 1;
            if ($staffmain->shift_id != $request->shift_id) {
                $previous_staff_history = StaffShiftHistory::where('staff_central_id', $staffmain->id)->latest()->first();
                if (!empty($previous_staff_history)) {
                    $previous_staff_history->effective_to = date('Y-m-d');
                    $previous_staff_history->updated_by = Auth::id();
                    $previous_staff_history->save();
                }

                $staff_shift_history = new StaffShiftHistory();
                $staff_shift_history->staff_central_id = $staffmain->id;
                $staff_shift_history->shift_id = $request->shift_id;
                $staff_shift_history->effective_from = date('Y-m-d', strtotime("+1 day"));
                $staff_shift_history->created_by = Auth::id();
                $staff_shift_history->save();
            }
            if ($previousPostId != $request->post_id) {
                if (strcasecmp($staffmain->jobtype->jobtype_code, "Con") != 0 && strcasecmp($staffmain->jobtype->jobtype_code, "Con1") != 0) {
                    $previous_staff_salary = StaffSalaryModel::where('staff_central_id', $id)->whereDate('salary_effected_date', '<=', date('Y-m-d'))->orderByDesc('salary_effected_date')->first();
                    $new_staff_salary = new StaffSalaryModel();
                    $new_staff_salary->staff_central_id = $id;
                    $new_staff_salary->post_id = $request->post_id;
                    $new_staff_salary->basic_salary = $request->post_id;
                    $post = SystemPostMastModel::find($request->post_id);
                    $new_staff_salary->basic_salary = $post->basic_salary;
                    $new_staff_salary->add_salary_amount = $previous_staff_salary->add_salary_amount ?? 0; //copied previous grade amount and inserted into new record
                    $new_staff_salary->total_grade_amount = $previous_staff_salary->total_grade_amount ?? 0;
                    $new_staff_salary->add_grade_this_fiscal_year = $previous_staff_salary->add_grade_this_fiscal_year ?? 0;
                    $new_staff_salary->salary_effected_date_np = BSDateHelper::AdToBs('-', date('Y-m-d'));
                    $new_staff_salary->salary_effected_date = date('Y-m-d');
                    $new_staff_salary->salary_payment_status = $previous_staff_salary->salary_payment_status ?? 0;
                    $fiscal_year_id = FiscalYearModel::where('fiscal_start_date', '<=', $new_staff_salary->salary_effected_date)->where('fiscal_end_date', '>=', $new_staff_salary->salary_effected_date)->first()->id ?? null;
                    $new_staff_salary->fiscal_year_id = $fiscal_year_id;
                    $new_staff_salary->created_by = Auth::user()->id;
                    $new_staff_salary->save();
                }

            }

            $staffmain->shift_id = $request->shift_id;
            $staffmain->updated_by = Auth::id();
            $status_mesg = $staffmain->save();


            $staffTransfer = StaffTransferModel::where('staff_central_id', $id)->orderBy('from_date', 'ASC')->first();
            if ($staffmain->staff_type == 0 || $staffmain->staff_type == 1) {

                if (empty($staffTransfer)) {
                    $staffTransfer = new StaffTransferModel();
                    $staffTransfer->office_from = $request->appo_office;
                    $staffTransfer->staff_central_id = $staffmain->id;
                    $staffTransfer->from_date = $staffmain->appo_date;
                    $staffTransfer->from_date_np = BSDateHelper::AdToBs('-', $staffmain->appo_date);
                    $staffTransfer->save();
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
        return redirect()->back()->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    public function staffWorkschedule($id)
    {
        $staffmain = StafMainMastModel::find($id);
        $staff_workschedule = StaffWorkScheduleMastModel::where('staff_central_id', $id)->latest()->first();
        $weekend_days = Config::get('constants.weekend_days');
        return view('staffmain.workschedule', [
            'title' => 'Work Schedule',
            'staffmain' => $staffmain,
            'staff_workschedule' => $staff_workschedule,
            'weekend_days' => $weekend_days,
        ]);
    }

    public function staffWorkscheduleStore(Request $request, $id)
    {
        $staff_workschedule = new StaffWorkScheduleMastModel();
        $staff_workschedule->staff_central_id = $id;
        $staff_workschedule->work_hour = $request->work_hour;
        $staff_workschedule->max_work_hour = $request->max_work_hour;
        $staff_workschedule->weekend_day = $request->weekend_day;
        $staff_workschedule->effect_day = BSDateHelper::BsToAd('-', $request->effect_date_np);
        $staff_workschedule->effect_date_np = $request->effect_date_np;
        $staff_workschedule->work_status = $request->work_status;
        $staff_workschedule->created_by = Auth::id();
        $status_mesg = $staff_workschedule->save();

        $staff = StafMainMastModel::find($id);
        $staff->sync = 1;
        $staff->save();

        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->back()->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    public function staffLeaveBalance($id)
    {
        $staff_main = StafMainMastModel::find($id);
        $system_leaves = SystemLeaveMastModel::where('initial_setup', 1)->get();
        return view('staffmain.leavebalance', [
            'title' => 'Staff Leave Balance',
            'staffmain' => $staff_main,
            'system_leaves' => $system_leaves,
        ]);

    }

    public function staffLeaveBalanceStore(Request $request, $id)
    {
        $status_mesg = true;
        if (!empty($request->leave)) {
            foreach ($request->leave as $leave_id => $leave) {
                $active_fiscal_year = FiscalYearModel::where('fiscal_status', 1)->first();
                $leave_balance = new LeaveBalance();
                $leave_balance->staff_central_id = $id;
                $leave_balance->leave_id = $leave_id;
                $leave_balance->fy_id = $active_fiscal_year->id;
                $leave_balance->description = "During Staff Create";
                $leave_balance->consumption = 0;
                $leave_balance->earned = $leave;
                $leave_balance->balance = $leave;
                $leave_balance->date = date('Y-m-d');
                $leave_balance->date_np = BSDateHelper::AdToBs('-', $leave_balance->date);
                $leave_balance->authorized_by = Auth::id();
                $status_mesg = $leave_balance->save();
            }
        }

        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->back()->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    public function staffSalary($id)
    {
        $staffmain = StafMainMastModel::find($id);
        $staff_salary = StaffSalaryModel::where('staff_central_id', $id)->where('salary_effected_date', '<=', date('Y-m-d'))->orderByDesc('salary_effected_date')->first();
        $staff_salaries = StaffSalaryModel::where('staff_central_id', $id)->orderByDesc('salary_effected_date')->get();
        return view('staffmain.staffsalary', [
            'title' => 'Staff Salary',
            'staffmain' => $staffmain,
            'staff_salary' => $staff_salary,
            'staff_salaries' => $staff_salaries,
            'i' => 1,
        ]);
    }

    public function staffSalaryStore(Request $request, $id)
    {
        $staffsalarycheck = StaffSalaryModel::where('staff_central_id', $id)->orderBy('created_at', 'desc')->first();
        if (empty($staffsalarycheck) || (
                $request->add_salary_amount != $staffsalarycheck->add_salary_amount ||
                $request->salary_payment_status != $staffsalarycheck->salary_payment_status ||
                $request->salary_effected_date_np != $staffsalarycheck->salary_effected_date_np)) {


            $staffmain = StafMainMastModel::with('additionalSalary')->where('id', $id)->first();

            $staffsalary = new StaffSalaryModel();
            $staffsalary->staff_central_id = $id;

            $staffsalary->post_id = $staffmain->post_id;
            $post = SystemPostMastModel::find($staffmain->post_id);

            if (!empty($staffmain->jobtype) && strcasecmp($staffmain->jobtype->jobtype_code, "Con") != 0 && strcasecmp($staffmain->jobtype->jobtype_code, "Con1") != 0) {
                $staffsalary->basic_salary = $post->basic_salary;
            } else {
                $staffsalary->basic_salary = $request->basic_salary ?? $post->basic_salary;
            }
            $staffsalary->add_salary_amount = $request->add_salary_amount;
            $staffsalary->total_grade_amount = $staffmain->additionalSalary->last()->total_grade_amount ?? 0;
            $staffsalary->salary_effected_date_np = $request->salary_effected_date_np;
            $staffsalary->salary_effected_date = BSDateHelper::BsToAd('-', $request->salary_effected_date_np);
            $fiscal_year_id = FiscalYearModel::where('fiscal_start_date', '<=', $staffsalary->salary_effected_date)->where('fiscal_end_date', '>=', $staffsalary->salary_effected_date)->first()->id ?? null;
            $staffsalary->add_grade_this_fiscal_year = $staffmain->additionalSalary->where('fiscal_year_id', $fiscal_year_id)->where('salary_effected_date', '<=', $staffsalary->salary_effected_date)->sortByDesc('salary_effected_date')->first()->add_grade_this_fiscal_year ?? 0;
            $staffsalary->fiscal_year_id = $fiscal_year_id;
            $staffsalary->salary_payment_status = $request->salary_payment_status;
            $staffsalary->created_by = Auth::user()->id;

            $staffmain->total_grade_amount = $staffsalary->total_grade_amount + $staffsalary->add_grade_this_fiscal_year;
            $staffmain->updated_by = Auth::id();
            $staffmain->save();
            $status_mesg = $staffsalary->save();

            $status = ($status_mesg) ? 'success' : 'error';
            $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
            return redirect()->back()->with('flash', array('status' => $status, 'mesg' => $mesg));
        } else {
            return redirect()->back();
        }
    }

    public function deleteStaffSalary(Request $request)
    {
        if (!empty($request->id)) {
            $staff_salary = StaffSalaryModel::find($request->id);
            if ($staff_salary->salary_effected_date > date('Y-m-d')) {
                $success = StaffSalaryModel::destroy($request->id);
                if ($success) {
                    echo 'Successfully Deleted';
                } else {
                    echo "Error deleting!";
                }
            } else {
                $count_staff_salary = StaffSalaryModel::where('staff_central_id', $staff_salary->staff_central_id)->where('salary_effected_date', '<=', date('Y-m-d'))->count();
                if ($count_staff_salary > 1) {
                    $salary_for_del = StaffSalaryModel::find($request->id);
                    $salary_for_del->deleted_by = Auth::id();
                    $salary_for_del->save();
                    $success = $salary_for_del->delete();
                    if ($success) {
                        echo 'Successfully Deleted';
                    } else {
                        echo "Error deleting!";
                    }
                } else {
                    echo "Error deleting! You cannot delete this because this is the only effective salary record.";
                }
            }
        } else {
            echo "Error deleting!";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $weekend_days = Config::get('constants.weekend_days');
        $banks = BankMastModel::select('id', 'bank_name')->get();
        $educations = Education::select('edu_id', 'edu_description')->get();
        $posts = SystemPostMastModel::select('post_id', 'post_title')->get();
        $jobtypes = SystemJobTypeMastModel::select('jobtype_id', 'jobtype_name')->get();
        $offices = SystemOfficeMastModel::select('office_id', 'office_name', 'office_location')->get();
        $districts = District::select('district_id', 'district_name')->groupBy('district_id')->get();
        $staffmain = StafMainMastModel::with([
            "salary" => function ($query) use ($id) {
                $query->latest()->first();
            },
            "nominee" => function ($query) use ($id) {
                $query->latest()->first();
            },
            "workschedule" => function ($query) use ($id) {
                $query->latest()->first();
            }])->find($id);
        $shifts = Shift::where('branch_id', $staffmain->branch_id)->pluck('shift_name', 'id')->toArray();

        //get default allowances
        $dearness_allowance = $this->getAllowanceByID(0);
        $extra_allowance = $this->getAllowanceByID(1);
        $special_allowance = $this->getAllowanceByID(2);
        $gratuity_allowance = $this->getAllowanceByID(3);
        $risk_allowance = $this->getAllowanceByID(4);
        $dashain_allowance = $this->getAllowanceByID(5);
        $other_allowance = $this->getAllowanceByID(6);
        $special_allowance_2 = $this->getAllowanceByID(7);
        $outstation_facility = $this->getAllowanceByID(8);
        $incentive = $this->getAllowanceByID(9);
        $sections = Section::pluck('section_name', 'id');
        $departments = Department::pluck('department_name', 'id');
        $nominee = StaffNomineeMastModel::where('staff_central_id', $id)->latest()->first();
        $religions = Religion::pluck('religion_name', 'id');
        $castes = Caste::pluck('caste_name', 'id');
        return view('staffmain.edit', [
            'title' => 'Edit Staff Detail',
            'staffmain' => $staffmain,
            'educations' => $educations,
            'posts' => $posts,
            'religions' => $religions,
            'castes' => $castes,
            'jobtypes' => $jobtypes,
            'offices' => $offices,
            'districts' => $districts,
            'banks' => $banks,
//            'jobtype_options' => $jobtype_options,
            'nominee' => $nominee,
            'weekend_days' => $weekend_days,
            'dearness_allowance' => $dearness_allowance,
            'extra_allowance' => $extra_allowance,
            'special_allowance' => $special_allowance,
            'gratuity_allowance' => $gratuity_allowance,
            'risk_allowance' => $risk_allowance,
            'dashain_allowance' => $dashain_allowance,
            'other_allowance' => $other_allowance,
            'special_allowance_2' => $special_allowance_2,
            'outstation_facility' => $outstation_facility,
            'incentive' => $incentive,
            'sections' => $sections,
            'departments' => $departments,
            'shifts' => $shifts
        ]);
    }

    public function uploadFile(Request $request)
    {
        $filename = '';
        $uploaded_file = false;
        $file = $request->file('track');
        // generate a new filename. getClientOriginalExtension() for the file extension
        $filename = $file->getClientOriginalName();
        $primary_name = pathinfo($filename, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $filename = 'file-' . $primary_name . '-' . time() . '.' . $extension;
        if (!empty($request->staff_id) && !empty($request->file_type_id)) {
            // generate a new filename. getClientOriginalExtension() for the file extension

            $staff_detail = StafMainMastModel::find($request->staff_id);

            if (empty($staff_detail->uuid)) {
                $staff_detail->uuid = Str::uuid();
                $staff_detail->save();
            }
            $directory = 'staff/' . $staff_detail->uuid . ' ' . $staff_detail->id;
            if (!is_dir($directory)) {
                mkdir($directory, 0775, true);
            }
            // save to storage/app/ as the new $filename
            $path = $file->storeAs($directory, $filename);

            $staff_file = new StaffFileModel();
            $staff_file->staff_central_id = $staff_detail->id;
            $staff_file->file_name = $filename;
            $staff_file->url = $directory . '/' . $filename;
            $staff_file->file_type_id = $request->file_type_id;
            $staff_file->created_by = Auth::id();
            $staff_file->save();

        }

        if (!$uploaded_file) {
            // save to storage/app/ as the new $filename
            $path = $file->storeAs('staff/uploads', $filename);
        }
        if ($request->responseType == 'id') {
            return response()->json($staff_file->id);
        } else {
            return response()->json($filename);
        }


    }

    public function fileRemove(Request $request)
    {
        try {
            $id = $request->id;
            $file = StaffFileModel::find($id);
            $file->deleted_by = Auth::id();
            $file->save();
            $status = $file->delete();
            if ($status) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        } catch (\Exception $e) {
            return response()->json(false);
        }
    }

    public function fileDownload($filename)
    {
        return Storage::download('staff/uploads/' . $filename);
    }


    public function viewdetail($id)
    {
        $status = Config::get('constants.employee_status');
        $weekend_days = Config::get('constants.weekend_days');
        $genders = Config::get('constants.gender');
        $martial_status = Config::get('constants.tds_options');
        $staffmain = StafMainMastModel::with(['district', 'jobtype', 'education', 'appooffice', 'jobposition', 'getSection', 'getDepartment',
            "salary" => function ($query) use ($id) {
                $query->with(['created_get', 'updated_get', 'post']);
                $query->latest();
            },
            "staffType:id,staff_type_code,staff_type_title",
            "nominee" => function ($query) use ($id) {
                $query->latest();
            },
            "workschedule" => function ($query) use ($id) {
                $query->with(['createdBy']);
                $query->latest();
            },
            "staffTransfer" => function ($query) use ($id) {
                $query->with(['office_from_get', 'office', 'author']);
                $query->latest();
            },
            "staffStatus" => function ($query) use ($id) {
                $query->with(['created_name', 'updated_name']);
                $query->latest();
            },
            "payment" => function ($query) use ($id) {
                $query->with(['createdBy', 'updatedBy', 'allowance']);
                $query->latest();
            },
            "shiftHistory" => function ($query) use ($id) {
                $query->with(['createdBy', 'shift']);
                $query->latest();
            }])->where('id', $id)->first();


        return view('staffmain.detail', [
            'title' => 'View Staff Detail',
            'staffmain' => $staffmain,
            'weekend_days' => $weekend_days,
            'staff_status' => $status,
            'genders' => $genders,
            'martial_status' => $martial_status,
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
            'name_eng' => 'required',
            'staff_central_id' => 'nullable|unique:staff_main_mast,staff_central_id,' . $id
        ],
            [
                'name_eng.required' => 'You must enter the Full Name!',
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('staff-main-edit', [$id])
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                $checkBranchMainID = StafMainMastModel::where('branch_id', $request->branch_id)->where('id', '<>', $id)->where('main_id', $request->main_id)->exists();
                if ($checkBranchMainID) {
                    return redirect()->back()->withInput()->with('flash', array('status' => 'error', 'mesg' => 'Main ID ' . $request->main_id . ' Has already been taken'));
                }

                if (!empty($request->staff_central_id)) {
                    $checkCentralID = StafMainMastModel::where('id', '<>', $id)->where('staff_central_id', $request->staff_central_id)->exists();
                    if ($checkCentralID) {
                        return redirect()->back()->withInput()->with('flash', array('status' => 'error', 'mesg' => 'Staff Centra; ID ' . $request->staff_central_id . ' Has already been taken'));
                    }
                }

                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                //business/personal info
                $staffmain = StafMainMastModel::find($id);
                $staffmain->name_eng = $request->name_eng;
                $staffmain->FName_Eng = $request->FName_Eng;
                $staffmain->gfname_eng = $request->gfname_eng;
                $staffmain->spname_eng = $request->spname_eng;
                $staffmain->district_id = $request->show_vdc;
                $staffmain->ward_no = $request->ward_no;
                $staffmain->tole_basti = $request->tole_basti;
                $staffmain->marrid_stat = $request->marrid_stat;
                $staffmain->Gender = $request->Gender;
                $staffmain->date_birth_np = $request->staff_dob;
                $staffmain->date_birth = BSDateHelper::BsToAd('-', $request->staff_dob);
                $staffmain->caste_id = $request->caste_id;
                $staffmain->religion_id = $request->religion_id;
                $staffmain->main_id = $request->main_id;
                /*if (Auth::user()->hasRole('Administrator')) {*/
                $staffmain->staff_central_id = $request->staff_central_id;
                /* }   */


                $staffmain->phone_number = $request->phone_number;
                $staffmain->emergency_phone_number = $request->emergency_phone_number;

                $staffmain->staff_dob = BSDateHelper::BsToAd('-', $request->staff_dob);
                $staffmain->staff_citizen_no = $request->staff_citizen_no;
                $staffmain->staff_citizen_issue_office = $request->staff_citizen_issue_office;
                $staffmain->staff_citizen_issue_date_np = $request->staff_citizen_issue_date_np;
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $filename = rand() . time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path() . '/Images/', $filename);
                    $oldFilename = $staffmain->image;
                    $staffmain->image = $filename;
                    Storage::delete($oldFilename);
                }
                $staffmain->sync = 1;
                $staffmain->updated_by = Auth::id();
                $staffmain->save();
                /*storing id of data*/
                $staff_id = $staffmain->id;


                //now save the staff central id to docs table
                if (!empty($request->upload)) {
                    foreach ($request->upload as $filename) {
                        $staff_file = new StaffFileModel();
                        $staff_file->staff_central_id = $staff_id;
                        $staff_file->file_name = $filename;
                        $staff_file->url = 'staff/uploads/' . $filename;
                        $staff_file->created_by = Auth::id();
                        $staff_file->save();
                    }
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
        return redirect()->route('staff-main-edit', [$id])->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function showvdc(Request $request)
    {
        $vdc = District::where("district_id", $request->id)->pluck("mun_vdc", "id");
        return json_encode($vdc);
    }

    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $staffmain = StafMainMastModel::find($request->id);
            //first delete the related tables then only delete the main

            StaffWorkScheduleMastModel::where('staff_central_id', $request->id)->delete();
            StaffSalaryModel::where('staff_central_id', $request->id)->delete();
            StaffPaymentMast::where('staff_central_id', $request->id)->delete();
            StaffShiftHistory::where('staff_central_id', $request->id)->delete();
            StaffNomineeMastModel::where('staff_central_id', $request->id)->delete();
            LeaveBalance::where('staff_central_id', $request->id)->delete();

            if ($staffmain->delete()) {
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

    public function showsalary(Request $request)
    {
//        dd("echo working");
        $salary = SystemPostMastModel::where("post_id", $request->id)->pluck("basic_salary");
//        dd($salary);
        return json_encode($salary);
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
                        $staffmain = StafMainMastModel::find($id);
                        //first delete the related tables then only delete the main
                        StaffWorkScheduleMastModel::where('staff_central_id', $id)->delete();
                        StaffSalaryModel::where('staff_central_id', $id)->delete();
                        StaffPaymentMast::where('staff_central_id', $id)->delete();
                        StaffShiftHistory::where('staff_central_id', $id)->delete();
                        StaffNomineeMastModel::where('staff_central_id', $id)->delete();
                        LeaveBalance::where('staff_central_id', $id)->delete();
                        $staffmain->delete();
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


//upload multiple file
    public function uploadSubmit(Request $request)
    {
        $photos = [];
        foreach ($request->photos as $photo) {
            $filename = $photo->store('photos');
            $product_photo = StaffFileModel::create([
                'filename' => $filename
            ]);

            $photo_object = new \stdClass();
            $photo_object->name = str_replace('photos/', '', $photo->getClientOriginalName());
            $photo_object->size = round(Storage::size($filename) / 1024, 2);
            $photo_object->fileID = $product_photo->id;
            $photos[] = $photo_object;
        }

        return response()->json(array('files' => $photos), 200);

    }

    public function postProduct(Request $request)
    {
        $staff = StafMainMastModel::create($request->all());
        StaffFileModel::whereIn('id', explode(",", $request->file_ids))
            ->update(['file_id' => $staff->id]);
        return 'File saved successfully';
    }

    public function getStaff(Request $request)
    {

        $staffs = StafMainMastModel::with('branch')->search($request->search);

        if (!empty($request->branch_id)) {
            $staffs = $staffs->where('branch_id', $request->branch_id);
        }
        if (!empty($request->limit)) {
            $staffs = $staffs->take($request->limit);
        }

        if (!empty($request->department_id)) {
            $staffs = $staffs->where('department', $request->department_id);
        }

        $staffs = $staffs->get();


        if (!empty($request->staff_central_id)) {
            $specificStaffs = StafMainMastModel::with('branch')->where('id', $request->staff_central_id)->get();
            $staffs = $staffs->merge($specificStaffs);
        }

        return response()->json([
            'staffs' => $staffs
        ]);
    }

    public function getStaffById(Request $request)
    {
        if (empty($request->id)) {
            return response()->json([
                'status' => 'false',
                'data' => null,
                'message' => 'ID not found'
            ]);
        }

        $staff = StafMainMastModel::find($request->id);

        if (empty($staff)) {
            return response()->json([
                'status' => 'false',
                'data' => null,
                'message' => 'Staff not found'
            ]);
        }

        return response()->json([
            'status' => 'true',
            'data' => $staff,
            'message' => 'Staff retrieved successfully'
        ]);
    }

    public function getoneStaff(Request $request)
    {
        $staff = StafMainMastModel::find($request->staff_id);
        return response()->json([
            'office' => $staff->branch->office_name
        ]);
    }

    public function by_branch(Request $request)
    {
        $staffs = StafMainMastModel::where('branch_id', $request->branch);

        if (!empty($request->department_id)) {
            $staffs = $staffs->where('department', $request->department_id);
        }

        if (!empty($request->onlyBBSM)) {
            $staffs = $staffs->whereIn('staff_type', [0, 1]);
        }

        if (!empty($request->noScope)) {
            $staffs = $staffs->withoutGlobalScopes();
        }

        if (!empty($request->from_date_np) && !empty($request->to_date_np)) {
            $to_date_np_array = explode('-', $request->to_date_np);
            $to_date_np_array[2] = BSDateHelper::getLastDayByYearMonth($to_date_np_array[0], (int)$to_date_np_array[1]);
            $to_date_np = implode('-', $to_date_np_array);
            $endDate = BSDateHelper::BsToAd('-', $to_date_np);
            $staffCentralIdsFromStaffTransfer = [];


            $staffTransfers = StaffTransferModel::whereNotNull('office_id')
                ->where(function ($query) use ($endDate) {
                    $query->where('from_date', '<=', $endDate)
                        ->where('transfer_date', '>=', $endDate);
                });


            $excludeStaffTransfer = clone($staffTransfers);
            $staffTransfers = $staffTransfers->where('office_from', $request->branch);
            $excludeStaffCentralIdsFromStaffTransfer = $excludeStaffTransfer->where('office_id', $request->branch)->pluck('staff_central_id');

            if (!empty($staffTransfers)) {
                $staffCentralIdsFromStaffTransfer = $staffTransfers->pluck('staff_central_id');
            }
            if (!$staffCentralIdsFromStaffTransfer->isEmpty()) {
                $staffs = $staffs->orWhere('id', $staffCentralIdsFromStaffTransfer);
            }
            $staffs = $staffs->whereDate('appo_date', '<=', $endDate);

            if (!$excludeStaffCentralIdsFromStaffTransfer->isEmpty()) {
                $staffs = $staffs->whereNotIn('id', $excludeStaffCentralIdsFromStaffTransfer);
            }
        }

        if (!empty($request->payrollStaffs)) {
            $staffs = $staffs->where(function ($innerQuery) {
                $innerQuery->whereHas('workschedule', function ($innerInnerQuery) {
                    $innerInnerQuery->whereNotNull('weekend_day')->whereNotNull('work_hour');
                })->where(function ($innerInnerQuery) {
                    $innerInnerQuery->where('post_id', '<>', 0)
                        ->whereNotNull('post_id')
                        ->whereNotNull('jobtype_id')
                        ->where('jobtype_id', '<>', 0);
                });
            })->whereIn('staff_type', [0, 1]);

        }
        if (!empty($request->limit)) {
            $staffs = $staffs->take($request->limit);
        }

        $staffs = $staffs->with('branch')->select('id', 'name_eng', 'main_id', 'branch_id', 'staff_central_id')->orderBy('main_id', 'ASC')->get();
        return response()->json($staffs);
    }

    public function payroll_by_branch(Request $request)
    {

        $staffs = $this->getPayrollStaffs($request->branch_id, $request->department_id, $request->from_date_np, $request->to_date_np);

        $branch = SystemOfficeMastModel::find($request->branch_id);
        //ordering the staff according to the staff excel file
        if (!empty($branch->order_staff_ids)) {
            //mapping the staff branch ids to array
            $staff_order_ids = array_map('intval', explode(',', $branch->order_staff_ids));
            //adding the branch ids not available on the
            $staff_order_ids = array_merge($staff_order_ids, array_diff($staffs->pluck('main_id')->toArray(), $staff_order_ids));
            //sorting function
            $staffs = $staffs->sortBy(function ($model) use ($staff_order_ids) {
                return array_search($model->main_id, $staff_order_ids);
            });
        }

        $order_ids = implode(',', $staffs->pluck('id')->toArray());
        $staffs = StafMainMastModel::select('id', 'name_eng', 'main_id', 'staff_central_id')->orderByRaw("FIELD(id, $order_ids)")->whereIn('id', $staffs->pluck('id')->toArray())->get();


        return response()->json($staffs);
    }

    public function warning_by_branch(Request $request)
    {
        $staffs = StafMainMastModel::where(function ($innerQuery) {
            $innerQuery->whereDoesntHave('workschedule')
                ->orWhereHas('workschedule', function ($innerInnerQuery) {
                    $innerInnerQuery->whereNull('weekend_day')->orWhereNull('work_hour');
                })
                ->orWhere(function ($innerInnerQuery) {
                    $innerInnerQuery->where('post_id', 0)
                        ->orWhereNull('post_id')
                        ->orWhereNull('jobtype_id')->orWhere('jobtype_id', 0)
                        ->orWhereNull('branch_id')
                        ->orWhere('branch_id', 0);
                });
        })->where('branch_id', $request->branch);;

        if (!empty($request->department_id)) {
            $staffs = $staffs->where('department', $request->department_id);
        }

        if (!empty($request->department_id)) {
            $staffs = $staffs->where('department', $request->department_id);
        }

        $staffs = $staffs->select('id', 'name_eng', 'main_id')->orderBy('main_id', 'ASC')->get();
        return response()->json($staffs);
    }

    public function lastMainIdofBranch(Request $request)
    {
        $staff_main_id = StafMainMastModel::withoutGlobalScopes()->where('branch_id', $request->branch_id)->where('main_id', '<', 8000);

        $staff_type = $request->staff_type;

        if (!empty($request->staff_id)) {
            $staff = StafMainMastModel::where('id', $request->staff_id)->first();

            if (!empty($staff)) {
                $staff_type = $staff->staff_type;
            }
        }

        if (!empty($staff_type)) {
            if ($staff_type != 2 && $staff_type != 3) {
                $staff_main_id = $staff_main_id->where('main_id', '<', 5000);
            }
        } else {
            $staff_main_id = $staff_main_id->where('main_id', '<', 5000);
        }
        $staff_main_id = $staff_main_id->max('main_id') ?? 0;

        $staff_main_id += 1;
        return response()->json($staff_main_id);
    }

//excel
    public function excelIndex()
    {
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        return view('staffmain.excel.index', [
            'title' => 'Excel Import',
            'branches' => $branches
        ]);
    }

    public function excelStore(Request $request)
    {
        $path = $request->file('excel_file');
        $data = Excel::load($path, function ($reader) {
        })->all();

        $status_mesg = false;
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '1024M');
        $payment_count = 0;
        try {
            //start transaction for rolling back if some problem occurs
            DB::beginTransaction();
            foreach ($data as $item) {
                if (strcasecmp($item['sn'], 'END') == 0) {
                    break;
                }

                $staffmain = StafMainMastModel::whereNotNull('staff_central_id')
                    ->where('staff_central_id', '<>', null)
                    ->where('staff_central_id', $item['cid'])->first();

                if (isset($staffmain)) {
                    $status_mesg = false;
                    $mesg = "SN: {$item['sn']} of cid {$item['cid']} already exists in the database.";
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('flash', array('status' => $status_mesg, 'mesg' => $mesg));
                    break;
                }

                $staffmain = new StafMainMastModel();

                $staffmain->main_id = $item['main_id'];
                $staffmain->staff_central_id = $item['cid'];
                $staffmain->name_eng = $item['name'];
                $staffmain->district_id = 1;
                $gender = null;
                if (!empty($item['gender'])) {
                    if (strcasecmp($item['gender'], 'F') == 0) {//is female
                        $gender = 2;
                    } elseif (strcasecmp($item['gender'], 'M') == 0) {
                        $gender = 1; //is male
                    } else {
                        $gender = 3; //others
                    }
                }

                $post = SystemPostMastModel::where('post_title', trim($item['designation']))->orWhere('post_title', 'like', '%' . trim($item['designation']) . '%')->first();
                if (empty($post)) {
                    $status_mesg = false;
                    $mesg = "SN: {$item['sn']} of designation {$item['designation']} post does not exist in the database.";
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('flash', array('status' => $status_mesg, 'mesg' => $mesg));
                    break;
                }
                $staffmain->pan_number = $item['pan_no'];

                if (strtolower(trim($item['marital_status'])) == 'u') {
                    $staffmain->marrid_stat = 0;
                } else {
                    $staffmain->marrid_stat = 1;
                }

                $staffmain->edu_id = 1;
                $item['work_date'] = !empty($item['work_date_ad']) ? (date('Y-m-d', strtotime($item['work_date_ad']))) : null;
                $item['appointment_date'] = !empty($item['appointment_date_ad']) ? (date('Y-m-d', strtotime($item['appointment_date_ad']))) : null;
                $item['permanent_date_date_ad'] = !empty($item['permanent_date_ad']) ? (date('Y-m-d', strtotime($item['permanent_date_ad']))) : null;

                $staffmain->work_start_date = $item['work_date'];
                $staffmain->work_start_date_np = !empty($item['work_date']) ? BSDateHelper::AdToBs('-', $item['work_date']) : null;
                $staffmain->appo_date = $item['appointment_date'];
                $staffmain->appo_date_np = !empty($item['appointment_date']) ? BSDateHelper::AdToBs('-', $item['appointment_date']) : null;

                $staffmain->temporary_con_date = $staffmain->appo_date;
                $staffmain->temporary_con_date_np = $staffmain->appo_date_np;

                $staffmain->permanent_date = $item['permanent_date_date_ad'];
                $staffmain->permanent_date_np = !empty($item['permanent_date_date_ad']) ? BSDateHelper::AdToBs('-', $item['permanent_date_date_ad']) : null;


                $staffmain->post_id = $post->post_id;
                $staff_type_id = 0;
                $staff_type = strtolower($item['staff_type']);
                if ($staff_type == 'bbsm') {
                    $staff_type_id = 0;
                } elseif ($staff_type == 'bbsm guard') {
                    $staff_type_id = 1;
                } elseif ($staff_type == 'company') {
                    $staff_type_id = 2;
                } elseif ($staff_type == 'company guard') {
                    $staff_type_id = 3;
                }
                $job_type_id = 1;
                $staffmain->staff_type = $staff_type_id;
                $job_type = strtolower($item['job_type']);
                if ($job_type == 'p') {
                    $job_type_id = 1;
                } elseif ($job_type == 'np') {
                    $job_type_id = 2;
                } elseif ($job_type == 'con') {
                    $job_type_id = 3;
                } elseif ($job_type == 'con1') {
                    $job_type_id = 4;
                } else {
                    $job_type_id = 5;
                }


                $staffmain->jobtype_id = $job_type_id;
                $staffmain->branch_id = $request->branch_id;
                $staffmain->payroll_branch_id = $request->branch_id;


                if (!empty($gender)) {
                    $staffmain->Gender = $gender;
                }


                if (strcasecmp($item['extra_facility_in_dashai_tihar'], 'H') == 0) {
                    $staffmain->dashain_allow = 0;
                } else {
                    $staffmain->dashain_allow = 1;
                }

                if (strcasecmp($item['extra_allowance_facility'], 'B') == 0) {
                    $staffmain->extra_allow = 0;
                } else {
                    $staffmain->extra_allow = 1;
                }

                //overriden amount
                $staffmain->outstation_facility_amount = 0;
                $staffmain->special_allowance_amount = $item['special_allowance_amount'];
                $staffmain->special_allowance_2_amount = $item['special_allowance_2_amount'];
                $staffmain->risk_allowance_amount = $item['risk_allowance_amount'];
                $staffmain->dearness_allowance_amount = $item['dearness_allowance_amount'];
                $staffmain->incentive_amount = $item['incentive'];
                $staffmain->gratuity_allowance_amount = 0;
                $staffmain->profund_allowance_amount = 0;
                $staffmain->extra_allowance_amount = 0;
                $staffmain->dashain_allowance_amount = 0;
                $staffmain->other_allowance_amount = 0;

                if (!empty($item['work_date'])) {
                    $staffmain->work_start_date_np = BSDateHelper::AdToBs('-', $item['work_date']);
                    $staffmain->work_start_date = $item['work_date'];
                }


                $dob_ad = null;
                $dob_bs = null;
                if (!empty($item['date_of_birth'])) {
                    $staffmain->date_birth_np = BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($item['date_of_birth'])));
                    $staffmain->date_birth = $item['date_of_birth'];
                    $staffmain->staff_dob = $item['date_of_birth'];
                }
                if (!empty($item['bank'])) {
                    $bank_name = $item['bank'];
                    if (strtolower(trim($bank_name)) != strtolower('CASH')) {
                        $bankModel = BankMastModel::where('bank_name', 'LIKE', '%' . trim($item['bank']) . '%')->first();
                        if (!isset($bankModel)) {
                            $status_mesg = false;
                            $mesg = "SN: {$item['sn']} of bank name {$item['bank']} does not exists in the database";
                            DB::rollBack();
                            return redirect()->back()->withInput()->with('flash', array('status' => $status_mesg, 'mesg' => $mesg));
                            break;
                        }
                        $staffmain->bank_id = $bankModel->id;
                        $staffmain->acc_no = $item['acc_no'];
                    }
                }
                $staffmain->profund_acc_no = $item['provident_fund_account_no'];
                $staffmain->social_security_fund_acc_no = $item['social_security_fund_account_no'];
                $staffmain->total_grade_amount = $item['grade'] + $item['grade_2'];
                $staffmain->staff_status = 1;
                if (isset($item['staff_status']) && !empty($item['staff_status'])) {
                    if ((strcasecmp($item['staff_status'], 'resign') == 0) || (strcasecmp($item['staff_status'], 'dismiss') == 0) || (strcasecmp($item['staff_status'], 'suspense') == 0)) {
                        $staffmain->staff_status = 2;
                    }
                } else {
                    $staffmain->staff_status = 1;
                }
                $staffmain->phone_number = isset($item['phone_number']) ? $item['phone_number'] : null;
                $staffmain->tole_basti = isset($item['address']) ? $item['address'] : null;
                $staffmain->sync = 1;
                $staffmain->save();

                if ($staffmain->staff_status == 2) {
                    $staff_status_code = 0;
                    if ((strcasecmp($item['staff_status'], 'resign') == 0)) {
                        $staff_status_code = 1;
                    }
                    if ((strcasecmp($item['staff_status'], 'dismiss') == 0)) {
                        $staff_status_code = 2;
                    }
                    if ((strcasecmp($item['staff_status'], 'suspense') == 0)) {
                        $staff_status_code = 4;
                    }
                    $employee_status = new EmployeeStatus();
                    $employee_status->staff_central_id = $staffmain->id;
                    $employee_status->date_from = $item['status_from'] ?? date('Y-m-d');
                    $employee_status->date_from_np = BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($employee_status->date_from)));
                    $employee_status->date_to_np = null;
                    $employee_status->date_to = null;
                    $employee_status->status = $staff_status_code;
                    $employee_status->created_by = Auth::user()->id;
                    $employee_status->save();
                }

                //dearness allowance
                $payment[$payment_count]['staff_central_id'] = $staffmain->id;
                $payment[$payment_count]['allow_id'] = 1;
                $payment[$payment_count]['allow'] = !empty($staffmain->dearness_allowance_amount) ? 1 : 0;
                $payment[$payment_count]['amount'] = !empty($staffmain->dearness_allowance_amount) ? $staffmain->dearness_allowance_amount : 0;
                $payment[$payment_count]['effective_from'] = date('Y-m-d');
                $payment[$payment_count]['effective_from_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $payment_count++;
                //risk allowance
                $payment[$payment_count]['staff_central_id'] = $staffmain->id;
                $payment[$payment_count]['allow_id'] = 2;
                $payment[$payment_count]['allow'] = !empty($staffmain->risk_allowance_amount) ? 1 : 0;
                $payment[$payment_count]['amount'] = !empty($staffmain->risk_allowance_amount) ? $staffmain->risk_allowance_amount : 0;
                $payment[$payment_count]['effective_from'] = date('Y-m-d');
                $payment[$payment_count]['effective_from_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $payment_count++;
                //special allowance 1 allowance
                $payment[$payment_count]['staff_central_id'] = $staffmain->id;
                $payment[$payment_count]['allow_id'] = 3;
                $payment[$payment_count]['allow'] = !empty($staffmain->special_allowance_amount) ? 1 : 0;
                $payment[$payment_count]['amount'] = !empty($staffmain->special_allowance_amount) ? $staffmain->special_allowance_amount : 0;
                $payment[$payment_count]['effective_from'] = date('Y-m-d');
                $payment[$payment_count]['effective_from_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $payment_count++;
                //special allowance 2 allowance
                $payment[$payment_count]['staff_central_id'] = $staffmain->id;
                $payment[$payment_count]['allow_id'] = 4;
                $payment[$payment_count]['allow'] = !empty($staffmain->special_allowance_2_amount) ? 1 : 0;
                $payment[$payment_count]['amount'] = !empty($staffmain->special_allowance_2_amount) ? $staffmain->special_allowance_2_amount : 0;
                $payment[$payment_count]['effective_from'] = date('Y-m-d');
                $payment[$payment_count]['effective_from_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $payment_count++;
                //incentive allowance
                $payment[$payment_count]['staff_central_id'] = $staffmain->id;
                $payment[$payment_count]['allow_id'] = 6;
                $payment[$payment_count]['allow'] = !empty($staffmain->incentive_amount) ? 1 : 0;
                $payment[$payment_count]['amount'] = !empty($staffmain->incentive_amount) ? $staffmain->incentive_amount : 0;
                $payment[$payment_count]['effective_from'] = date('Y-m-d');
                $payment[$payment_count]['effective_from_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $payment_count++;
                //extra  allowance
                $payment[$payment_count]['staff_central_id'] = $staffmain->id;
                $payment[$payment_count]['allow_id'] = 8;
                $payment[$payment_count]['allow'] = $staffmain->extra_allow;
                $payment[$payment_count]['amount'] = 0;
                $payment[$payment_count]['effective_from'] = date('Y-m-d');
                $payment[$payment_count]['effective_from_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $payment_count++;

                //dashain allowance
                $payment[$payment_count]['staff_central_id'] = $staffmain->id;
                $payment[$payment_count]['allow_id'] = 9;
                $payment[$payment_count]['allow'] = $staffmain->dashain_allow;
                $payment[$payment_count]['amount'] = 0;
                $payment[$payment_count]['effective_from'] = date('Y-m-d');
                $payment[$payment_count]['effective_from_np'] = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $payment_count++;


                $staff_id = $staffmain->id;

                $staffsalary = new StaffSalaryModel();
                $staffsalary->fiscal_year_id = 3;
                $staffsalary->staff_central_id = $staff_id;
                $staffsalary->post_id = $staffmain->post_id;
                $post = SystemPostMastModel::find($staffmain->post_id);
                if ($job_type == 1 || $job_type == 2) {
                    $staffsalary->basic_salary = $post->basic_salary;
                } else {
                    $staffsalary->basic_salary = $item['basic_salary'] ?? $post->basic_salary;
                }
                $staffsalary->total_grade_amount = $item['grade'] ?? 0;
                $staffsalary->add_grade_this_fiscal_year = $item['grade_2'] ?? 0;
                $staffsalary->salary_effected_date = date('Y-m-d');
                $staffsalary->salary_effected_date_np = BSDateHelper::AdToBs('-', date('Y-m-d'));
                $staffsalary->created_by = Auth::user()->id;
                $staffsalary->save();

                /* Staff Transfer Create */
                $staffTransfer = new StaffTransferModel();
                $staffTransfer->from_date_np = BSDateHelper::AdToBs('-', $staffmain->appo_date);
                $staffTransfer->from_date = $staffmain->appo_date;
                $staffTransfer->staff_central_id = $staffmain->id;
                $staffTransfer->transfer_date = null;
                $staffTransfer->office_id = null;
                $staffTransfer->office_from = $staffmain->branch_id;
                $staffTransfer->autho_id = auth()->id();

                $staffTransfer->save();


                $staffworkschedule = new StaffWorkScheduleMastModel();
                $staffworkschedule->staff_central_id = $staff_id;
                $staffworkschedule->work_hour = $item['minimum_duty_hours'];
                $staffworkschedule->weekend_day = array_search($item['weekend'], config('constants.weekend_days'));

                if (!empty($item['work_date'])) {
                    $staffworkschedule->effect_day = $item['work_date'];
                    $staffworkschedule->effect_date_np = BSDateHelper::AdToBs('-', $item['work_date']);
                } else if (!empty($staffmain->appo_date)) {
                    $staffworkschedule->effect_day = $staffmain->appo_date;
                    $staffworkschedule->effect_date_np = $staffmain->appo_date_np;
                } else { //check if work date is there or not else put the current month first date
                    $staffworkschedule->effect_date_np = BSDateHelper::getFirtDayOfMonthNPByDate('-', date('Y-m-d'));
                    $staffworkschedule->effect_day = BSDateHelper::BsToAd('-', $staffworkschedule->effect_date_np);
                }

                $staffworkschedule->work_status = 'A';
                $staffworkschedule->save();


                $fiscal_year = FiscalYearModel::where('fiscal_status', 1)->first()->id;

                $leave_balances = LeaveBalance::where('staff_central_id', $staff_id)->get();

                $leave_balance = $leave_balances->where('leave_id', 7)->last();
                if (empty($leave_balance)) {
                    $leave_balance = new LeaveBalance();
                }
                $leave_balance->staff_central_id = $staff_id;
                $leave_balance->leave_id = 7;
                $leave_balance->fy_id = $fiscal_year;
                $leave_balance->description = "Excel Import";
                $leave_balance->consumption = 0;
                $leave_balance->earned = 0;
                $leave_balance->balance = empty($item['home_leave']) ? 0 : $item['home_leave'];
                $leave_balance->authorized_by = Auth::id();
                $leave_balance->save();

                $leave_balance = $leave_balances->where('leave_id', 8)->last();
                if (empty($leave_balance)) {
                    $leave_balance = new LeaveBalance();
                }
                $leave_balance->staff_central_id = $staff_id;
                $leave_balance->leave_id = 8;
                $leave_balance->fy_id = $fiscal_year;
                $leave_balance->description = "Excel Import";
                $leave_balance->consumption = 0;
                $leave_balance->earned = 0;
                $leave_balance->balance = empty($item['sick_leave']) ? 0 : $item['sick_leave'];
                $leave_balance->authorized_by = Auth::id();
                $leave_balance->save();

                $leave_balance = $leave_balances->where('leave_id', 9)->last();
                if (empty($leave_balance)) {
                    $leave_balance = new LeaveBalance();
                }
                $leave_balance->staff_central_id = $staff_id;
                $leave_balance->leave_id = 9;
                $leave_balance->fy_id = $fiscal_year;
                $leave_balance->description = "Excel Import";
                $leave_balance->consumption = 0;
                $leave_balance->earned = 0;
                $leave_balance->balance = empty($item['maternity_leave']) ? 0 : $item['maternity_leave'];
                $leave_balance->authorized_by = Auth::id();
                $leave_balance->save();

                $leave_balance = $leave_balances->where('leave_id', 11)->last();
                if (empty($leave_balance)) {
                    $leave_balance = new LeaveBalance();
                }
                $leave_balance->staff_central_id = $staff_id;
                $leave_balance->leave_id = 11;
                $leave_balance->fy_id = $fiscal_year;
                $leave_balance->description = "Excel Import";
                $leave_balance->consumption = 0;
                $leave_balance->earned = 0;
                $leave_balance->balance = empty($item['funeral_leave']) ? 0 : $item['funeral_leave'];
                $leave_balance->authorized_by = Auth::id();
                $leave_balance->save();


                $leave_balance = $leave_balances->where('leave_id', 13)->last();
                if (empty($leave_balance)) {
                    $leave_balance = new LeaveBalance();
                }
                $leave_balance->staff_central_id = $staff_id;
                $leave_balance->leave_id = 13;
                $leave_balance->fy_id = $fiscal_year;
                $leave_balance->description = "Excel Import";
                $leave_balance->consumption = 0;
                $leave_balance->earned = 0;
                $leave_balance->balance = empty($item['leave_without_pay']) ? 0 : $item['leave_without_pay'];
                $leave_balance->authorized_by = Auth::id();
                $leave_balance->save();

                $leave_balance = $leave_balances->where('leave_id', 12)->last();
                if (empty($leave_balance)) {
                    $leave_balance = new LeaveBalance();
                }
                $leave_balance->staff_central_id = $staff_id;
                $leave_balance->leave_id = 12;
                $leave_balance->fy_id = $fiscal_year;
                $leave_balance->description = "Excel Import";
                $leave_balance->consumption = 0;
                $leave_balance->earned = 0;
                $leave_balance->balance = empty($item['substitute_leave']) ? 0 : $item['substitute_leave'];
                $leave_balance->authorized_by = Auth::id();
                $leave_balance->save();

                $leave_balance = $leave_balances->where('leave_id', 10)->last();
                if (empty($leave_balance)) {
                    $leave_balance = new LeaveBalance();
                }
                $leave_balance->staff_central_id = $staff_id;
                $leave_balance->leave_id = 10;
                $leave_balance->fy_id = $fiscal_year;
                $leave_balance->description = "Excel Import";
                $leave_balance->consumption = 0;
                $leave_balance->earned = 0;
                $leave_balance->balance = empty($item['maternity_care_leave']) ? 0 : $item['maternity_care_leave'];
                $leave_balance->authorized_by = Auth::id();
                $leave_balance->save();

                $fiscal_year_attendance = new FiscalYearAttendanceSum();
                $fiscal_year_attendance->fiscal_year = 1;
                $fiscal_year_attendance->staff_central_id = $staff_id;
                $fiscal_year_attendance->branch_id = $request->branch_id;
                $fiscal_year_attendance->total_attendance = empty($item['present_days_in_fiscal_year']) ? 0 : $item['present_days_in_fiscal_year'];
                $fiscal_year_attendance->save();

                $status_mesg = true;
                //
            }
            StaffPaymentMast::insert($payment);
        } catch (Exception $e) {
            DB::rollback();
            $status_mesg = false;
        }
        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Data Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('staff-main')->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    public function companyExcelStore(Request $request)
    {
        $path = $request->file('excel_file');
        $data = Excel::load($path, function ($reader) {
        })->all();
        $status_mesg = false;
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '1024M');
        try {
            DB::beginTransaction();
            foreach ($data as $item) {
                if (strcasecmp($item['sn'], 'END') == 0) {
                    break;
                }
                $staffmain = new StafMainMastModel();
                $staffmain->main_id = $item['main_id'];
                $staffmain->name_eng = $item['name'];
                $staffmain->district_id = 1;
                $gender = null;
                if (!empty($item['gender'])) {
                    if (strcasecmp($item['gender'], 'F') == 0) {//is female
                        $gender = 2;
                    } elseif (strcasecmp($item['gender'], 'M') == 0) {
                        $gender = 1; //is male
                    } else {
                        $gender = 3; //others
                    }
                }
                if (!empty($gender)) {
                    $staffmain->Gender = $gender;
                }
                $post = SystemPostMastModel::where('post_title', trim($item['designation']))->orWhere('post_title', 'like', '%' . trim($item['designation']) . '%')->first();
                if (empty($post)) {
                    $status_mesg = false;
                    $mesg = "SN: {$item['sn']} of designation {$item['designation']} post does not exist in the database.";
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('flash', array('status' => $status_mesg, 'mesg' => $mesg));
                    break;
                }

                if (strtolower(trim($item['marital_status'])) == 'u') {
                    $staffmain->marrid_stat = 0;
                } else {
                    $staffmain->marrid_stat = 1;
                }

                $staffmain->edu_id = 1;
                $item['work_date'] = !empty($item['work_date_ad']) ? (date('Y-m-d', strtotime($item['work_date_ad']))) : null;
                $item['appointment_date'] = !empty($item['appointment_date_ad']) ? (date('Y-m-d', strtotime($item['appointment_date_ad']))) : null;

                $staffmain->work_start_date = $item['work_date'];
                $staffmain->work_start_date_np = !empty($item['work_date']) ? BSDateHelper::AdToBs('-', $item['work_date']) : null;
                $staffmain->appo_date = $item['appointment_date'];
                $staffmain->appo_date_np = !empty($item['appointment_date']) ? BSDateHelper::AdToBs('-', $item['appointment_date']) : null;

                $staffmain->post_id = $post->post_id;
                $staff_type_id = 0;
                $staff_type = strtolower($item['staff_type']);
                if ($staff_type == 'bbsm') {
                    $staff_type_id = 0;
                } elseif ($staff_type == 'bbsm guard') {
                    $staff_type_id = 1;
                } elseif ($staff_type == 'company') {
                    $staff_type_id = 2;
                } elseif ($staff_type == 'company guard') {
                    $staff_type_id = 3;
                }
                $job_type_id = 2;
                $staffmain->staff_type = $staff_type_id;
                $staffmain->jobtype_id = $job_type_id;
                $staffmain->branch_id = $request->branch_id;

                $dob_ad = null;
                $dob_bs = null;
                if (!empty($item['date_of_birth'])) {
                    $staffmain->date_birth_np = BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($item['date_of_birth'])));
                    $staffmain->date_birth = $item['date_of_birth'];
                    $staffmain->staff_dob = $item['date_of_birth'];
                }

                $staffmain->staff_status = 1;
                $staffmain->phone_number = isset($item['phone_number']) ? $item['phone_number'] : null;
                $staffmain->tole_basti = isset($item['address']) ? $item['address'] : null;
                $staffmain->company_name = isset($item['company_name']) ? $item['company_name'] : null;
                $staffmain->sync = 1;
                $staffmain->save();

                $staff_id = $staffmain->id;

                $staffworkschedule = new StaffWorkScheduleMastModel();
                $staffworkschedule->staff_central_id = $staff_id;
                $staffworkschedule->work_hour = 8;
                $staffworkschedule->weekend_day = array_search($item['weekend'], config('constants.weekend_days'));
                if (!empty($item['work_date'])) {
                    $staffworkschedule->effect_day = $item['work_date'];
                    $staffworkschedule->effect_date_np = BSDateHelper::AdToBs('-', $item['work_date']);
                } else if (!empty($staffmain->appo_date)) {
                    $staffworkschedule->effect_day = $staffmain->appo_date;
                    $staffworkschedule->effect_date_np = $staffmain->appo_date_np;
                } else { //check if work date is there or not else put the current month first date
                    $staffworkschedule->effect_date_np = BSDateHelper::getFirtDayOfMonthNPByDate('-', date('Y-m-d'));
                    $staffworkschedule->effect_day = BSDateHelper::BsToAd('-', $staffworkschedule->effect_date_np);
                }

                $staffworkschedule->work_status = 'A';
                $staffworkschedule->save();
                $status_mesg = true;
            }
        } catch (\Exception $e) {
            DB::rollback();
            $status_mesg = false;
        }
        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Data Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('staff-main')->with('flash', array('status' => $status, 'mesg' => $mesg));
    }

    public function accountNumberImport(Request $request)
    {
        $path = $request->file('excel_file');
        $datas = Excel::load($path, function ($reader) {
        })->all();
        $branch_id = $request->branch_id;
        foreach ($datas as $data) {
            $staff = StafMainMastModel::query();
            if (!empty($data['cid'])) {
                $staff = $staff->where('staff_central_id', $data['cid']);
            } else {
                $staff = $staff->where(function ($query) use ($data, $branch_id) {
                    $query->where('main_id', $data['branch_id']);
                    $query->where('payroll_branch_id', $branch_id);
                })->orWhere(function ($query) use ($data, $branch_id) {
                    $query->where('main_id', $data['branch_id']);
                    $query->where('branch_id', $branch_id);
                });
            }
            $staff = $staff->first();
            if (!empty($staff)) {

                $staff->acc_no = $data['account_number'] ?? $data['acc_no'] ?? null;
                $staff->profund_acc_no = $data['profund_account_number'] ?? $data['profund_acc_no'] ?? null;
                $staff->pan_number = $data['pan_number'];

                if (isset($data['temporary_con_date'])) {
                    if (!empty($data['temporary_con_date'])) {
                        $staff->temporary_con_date = date('Y-m-d', strtotime($data['temporary_con_date']));
                        $year = date('Y', strtotime($data['temporary_con_date']));
                        if ($year < 1944 || $year > 2033) {
                            dd($data);
                        }
                        $staff->temporary_con_date_np = BSDateHelper::AdToBs('-', $staff->temporary_con_date);
                    }
                }
                if (isset($data['permanent_date'])) {
                    if (!empty($data['permanent_date'])) {
                        $staff->permanent_date = date('Y-m-d', strtotime($data['permanent_date']));

                        $year = date('Y', strtotime($data['permanent_date']));
                        if ($year < 1944 || $year > 2033) {
                            dd($data);
                        }
                        $staff->permanent_date_np = BSDateHelper::AdToBs('-', $staff->permanent_date);
                    }
                }
                if (isset($data['date_birth'])) {
                    if (!empty($data['date_birth'])) {
                        $staff->staff_dob = date('Y-m-d', strtotime($data['date_birth']));
                        $year = date('Y', strtotime($data['date_birth']));
                        if ($year < 1944 || $year > 2033) {
                            dd($data);
                        }
                        $staff->date_birth = date('Y-m-d', strtotime($data['date_birth']));
                        $staff->date_birth_np = BSDateHelper::AdToBs('-', $staff->date_birth);
                    }
                }

                if (isset($data['gender'])) {
                    $gender = 1;
                    if (strcasecmp($data['gender'], 'F') == 0) {
                        $gender = 2;
                    }
                    $staff->Gender = $gender;
                }

                if (isset($data['marital_status'])) {
                    $marital_status = 0;
                    if (strcasecmp($data['marital_status'], 'M') == 0) {
                        $marital_status = 1;
                    }
                    $staff->marrid_stat = $marital_status;
                }


                $staff->save();
            }
        }
        echo 'Completed.....';
    }

    public function excelExport(Request $request)
    {
        $staffmains = StafMainMastModel::with('branch', 'jobtype', 'jobposition', 'salary',
            'latestsalary',
            'homeLeaveBalanceLast',
            'sickLeaveBalanceLast',
            'maternityLeaveBalanceLast',
            'maternityCareLeaveBalanceLast',
            'funeralLeaveBalanceLast',
            'payrollBranch'
        );

        if (isset($request->staff_central_id)) {
            $staffmains->where('id', $request->staff_central_id);
        }

        if (isset($request->job_type_id)) {
            $staffmains->where('jobtype_id', $request->job_type_id);
        }

        if (isset($request->department_id)) {
            $staffmains->where('department', $request->department_id);
        }

        if (isset($request->branch_id)) {
            $staffmains->where('payroll_branch_id', $request->branch_id);
        }


        if (isset($request->designation_id)) {
            $staffmains->where('post_id', $request->designation_id);
            $staffmains->where('post_id', $request->designation_id);
        }

        if (isset($request->shift_id)) {
            $staffmains->where('shift_id', $request->shift_id);
        }

        if (isset($request->staff_type)) {
            $staffmains->whereIn('staff_type', $request->staff_type);
        }

        if (!isset($request->show_inactive)) {
            $staffmains->where('staff_status', '=', 1);
        }

        $staffmains = $staffmains->orderBy('main_id', 'desc')->get();
        \Excel::create('Staff Excel Export', function ($excel) use ($staffmains) {

            $excel->sheet('Staffs', function ($sheet) use ($staffmains) {

                $sheet->loadView('staffmain.excelexport.excel-view', [
                    'staffmains' => $staffmains
                ]);

            });

        })->download('xlsx');;

//        return view('staffmain . excelexport . excel - view', compact('staffmains'));
    }

    public function checkMainIdUnique(Request $request)
    {

        $checkUnique = StafMainMastModel::where('main_id', $request->main_id)->exists();
        $response = array(
            'valid' => $checkUnique,
        );
        echo json_encode($response);

    }

    /**
     * @param Request $request
     */
    public function getWarningStaff(Request $request)
    {
        $warningStaffMains = StafMainMastModel::with('workschedule', 'jobposition', 'jobtype', 'branch', 'shift')->where(function ($query) use ($request) {
            if (isset($request->staff_central_id)) {
                $query->where('id', $request->staff_central_id);
            }

            if (isset($request->job_type_id)) {
                $query->where('jobtype_id', $request->job_type_id);
            }

            if (isset($request->department_id)) {
                $query->where('department', $request->department_id);
            }

            if (isset($request->branch_id)) {
                $query->where('branch_id', $request->branch_id);
            }

            if (isset($request->designation_id)) {
                $query->where('post_id', $request->designation_id);
            }

            if (isset($request->shift_id)) {
                $query->where('shift_id', $request->shift_id);
            }
            if (!isset($request->show_inactive)) {

                $query->where('staff_status', '=', 1);
            }

            $query->whereIn('staff_type', [0, 1]);
        });
        $warning_options = $this->stafMainMastRepository->getListsOfWarning();

        $request_warning_option = $request->warning_option;

        if (empty($request_warning_option) || !in_array($request_warning_option, array_keys($warning_options))) {
            $warningStaffMains->where(function ($innerQuery) {
                $innerQuery->whereDoesntHave('workschedule')
                    ->orWhereHas('workschedule', function ($innerInnerQuery) {
                        $innerInnerQuery->whereNull('weekend_day')->orWhereNull('work_hour');
                    })
                    ->orWhere(function ($innerInnerQuery) {
                        $innerInnerQuery->where('post_id', 0)
                            ->orWhereNull('post_id')
                            ->orWhereNull('jobtype_id')
                            ->orWhere('jobtype_id', 0)
                            ->orWhereNull('branch_id')
                            ->orWhere('branch_id', 0)
                            ->orWhereNull('staff_central_id')
                            ->orWhereNull('appo_date');
                    })
                    ->orWhere(function ($permanentQuery) {
                        $permanentQuery->warningNoPermanentDateForPermanentStaff();
                    })
                    ->orWhere(function ($bankQuery) {
                        $bankQuery->warningNoBankForBankAccountStaff();
                    })->orWhere(function ($temporaryContractQuery) {
                        $temporaryContractQuery->warningNoTemporaryConDate();
                    });
            });
        } else {
            if ($request_warning_option === 'no_work_schedule') {
                $warningStaffMains = $warningStaffMains->warningNoWorkSchedule();
            } elseif ($request_warning_option === 'no_weekend') {
                $warningStaffMains = $warningStaffMains->warningNoWeekend();
            } elseif ($request_warning_option === 'no_work_hour') {
                $warningStaffMains = $warningStaffMains->warningNoWorkHour();
            } elseif ($request_warning_option === 'no_post_id') {
                $warningStaffMains = $warningStaffMains->warningNoPostId();
            } elseif ($request_warning_option === 'no_job_type') {
                $warningStaffMains = $warningStaffMains->warningNoJobType();
            } elseif ($request_warning_option === 'no_branch') {
                $warningStaffMains = $warningStaffMains->warningNoBranch();
            } elseif ($request_warning_option === 'no_date_of_birth') {
                $warningStaffMains = $warningStaffMains->warningNoDateOfBirth();
            } elseif ($request_warning_option === 'no_appo_date') {
                $warningStaffMains = $warningStaffMains->warningNoAppoDate();
            } elseif ($request_warning_option === 'no_central_id') {
                $warningStaffMains = $warningStaffMains->warningNoStaffCentralId();
            } elseif ($request_warning_option === 'no_permanent_date_for_permanent_staff') {
                $warningStaffMains = $warningStaffMains->warningNoPermanentDateForPermanentStaff();
            } elseif ($request_warning_option === 'no_bank_for_bank_account_staff') {
                $warningStaffMains = $warningStaffMains->warningNoBankForBankAccountStaff();
            } elseif ($request_warning_option === 'no_temporary_con_date') {
                $warningStaffMains = $warningStaffMains->warningNoTemporaryConDate();
            }
        }


        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');

        $staffmains = $warningStaffMains->orderBy('name_eng', 'asc')->paginate($records_per_page);
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        $departments = $this->departmentRepository->getAllDepartments();
        $staff_types = StaffType::pluck('staff_type_title', 'staff_type_code');
        $jobTypes = $this->systemJobTypeMastRepository->getAllJobTypes()->pluck('jobtype_name', 'jobtype_id');
        $designations = $this->systemPostMastRepository->getAllPosts()->pluck('post_title', 'post_id');
        $shifts = $this->shiftRepository->getAllShiftsByBranch($request->branch_id)->pluck('shift_name', 'id');
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $weekend_days = Config::get('constants.weekend_days');
        return view('staffmain.warning', ['title' => 'Staff Main',
            'staffmains' => $staffmains,
            'branches' => $branches,
            'departments' => $departments,
            'staff_types' => $staff_types,
            'jobTypes' => $jobTypes,
            'designations' => $designations,
            'shifts' => $shifts,
            'records_per_page' => $records_per_page,
            'records_per_page_options' => $records_per_page_options,
            'weekend_days' => $weekend_days,
            'warning_options' => $warning_options]);

    }

    public function staffLeaveImport(Request $request)
    {
        $path = $request->file('excel_file');
        $datas = Excel::formatDates(true)->load($path, function ($reader) {
        })->all();

        try {
            DB::beginTransaction();
            $fiscal_years = FiscalYearModel::get();
            foreach ($datas as $data) {
                $date = $data['date']->format('Y - m - d');
                $date_np = BSDateHelper::AdToBs(' - ', $date);
                $date = BSDateHelper::BsToAd('-', $date_np);
                $fiscal_year = $fiscal_years->where('fiscal_start_date', '<=', $date)->where('fiscal_end_date', '>=', $date)->last()->id ?? 4;
                $staff = StafMainMastModel::query();
                if (!empty($data['cid'])) {
                    $staff = $staff->where('staff_central_id', $data['cid']);
                } else {
                    $staff = $staff->where('payroll_branch_id', $request->branch_id)->where('main_id', $data['main_id']);
                }
                $staff = $staff->first();

                if (!empty($staff)) {
                    $staff_id = $staff->id;
                    /* if ($data['job_type'] == "P") {
                         $jobtype_id = 1;
                     } elseif ($data['job_type'] == "NP") {
                         $jobtype_id = 2;
                     } elseif ($data['job_type'] == "CON") {
                         $jobtype_id = 3;
                     } elseif ($data['job_type'] == "CON1") {
                         $jobtype_id = 4;
                     } else {
                         $jobtype_id = 5;
                     }
                     $staff->jobtype_id = $jobtype_id;
                     if (!empty($data['temporary_date'])) {
                         $staff->temporary_con_date = date('Y - m - d', strtotime($data['temporary_date']));
                         $staff->temporary_con_date_np = BSDateHelper::AdToBs(' - ', $staff->temporary_con_date);

                     }
                     if (!empty($data['permanent_date'])) {
                         $staff->permanent_date = date('Y - m - d', strtotime($data['permanent_date']));
                         $staff->permanent_date_np = BSDateHelper::AdToBs(' - ', $staff->permanent_date);
                     }
                     if (!empty($data['staff_central_id'])) {
                         $staff->staff_central_id = $data['staff_central_id'];
                     }
                     $staff->save();*/


                    $leave_balances = LeaveBalance::where('staff_central_id', $staff_id)->get();

                    $leave_balance = $leave_balances->where('leave_id', 7)->last();
                    if (empty($leave_balance)) {
                        $leave_balance = new LeaveBalance();
                    }
                    $leave_balance->staff_central_id = $staff_id;
                    $leave_balance->leave_id = 7;
                    $leave_balance->fy_id = $fiscal_year;
                    $leave_balance->description = "Excel Import";
                    $leave_balance->consumption = 0;
                    $leave_balance->earned = 0;
                    $leave_balance->balance = empty($data['home_leave']) ? 0 : $data['home_leave'];
                    $leave_balance->authorized_by = Auth::id();
                    $leave_balance->date = $date;
                    $leave_balance->date_np = $date_np;
                    $leave_balance->save();

                    $leave_balance = $leave_balances->where('leave_id', 8)->last();
                    if (empty($leave_balance)) {
                        $leave_balance = new LeaveBalance();
                    }
                    $leave_balance->staff_central_id = $staff_id;
                    $leave_balance->leave_id = 8;
                    $leave_balance->fy_id = $fiscal_year;
                    $leave_balance->description = "Excel Import";
                    $leave_balance->consumption = 0;
                    $leave_balance->earned = 0;
                    $leave_balance->balance = empty($data['sick_leave']) ? 0 : $data['sick_leave'];
                    $leave_balance->authorized_by = Auth::id();
                    $leave_balance->date = $date;
                    $leave_balance->date_np = $date_np;
                    $leave_balance->save();

                    $leave_balance = $leave_balances->where('leave_id', 9)->last();
                    if (empty($leave_balance)) {
                        $leave_balance = new LeaveBalance();
                    }
                    $leave_balance->staff_central_id = $staff_id;
                    $leave_balance->leave_id = 9;
                    $leave_balance->fy_id = $fiscal_year;
                    $leave_balance->description = "Excel Import";
                    $leave_balance->consumption = 0;
                    $leave_balance->earned = 0;
                    $leave_balance->balance = empty($data['maternity_leave']) ? 0 : $data['maternity_leave'];
                    $leave_balance->authorized_by = Auth::id();
                    $leave_balance->date = $date;
                    $leave_balance->date_np = $date_np;
                    $leave_balance->save();

                    $leave_balance = $leave_balances->where('leave_id', 11)->last();
                    if (empty($leave_balance)) {
                        $leave_balance = new LeaveBalance();
                    }
                    $leave_balance->staff_central_id = $staff_id;
                    $leave_balance->leave_id = 11;
                    $leave_balance->fy_id = $fiscal_year;
                    $leave_balance->description = "Excel Import";
                    $leave_balance->consumption = 0;
                    $leave_balance->earned = 0;
                    $leave_balance->balance = empty($data['funeral_leave']) ? 0 : $data['funeral_leave'];
                    $leave_balance->authorized_by = Auth::id();
                    $leave_balance->date = $date;
                    $leave_balance->date_np = $date_np;
                    $leave_balance->save();


                    $leave_balance = $leave_balances->where('leave_id', 13)->last();
                    if (empty($leave_balance)) {
                        $leave_balance = new LeaveBalance();
                    }
                    $leave_balance->staff_central_id = $staff_id;
                    $leave_balance->leave_id = 13;
                    $leave_balance->fy_id = $fiscal_year;
                    $leave_balance->description = "Excel Import";
                    $leave_balance->consumption = 0;
                    $leave_balance->earned = 0;
                    $leave_balance->balance = empty($data['leave_without_pay']) ? 0 : $data['leave_without_pay'];
                    $leave_balance->authorized_by = Auth::id();
                    $leave_balance->date = $date;
                    $leave_balance->date_np = $date_np;
                    $leave_balance->save();

                    $leave_balance = $leave_balances->where('leave_id', 12)->last();
                    if (empty($leave_balance)) {
                        $leave_balance = new LeaveBalance();
                    }
                    $leave_balance->staff_central_id = $staff_id;
                    $leave_balance->leave_id = 12;
                    $leave_balance->fy_id = $fiscal_year;
                    $leave_balance->description = "Excel Import";
                    $leave_balance->consumption = 0;
                    $leave_balance->earned = 0;
                    $leave_balance->balance = empty($data['substitute_leave']) ? 0 : $data['substitute_leave'];
                    $leave_balance->authorized_by = Auth::id();
                    $leave_balance->date = $date;
                    $leave_balance->date_np = $date_np;
                    $leave_balance->save();

                    $leave_balance = $leave_balances->where('leave_id', 10)->last();
                    if (empty($leave_balance)) {
                        $leave_balance = new LeaveBalance();
                    }
                    $leave_balance->staff_central_id = $staff_id;
                    $leave_balance->leave_id = 10;
                    $leave_balance->fy_id = $fiscal_year;
                    $leave_balance->description = "Excel Import";
                    $leave_balance->consumption = 0;
                    $leave_balance->earned = 0;
                    $leave_balance->balance = empty($data['maternity_care_leave']) ? 0 : $data['maternity_care_leave'];
                    $leave_balance->authorized_by = Auth::id();
                    $leave_balance->date = $date;
                    $leave_balance->date_np = $date_np;
                    $leave_balance->save();
                } else {
                    DB::rollBack();
                    echo 'CID ' . $data['cid'] . 'Branch ID' . $data['main_id'] . ' is not found! Please resolve this issue and try again';
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }

        DB::commit();
        echo 'Completed ....';
    }

    public function staffSundryImport(Request $request)
    {
        $path = $request->file('excel_file');
        $datas = Excel::formatDates(true)->load($path, function ($reader) {
        })->all();

        try {
            DB::beginTransaction();

            foreach ($datas as $data) {
                $date = $data['date']->format('Y - m - d');
                $staff = StafMainMastModel::query();
                if (!empty($data['cid'])) {
                    $staff = $staff->where('staff_central_id', $data['cid']);
                } else {
                    $staff = $staff->where(function ($query) use ($request, $data) {
                        $query->where('payroll_branch_id', $request->branch_id)->where('main_id', $data['main_id']);
                    });
                }
                $staff = $staff->first();

                if (!empty($staff)) {
                    $staff_central_id = $staff->id;

                    $sundry = new SundryTransaction();
                    $sundry->staff_central_id = $staff_central_id;
                    $sundry_type = ($data['sundry_balance'] > 0) ? 1 : 2;
                    $no_installment = 1;
                    $installment_amount = abs($data['sundry_balance']);
                    $amount = abs($data['sundry_balance']);
                    $is_cr = SundryType::isCR($sundry_type);
                    $sundry->transaction_type_id = $sundry_type;
                    if ($is_cr) { //cr
                        $sundry->cr_installment = $no_installment;
                        $sundry->cr_amount = $installment_amount; // installment amount
                        $sundry->cr_balance = $amount;
                    } else { //dr
                        $sundry->dr_installment = $no_installment;
                        $sundry->dr_amount = $installment_amount; // installment amount
                        $sundry->dr_balance = $amount;
                    }
                    $sundry->transaction_date = BSDateHelper::AdToBs(' - ', $date);
                    $sundry->transaction_date_en = $date;
                    $sundry->notes = $data['notes'];
                    $user_id = \Auth::user()->id;
                    $sundry->created_by = $user_id;
                    if ($sundry->save()) {
                        //record of the transactions
                        $sundry_transaction_log = new SundryTransactionLog();
                        $sundry_transaction_log->sundry_id = $sundry->id;
                        $sundry_transaction_log->staff_central_id = $staff_central_id;
                        $sundry_transaction_log->transaction_date = BSDateHelper::BsToAd(' - ', $date);
                        $sundry_transaction_log->transaction_date_en = $date;
                        $sundry_transaction_log->notes = 'Begining Transaction';
                        $is_cr = SundryType::isCR($sundry_type);
                        $sundry_transaction_log->transaction_type_id = $sundry_type;
                        if ($is_cr) { //cr
                            $sundry_transaction_log->cr_installment = $no_installment;
                            $sundry_transaction_log->cr_amount = $installment_amount; // installment amount
                            $sundry_transaction_log->cr_balance = $amount;
                        } else { //dr
                            $sundry_transaction_log->dr_installment = $no_installment;
                            $sundry_transaction_log->dr_amount = $installment_amount; // installment amount
                            $sundry_transaction_log->dr_balance = $amount;
                        }
                        $sundry_transaction_log->save();

                        //also update the main master account of that same employee
                        $sundry_balance = SundryBalance::where('staff_central_id', $staff_central_id)->first();
                        if (!empty($sundry_balance)) { //check is empty that means first transaction
                            if ($is_cr) { //cr
                                $sundry_balance->cr_installment = $sundry_balance->cr_installment + $no_installment;
                                $sundry_balance->cr_amount = $sundry_balance->cr_amount + $installment_amount; // installment amount
                                $sundry_balance->cr_balance = $sundry_balance->cr_balance + $amount;
                            } else { //dr
                                $sundry_balance->dr_installment = $sundry_balance->dr_installment + $no_installment;
                                $sundry_balance->dr_amount = $sundry_balance->dr_amount + $installment_amount; // installment amount
                                $sundry_balance->dr_balance = $sundry_balance->dr_balance + $amount;
                            }
                        } else { //make new master account for the employee
                            $sundry_balance = new SundryBalance();
                            $sundry_balance->dr_installment = $sundry->dr_installment;
                            $sundry_balance->dr_amount = $sundry->dr_amount;
                            $sundry_balance->dr_balance = $sundry->dr_balance;
                            $sundry_balance->cr_installment = $sundry->cr_installment;
                            $sundry_balance->cr_amount = $sundry->cr_amount;
                            $sundry_balance->cr_balance = $sundry->cr_balance;
                        }
                        $sundry_balance->staff_central_id = $sundry->staff_central_id;
                        $sundry_balance->transaction_date = $sundry->transaction_date;
                        $sundry_balance->transaction_date_en = $sundry->transaction_date_en;
                        $sundry_balance->transaction_type_id = $sundry->transaction_type_id;
                        $sundry_balance->notes = $sundry->notes;
                        $sundry_balance->status = 0;
                        $sundry_balance->created_by = $user_id;
                        if ($sundry_balance->save()) {
                            $status_mesg = true;
                        }
                    }

                }

            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }

        DB::commit();
        echo 'Completed ....';
    }

    public function staffGradeRevisonImport(Request $request)
    {
        ini_set('max_execution_time', 1500);
        $path = $request->file('excel_file');
        $datas = Excel::formatDates(true)->load($path, function ($reader) {
        })->all();
        $deleted_work_hour = 0;
        foreach ($datas as $data) {
            $fetch_attendances = FetchAttendance::where('staff_central_id', $data['staff_central_id'])->whereDate('punchin_datetime', $data['punchin_date'])->get();
            if ($fetch_attendances->count() == 2) {
                $one = $fetch_attendances->first();
                $two = $fetch_attendances->last();
                if ($one->total_work_hour == $two->total_work_hour) {
                    $deleted_work_hour += $two->total_work_hour;
                    $two->delete();
                } elseif (!empty($one->punchout_datetime) && empty($two->punchout_datetime)) {
                    $two->delete();
                } elseif (!empty($two->punchout_datetime) && empty($one->punchout_datetime)) {
                    $one->delete();
                } else {
                    dd($one, $two);
                }
            } else {
//                dd($data);
            }
        }
        dd($deleted_work_hour);

        /*try {
            DB::beginTransaction();

            foreach ($datas as $data) {
                $date = $data['date']->format('Y - m - d');
                $date_np = BSDateHelper::AdToBs(' - ', $date);
                $staff = StafMainMastModel::query();
                if (!empty($data['cid'])) {
                    $staff = $staff->where('staff_central_id', $data['cid']);
                } else {
                    $staff = $staff->where(function ($query) use ($request, $data) {
                        $query->where('payroll_branch_id', $request->branch_id)->where('main_id', $data['main_id']);
                    });
                }
                $staff = $staff->first();
                if (!empty($staff)) {
                    if (strcasecmp($staff->jobtype->jobtype_code, "P") == 0) {
                        $additionalGradeThisFiscalYear = $data['grade_amount'];
                        $previousFiscalYearGrade = 0;
                        $previousFiscalYearBasicSalary = 0;
                        $previousFiscalYearAdditionalSalary = 0;

                        $effectiveDateFiscalYear = FiscalYearModel::where('fiscal_start_date', ' <= ', $date)->where('fiscal_end_date', ' >= ', $date)->first();
                        $previousFiscalYearDate = date('Y - m - d', strtotime(' - 30 days', strtotime($effectiveDateFiscalYear->fiscal_start_date)));
                        $previousFiscalYear = FiscalYearModel::where('fiscal_start_date', ' <= ', $previousFiscalYearDate)->where('fiscal_end_date', ' >= ', $previousFiscalYearDate)->first();

                        $staffSalaryMastPreviousFiscalYear = StaffSalaryModel::where('staff_central_id', $staff->id)->where('fiscal_year_id', $previousFiscalYear->id)->orderByDesc('salary_effected_date')->first();
                        if (!empty($staffSalaryMastPreviousFiscalYear)) {
                            $previousFiscalYearGrade = ($staffSalaryMastPreviousFiscalYear->total_grade_amount ?? 0) + ($staffSalaryMastPreviousFiscalYear->add_grade_this_fiscal_year ?? 0);
                            $previousFiscalYearBasicSalary = $staffSalaryMastPreviousFiscalYear->basic_salary;
                            $previousFiscalYearAdditionalSalary = $staffSalaryMastPreviousFiscalYear->add_salary_amount;
                        }
                        $staffSalaryMast = StaffSalaryModel::where('staff_central_id', $staff->id)->whereDate('salary_effected_date', $date)->first();
                        if (empty($staffSalaryMast)) {
                            $staffSalaryMast = new StaffSalaryModel();
                        }
                        $staffSalaryMast->staff_central_id = $staff->id;
                        $staffSalaryMast->post_id = $staff->post_id;
                        $staffSalaryMast->fiscal_year_id = $effectiveDateFiscalYear->id;
                        if (!empty($staff->jobtype)) {
                            if (strcasecmp($staff->jobtype->jobtype_code, 'Con') == 0 || strcasecmp($staff->jobtype->jobtype_code, 'Con1') == 0) {
                                $staffSalaryMast->basic_salary = $previousFiscalYearBasicSalary;
                            } else {
                                $staffSalaryMast->basic_salary = $staff->jobposition->basic_salary;
                            }
                        }
                        $staffSalaryMast->add_salary_amount = $previousFiscalYearAdditionalSalary;
                        $staffSalaryMast->total_grade_amount = $data['previous_grade_amount'] ?? $previousFiscalYearGrade;
                        $staffSalaryMast->add_grade_this_fiscal_year = $additionalGradeThisFiscalYear;
                        $staffSalaryMast->salary_effected_date = $date;
                        $staffSalaryMast->salary_effected_date_np = $date_np;
                        $staffSalaryMast->salary_payment_status = 'A';
                        $staffSalaryMast->created_by = Auth::id();
                        $staffSalaryMast->updated_by = Auth::id();
                        $staffSalaryMast->save();
                    } else {
                        dd('This staff is not recorded as permanent ' . $staff->name_eng);
                    }
                } else {
                    dd($staff);
                }
            }
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
        }*/
        DB::commit();
        echo 'Completed ......';
    }

    public function checkStaffDataWithExcel(Request $request)
    {
        $path = $request->file('excel_file');
        $datas = Excel::formatDates(true)->load($path, function ($reader) {
        })->all();
        $branch_id = $request->branch_id;
        $branch = SystemOfficeMastModel::find($branch_id);
        $errors = [];
        $error_count = 0;
        foreach ($datas as $data) {
            $staff_detail = StafMainMastModel::with('jobtype', 'payment', 'jobposition', 'additionalSalary', 'payrollBranch')->whereIn('staff_type', [0, 1]);
            if (!empty($data['cid'])) {
                $staff_detail = $staff_detail->where('staff_central_id', $data['cid'])->first();
            } else {
                $staff_detail = $staff_detail->where(function ($query) use ($data, $branch_id) {
                    $query->where('main_id', $data['branch_id']);
                    $query->where('payroll_branch_id', $branch_id);
                })->first();
            }
            if (!empty($staff_detail)) {

                if ($staff_detail->payroll_branch_id != $branch_id || $staff_detail->branch_id != $branch_id) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->payrollBranch->office_name ?? '';
                    $errors[$error_count]['correct_record'] = $branch->office_name;
                    $errors[$error_count]['error'] = "Payroll Branch Mismatch";
                    $error_count++;
                }
                if ($staff_detail->payroll_branch_id != $branch_id && $staff_detail->branch_id != $branch_id) {
                    continue;
                }
                if (!empty($staff_detail->jobtype->jobtype_code)) {
                    if (strcasecmp($staff_detail->jobtype->jobtype_code, $data['job_type']) != 0) {
                        $errors[$error_count]['id'] = $staff_detail->id;
                        $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                        $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                        $errors[$error_count]['name'] = $staff_detail->name_eng;
                        $errors[$error_count]['current_record'] = $staff_detail->jobtype->jobtype_code ?? '';
                        $errors[$error_count]['correct_record'] = $data['job_type'];
                        $errors[$error_count]['error'] = "Working Position Mismatch";
                        $error_count++;
                    }
                } else {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->jobtype->jobtype_code ?? '';
                    $errors[$error_count]['correct_record'] = $data['job_type'];
                    $errors[$error_count]['error'] = "No Job Type!";
                    $error_count++;
                }


                if ($staff_detail->pan_number != $data['pan_number']) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->pan_number ?? '';
                    $errors[$error_count]['correct_record'] = $data['pan_number'];
                    $errors[$error_count]['error'] = "Pan Number Mismatch";
                    $error_count++;
                }
                $gender = 1;
                if (strcasecmp($data['gender'], 'F') == 0) {
                    $gender = 2;
                }
                if ($staff_detail->Gender != $gender) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->Gender == 1 ? 'Male' : 'Female';
                    $errors[$error_count]['correct_record'] = $gender == 1 ? 'Male' : 'Female';
                    $errors[$error_count]['error'] = "Gender Mismatch";
                    $error_count++;
                }

                $extra_allow = 0;
                if (strcasecmp($data['extra_allow'], 'A') == 0) {
                    $extra_allow = 1;
                }
                if ($staff_detail->extra_allow != $extra_allow) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->extra_allow == 0 ? 'A' : 'B';
                    $errors[$error_count]['correct_record'] = $extra_allow == 0 ? 'A' : 'B';
                    $errors[$error_count]['error'] = "Extra Allowance Main Record Mismatch";
                    $error_count++;
                }
                if (($staff_detail->payment->where('allow_id', 8)->last()->allow ?? 0) != $extra_allow) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = ($staff_detail->payment->where('allow_id', 8)->last()->allow ?? 0);
                    $errors[$error_count]['correct_record'] = $extra_allow;
                    $errors[$error_count]['error'] = "Extra Allowance Relational Record Mismatch";
                    $error_count++;
                }

                $extra_dashain_allow = 0;
                if (strcasecmp($data['extra_facility_in_dashai_tihar'], 'G') == 0) {
                    $extra_dashain_allow = 1;
                }
                if ($staff_detail->dashain_allow != $extra_dashain_allow) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->dashain_allow == 0 ? 'G' : 'H';
                    $errors[$error_count]['correct_record'] = $extra_dashain_allow == 0 ? 'G' : 'H';
                    $errors[$error_count]['error'] = "Extra Dashain Tihar Allowance Main Record Mismatch";
                    $error_count++;
                }
                if (($staff_detail->payment->where('allow_id', 9)->last()->allow ?? 0) != $extra_dashain_allow) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = ($staff_detail->payment->where('allow_id', 9)->last()->allow ?? 0);
                    $errors[$error_count]['correct_record'] = $extra_dashain_allow;
                    $errors[$error_count]['error'] = "Extra Dashain Tihar Allowance Relational Record Mismatch";
                    $error_count++;
                }
                if (strtotime($staff_detail->temporary_con_date) != strtotime($data['temporary_con_date'])) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->temporary_con_date . " (" . ((!empty($staff_detail->temporary_con_date)) ? BSDateHelper::AdToBs('-', $staff_detail->temporary_con_date) : "") . ")";
                    $errors[$error_count]['correct_record'] = (!empty($data['temporary_con_date']) ? date('Y-m-d', strtotime($data['temporary_con_date'])) : "") . " (" .
                        ((!empty($data['temporary_con_date']) && isset($data['temporary_con_date'])) ? BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($data['temporary_con_date']))) : "") . ")";
                    $errors[$error_count]['error'] = "Temporary Date Mismatch";
                    $error_count++;
                }

                if (strtotime($staff_detail->permanent_date) != strtotime($data['permanent_date'])) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->permanent_date . " (" . ((!empty($staff_detail->permanent_date)) ? BSDateHelper::AdToBs('-', $staff_detail->permanent_date) : "") . ")";
                    $errors[$error_count]['correct_record'] = (!empty($data['permanent_date']) ? date('Y-m-d', strtotime($data['permanent_date'])) : "") . " (" .
                        ((!empty($data['permanent_date']) && isset($data['permanent_date'])) ? BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($data['permanent_date']))) : "") . ")";
                    $errors[$error_count]['error'] = "Permanent Date Mismatch";
                    $error_count++;
                }
                if (strtotime($staff_detail->date_birth) != strtotime($data['date_birth'])) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->date_birth . " (" . ((!empty($staff_detail->date_birth)) ? BSDateHelper::AdToBs('-', $staff_detail->date_birth) : "") . ")";

                    $errors[$error_count]['correct_record'] = (!empty($data['date_birth']) ? date('Y-m-d', strtotime($data['date_birth'])) : "") .
                        " (" . ((!empty($data['date_birth']) && isset($data['date_birth'])) ? BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($data['date_birth']))) : "") . ")";
                    $errors[$error_count]['error'] = "Date of Birth Mismatch";
                    $error_count++;
                }

                if ($staff_detail->acc_no != $data['acc_no']) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->acc_no;
                    $errors[$error_count]['correct_record'] = $data['acc_no'];
                    $errors[$error_count]['error'] = "Bank Account Number Mismatch";
                    $error_count++;
                }

                if ($staff_detail->profund_acc_no != $data['profund_acc_no']) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->profund_acc_no;
                    $errors[$error_count]['correct_record'] = $data['profund_acc_no'];
                    $errors[$error_count]['error'] = "Profund Account Number Mismatch";
                    $error_count++;
                }
                if ($staff_detail->social_security_fund_acc_no != $data['social_security_fund_acc_no']) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->social_security_fund_acc_no;
                    $errors[$error_count]['correct_record'] = $data['social_security_fund_acc_no'];
                    $errors[$error_count]['error'] = "Social Security Account Number Mismatch";
                    $error_count++;
                }
                $basic_salary = $staff_detail->jobposition->basic_salary;
                if (!empty($staff_detail->jobtype->jobtype_code)) {
                    $job_type_details = $staff_detail->jobtype;
                    if (strcasecmp($job_type_details->jobtype_code, "Con") == 0 || strcasecmp($job_type_details->jobtype_code, "Con1") == 0) {
                        $basic_salary = $staff_detail->additionalSalary->last()->basic_salary;
                    }
                } else {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->jobtype->jobtype_code ?? '';
                    $errors[$error_count]['correct_record'] = $data['job_type'];
                    $errors[$error_count]['error'] = "No Job Type!";
                    $error_count++;
                }

                if ($basic_salary != $data['basic_salary']) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $basic_salary;
                    $errors[$error_count]['correct_record'] = $data['basic_salary'];
                    $errors[$error_count]['error'] = "Basic Salary Mismatch/Designation Mismatch";
                    $error_count++;
                }

                $grade_amount = ($staff_detail->additionalSalary->last()->total_grade_amount ?? 0) + ($staff_detail->additionalSalary->last()->add_grade_this_fiscal_year ?? 0);
                if ($grade_amount != ($data['current_grade'] + $data['previous_grade'])) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $grade_amount;
                    $errors[$error_count]['correct_record'] = $data['current_grade'] + $data['previous_grade'];
                    $errors[$error_count]['error'] = "Grade Amount Mismatch";
                    $error_count++;
                }


                if ($staff_detail->dearness_allowance_amount != $data['dearness_allowance_amount']) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->dearness_allowance_amount;
                    $errors[$error_count]['correct_record'] = $data['dearness_allowance_amount'];
                    $errors[$error_count]['error'] = "Dearness Allowance Main Record Mismatch";
                    $error_count++;
                }
                if (($staff_detail->payment->where('allow_id', 1)->last()->amount ?? 0) != $data['dearness_allowance_amount']) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = ($staff_detail->payment->where('allow_id', 1)->last()->amount ?? 0);
                    $errors[$error_count]['correct_record'] = $data['dearness_allowance_amount'];
                    $errors[$error_count]['error'] = "Dearness Allowance Relational Record Mismatch";
                    $error_count++;
                }

                if ($staff_detail->risk_allowance_amount != $data['risk_allowance_amount']) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->risk_allowance_amount;
                    $errors[$error_count]['correct_record'] = $data['risk_allowance_amount'];
                    $errors[$error_count]['error'] = "Risk Allowance Main Record Mismatch";
                    $error_count++;
                }
                if (($staff_detail->payment->where('allow_id', 2)->last()->amount ?? 0) != $data['risk_allowance_amount']) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = ($staff_detail->payment->where('allow_id', 2)->last()->amount ?? 0);
                    $errors[$error_count]['correct_record'] = $data['risk_allowance_amount'];
                    $errors[$error_count]['error'] = "Risk Allowance Relational Record Mismatch";
                    $error_count++;
                }

                if ($staff_detail->special_allowance_amount != $data['special_allowance_amount']) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->special_allowance_amount;
                    $errors[$error_count]['correct_record'] = $data['special_allowance_amount'];
                    $errors[$error_count]['error'] = "Special Allowance Main Record Mismatch";
                    $error_count++;
                }
                if (($staff_detail->payment->where('allow_id', 3)->last()->amount ?? 0) != $data['special_allowance_amount']) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = ($staff_detail->payment->where('allow_id', 3)->last()->amount ?? 0);
                    $errors[$error_count]['correct_record'] = $data['special_allowance_amount'];
                    $errors[$error_count]['error'] = "Special Allowance Relational Record Mismatch";
                    $error_count++;
                }

                if ($staff_detail->special_allowance_2_amount != $data['special_allowance_2_amount']) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->special_allowance_2_amount;
                    $errors[$error_count]['correct_record'] = $data['special_allowance_2_amount'];
                    $errors[$error_count]['error'] = "Misc. Allowance Main Record Mismatch";
                    $error_count++;
                }
                if (($staff_detail->payment->where('allow_id', 4)->last()->amount ?? 0) != $data['special_allowance_2_amount']) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = ($staff_detail->payment->where('allow_id', 4)->last()->amount ?? 0);
                    $errors[$error_count]['correct_record'] = $data['special_allowance_2_amount'];
                    $errors[$error_count]['error'] = "Misc. Allowance Relational Record Mismatch";
                    $error_count++;
                }

                if ($staff_detail->incentive_amount != $data['incentive_amount']) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = $staff_detail->incentive_amount;
                    $errors[$error_count]['correct_record'] = $data['incentive_amount'];
                    $errors[$error_count]['error'] = "Incentive Main Record Mismatch";
                    $error_count++;
                }
                if (($staff_detail->payment->where('allow_id', 6)->last()->amount ?? 0) != $data['incentive_amount']) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = ($staff_detail->payment->where('allow_id', 6)->last()->amount ?? 0);
                    $errors[$error_count]['correct_record'] = $data['incentive_amount'];
                    $errors[$error_count]['error'] = "Incentive Relational Record Mismatch";
                    $error_count++;
                }
                $marital_status = 0;
                if (strcasecmp($data['marital_status'], 'M') == 0) {
                    $marital_status = 1;
                }
                if ($staff_detail->marrid_stat != $marital_status) {
                    $errors[$error_count]['id'] = $staff_detail->id;
                    $errors[$error_count]['staff_central_id'] = $staff_detail->staff_central_id;
                    $errors[$error_count]['branch_id'] = $staff_detail->main_id;
                    $errors[$error_count]['name'] = $staff_detail->name_eng;
                    $errors[$error_count]['current_record'] = ($staff_detail->marrid_stat == 0) ? 'Unmarried' : 'Married';
                    $errors[$error_count]['correct_record'] = ($marital_status == 0) ? 'Unmarried' : 'Married';
                    $errors[$error_count]['error'] = "Marital Status Mismatch";
                    $error_count++;
                }

            } else {
                $errors[$error_count]['id'] = null;
                $errors[$error_count]['staff_central_id'] = $data['cid'];
                $errors[$error_count]['branch_id'] = $data['branch_id'];
                $errors[$error_count]['name'] = $data['name'];
                $errors[$error_count]['current_record'] = '';
                $errors[$error_count]['correct_record'] = '';
                $errors[$error_count]['error'] = "Staff Not Found";
                $error_count++;
            }

        }
        $data['list_errors'] = $errors;
        $data['title'] = 'Tally Error';
        return view('staffmain.excel.tallyerror', $data);
    }

    public function importStaffAllowance(Request $request)
    {
        ini_set('max_execution_time', 1500);
        $path = $request->file('excel_file');
        $datas = Excel::formatDates(true)->load($path, function ($reader) {
        })->all();
        $allowances = AllowanceModelMast::get();
        $fiscal_year = FiscalYearModel::where('fiscal_status', 1)->first();
        foreach ($datas as $data) {
            try {
                DB::beginTransaction();
                $staff_central_id = $data['cid'];
                $main_id = $data['main_id'];
                $branch_id = $request->branch_id;
                $staff = StafMainMastModel::where(function ($query) use ($staff_central_id, $branch_id) {
                    $query->where('staff_central_id', $staff_central_id)->where('branch_id', $branch_id);
                })->orWhere(function ($query) use ($main_id, $branch_id) {
                    $query->where('main_id', $main_id)->where('branch_id', $branch_id);
                })->first();
                $status_mesg = true;
                if (!empty($staff)) {
                    /*foreach ($allowances as $allowance) {
                        $code = strtolower($allowance->allow_code);
                        if (isset($data[$code])) {
                            $staff_payment = new StaffPaymentMast();
                            $staff_payment->staff_central_id = $staff->id;
                            $staff_payment->allow_id = $allowance->allow_id;
                            $staff_payment->created_by = Auth::id();
                            $staff_payment->allow = ($data[$code] != 0) ? 1 : 0;
                            $staff_payment->amount = $data[$code] ?? 0;
                            $staff_payment->effective_from = (!empty($data[$code . '_effect_date']) && isset($data[$code . '_effect_date'])) ? $data[$code . '_effect_date'] : ($fiscal_year->fiscal_start_date ?? date('Y-m-d'));
                            if (strtotime($staff_payment->effective_from) < strtotime($staff->appo_date)) {
                                $staff_payment->effective_from = $staff->appo_date;
                            }
                            $staff_payment->effective_from_np = BSDateHelper::AdToBs('-', date('Y-m-d', strtotime($staff_payment->effective_from)));
                            $status_mesg = $staff_payment->save();
                        }
                    }

                    if (isset($data['before_grade_value']) && isset($data['before_grade_date'])) {
                        if (!empty($data['before_grade_value'])) {
                            $staffGrade = new StaffGrade();
                            $staffGrade->staff_central_id = $staff->id;
                            $staffGrade->grade_id = $data['before_grade_value'];
                            $staffGrade->effective_from_date = date('Y-m-d', strtotime($data['before_grade_date']));
                            $staffGrade->effective_from_date_np = BSDateHelper::AdToBs('-', $staffGrade->effective_from_date);
                            if (isset($data['after_grade_value']) && isset($data['after_grade_date'])) {
                                if (!empty($data['after_grade_date'])) {
                                    $staffGrade->effective_to_date = date('Y-m-d', strtotime($data['after_grade_date']));
                                    $staffGrade->effective_to_date_np = BSDateHelper::AdToBs('-', $staffGrade->effective_to_date);
                                }

                            }
                            $staffGrade->created_by = Auth::id();
                            $staffGrade->save();
                        }
                    }
                    if (isset($data['after_grade_value']) && isset($data['after_grade_date'])) {
                        if (!empty($data['after_grade_date'])) {
                            $staffGrade = new StaffGrade();
                            $staffGrade->staff_central_id = $staff->id;
                            $staffGrade->grade_id = $data['after_grade_value'];
                            $staffGrade->effective_from_date = date('Y-m-d', strtotime($data['after_grade_date']));
                            $staffGrade->effective_from_date_np = BSDateHelper::AdToBs('-', $staffGrade->effective_from_date);
                            $staffGrade->created_by = Auth::id();
                            $staffGrade->save();
                        }
                    }
                    */

                    /*if (!empty($data['house_loan_income'])) {
                        $houseloan = new HouseLoanModelMast();
                        $houseloan->staff_central_id = $staff->id;
                        $houseloan->trans_date = '2020-03-14';
                        $houseloan->loan_amount = 1;
                        $houseloan->no_installment = 1;
                        $houseloan->installment_amount = 1;
                        $houseloan->autho_id = Auth::id();
                        $houseloan->save();

                        $houseLoanDiffIncome = new HouseLoanDiffIncome();
                        $houseLoanDiffIncome->fiscal_year_id = 4;
                        $houseLoanDiffIncome->house_loan_id = $houseloan->house_id;
                        $houseLoanDiffIncome->created_by = auth()->id();
                        $houseLoanDiffIncome->diff_income = $data['house_loan_income'];
                        $houseLoanDiffIncome->save();
                    }

                    if (!empty($data['vehicle_loan_income'])) {
                        $vehicleLoan = new VehicalLoanModelTrans();
                        $vehicleLoan->staff_central_id = $staff->id;
                        $vehicleLoan->trans_date = '2020-03-14';
                        $vehicleLoan->loan_amount = 1;
                        $vehicleLoan->no_installment = 1;
                        $vehicleLoan->installment_amount = 1;
                        $vehicleLoan->autho_id = Auth::id();
                        $vehicleLoan->save();

                        $vehicleLoanDiffIncome = new VehicleLoanDiffIncome();
                        $vehicleLoanDiffIncome->fiscal_year_id = 4;
                        $vehicleLoanDiffIncome->vehicle_loan_id = $vehicleLoan->vehical_id;
                        $vehicleLoanDiffIncome->created_by = auth()->id();
                        $vehicleLoanDiffIncome->diff_income = $data['vehicle_loan_income'];
                        $vehicleLoanDiffIncome->save();
                    }*/

                    /*$premium = new StaffInsurancePremium();
                    $premium->staff_central_id = $staff->id;
                    $premium->branch_id = $staff->branch_id;
                    $premium->fiscal_year_id = 4;
                    $premium->premium_amount = $data['investment_amount'];
                    $premium->created_by = Auth::user()->id;
                    $premium->save();*/

                } else {
                    $status_mesg = false;
                    DB::rollBack();
                    $status = 'error';
                    $mesg = 'Staff Not Found :' . $data['name'];
                    return redirect()->route('staff-main')->with('flash', array('status' => $status, 'mesg' => $mesg));
                }
            } catch (\Exception $e) {
                dd($e);
                DB::rollBack();
                $status_mesg = false;
                break;
            }
            if ($status_mesg) {
                DB::commit();
            }
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Data Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('staff-main')->with('flash', array('status' => $status, 'mesg' => $mesg));
    }
}
