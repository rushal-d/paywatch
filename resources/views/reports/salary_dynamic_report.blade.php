@extends('layouts.default', ['crumbroute' => 'salary-dynamic-report'])
@section('title', $title)
@section('style')
    <style>
        .visibility {
            display: block;
        }

        .non-visibility {
            display: none !important;
        }
    </style>


@endsection
@section('content')

    <div class="modal fade" id="singleStaffModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="width: 750px; margin: auto;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Dynamic Salary Filter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['method' => 'get', 'route' => 'dynamicReport','id' => 'modal-single-form']) !!}
                    <input type="hidden" name="branch_id" value="{{request('branch_id')}}">
                    <input type="hidden" name="department_id" value="{{request('department_id')}}">
                    <input type="hidden" name="fiscal_year_id" value="{{request('fiscal_year_id')}}">
                    <input type="hidden" name="month_id" value="{{request('month_id')}}">
                    <div class="modal-body">
                        <div class="form-group">
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                 data-parent="#accordion">
                                <div class="card-body">
                                    <div class="card-text">
                                        <div class="row">
                                            <div class="col-md-4">
                                                @foreach($combinedClasses as $className => $optionName)
                                                    <div class="visibility">
                                                        <input type="checkbox" id="class-name"
                                                               value="{{$className}}"
                                                               data-class-id="{{$className}}"
                                                               name="classes[]"
                                                               multiple="multiple" {{in_array($className, $selectedClassesArray)? "checked"  : ''}}>{{$optionName}}
                                                    </div>
                                            </div>
                                            <div class="col-md-4">
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="modal-single-submit">Filter</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-primary" id="launch-demo" data-toggle="modal" data-target="#exampleModal">
        Filter Report
    </button>

    <a href="{{route('dynamicReport.download', $_GET)}}" type="button" class="btn btn-primary" id="download">
        Download
    </a>
    <div class="overflow" style="height: 700px; overflow: auto;">

        {{--<table class="table table-bordered table-responsive" width="100%" cellspacing="0">
            <tr>
                <td>S.No.</td>
                <td>Working Position</td>
                <td>Staff Central ID</td>
                <td class="non-visibility name">Name</td>
                <td class="non-visibility branch-staff-id">Branch Staff ID</td>
                <td class="non-visibility designation">Designation</td>
                <td class="non-visibility pan-no">Pan No.</td>
                <td class="non-visibility gender">Gender</td>
                <td class="non-visibility is-extra-allowance">Is Extra Allowance</td>
                <td class="non-visibility extra-in-dashain">Extra in Dashain</td>

                <td class="non-visibility saruwa">Saruwa</td>
                <td class="non-visibility saruwa-date">Saruwa Date</td>
                <td class="non-visibility temporary-date-en">Temporary Date English</td>
                <td class="non-visibility temporary-date-np">Temporary Date Nepali</td>
                <td class="non-visibility permanent-date-en">Permanent Date English</td>
                <td class="non-visibility permanent-date-np">Permanent Date Nepali</td>
                <td class="non-visibility date-of-birth-en">DOB Date English</td>
                <td class="non-visibility date-of-birth-np">DOB Date Nepali</td>
                <td class="non-visibility transferred-date-en">Transferred Date English</td>
                <td class="non-visibility transferred-date-np">Transferred Date Nepali</td>

                <td class="non-visibility resign-status">Resign / Retire</td>
                <td class="non-visibility resign-date-en">Resign / Retire Date</td>
                <td class="non-visibility resign-date-np">Resign / Retire Date Nepali</td>
                <td class="non-visibility bank-name">Bank Name</td>
                <td class="non-visibility bank-ac-number">Bank Account No</td>
                <td class="non-visibility provident-ac-number">Provident Account No</td>
                <td class="non-visibility social-security-ac-number">Social Security Account No</td>
                <td class="non-visibility basic-salary">Basic Salary</td>
                <td class="non-visibility previous-grade">Previous Grade</td>
                <td class="non-visibility current-grade">Current Grade</td>

                <td class="non-visibility dearness-allowance">Dearness Allowance</td>
                <td class="non-visibility risk-allowance">Risk Allowance</td>
                <td class="non-visibility special-allowance">Special Allowance</td>
                <td class="non-visibility misc-allowance">Miscellaneous Allowance</td>

                <td class="non-visibility incentive">Incentive</td>
                <td class="non-visibility home-leave">Home Leave</td>
                <td class="non-visibility sick-leave">Sick Leave</td>
                <td class="non-visibility pregnant-leave">Pregnant Leave</td>
                <td class="non-visibility pregnant-care-leave">Pregnant Care Leave</td>
                <td class="non-visibility funeral-leave">Funeral Leave</td>

                <td class="non-visibility leave-without-pay">Leave Without Pay</td>
                <td class="non-visibility gayal-nilamban">Gayal Nilamban</td>
                <td class="non-visibility present-days">Present Days</td>
                <td class="non-visibility upabhog-days">Upabhog Days</td>
                <td class="non-visibility dashain-tihar-present-days">Dashain Tihar Present Days</td>
                <td class="non-visibility karyarat-awasta">Karyarat Awasta</td>
                <td class="non-visibility tax-code">Tax Code</td>
                <td class="non-visibility karyarat-awasta">Karyarat Awasta</td>
                <td class="non-visibility bharna-sewa-awadhi">Bharna Sewa Awadhi</td>
                <td class="non-visibility age">Age</td>

                <td class="non-visibility salary">Salary</td>
                <td class="non-visibility dearness-allowance">Dearness Allowance</td>
                <td class="non-visibility dearness-allowance">Other Allowance</td>
                <td class="non-visibility total">Total</td>
                <td class="non-visibility home-leave">Home Leave</td>
                <td class="non-visibility sick-leave">Sick Leave</td>
                <td class="non-visibility pregnant-leave">Pregnant Leave</td>
                <td class="non-visibility pregnant-care-leave">Pregnant Care Leave</td>
                <td class="non-visibility funeral-leave">Funeral Leave</td>
                <td class="non-visibility leave-without-pay">Leave Without Pay</td>

                <td class="non-visibility gayal-niamban-awadhi">Gayal nilamban Awadhi</td>
                <td class="non-visibility present-days">Present Days</td>
                <td class="non-visibility satta-bida">Satta Bida</td>
                <td class="non-visibility empty">Empty</td>
                <td class="non-visibility karyarat-awasta">Karyarat Awasta</td>
                <td class="non-visibility tax-code">Tax Code</td>
                <td class="non-visibility remarks">Remarks</td>
            </tr>
            @foreach($staffmains as $staffmain)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{ $staffmain->jobtype->jobtype_name ?? ''}}</td>
                    <td>{{$staffmain->staff_central_id}}</td>
                    <td class="non-visibility name">{{$staffmain->name_eng}}</td>
                    <td class="non-visibility branch-staff-id">{{$staffmain->branch_id}}</td>
                    <td class="non-visibility designation">{{$staffmain->jobposition->post_title ?? ''}}</td>
                    <td class="non-visibility pan-no">{{$staffmain->pan_number}}</td>
                    @if($staffmain->Gender == 1)
                        <td class="non-visibility gender">M</td>
                    @elseif($staffmain->Gender == 2)
                        <td class="non-visibility gender">F</td>
                    @else
                        <td class="non-visibility gender">O</td>
                    @endif
                    <td class="non-visibility is-extra-allowance">{{$staffmain->extra_allow ? 'Yes' : 'No'}}</td>
                    <td class="non-visibility extra-in-dashain">{{$staffmain->dashain_allow ? 'Yes' : 'No'}}</td>
                    <td class="non-visibility saruwa">Saruwa</td>
                    <td class="non-visibility saruwa-date">Saruwa Date</td>
                    <td class="non-visibility temporary-date-en">{{$staffmain->temporary_con_date}}</td>
                    <td class="non-visibility temporary-date-np">{{$staffmain->temporary_con_date_np}}</td>
                    <td class="non-visibility permanent-date-en">{{$staffmain->permanent_date}}</td>
                    <td class="non-visibility permanent-date-np">{{$staffmain->permanent_date_np}}</td>
                    <td class="non-visibility date-of-birth-en">{{$staffmain->staff_dob}}</td>
                    @if($staffmain->staff_dob > '1800-01-01')
                        <td class="non-visibility date-of-birth-np">{{\App\Helpers\BSDateHelper::AdToBs('-', $staffmain->staff_dob)}}</td>
                    @else
                        <td class="non-visibility date-of-birth-np"></td>
                    @endif
                    @if($staffmain->staffTransfer->count() > 1)
                        <td class="non-visibility transferred-date-en">{{$staffmain->staffTransfer->last()->from_date}}</td>
                        <td class="non-visibility transferred-date-np">{{$staffmain->staffTransfer->last()->from_date_np}}</td>
                    @else
                        <td class="non-visibility transferred-date-en"></td>
                        <td class="non-visibility transferred-date-np"></td>
                    @endif

                    @if($staffmain->staff_status == \App\StafMainMastModel::STAFF_STATUS_FOR_WORKING_OR_SUSPENSE)
                        <td class="non-visibility resign-status"></td>
                        <td class="non-visibility resign-date-en"></td>
                        <td class="non-visibility resign-date-np"></td>
                    @elseif($staffmain->staff_status == \App\StafMainMastModel::STAFF_STATUS_FOR_RESIGN_OR_FIRED && $staffmain->staffStatus->count() > 0)
                        @php
                            $employeeStatus = $staffmain->staffStatus->last()
                        @endphp
                        <td class="non-visibility resign-status">{{config('constants.employee_status')[$employeeStatus->status]}}</td>
                        <td class="non-visibility resign-date-en">{{$employeeStatus->date_to}}</td>
                        <td class="non-visibility resign-date-np">{{$employeeStatus->date_to_np}}</td>
                    @else
                        <td class="non-visibility resign-status"></td>
                        <td class="non-visibility resign-date-en"></td>
                        <td class="non-visibility resign-date-np"></td>
                    @endif
                    <td class="non-visibility bank-name">{{$staffmain->bankInformation->bank_name ?? ''}}</td>
                    <td class="non-visibility bank-ac-number">{{$staffmain->acc_no}}</td>
                    <td class="non-visibility provident-ac-number">{{$staffmain->profund_acc_no}}</td>
                    <td class="non-visibility social-security-ac-number">{{$staffmain->social_security_fund_acc_no}}</td>
                    <td class="non-visibility basic-salary">{{$staffmain->jobposition->basic_salary ?? 0}}</td>
                    <td class="non-visibility previous-grade">{{$staffmain->total_grade_amount}}</td>
                    <td class="non-visibility current-grade">{{$staffmain->latestsalary->add_grade_this_fiscal_year ?? 0}}</td>

                    @php
                        $basicSalary = $staffmain->jobposition->basic_salary ?? 0;
                        $totalGrade = $staffmain->total_grade_amount ?? 0;
                        $dearnessAmount = $staffmain->dearness_allowance_amount ?? 0;
                        $specialAllowance = $staffmain->special_allowance_amount ?? 0;
                        $specialAllowance2 = $staffmain->special_allowance_2_amount ?? 0;
                        $riskAllowance = $staffmain->risk_allowance_amount ?? 0;
                        $totalSalary = $basicSalary + $totalGrade + $dearnessAmount + $specialAllowance + $specialAllowance2 + $riskAllowance;
                    @endphp

                    <td class="non-visibility dearness-allowance">{{$dearnessAmount}}</td>
                    <td class="non-visibility risk-allowance">{{$riskAllowance}}</td>
                    <td class="non-visibility special-allowance">{{$specialAllowance}}</td>
                    <td class="non-visibility misc-allowance">{{$specialAllowance2}}</td>
                    <td class="non-visibility incentive">Incentive</td>
                    <td class="non-visibility home-leave">Home Leave</td>
                    <td class="non-visibility sick-leave">Sick Leave</td>
                    <td class="non-visibility pregnant-leave">Pregnant Leave</td>
                    <td class="non-visibility pregnant-care-leave">Pregnant Care Leave</td>
                    <td class="non-visibility funeral-leave">Funeral Leave</td>

                    <td class="non-visibility leave-without-pay">Leave Without Pay</td>
                    <td class="non-visibility gayal-nilamban">Gayal Nilamban</td>
                    <td class="non-visibility present-days">Present Days</td>
                    <td class="non-visibility upabhog-days">Upabhog Days</td>
                    <td class="non-visibility dashain-tihar-present-days">Dashain Tihar Present Days</td>
                    <td class="non-visibility karyarat-awasta">Karyarat Awasta</td>
                    <td class="non-visibility tax-code">Tax Code</td>
                    <td class="non-visibility karyarat-awasta">Karyarat Awasta</td>
                    <td class="non-visibility bharna-sewa-awadhi">Bharna Sewa Awadhi</td>
                    @if(isset($staffmain->staff_dob))
                        <td class="non-visibility age">{{\Carbon\Carbon::parse($staffmain->staff_dob)->age}}</td>
                    @else
                        <td class="non-visibility age"></td>
                    @endif

                    <td class="non-visibility salary">{{$totalSalary}}</td>
                    <td class="non-visibility dearness-allowance">{{$staffmain->dearness_allowance_amount}}</td>
                    <td class="non-visibility other-allowance">{{$staffmain->other_allowance_amount}}</td>
                    <td class="non-visibility total">Total</td>
                    <td class="non-visibility home-leave">Home Leave</td>
                    <td class="non-visibility sick-leave">Sick Leave</td>
                    <td class="non-visibility pregnant-leave">Pregnant Leave</td>
                    <td class="non-visibility pregnant-care-leave">Pregnant Care Leave</td>
                    <td class="non-visibility funeral-leave">Funeral Leave</td>
                    <td class="non-visibility leave-without-pay">Leave Without Pay</td>

                    <td class="non-visibility gayal-niamban-awadhi">Gayal nilamban Awadhi</td>
                    <td class="non-visibility present-days">Present Days</td>
                    <td class="non-visibility satta-bida">Satta Bida</td>
                    <td class="non-visibility empty">Empty</td>
                    <td class="non-visibility karyarat-awasta">Karyarat Awasta</td>
                    <td class="non-visibility tax-code">Tax Code</td>
                    <td class="non-visibility remarks">Remarks</td>
            @endforeach
        </table>--}}

        <div class="card">
            <h5 class="card-header">Salary Dynamic Report</h5>
            <div class="card-block">
                <div class="payroll-details">
                    <div class="row">
                        <div class="col-6 col-md-2 col-sm-6">
                            <strong>Branch: </strong> {{ $payroll->branch->office_name }}
                        </div>

                        <div class="col-6 col-md-2 col-sm-6">
                            <strong>Fiscal Year: </strong> {{ $payroll->fiscalyear->fiscal_code }}
                        </div>

                        <div class="col-6 col-md-2 col-sm-6">
                            <strong> Salary
                                Month: </strong> {{ \App\Helpers\BSDateHelper::_get_nepali_month($payroll->salary_month) }}
                        </div>

                        <div class="col-6 col-md-6 col-sm-6">
                            <strong> Date From: </strong> {{  $payroll->from_date_np }}
                            <strong> Date To: </strong> {{  $payroll->to_date_np }}
                        </div>

                        <div class="col-6 col-md-2 col-sm-6">
                            <strong> Total Days: </strong> {{ $payroll->total_days }}
                        </div>

                        <div class="col-6 col-md-2 col-sm-6">
                            <strong> Public Holidays: </strong> {{ $payroll->total_public_holidays }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-block">
                @include('reports.salary_dynamic_report_table')
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
            integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
            crossorigin="anonymous"></script>

    <script>
        let selectedClasses = @json($selectedClasses);

        $.each(JSON.parse(selectedClasses), function (key, value) {
            $('.' + value).removeClass('non-visibility');
        });
        $('#launch-demo').on('click', function () {
            $('#singleStaffModal').modal('show');
        });
    </script>
@endsection
