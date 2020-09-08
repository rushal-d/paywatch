@extends('layouts.default', ['crumbroute' => 'staff-payroll-detail-show'])
@section('title', $title)
@section('content')

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i>
            <span>{{ $title }}</span>
        </div>
        <div class="card-block">
            <p class="text-right"><input type="button" onclick="printDiv('printableArea')" value="Print"
                                         class="btn btn-primary btn-sm" id="print">
            </p>
            <div class="row" id="printableArea">
                <div class="text-center col-md-12">
                    <h6>Staff Month-Wise Payroll Details</h6>
                </div>
                <table width="100%">
                    <tr>
                        <td><b>Staff Name</b> : {{$staff_detail->name_eng}}</td>
                        <td><b>Staff Central Id</b> : {{$staff_detail->staff_central_id}}</td>
                        <td><b>Fiscal Year</b> : {{$fiscal_year->fiscal_code ?? ''}}</td>
                    </tr>
                </table>
                <table border="1px" width="100%">
                    <thead>
                    <th>SN</th>
                    <th>Particulars</th>
                    @for($month_count=1;$month_count<=12;$month_count++)
                        <th>{{$month_names[$month_count]}}</th>
                    @endfor
                    </thead>
                    <tbody>

                    <tr>
                        <td>{{$i++}}</td>
                        <td>Payroll Branch</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->payroll->branch->office_name ?? ''}}</td>
                        @endfor
                    </tr>

                    <tr>
                        <td>{{$i++}}</td>
                        <td>Salary Hour Payable</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->salary_hour_payable ?? ''}}</td>
                        @endfor
                    </tr>

                    <tr>
                        <td>{{$i++}}</td>
                        <td>OT Hour Payable</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->ot_hour_payable ?? ''}}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>Basic Salary</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->basic_salary ?? ''}}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>Dearness Allowance</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->dearness_allowance ?? ''}}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>Other Allowance</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->special_allowance ?? ''}}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>Extra Allowance</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->extra_allowance ?? ''}}</td>
                        @endfor
                    </tr>

                    <tr>
                        <td>{{$i++}}</td>
                        <td>Incentive</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->incentive ?? ''}}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>Redeem Home/Sick Leave</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->home_sick_redeem_amount ?? ''}}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>Providend Fund</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->pro_fund ?? ''}}</td>
                        @endfor
                    </tr>

                    <tr>
                        <td>{{$i++}}</td>
                        <td>Social Security Fund</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->gratuity_amount ?? ''}}</td>
                        @endfor
                    </tr>

                    <tr>
                        <td>{{$i++}}</td>
                        <td>Social Security Fund 1%</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->social_security_fund_amount ?? ''}}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>OT Amount</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->ot_amount ?? ''}}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>Outstation Facility</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->outstation_facility_amount ?? ''}}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td>{{$i++}}</td>
                        <td><b>Gross Payable</b></td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td><b>{{$payroll_confirm->gross_payable ?? ''}}</b></td>
                        @endfor
                    </tr>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>Levy</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->levy_amount ?? ''}}</td>
                        @endfor
                    </tr>

                    <tr>
                        <td>{{$i++}}</td>
                        <td>Loan Amount</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->loan_payment ?? ''}}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>Sundry Debtors</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->sundry_dr ?? ''}}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>Sundry Creditors</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->sundry_cr ?? ''}}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>Providend Fund Company's Contribution</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->pro_fund_contribution ?? ''}}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td>{{$i++}}</td>
                        <td>Tax Amount</td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td>{{$payroll_confirm->tax ?? ''}}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td colspan="2"><b>Total Salary Payable</b></td>
                        @for($month_count=1;$month_count<=12;$month_count++)
                            @php $payroll_confirm=$payroll_confirms->where('payroll.salary_month',$month_count)->first() @endphp
                            <td><b>{{$payroll_confirm->net_payable ?? ''}}</b></td>
                        @endfor
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection

