<?php

namespace App\Http\Controllers;

use App\AttendanceDetailModel;
use App\BankMastModel;
use App\CitLedger;
use App\FiscalYearModel;
use App\Helpers\BSDateHelper;
use App\Helpers\DynamicReportHelper;
use App\HouseLoanModelMast;
use App\HouseLoanTransactionLog;
use App\LeaveBalance;
use App\PayrollConfirm;
use App\PayrollDetailModel;
use App\ProFund;
use App\Repositories\DepartmentRepository;
use App\Repositories\FiscalYearRepository;
use App\Repositories\SocialSecurityTaxStatementRepository;
use App\Repositories\SystemOfficeMastRepository;
use App\SocialSecurityTaxStatement;
use App\StafMainMastModel;
use App\SundryTransactionLog;
use App\SundryType;
use App\SystemHolidayMastModel;
use App\SystemLeaveMastModel;
use App\SystemOfficeMastModel;
use App\TaxStatement;
use App\TransBankStatement;
use App\TransCashStatement;
use App\VehicalLoanModelTrans;
use App\VehicleLoanTransactionLog;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private $branches, $fiscalyears;
    /**
     * @var SystemOfficeMastRepository
     */
    private $systemOfficeMastRepository;
    /**
     * @var FiscalYearRepository
     */
    private $fiscalYearRepository;
    /**
     * @var SocialSecurityTaxStatementRepository
     */
    private $socialSecurityTaxStatementRepository;
    /**
     * @var DepartmentRepository
     */
    private $departmentRepository;

    public function __construct(
        SystemOfficeMastRepository $systemOfficeMastRepository,
        FiscalYearRepository $fiscalYearRepository,
        SocialSecurityTaxStatementRepository $socialSecurityTaxStatementRepository,
        DepartmentRepository $departmentRepository
    )

    {
        $this->middleware("auth");
        $this->systemOfficeMastRepository = $systemOfficeMastRepository;
        $this->fiscalYearRepository = $fiscalYearRepository;
        $this->fiscalyears = $this->fiscalYearRepository->getAllFiscalYears();
        $this->socialSecurityTaxStatementRepository = $socialSecurityTaxStatementRepository;
        $this->departmentRepository = $departmentRepository;
    }

    public function index(Request $request)
    {

    }

    public function taxStatement(Request $request)
    {
        $data = array();
        $filter_data = array();
        $fiscal_year = FiscalYearModel::where('fiscal_status', 1)->first();
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $status = true;
        $month_names = Config::get('constants.month_name_with_extra');
        $staffs = StafMainMastModel::select('id', 'name_eng')->get();


        $i = 1;
        $total = 0;
        $months = array(4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3);
        if (!empty($request->fiscal_year)) {
            $fiscal_year = FiscalYearModel::where('id', $request->fiscal_year)->first();
        }
        if (!(empty($request->from_month) && empty($request->to_month))) {
            $from = $request->from_month;
            $to = $request->to_month;
            $months = $this->month_range($from, $to);
        }
        $payrolldetail_object = PayrollDetailModel::withoutGlobalScope('has_bonus')->where('fiscal_year', $fiscal_year->id)->whereIn('salary_month', $months)->get();
        $taxstatement_object = TaxStatement::whereIn('payroll_id', $payrolldetail_object->pluck('id')->toArray())->get();
        foreach ($months as $month) {
            $payroll_details = $payrolldetail_object->where('salary_month', $month)->where('has_bonus', null)->pluck('id')->toArray();
            $tax_statement = $taxstatement_object->whereIn('payroll_id', $payroll_details);
            if (!empty($request->branch_id) && $request->branch_id != 'all') {
                $tax_statement = $taxstatement_object->whereIn('payroll_id', $payroll_details)->where('branch_id', $request->branch_id);
            }
            if ($tax_statement->count()) {
                $data['no_of_staff_paid'][$month] = $tax_statement->count();
                $data['amount'][$month] = $tax_statement->sum('tax_amount');
                $data['total'][$month] = $total + $tax_statement->sum('tax_amount');
                $total = $total + $data['amount'][$month];
            }
            $payroll_details = $payrolldetail_object->where('salary_month', $month)->where('has_bonus', 1)->pluck('id')->toArray();
            if (!empty($payroll_details)) {
                $tax_statement = $taxstatement_object->whereIn('payroll_id', $payroll_details);
                if (!empty($request->branch_id) && $request->branch_id != 'all') {
                    $tax_statement = $taxstatement_object->whereIn('payroll_id', $payroll_details)->where('branch_id', $request->branch_id);
                }
                if ($tax_statement->count()) {
                    $data['no_of_staff_paid'][13] = $tax_statement->count();
                    $data['amount'][13] = $tax_statement->sum('tax_amount');
                    $data['total'][13] = $total + $tax_statement->sum('tax_amount');
                    $total = $total + $data['amount'][13];
                }
            }
            $payroll_details = $payrolldetail_object->where('salary_month', $month)->where('has_bonus', 2)->pluck('id')->toArray();
            if (!empty($payroll_details)) {
                $tax_statement = $taxstatement_object->whereIn('payroll_id', $payroll_details);
                if (!empty($request->branch_id) && $request->branch_id != 'all') {
                    $tax_statement = $taxstatement_object->whereIn('payroll_id', $payroll_details)->where('branch_id', $request->branch_id);
                }
                if ($tax_statement->count()) {
                    $data['no_of_staff_paid'][14] = $tax_statement->count();
                    $data['amount'][14] = $tax_statement->sum('tax_amount');
                    $data['total'][14] = $total + $tax_statement->sum('tax_amount');
                    $total = $total + $data['amount'][14];
                }
            }

        }
        if (!empty($request->branch_id) && $request->branch_id != 'all') {
            $filter_data['fiscal_year'] = $fiscal_year->fiscal_code;
            $filter_data['branch'] = SystemOfficeMastModel::where('office_id', $request->branch_id)->first()->office_name;
            $filter_data['from'] = $from;
            $filter_data['to'] = $to;
        }
        $this->branches = $this->systemOfficeMastRepository->retrieveAllBranchList();


        return view('reports.taxstatement',
            [
                'details' => $data,
                'title' => 'Tax Statement',
                'branch' => $this->branches,
                'fiscalyear' => $this->fiscalyears,
                'records_per_page_options' => $records_per_page_options,
                'records_per_page' => $records_per_page,
                'status' => $status,
                'months' => $months,
                'fiscal_year' => $fiscal_year,
                'month_names' => $month_names,
                'i' => $i,
                'staffs' => $staffs,
                'filter_data' => $filter_data
            ]
        );
    }

    public function taxStatement_personal(Request $request)
    {
        $data = array();
        $fiscal_year = FiscalYearModel::where('id', $request->fiscal_year)->first();
        $from = $request->from_month;
        $to = $request->to_month;
        $months = $this->month_range($from, $to);
        $month_names = Config::get('constants.month_name_with_extra');
        $staff = StafMainMastModel::find($request->staff_central_id);
        $i = 1;
        $total = 0;
        foreach ($months as $month) {
            $payroll_details = PayrollDetailModel::where('fiscal_year', $fiscal_year->id)->where('salary_month', $month)->pluck('id')->where('has_bonus', null)->toArray();

            $tax_statement = TaxStatement::whereIn('payroll_id', $payroll_details)->where('staff_central_id', $request->staff_central_id)->get();

            if ($tax_statement->count()) {
                $payroll_date = PayrollDetailModel::find($tax_statement->first()->payroll_id)->updated_at;
                $payroll_date = (date('Y-m-d', strtotime($payroll_date)));
                $data['payroll_id'][$month] = PayrollConfirm::find($tax_statement->first()->payroll_id);
                $data['date'][$month] = BSDateHelper::AdToBs('-', $payroll_date);
                $data['amount'][$month] = $tax_statement->first()->tax_amount;
                $data['total'][$month] = $total + $tax_statement->first()->tax_amount;
                $total = $total + $data['amount'][$month];
            }
            $payroll_details = PayrollDetailModel::withoutGlobalScope('has_bonus')->where('fiscal_year', $fiscal_year->id)->where('salary_month', $month)->where('has_bonus', 1)->pluck('id')->toArray();

            if (!empty($payroll_details)) {
                $tax_statement = TaxStatement::whereIn('payroll_id', $payroll_details)->where('staff_central_id', $request->staff_central_id)->get();

                if ($tax_statement->count()) {
                    $payroll_date = PayrollDetailModel::withoutGlobalScope('has_bonus')->find($tax_statement->first()->payroll_id)->updated_at;
                    $payroll_date = (date('Y-m-d', strtotime($payroll_date)));
                    $data['payroll_id'][13] = PayrollConfirm::find($tax_statement->first()->payroll_id);
                    $data['date'][13] = BSDateHelper::AdToBs('-', $payroll_date);
                    $data['amount'][13] = $tax_statement->first()->tax_amount;
                    $data['total'][13] = $total + $tax_statement->first()->tax_amount;
                    $total = $total + $data['amount'][13];
                }
            }
        }
        return view('reports.taxstatement-personal',
            [
                'details' => $data,
                'title' => 'Personal Tax Statement',
                'fiscalyear' => $this->fiscalyears,
                'months' => $months,
                'fiscal_year' => $fiscal_year,
                'month_names' => $month_names,
                'i' => $i,
                'staff' => $staff,
                'from_month' => $from,
                'to_month' => $to,

            ]
        );
    }

    public function bankStatement(Request $request)
    {
        $data = TransBankStatement::with(['staff', 'branch', 'bank', 'payroll' => function ($query) {
            $query->with('fiscalyear');
        }]);
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $status = true;
        $inputs = array();
        $currentFiscalYear = FiscalYearModel::isActiveFiscalYear()->first();
        $banks = BankMastModel::all();
        if (!empty($request->payroll_id)) {
            $data = $data->where('payroll_id', $request->payroll_id)->paginate(100);
        } else {
            $data = $data->paginate(100);
        }
        $inputs['branch_id'] = $request->branch_id;
        $inputs['fiscal_year'] = $request->fiscal_year;
        $inputs['month'] = $request->month;
        $inputs['bank'] = $request->bank;

        $this->branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        $payrolls = PayrollDetailModel::whereNotNull('confirmed_by')->pluck('payroll_name', 'id');
        return view('reports.bankstatement',
            ['details' => $data,
                'title' => 'Bank Statement',
                'branch' => $this->branches,
                'fiscalyear' => $this->fiscalyears,
                'records_per_page_options' => $records_per_page_options,
                'records_per_page' => $records_per_page,
                'currentFiscalYear' => $currentFiscalYear,
                'status' => $status,
                'banks' => $banks,
                'inputs' => $inputs,
                'payrolls' => $payrolls,]
        );
    }

    public function bankStatementExport(Request $request)
    {
        $data = TransBankStatement::with(['staff', 'branch', 'bank', 'payroll' => function ($query) {
            $query->with('fiscalyear');
        }]);

        if (!empty($request->payroll_id)) {
            $data = $data->where('payroll_id', $request->payroll_id)->paginate(100);
        } else {
            $data = $data->paginate(100);
        }

        Excel::create('Bank Statement', function ($excel) use ($data) {
            $excel->sheet('Bank Statement', function ($sheet) use ($data) {
                $sheet->cell('A1', function ($cell) {
                    $cell->setValue('SN')->setFontWeight('bold');
                });
                $sheet->cell('B1', function ($cell) {
                    $cell->setValue('Staff Name')->setFontWeight('bold');
                });
                $sheet->cell('C1', function ($cell) {
                    $cell->setValue('Staff Central ID')->setFontWeight('bold');
                });
                $sheet->cell('D1', function ($cell) {
                    $cell->setValue('Payroll Month')->setFontWeight('bold');
                });
                $sheet->cell('E1', function ($cell) {
                    $cell->setValue('Branch')->setFontWeight('bold');
                });
                $sheet->cell('F1', function ($cell) {
                    $cell->setValue('Bank')->setFontWeight('bold');
                });
                $sheet->cell('G1', function ($cell) {
                    $cell->setValue('Account Number')->setFontWeight('bold');
                });
                $sheet->cell('H1', function ($cell) {
                    $cell->setValue('BrCode')->setFontWeight('bold');
                });
                $sheet->cell('I1', function ($cell) {
                    $cell->setValue('Transaction Type')->setFontWeight('bold');
                });
                $sheet->cell('J1', function ($cell) {
                    $cell->setValue('Total Payement')->setFontWeight('bold');
                });
                $sheet->cell('K1', function ($cell) {
                    $cell->setValue('Remarks')->setFontWeight('bold');
                });
                $i = 1;
                $total = 0;
                foreach ($data as $value) {
                    $sheet->cell('A' . ++$i, $i - 1);
                    $sheet->cell('B' . $i, $value->staff->name_eng);
                    $sheet->cell('C' . $i, $value->staff->staff_central_id ?? '');
                    $sheet->cell('D' . $i, $value->payroll->salary_month ?? '');
                    $sheet->cell('E' . $i, $value->branch->office_name ?? '');
                    $sheet->cell('F' . $i, $value->bank->bank_name ?? '');
                    $sheet->cell('G' . $i, "'" . $value->acc_no);
                    $sheet->cell('H' . $i, $value->brcode);
                    $sheet->cell('I' . $i, $value->trans_type);
                    $sheet->cell('J' . $i, $value->total_payment);
                    $sheet->cell('K' . $i, ' ');
                    $total += $value->total_payment;
                }

                $to = $i + 1;
                $sheet->cell('I' . $to, function ($cell) {
                    $cell->setValue('Grand Amount')->setFontWeight('bold');
                });
                $sheet->cell('J' . $to, $total);

            });
        })->download('xls');
    }

    public function cashStatement(Request $request)
    {
        $fiscal_year = FiscalYearModel::get();
        $branches = SystemOfficeMastModel::get();

        $data = TransCashStatement::with(['staff', 'branch', 'payroll' => function ($query) {
            $query->with('fiscalyear');
        }]);
        if (!empty($request->payroll_id)) {
            $data = $data->where('payroll_id', $request->payroll_id)->paginate(100);
        } else {
            $data = $data->paginate(100);
        }

        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $status = true;

        $values = array();
        $i = 0;
        foreach ($data as $single_data) {
            $values[$i]['name_eng'] = $single_data->staff->name_eng ?? '';
            $values[$i]['staff_central_id'] = $single_data->staff->staff_central_id ?? '';
            $values[$i]['salary_month'] = $single_data->payroll->salary_month ?? '';
            $values[$i]['fiscal_code'] = $single_data->payroll->fiscalyear->fiscal_code ?? '';
            $values[$i]['office_name'] = $single_data->branch->office_name ?? '';
            $values[$i]['total_payment'] = $single_data->total_payment;
            $i++;
        }
        $currentFiscalYear = FiscalYearModel::isActiveFiscalYear()->first();


        $this->branches = $this->systemOfficeMastRepository->retrieveAllBranchList();

        return view('reports.cashstatement',
            [
                'details' => $data,
                'title' => 'Cash Statement',
                'branch' => $this->branches,
                'fiscalyear' => $this->fiscalyears,
                'currentFiscalYear' => $currentFiscalYear,
                'records_per_page_options' => $records_per_page_options,
                'records_per_page' => $records_per_page,
                'status' => $status,
                'values' => $values

            ]
        );
    }

    public function cashStatementExport(Request $request)
    {
        $data = TransCashStatement::with(['staff', 'branch', 'bank', 'payroll' => function ($query) {
            $query->with('fiscalyear');
        }]);

        if (!empty($request->payroll_id)) {
            $data = $data->where('payroll_id', $request->payroll_id)->paginate(100);
        } else {
            $data = $data->paginate(100);
        }

        Excel::create('Cash Statement', function ($excel) use ($data) {
            $excel->sheet('Cash Statement', function ($sheet) use ($data) {
                $sheet->cell('A1', function ($cell) {
                    $cell->setValue('SN')->setFontWeight('bold');
                });
                $sheet->cell('B1', function ($cell) {
                    $cell->setValue('Staff Name')->setFontWeight('bold');
                });
                $sheet->cell('C1', function ($cell) {
                    $cell->setValue('Staff Central ID')->setFontWeight('bold');
                });
                $sheet->cell('D1', function ($cell) {
                    $cell->setValue('Payroll Month')->setFontWeight('bold');
                });
                $sheet->cell('E1', function ($cell) {
                    $cell->setValue('Payroll Branch')->setFontWeight('bold');
                });
                $sheet->cell('F1', function ($cell) {
                    $cell->setValue('Branch ID')->setFontWeight('bold');
                });
                $sheet->cell('G1', function ($cell) {
                    $cell->setValue('Total Salary Payment')->setFontWeight('bold');
                });
                $sheet->cell('H1', function ($cell) {
                    $cell->setValue('Remarks')->setFontWeight('bold');
                });
                $sheet->cell('I1', function ($cell) {
                    $cell->setValue('Signature')->setFontWeight('bold');
                });
                $i = 1;
                $total = 0;
                foreach ($data as $value) {
                    $sheet->cell('A' . ++$i, $i - 1);
                    $sheet->cell('B' . $i, $value->staff->name_eng);
                    $sheet->cell('C' . $i, $value->staff->staff_central_id ?? '');
                    $sheet->cell('D' . $i, $value->payroll->salary_month ?? '');
                    $sheet->cell('E' . $i, $value->branch->office_name ?? '');
                    $sheet->cell('F' . $i, $value->staff->main_id ?? '');
                    $sheet->cell('G' . $i, $value->total_payment);
                    $sheet->cell('H' . $i, '');
                    $sheet->cell('I' . $i, '');
                    $total += $value->total_payment;
                }

                $to = $i + 1;
                $sheet->cell('F' . $to, function ($cell) {
                    $cell->setValue('Grand Amount')->setFontWeight('bold');
                });
                $sheet->cell('G' . $to, $total);

            });
        })->download('xls');
    }

    public function pfLedger(Request $request)
    {
        $data = array();
        $filter_data = array();
        $fiscal_year = FiscalYearModel::where('fiscal_status', 1)->first();
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $status = true;
        $month_names = Config::get('constants.month_name');
        $staffs = StafMainMastModel::select('id', 'name_eng')->get();
        $i = 1;
        $total = 0;
        $months = array(4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3);
        if (!empty($request->fiscal_year)) {
            $fiscal_year = FiscalYearModel::where('id', $request->fiscal_year)->first();
        }
        if (!(empty($request->from_month) && empty($request->to_month))) {
            $from = $request->from_month;
            $to = $request->to_month;
            $months = $this->month_range($from, $to);
        }
        foreach ($months as $month) {
            $payroll_details = PayrollDetailModel::where('fiscal_year', $fiscal_year->id)->where('salary_month', $month)->pluck('id')->toArray();
            $profund = ProFund::whereIn('payroll_id', $payroll_details)->get();
            if (!empty($request->branch_id) && $request->branch_id != 'all') {
                $profund = ProFund::whereIn('payroll_id', $payroll_details)->where('branch_id', $request->branch_id)->get();
            }
            if ($profund->count()) {
                $data['no_of_staff_paid'][$month] = $profund->count();
                $data['employee_contri'][$month] = $profund->sum('employee_contri');
                $data['company_contri'][$month] = $profund->sum('company_contri');
                $data['amount'][$month] = $profund->sum('employee_contri') + $profund->sum('company_contri');
                $data['total'][$month] = $total + $profund->sum('employee_contri') + $profund->sum('company_contri');
                $total = $total + $data['amount'][$month];
            }
        }
        if (!empty($request->branch_id) && $request->branch_id != 'all') {
            $filter_data['fiscal_year'] = $fiscal_year->fiscal_code;
            $filter_data['branch'] = SystemOfficeMastModel::where('office_id', $request->branch_id)->first()->office_name;
            $filter_data['from'] = $from;
            $filter_data['to'] = $to;
        }
        $this->branches = $this->systemOfficeMastRepository->retrieveAllBranchList();

        return view('reports.pfledger',
            [
                'details' => $data,
                'title' => 'Pro Fund Ledger',
                'branch' => $this->branches,
                'fiscalyear' => $this->fiscalyears,
                'records_per_page_options' => $records_per_page_options,
                'records_per_page' => $records_per_page,
                'status' => $status,
                'months' => $months,
                'fiscal_year' => $fiscal_year,
                'month_names' => $month_names,
                'i' => $i,
                'staffs' => $staffs,
                'filter_data' => $filter_data
            ]
        );
    }

    public function pfLedger_personal(Request $request)
    {
        $data = array();
        $fiscal_year = FiscalYearModel::where('id', $request->fiscal_year)->first();
        $from = $request->from_month;
        $to = $request->to_month;
        $months = $this->month_range($from, $to);

        $month_names = Config::get('constants.month_name');
        $staff = StafMainMastModel::find($request->staff_central_id);
        $i = 1;
        $total = 0;
        foreach ($months as $month) {
            $payroll_details = PayrollDetailModel::where('fiscal_year', $fiscal_year->id)->where('salary_month', $month)->pluck('id')->toArray();
            $profund = ProFund::whereIn('payroll_id', $payroll_details)->where('staff_central_id', $request->staff_central_id)->get();

            if ($profund->count()) {
                $data['employee_contri'][$month] = $profund->sum('employee_contri');
                $data['company_contri'][$month] = $profund->sum('company_contri');
                $data['payroll_id'][$month] = PayrollConfirm::find($profund->first()->payroll_id);

                $payroll_date = PayrollDetailModel::find($profund->first()->payroll_id)->updated_at;
                $payroll_date = (date('Y-m-d', strtotime($payroll_date)));
                $data['date'][$month] = BSDateHelper::AdToBs('-', $payroll_date);

                $data['amount'][$month] = $profund->sum('employee_contri') + $profund->sum('company_contri');
                $data['total'][$month] = $total + $profund->sum('employee_contri') + $profund->sum('company_contri');
                $total = $total + $data['amount'][$month];
            }
        }
        return view('reports.pfledger-personal',
            [
                'details' => $data,
                'title' => 'Pro Fund Ledger',
                'fiscalyear' => $this->fiscalyears,
                'months' => $months,
                'fiscal_year' => $fiscal_year,
                'month_names' => $month_names,
                'i' => $i,
                'staff' => $staff,
                'from_month' => $from,
                'to_month' => $to,

            ]
        );

    }

    public function citLedger(Request $request)
    {
        $data = array();
        $filter_data = array();
        $fiscal_year = FiscalYearModel::where('fiscal_status', 1)->first();
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $status = true;
        $month_names = Config::get('constants.month_name');
        $staffs = StafMainMastModel::select('id', 'name_eng')->get();
        $i = 1;
        $total = 0;
        $months = array(4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3);
        if (!empty($request->fiscal_year)) {
            $fiscal_year = FiscalYearModel::where('id', $request->fiscal_year)->first();
        }
        if (!(empty($request->from_month) && empty($request->to_month))) {
            $from = $request->from_month;
            $to = $request->to_month;
            $months = $this->month_range($from, $to);
        }
        foreach ($months as $month) {
            $payroll_details = PayrollDetailModel::where('fiscal_year', $fiscal_year->id)->where('salary_month', $month)->pluck('id')->toArray();
            $cita = CitLedger::whereIn('payroll_id', $payroll_details)->get();
            if (!empty($request->branch_id) && $request->branch_id != 'all') {
                $cita = CitLedger::whereIn('payroll_id', $payroll_details)->where('branch_id', $request->branch_id)->get();
            }
            if ($cita->count()) {
                $data['no_of_staff_paid'][$month] = $cita->count();
                $data['cit_amount'][$month] = $cita->sum('cit_amount');
                $data['total'][$month] = $total + $cita->sum('cit_amount');
                $total = $total + $data['cit_amount'][$month];
            }
        }
        if (!empty($request->branch_id) && $request->branch_id != 'all') {
            $filter_data['fiscal_year'] = $fiscal_year->fiscal_code;
            $filter_data['branch'] = SystemOfficeMastModel::where('office_id', $request->branch_id)->first()->office_name;
            $filter_data['from'] = $from;
            $filter_data['to'] = $to;
        }
        $this->branches = $this->systemOfficeMastRepository->retrieveAllBranchList();

        return view('reports.citledger',
            [
                'details' => $data,
                'title' => 'CIT Ledger',
                'branch' => $this->branches,
                'fiscalyear' => $this->fiscalyears,
                'records_per_page_options' => $records_per_page_options,
                'records_per_page' => $records_per_page,
                'status' => $status,
                'months' => $months,
                'fiscal_year' => $fiscal_year,
                'month_names' => $month_names,
                'i' => $i,
                'staffs' => $staffs,
                'filter_data' => $filter_data
            ]
        );
    }

    public function citLedger_personal(Request $request)
    {
        $data = array();
        $fiscal_year = FiscalYearModel::where('id', $request->fiscal_year)->first();
        $from = $request->from_month;
        $to = $request->to_month;
        $months = $this->month_range($from, $to);

        $month_names = Config::get('constants.month_name');
        $staff = StafMainMastModel::find($request->staff_central_id);
        $i = 1;
        $total = 0;
        foreach ($months as $month) {
            $payroll_details = PayrollDetailModel::where('fiscal_year', $fiscal_year->id)->where('salary_month', $month)->pluck('id')->toArray();
            $cita = CitLedger::whereIn('payroll_id', $payroll_details)->where('staff_central_id', $request->staff_central_id)->get();

            if ($cita->count()) {
                $data['cit_amount'][$month] = $cita->sum('cit_amount');
                $data['payroll_id'][$month] = PayrollConfirm::find($cita->first()->payroll_id);

                $payroll_date = PayrollDetailModel::find($cita->first()->payroll_id)->updated_at;
                $payroll_date = (date('Y-m-d', strtotime($payroll_date)));
                $data['date'][$month] = BSDateHelper::AdToBs('-', $payroll_date);
                $data['total'][$month] = $total + $cita->sum('cit_amount');
                $total = $total + $data['cit_amount'][$month];
            }
        }
        return view('reports.citledger-personal',
            [
                'details' => $data,
                'title' => 'CIT Ledger',
                'fiscalyear' => $this->fiscalyears,
                'months' => $months,
                'fiscal_year' => $fiscal_year,
                'month_names' => $month_names,
                'i' => $i,
                'staff' => $staff,
                'from_month' => $from,
                'to_month' => $to,

            ]
        );

    }

    public function homeloanStatement(Request $request)
    {
        $fiscal_year = FiscalYearModel::where('fiscal_status', 1)->first();
        if (!empty($request->fiscal_year)) {
            $fiscal_year = FiscalYearModel::find($request->fiscal_year);
        }
        $payroll_details = PayrollDetailModel::where('fiscal_year', $fiscal_year->id)->pluck('id')->toArray();
        $house_loan = HouseLoanModelMast::pluck('staff_central_id')->toArray();
        $data = StafMainMastModel::with('houseLoan', 'jobposition')->whereIn('id', $house_loan);
        if (!empty($request->branch_id)) {
            $data = $data->where('branch_id', $request->branch_id);
        }
        $data = $data->get();
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $status = true;
        $loan_amt = array();
        $loan_date = array();
        $remaining_loan_amt = array();
        $loan_in_month = array();
        $months = array(4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3);
        $month_names = Config::get('constants.month_name');
        $houseloan_transactions = HouseLoanTransactionLog::whereIn('staff_central_id', $data->pluck('id')->toArray())->whereIn('deduc_salary_month', $months)->whereIn('payroll_id', $payroll_details)->get();
        foreach ($data as $staff) {
            $house_loan_amt = 0;
            $remaining_house_loan = 0;
            if (!empty($staff->houseLoan)) {
                $house_loan_amt = $staff->houseLoan->sum('loan_amount');
                $remaining_house_loan = $staff->houseLoan->sum('loan_amount') - $staff->houseLoan->sum('paid_amt');
                $loan_date[$staff->id] = $staff->houseLoan->first()->trans_date;
            }
            $loan_amt[$staff->id] = $house_loan_amt;
            $remaining_loan_amt[$staff->id] = $remaining_house_loan;
            foreach ($months as $month) {
                $installment_amount = $houseloan_transactions->where('staff_central_id', $staff->id)->where('deduc_salary_month', $month)->sum('paid_installment_amt');
                $loan_in_month[$staff->id][$month] = empty($installment_amount) ? '-' : $installment_amount;
            }
        }
        $this->branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        return view('reports.houseloanstatement',
            [
                'details' => $data,
                'title' => 'House Loan Statement',
                'branch' => $this->branches,
                'fiscalyear' => $this->fiscalyears,
                'records_per_page_options' => $records_per_page_options,
                'records_per_page' => $records_per_page,
                'status' => $status,
                'loan_amt' => $loan_amt,
                'loan_date' => $loan_date,
                'remaining_loan_amt' => $remaining_loan_amt,
                'months' => $months,
                'loan_in_month' => $loan_in_month,
                'fiscal_year' => $fiscal_year,
                'month_names' => $month_names
            ]
        );
    }

    public function vehicleloanStatement(Request $request)
    {
        $fiscal_year = FiscalYearModel::where('fiscal_status', 1)->first();
        if (!empty($request->fiscal_year)) {
            $fiscal_year = FiscalYearModel::find($request->fiscal_year);
        }
        $payroll_details = PayrollDetailModel::where('fiscal_year', $fiscal_year->id)->pluck('id')->toArray();
        $vehicle_loan = VehicalLoanModelTrans::pluck('staff_central_id')->toArray();
        $data = StafMainMastModel::whereIn('id', $vehicle_loan)->get();
        if (!empty($request->branch_id)) {
            $data = StafMainMastModel::whereIn('id', $vehicle_loan)->where('branch_id', $request->branch_id)->get();
        }
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $status = true;
        $loan_amt = array();
        $loan_date = array();
        $remaining_loan_amt = array();
        $loan_in_month = array();
        $months = array(4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3);
        $month_names = Config::get('constants.month_name');
        foreach ($data as $staff) {
            $vehicle_loan_amt = 0;
            $remaining_vehicle_loan = 0;
            if (!empty($staff->vehicleLoan)) {
                $vehicle_loan_amt = $staff->vehicleLoan->sum('loan_amount');
                $remaining_vehicle_loan = $staff->vehicleLoan->sum('loan_amount') - $staff->vehicleLoan->sum('paid_amt');
                $loan_date[$staff->id] = $staff->vehicleLoan->first()->trans_date;
            }
            $loan_amt[$staff->id] = $vehicle_loan_amt;
            $remaining_loan_amt[$staff->id] = $remaining_vehicle_loan;

            foreach ($months as $month) {
                $installment_amount = VehicleLoanTransactionLog::where('staff_central_id', $staff->id)->where('deduc_salary_month', $month)->whereIn('payroll_id', $payroll_details)->sum('paid_installment_amt');
                $loan_in_month[$staff->id][$month] = empty($installment_amount) ? '-' : $installment_amount;
            }
        }
        $this->branches = $this->systemOfficeMastRepository->retrieveAllBranchList();

        return view('reports.vehicleloanstatement',
            [
                'details' => $data,
                'title' => 'Vehicle Loan Statement',
                'branch' => $this->branches,
                'fiscalyear' => $this->fiscalyears,
                'records_per_page_options' => $records_per_page_options,
                'records_per_page' => $records_per_page,
                'status' => $status,
                'loan_amt' => $loan_amt,
                'loan_date' => $loan_date,
                'remaining_loan_amt' => $remaining_loan_amt,
                'months' => $months,
                'loan_in_month' => $loan_in_month,
                'fiscal_year' => $fiscal_year,
                'month_names' => $month_names
            ]
        );
    }

    public function summary(Request $request)
    {
        $data = array();
        $fiscal_year = FiscalYearModel::where('fiscal_status', 1)->first();
        if (!empty($request->fiscal_year)) {
            $fiscal_year = FiscalYearModel::find($request->fiscal_year);
        }
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $records_per_page_options = Config::get('constants.records_per_page_options');
        $status = true;
        $month_names = Config::get('constants.month_name');
        $months = array(9, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3, 4);
        $i = 1;
        $banks = BankMastModel::all();
        foreach ($months as $month) {
            $payroll_details = PayrollDetailModel::where('fiscal_year', $fiscal_year->id)->where('salary_month', $month)->pluck('id')->toArray();
            if (!empty($request->branch_id)) {
                $payroll_details = PayrollDetailModel::where('fiscal_year', $fiscal_year->id)->where('salary_month', $month)->where('branch_id', $request->branch_id)->pluck('id')->toArray();
            }
            $payroll_confirm = PayrollConfirm::whereIn('payroll_id', $payroll_details)->get();

            if ($payroll_confirm->count()) {
                $data['no_of_staff_paid'][$month] = $payroll_confirm->count();
                $data['average_attendance'][$month] = round(($payroll_confirm->sum('salary_hour_payable') / $data['no_of_staff_paid'][$month]), 2);
                $data['basic_salary'][$month] = $payroll_confirm->sum('basic_salary');
                $data['dearness_allowance'][$month] = $payroll_confirm->sum('dearness_allowance');
                $data['special_allowance'][$month] = $payroll_confirm->sum('special_allowance');
                $data['extra_allowance'][$month] = $payroll_confirm->sum('extra_allowance');
                $data['pro_fund'][$month] = $payroll_confirm->sum('pro_fund');
                $data['pro_fund_contribution'][$month] = $payroll_confirm->sum('pro_fund_contribution');
                $data['home_sick_redeem_amount'][$month] = $payroll_confirm->sum('home_sick_redeem_amount');
                $data['ot_amount'][$month] = $payroll_confirm->sum('ot_amount');
                $data['outstation_facility_amount'][$month] = $payroll_confirm->sum('outstation_facility_amount');
                $data['gross_payable'][$month] = $payroll_confirm->sum('gross_payable');
                foreach ($banks as $bank) {
                    $data[$bank->id][$month] = TransBankStatement::where('bank_id', $bank->id)->whereIn('payroll_id', $payroll_details)->sum('total_payment');
                }
                $data['hand_cash'][$month] = TransCashStatement::whereIn('payroll_id', $payroll_details)->sum('total_payment');
                $data['tax'][$month] = TaxStatement::whereIn('payroll_id', $payroll_details)->sum('tax_amount');
                $data['net_payment'][$month] = $payroll_confirm->sum('net_payable');
            }

        }
        $this->branches = $this->systemOfficeMastRepository->retrieveAllBranchList();

        return view('reports.summary',
            [
                'details' => $data,
                'title' => 'Summary',
                'branch' => $this->branches,
                'fiscalyear' => $this->fiscalyears,
                'records_per_page_options' => $records_per_page_options,
                'records_per_page' => $records_per_page,
                'status' => $status,
                'months' => $months,
                'fiscal_year' => $fiscal_year,
                'month_names' => $month_names,
                'i' => $i,
                'banks' => $banks,
            ]
        );
    }

    public function sundry(Request $request)
    {
        $fiscal_year = FiscalYearModel::where('fiscal_status', 1)->first();
        $sundry_transaction_logs = SundryTransactionLog::where('transaction_date', '>', $fiscal_year->fiscal_start_date_np)->where('transaction_date', '<', $fiscal_year->fiscal_end_date_np)->get();
        $i = 0;
        $j = 0;
        $sundry_creditors = array();
        $sundry_debtors = array();
        $staff_details = array();
        if (!empty($request->staff_central_id)) {
            $sundry_transaction_logs = SundryTransactionLog::where('staff_central_id', $request->staff_central_id)->where('transaction_date', '>', $request->from_date_np)->where('transaction_date', '<', $request->to_date_np)->get();
            $staff_main = StafMainMastModel::find($request->staff_central_id);
            $staff_details['name'] = $staff_main->name_eng;
            $staff_details['from'] = $request->from_date_np;
            $staff_details['to'] = $request->to_date_np;
        }
        $month_names = Config::get('constants.month_name');
        $staffs = StafMainMastModel::select('id', 'name_eng')->get();
        foreach ($sundry_transaction_logs as $sundry_transaction_log) {
            $sundry_type = SundryType::find($sundry_transaction_log->transaction_type_id);
            /* $is_cr = SundryType::isCR($sundry_type->type);*/
            $is_cr = SundryType::isCR($sundry_transaction_log->transaction_type_id);
            $name = '';
            if (empty($request->staff_central_id)) {
                $staff_name = StafMainMastModel::find($sundry_transaction_log->staff_central_id);
                $name = '[' . $staff_name->name_eng . ']';
            }
            if ($is_cr) {
                //$dr_total = SundryTransactionLog::where('sundry_id', $sundry_transaction_log->sundry_id)->where('updated_at', '<=', $sundry_transaction_log->updated_at)->sum('dr_amount');
                //$cr_total = SundryTransactionLog::where('sundry_id', $sundry_transaction_log->sundry_id)->where('updated_at', '<=', $sundry_transaction_log->updated_at)->sum('cr_balance');
                $sundry_creditors[$i]['date'] = $sundry_transaction_log->transaction_date;
                $sundry_creditors[$i]['transaction_type'] = $sundry_type->title . ' ' . $name;
                $sundry_creditors[$i]['dr_amount'] = $sundry_transaction_log->dr_amount;
                $sundry_creditors[$i]['cr_amount'] = $sundry_transaction_log->cr_balance;
                // $sundry_creditors[$i]['balance'] = $cr_total - $dr_total;
                if ($i == 0) {
                    $sundry_creditors[$i]['balance'] = $sundry_transaction_log->cr_balance;
                } else {
                    if (!empty($sundry_transaction_log->cr_balance)) {
                        $sundry_creditors[$i]['balance'] = $sundry_creditors[$i - 1]['balance'] + $sundry_transaction_log->cr_balance;
                    }
                    if (!empty($sundry_transaction_log->dr_amount)) {
                        $sundry_creditors[$i]['balance'] = $sundry_creditors[$i - 1]['balance'] - $sundry_transaction_log->dr_amount;
                    }
                }
                $i++;
            } else {
                // $dr_total = SundryTransactionLog::where('sundry_id', $sundry_transaction_log->sundry_id)->where('updated_at', '<=', $sundry_transaction_log->updated_at)->sum('dr_balance');
                //$cr_total = SundryTransactionLog::where('sundry_id', $sundry_transaction_log->sundry_id)->where('updated_at', '<=', $sundry_transaction_log->updated_at)->sum('cr_amount');
                $sundry_debtors[$j]['date'] = $sundry_transaction_log->transaction_date;
                $sundry_debtors[$j]['transaction_type'] = $sundry_type->title . ' ' . $name;
                $sundry_debtors[$j]['dr_amount'] = $sundry_transaction_log->dr_balance;
                $sundry_debtors[$j]['cr_amount'] = $sundry_transaction_log->cr_amount;
                //$sundry_debtors[$j]['balance'] = $dr_total - $cr_total;
                if ($j == 0) {
                    $sundry_debtors[$j]['balance'] = $sundry_transaction_log->dr_balance;
                } else {
                    if (!empty($sundry_transaction_log->dr_balance)) {
                        $sundry_debtors[$j]['balance'] = $sundry_debtors[$j - 1]['balance'] + $sundry_transaction_log->dr_balance;
                    }
                    if (!empty($sundry_transaction_log->cr_amount)) {
                        $sundry_debtors[$j]['balance'] = $sundry_debtors[$j - 1]['balance'] - $sundry_transaction_log->cr_amount;
                    }
                }
                $j++;
            }
        }
        if ($i > $j) {
            $count = $i;
        } else {
            $count = $j;
        }
        return view('reports.sundry', [
            'title' => 'Sundry Report',
            'debtors' => $sundry_debtors,
            'creditors' => $sundry_creditors,
            'count' => $count,
            'staffs' => $staffs,
            'fiscalyear' => $this->fiscalyears,
            'staff_detail' => $staff_details
        ]);
    }

    public function staff_payroll_summary()
    {
        $data['title'] = "Staff Salary Summary";
        $data['branches'] = SystemOfficeMastModel::all();
        return view('reports.staff_payroll_summary_form', $data);
    }

    public function staff_payroll_summary_show(Request $request)
    {
        $total = 0;
        $date_from = $request->from_date_np;
        $date_to = $request->to_date_np;

        $date_from_en = BSDateHelper::BsToAd('-', $date_from);
        $date_to_en = BSDateHelper::BsToAd('-', $date_to);
        $months = Config::get('constants.month_name');
        $staff = StafMainMastModel::find($request->staff_central_id);
        $payroll_details = PayrollDetailModel::with('payrollConfirm', 'fiscalyear', 'dashainPayment', 'tiharPayment')->whereDate('from_date', '>=', $date_from_en)->whereDate('to_date', '<=', $date_to_en)->whereNotNull('confirmed_by')->get();
        $detail = [];
        $i = 0;
        foreach ($payroll_details as $payroll_detail) {
            $detail[$i]['year'] = $payroll_detail->fiscalyear->fiscal_code;
            $detail[$i]['payroll_name'] = $payroll_detail->payroll_name ?? '';
            $detail[$i]['month'] = $months[$payroll_detail->salary_month] ?? '';
            if ($payroll_detail->has_bonus != 1 && $payroll_detail->has_bonus != 2) {
                $detail[$i]['amount'] = $payroll_detail->payrollConfirm->where('staff_central_id', $staff->id)->first()->net_payable;
            } elseif ($payroll_detail->has_bonus == 1) {
                $detail[$i]['amount'] = $payroll_detail->dashainPayment->where('staff_central_id', $staff->id)->first()->net_payable;
            } elseif ($payroll_detail->has_bonus == 2) {
                $detail[$i]['amount'] = $payroll_detail->tiharPayment->where('staff_central_id', $staff->id)->first()->net_payable;
            } else {
                $detail[$i]['amount'] = 0;
            }
            $total += $detail[$i]['amount'];
        }
        return view('reports.staff_payroll_summary', [
            'details' => $detail,
            'i' => 1,
            'staff' => $staff,
            'date_from' => $request->from_date_np,
            'date_to' => $request->to_date_np,
            'total' => $total,
            'title' => 'Staff Payroll Summary'

        ]);
    }

    public function leavebalance()
    {
        $data['title'] = 'Leave Balance Statement';
        $data['branches'] = SystemOfficeMastModel::all();
        $data['leaves'] = SystemLeaveMastModel::pluck('leave_name', 'leave_id');
        return view('reports.leavebalance', $data);
    }

    public function leavebalanceshow(Request $request)
    {
        $data['i'] = 1;
        $data['title'] = 'Leave Balance Statement';
        $leaveBalance = LeaveBalance::query();
        if (!empty($request->staff_central_id)) {
            $leaveBalance = $leaveBalance->where('staff_central_id', $request->staff_central_id);
        }
        if (!empty($request->leave_type)) {
            $leaveBalance = $leaveBalance->where('leave_id', $request->leave_type);
        }
        if (!empty($request->from_date && $request->to_date)) {
            $leaveBalance = $leaveBalance->whereBetween('date', [$request->from_date, $request->to_date]);
        }
        $leaveBalance = $leaveBalance->orderBy('leave_id')->orderBy('date');
        $data['leave_balances'] = $leaveBalance->get();
        $data['staff'] = StafMainMastModel::find($request->staff_central_id);
        $data['input'] = Input::all();
        if ($request->export == 1) {
            \Excel::create('Leave Balance Statement', function ($excel) use ($data) {
                $excel->sheet('Leave Balance Statement', function ($sheet) use ($data) {
                    $sheet->loadView('reports.leavebalancereport.table', $data);
                });
            })->download('xlsx');
        }
        return view('reports.leavebalancereport.leavebalanceshow', $data);
    }

    public function month_range(&$start_month, &$end_month)
    {
        if ($end_month < $start_month) {
            $part_one = range($start_month, 12, 1);
            $part_two = range(1, $end_month, 1);
            $months = array_merge($part_one, $part_two);
        } else {
            if ($start_month < 4 && $end_month >= 4) {
                $part_one = range(4, 12, 1);
                $part_two = range(1, $start_month, 1);
                $months = array_merge($part_one, $part_two);
                $end_month = $start_month;
                $start_month = 4;
            } else {
                $months = range($start_month, $end_month, 1);
            }
        }
        return $months;
    }

    public function socialSecurityTaxStatement(Request $request)
    {
        $staffs = StafMainMastModel::select('id', 'name_eng', 'main_id')->get();
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');
        $fiscal_years = FiscalYearModel::select('id', 'fiscal_code')->get();
        $fiscal_year = FiscalYearModel::where('fiscal_status', 1)->first();
        $month_names = Config::get('constants.month_name_with_extra');
        $i = 1;
        $total = 0;
        $months = array(4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3);
        if (!(empty($request->from_month) && empty($request->to_month))) {
            $from = $request->from_month;
            $to = $request->to_month;
            $months = $this->month_range($from, $to);
        }
        $payrolls = PayrollDetailModel::with(['socalSecurityTaxPayment', 'dashainTaxPayment'])->whereIn('salary_month', $months);

        if (!empty($request->fiscal_year)) {
            $payrolls = $payrolls->where('fiscal_year', $request->fiscal_year);
        } else {
            $payrolls = $payrolls->where('fiscal_year', $fiscal_year->id);
        }
        if (!empty($request->branch_id)) {
            $payrolls = $payrolls->where('branch_id', $request->branch_id);
        }
        $payrolls = $payrolls->get();
        $details = [];
        foreach ($month_names as $month_key => $month_value) {
            $tax_amount = 0;
            $number_of_staff = 0;
            if ($month_key == 13) {
                $temp_payrolls = $payrolls->where('has_bonus', 1);
                foreach ($temp_payrolls as $payroll) {
                    $socialSecurityTax = $payroll->socalSecurityTaxPayment;
                    $tax_amount = !empty($socialSecurityTax) ? $socialSecurityTax->sum('tax_amount') : 0;
                    $number_of_staff = !empty($socialSecurityTax) ? $socialSecurityTax->where('tax_amount', '>', 0)->count() : 0;
                }
            } elseif ($month_key == 14) {
                $temp_payrolls = $payrolls->where('has_bonus', 2);
                foreach ($temp_payrolls as $payroll) {
                    $socialSecurityTax = $payroll->socalSecurityTaxPayment;
                    $tax_amount = !empty($socialSecurityTax) ? $socialSecurityTax->sum('tax_amount') : 0;
                    $number_of_staff = !empty($socialSecurityTax) ? $socialSecurityTax->where('tax_amount', '>', 0)->count() : 0;
                }

            } else {
                $temp_payrolls = $payrolls->whereNotIn('has_bonus', [1, 2])->where('salary_month', $month_key);
                foreach ($temp_payrolls as $payroll) {
                    $socialSecurityTax = $payroll->socalSecurityTaxPayment;
                    $tax_amount = !empty($socialSecurityTax) ? $socialSecurityTax->sum('tax_amount') : 0;
                    $number_of_staff = !empty($socialSecurityTax) ? $socialSecurityTax->where('tax_amount', '>', 0)->count() : 0;
                }
            }
            $details[$month_key]['tax_amount'] = $tax_amount;
            $details[$month_key]['no_of_staff'] = $number_of_staff;
            $total += $tax_amount;
            $details[$month_key]['total'] = $total;
        }


        return view('reports.socialsecuritytaxstatement',
            [
                'title' => 'Social Security Tax Statement',
                'branch' => $branches,
                'fiscalyear' => $fiscal_years,
                'months' => $months,
                'fiscal_year' => $fiscal_year,
                'month_names' => $month_names,
                'i' => $i,
                'staffs' => $staffs,
                'details' => $details,
            ]
        );
    }

    public function socialSecurityTaxStatementPersonal(Request $request)
    {
        $currentFiscalYear = FiscalYearModel::where('id', $request->fiscal_year)->first();
        $from = $request->from_month;
        $to = $request->to_month;
        $fiscal_year_id = $request->fiscal_year ?? $currentFiscalYear->id;
        $months = $this->month_range($from, $to);
        $month_names = Config::get('constants.month_name_with_extra');

        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
        $i = 1;
        $total = 0;

        $staff = StafMainMastModel::with(['payrolls' => function ($query) use ($months, $fiscal_year_id) {
            $query->whereIn('salary_month', $months);
            $query->where('fiscal_year', $fiscal_year_id);
        }])->where('id', $request->staff_central_id);

        return view('reports.socialsecuritytaxstatement-personal',
            [
                'title' => 'Personal Social Security Tax Statement',
                'fiscalyears' => $fiscal_years,
                'months' => $months,
                'currentFiscalYear' => $currentFiscalYear,
                'month_names' => $month_names,
                'i' => $i,
                'staff' => $staff,
                'from_month' => $from,
                'to_month' => $to,

            ]
        );
    }

    public function dynamicReportIndex()
    {
        $branches = SystemOfficeMastModel::pluck('office_name', 'office_id');

        $attendanceInformationClass = DynamicReportHelper::getAttendanceInformationClass();
        $leavesClass = DynamicReportHelper::getLeaveClass();
        $payableParameters = DynamicReportHelper::getPayableParameters();
        $bankInformationClass = DynamicReportHelper::getBankInformation();
        $statementClass = DynamicReportHelper::getStatementClass();

        /*$classes =
            [
                'name' => 'name',
                'branch-staff-id' => 'branch staff id',
                'designation' => 'designation',
                'pan-no' => 'pan no',
                'gender' => 'gender',
                'is-extra-allowance' => 'is extra allowance',
                'extra-in-dashain' => 'extra in dashain',
                'saruwa' => 'saruwa',
                'saruwa-date' => 'saruwa date',
                'temporary-date-en' => 'temporary date en',
                'temporary-date-np' => 'temporary date np',
                'permanent-date-en' => 'permanent date eng',
                'permanent-date-np' => 'permanent date np',
                'date-of-birth-en' => 'date of birth en',
                'date-of-birth-np' => 'date of birth np',
                'transferred-date-en' => 'transferred date en',
                'transferred-date-np' => 'transferred date np',
                'resign-status' => 'resign status',
                'resign-date-en' => 'resign date en',
                'resign-date-np' => 'resign date np',
                'bank-name' => 'bank name',
                'bank-ac-number' => 'bank ac number',
                'provident-ac-number' => 'provident ac number',
                'social-security-ac-number' => 'social security ac number',
                'basic-salary' => 'basic salary',
                'previous-grade' => 'previous grade',
                'current-grade' => 'current grade',
                'dearness-allowance' => 'dearness allowance',
                'risk-allowance' => 'risk allowance',
                'special-allowance' => 'special allowance',
                'other-allowance' => 'other allowance',
                'misc-allowance' => 'misc allowance',
                'incentive' => 'incentive',
                'home-leave' => 'home leave',
                'sick-leave' => 'sick leave',
                'pregnant-leave' => 'pregnant leave',
                'pregnant-care-leave' => 'pregnant care leave',
                'funeral-leave' => 'funeral leave',
                'leave-without-pay' => 'leave without pay',
                'gayal-nilamban' => 'gayal nilamban',
                'present-days' => 'present days',
                'upabhog-days' => 'upabhog days',
                'dashain-tihar-present-days' => 'dashain tihar present days',
                'karyarat-awasta' => 'karyarat awasta',
                'tax-code' => 'tax code',
                'bharna-sewa-awadhi' => 'bharna sewa awadhi',
                'age' => 'age',
                'salary' => 'salary',
                'total' => 'total',
                'gayal-niamban-awadhi' => 'gayal niamban awadhi',
                'satta-bida' => 'satta bida',
                'empty' => 'empty',
                'remarks' => 'remarks'
            ];

        if (count($classes) > 50) {
            $breakCount = (ceil(count($classes) / 3));
        } else {
            $breakCount = count($classes);
        }*/

        $title = 'Salary Dynamic Report';

        $fiscal_years = FiscalYearModel::pluck('fiscal_code', 'id');
        $current_fiscal_year_id = FiscalYearModel::isActiveFiscalYear()->value('id');

        $months = Config::get('constants.month_name');

        $currentNepaliDateMonth = BSDateHelper::getBSYearMonthDayArrayFromEnDate(date('Y-m-d'))['month'];
        return view('reports.salary_dynamic_report_index', [
            'title' => $title,
            'branches' => $branches,
//            'classes' => $classes,
            'breakCount' => 3,
            'increment' => 1,
            'attendanceInformationClass' => $attendanceInformationClass,
            'leavesClass' => $leavesClass,
            'payableParameters' => $payableParameters,
            'bankInformationClass' => $bankInformationClass,
            'statementClass' => $statementClass,
            'fiscal_years' => $fiscal_years,
            'current_fiscal_year_id' => $current_fiscal_year_id,
            'months' => $months,
            'currentNepaliDateMonth' => $currentNepaliDateMonth,
        ]);
    }


    public function dynamicReport(Request $request)
    {
        if (empty($request->month_id)) {
            return redirect()->back()->withErrors([
                'Please select a month'
            ]);
        }
        if (empty($request->fiscal_year_id)) {
            return redirect()->back()->withErrors([
                'Please select a fiscal year'
            ]);
        }

        $payroll = PayrollDetailModel::with('branch', 'fiscalyear')->where('salary_month', $request->month_id)->where('fiscal_year', $request->fiscal_year_id)->first();

        if (empty($payroll)) {
            return redirect()->back()->withErrors([
                'Payroll not found'
            ]);
        }


        $staffs = StafMainMastModel::query();

        if (!empty($request->branch_id)) {
            $staffs = $staffs->where('payroll_branch_id', $request->branch_id);
        }

        if (!empty($request->staff_central_id)) {
            $staffs = $staffs->where('staff_central_id', $request->staff_central_id);
        }

        $staffs = $staffs->get();

        if ($staffs->count() < 1) {
            return redirect()->back()->withErrors([
                'Staffs not found'
            ]);
        }

        $payrollConfirms = PayrollConfirm::with(['jobposition', 'jobtype', 'leavePayrollConfirm', 'staff' => function ($query) {
            $query->with('jobposition', 'jobtype');
        }])->where('payroll_id', $payroll->id)
            ->whereIn('staff_central_id', $staffs->pluck('id'))
            ->get();

        if ($payrollConfirms->count() < 1) {
            return redirect()->back()->withErrors([
                'Payroll not confirmed'
            ]);
        }

        $attendanceInformationClass = DynamicReportHelper::getAttendanceInformationClass();
        $leavesClass = DynamicReportHelper::getLeaveClass();
        $payableParameters = DynamicReportHelper::getPayableParameters();
        $bankInformationClass = DynamicReportHelper::getBankInformation();
        $statementClass = DynamicReportHelper::getStatementClass();

        $transBankStatements = TransBankStatement::with('bank')->where('payroll_id', $payroll->id)->where('branch_id', $request->branch_id)->get();

        $socialSecurityTaxStatements = SocialSecurityTaxStatement::where('payroll_id', $payroll->id)->where('branch_id', $request->branch_id)->get();

        $combinedClasses = array_merge($attendanceInformationClass, $leavesClass, $payableParameters, $bankInformationClass, $statementClass);

        return view('reports.salary_dynamic_report', [
            'title' => 'Dynamic Report',
            'payrollConfirms' => $payrollConfirms,
            'selectedClasses' => json_encode($request->classes),
            'selectedClassesArray' => $request->classes ?? [],
            'payroll' => $payroll,
            'combinedClasses' => $combinedClasses,
            'transBankStatements' => $transBankStatements,
            'socialSecurityTaxStatements' => $socialSecurityTaxStatements
        ]);
    }

    public function dynamicReportDownload(Request $request)
    {
        if (empty($request->month_id)) {
            return redirect()->back()->withErrors([
                'Please select a month'
            ]);
        }
        if (empty($request->fiscal_year_id)) {
            return redirect()->back()->withErrors([
                'Please select a fiscal year'
            ]);
        }

        $payroll = PayrollDetailModel::with('branch', 'fiscalyear')->where('salary_month', $request->month_id)->where('fiscal_year', $request->fiscal_year_id)->first();

        if (empty($payroll)) {
            return redirect()->back()->withErrors([
                'Payroll not found'
            ]);
        }


        $staffs = StafMainMastModel::query();

        if (!empty($request->branch_id)) {
            $staffs = $staffs->where('payroll_branch_id', $request->branch_id);
        }

        if (!empty($request->staff_central_id)) {
            $staffs = $staffs->where('staff_central_id', $request->staff_central_id);
        }

        if (!empty($request->department_id)) {
            $staffs = $staffs->where('department', $request->department_id);
        }

        $staffs = $staffs->get();

        if ($staffs->count() < 1) {
            return redirect()->back()->withErrors([
                'Staffs not found'
            ]);
        }

        $payrollConfirms = PayrollConfirm::with(['jobposition', 'jobtype', 'leavePayrollConfirm', 'staff' => function ($query) {
            $query->with('jobposition', 'jobtype');
        }])->where('payroll_id', $payroll->id)
            ->whereIn('staff_central_id', $staffs->pluck('id'))
            ->get();

        if ($payrollConfirms->count() < 1) {
            return redirect()->back()->withErrors([
                'Payroll not confirmed'
            ]);
        }

        $attendanceInformationClass = DynamicReportHelper::getAttendanceInformationClass();
        $leavesClass = DynamicReportHelper::getLeaveClass();
        $payableParameters = DynamicReportHelper::getPayableParameters();
        $bankInformationClass = DynamicReportHelper::getBankInformation();
        $statementClass = DynamicReportHelper::getStatementClass();

        $transBankStatements = TransBankStatement::with('bank')->where('payroll_id', $payroll->id)->where('branch_id', $request->branch_id)->get();

        $combinedClasses = array_merge($attendanceInformationClass, $leavesClass, $payableParameters, $bankInformationClass, $statementClass);

        $socialSecurityTaxStatements = SocialSecurityTaxStatement::get();

        \Excel::create('Dynamic Report Excel', function ($excel) use ($payrollConfirms, $payroll, $combinedClasses, $request, $transBankStatements, $socialSecurityTaxStatements) {
            $excel->sheet('Dynamic Report Excel Sheet', function ($sheet) use ($payrollConfirms, $payroll, $combinedClasses, $request, $transBankStatements, $socialSecurityTaxStatements) {
                $sheet->loadView('reports.salary_dynamic_report_table', [
                    'title' => 'Dynamic Report',
                    'payrollConfirms' => $payrollConfirms,
                    'selectedClasses' => json_encode($request->classes),
                    'selectedClassesArray' => $request->classes ?? [],
                    'payroll' => $payroll,
                    'combinedClasses' => $combinedClasses,
                    'transBankStatements' => $transBankStatements,
                    'socialSecurityTaxStatements' => $socialSecurityTaxStatements
                ]);
            });
        })->download('xlsx');
    }
}
