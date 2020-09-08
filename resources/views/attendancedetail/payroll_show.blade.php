@extends('layouts.default', ['crumbroute' => 'payrollcreate'])
@section('title', $title)
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
@endsection
@section('content')

    <form method="POST" action="{{ route('attendance-calculate-confirm',$payroll_details->id) }}" id="confirm">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-12 col-sm-12">

                <div class="card">
                    <h5 class="card-header">Payroll Details</h5>
                    <div class="card-block">
                        <div class="payroll-details">
                            <div class="row">
                                <div class="col-6 col-md-2 col-sm-6">
                                    <strong>Branch: </strong> {{ $payroll_details->branch->office_name }}
                                </div>

                                <div class="col-6 col-md-2 col-sm-6">
                                    <strong>Fiscal Year: </strong> {{ $payroll_details->fiscalyear->fiscal_code }}
                                </div>

                                <div class="col-6 col-md-2 col-sm-6">
                                    <strong> Salary
                                        Month: </strong> {{ \App\Helpers\BSDateHelper::_get_nepali_month($payroll_details->salary_month) }}
                                </div>

                                <div class="col-6 col-md-6 col-sm-6">
                                    <strong> Date From: </strong> {{  $payroll_details->from_date_np }}
                                    <strong> Date To: </strong> {{  $payroll_details->to_date_np }}
                                </div>

                                <div class="col-6 col-md-2 col-sm-6">
                                    <strong> Total Days: </strong> {{ $payroll_details->total_days }}
                                </div>

                                <div class="col-6 col-md-2 col-sm-6">
                                    <strong> Public Holidays: </strong> {{ $payroll_details->total_public_holidays }}
                                </div>

                                <div class="col-6 col-md-2 col-sm-6">
                                    <a href="{{route('attendance-detail-download',$payroll_details->id)}}"
                                       class="btn btn-sm btn-primary">Download</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Basic Info --}}
                <div class="basic-info card">
                    <h5 class="card-header">Action</h5>
                    <div class="card-block">

                        <div class="table-scrollable dragscroll">
                            <table class="table table-bordered table-responsive" width="100%" cellspacing="0">
                                <tr>
                                    <td rowspan="2" class="small-cell">S.No.</td>
                                    <td rowspan="2" class="fullname-cell">Staff Name</td>
                                    <td rowspan="2">Branch ID</td>
                                    <td colspan="2" align="center" rowspan="2">Working Position</td>
                                    <td rowspan="2">Tax Code</td>
                                    <td colspan="2">Monthly Attendances</td>
                                    <td rowspan="2">साप्ताहिक/सार्वजनिक बिदामा अनुपस्थिती (Days)</td>
                                    <td colspan="2">Attendance on</td>
                                    <td colspan="6">तलबी बिदा उपभोग</td>
                                    <td colspan="2">बेतलबी अवस्था</td>
                                    <td colspan="2">बिदा सट्टा भुक्तानी</td>
                                    <td colspan="3">भुक्तानी/स्वीकृती योग्य</td>
                                    <td rowspan="2">Salary Hours Payable</td>
                                    <td rowspan="2">OT Hours Payable</td>
                                    <td colspan="11">Gross Payable</td>
                                    <td rowspan="2">Gross Payment</td>
                                    <td rowspan="2">लेवि रकम</td>
                                    <td rowspan="2">कर्जाको मासिक असुली रकम ( - )</td>
                                    <td rowspan="2">असुली गर्नुपर्ने अग्रिम भुक्तानी / विविध रकम ( - )</td>
                                    <td rowspan="2">भुक्तानी गर्नुपर्ने विविध रकम ( + )</td>
                                    <td rowspan="2">गत महिनाको फरक तलब (+/-)</td>
                                    <td rowspan="2">Tax Amount</td>
                                    <td rowspan="2">Net Payment</td>
                                    <td rowspan="2">यसै महिनाको भुक्तानी भईसकेको तलब रकम</td>
                                    <td rowspan="2">Diff. Amount</td>
                                    <td rowspan="2">बैंक खाताको सट्टा नगदीमा भुक्तानी गर्नु परेमा Cash लेख्ने</td>
                                    <td rowspan="2">Remarks</td>
                                </tr>
                                <tr>
                                    <td>Present Days</td>
                                    <td>Total Worked Hours</td>
                                    <td>WeekEnd Holiday (Hours)</td>
                                    <td>Public Holiday (Hours)</td>
                                    <td>घर बिदा</td>
                                    <td>बिरामी बिदा</td>
                                    <td>प्रसुती बिदा</td>
                                    <td>प्रसुती स्याहार बिदा</td>
                                    <td>किरिया बिदा</td>
                                    <td>सट्टा बिदा</td>
                                    <td>बेतलबी बिदा</td>
                                    <td>गयल / निलम्बन</td>
                                    <td>घर बिदा</td>
                                    <td>बिरामी बिदा</td>
                                    <td>संचित घर बिदा</td>
                                    <td>संचित बिरामी बिदा</td>
                                    <td>संचित सट्टा बिदा</td>
                                    <td>Salary</td>
                                    <td>Dearness Allowance</td>
                                    <td>Special Allowances</td>
                                    <td>Extra Allowance</td>
                                    <td>Pro. Fund</td>
                                    <td>Gratituity Amount</td>
                                    <td>Social Security Fund</td>
                                    <td>Home / Sick Leaves</td>
                                    <td>Incentive</td>
                                    <td>OT</td>
                                    <td>Outstation Facility</td>
                                </tr>

                                @foreach($details as $data)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$data->staff->name_eng}} (B-{{$data->staff->staff_central_id ?? ''}})</td>
                                        <td>{{$data->staff->main_id}}</td>
                                        <td>{{$data->staff->jobtype->jobtype_name ?? ''}}</td>
                                        <td>{{$data->min_work_hour}}</td>
                                        <td>{{($data->staff->marrid_stat==1) ?'Couple': 'Single'}}</td>
                                        <td>{{$data->present_days}}</td>
                                        <td>{{$data->total_worked_hours}}</td>
                                        <td>{{$data->days_absent_on_holiday}}</td>
                                        <td>{{$data->weekend_work_hours}}</td>
                                        <td>{{$data->public_holiday_work_hours}}</td>
                                        <td>{{$data->home_leave_taken}}</td>
                                        <td>{{$data->sick_leave_taken}}</td>
                                        <td>{{$data->maternity_leave_taken}}</td>
                                        <td>{{$data->maternity_care_leave_taken}}</td>
                                        <td>{{$data->funeral_leave_taken}}</td>
                                        <td>{{$data->substitute_leave_taken}}</td>
                                        <td>{{$data->unpaid_leave_taken}}</td>
                                        <td>{{$data->suspended_days}}</td>
                                        <td>{{$data->redeem_home_leave}}</td>
                                        <td>{{$data->redeem_sick_leave}}</td>
                                        <td>{{$data->useable_home_leave}}</td>
                                        <td>{{$data->useable_sick_leave}}</td>
                                        <td>{{$data->useable_substitute_leave}}</td>
                                        <td>{{$data->salary_hour_payable}}</td>
                                        <td>{{$data->ot_hour_payable}}</td>
                                        <td>{{$data->basic_salary}}</td>
                                        <td>{{$data->dearness_allowance}}</td>
                                        <td>{{$data->special_allowance}}</td>
                                        <td>{{$data->extra_allowance}}</td>
                                        <td>{{$data->pro_fund}}</td>
                                        <td>{{$data->gratuity_amount}}</td>
                                        <td>{{$data->social_security_fund_amount}}</td>
                                        <td>{{$data->home_sick_redeem_amount}}</td>
                                        <td>{{$data->incentive}}</td>
                                        <td>{{$data->ot_amount}}</td>
                                        <td>{{$data->outstation_facility_amount ?? '-'}}</td>
                                        <td>{{$data->gross_payable}}</td>
                                        <td>{{$data->levy_amount}}</td>
                                        <td>{{$data->loan_payment}}</td>
                                        <td>{{$data->sundry_dr}}</td>
                                        <td>{{$data->sundry_cr}}</td>
                                        <td></td>
                                        <td>{{$data->tax}}</td>
                                        <td>{{$data->net_payable}}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{$data->remarks}}</td>
                                    </tr>

                                @endforeach
                            </table>
                        </div>

                    </div>
                    @permission('attendance-calculate-confirm')
                    @if(empty($payroll_details->confirmed_by))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-right form-control">
                                    <button type="button" class="btn btn-success btn-lg confirm">Confirm</button>
                                </div>
                            </div>
                        </div>
                    @endif
                    @endpermission
                </div>

            </div>

        </div>

    </form>
@endsection


@section('script')
    <script src="{{ asset('assets/js/vex.combined.js') }}"></script>
    <script>
        //apply vex dialog
        (function () {
            vex.defaultOptions.className = 'vex-theme-os'
            //vex.dialog.buttons.YES.text = 'Yes'
            vex.dialog.buttons.YES.className = 'btn btn-danger'
        })();
    </script>
    <script>
        $('.confirm').click(function () {
            vex.dialog.confirm({
                message: 'Do you want to confirm payroll?',
                callback: function (value) {
                    console.log('Callback value: ' + value);
                    if (value) { //true if clicked on ok
                        $('#confirm').submit();
                    }
                }
            });
        });
    </script>
@endsection
