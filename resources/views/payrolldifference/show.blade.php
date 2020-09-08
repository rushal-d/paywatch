@extends('layouts.default', ['crumbroute' => 'bankstatement'])
@section('title', $title)
@section('content')
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="basic-info card">
                <h5 class="card-header">{{ $title }} Reports</h5>
                <div class="card-block">
                    <p>Staff Name: <b> {{$staff->name_eng}}</b></p>
                    <p>Branch: <b> {{$payrollDetails->branch->office_name ?? ''}}</b></p>
                    <table class="table table-bordered">
                        <thead>
                        <th>SN</th>
                        <th>Particulars</th>
                        <th>Previous</th>
                        <th>New</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>Salary Hour Payable</td>
                            <td>{{$old_calculation->salary_hour_payable}}</td>
                            <td>{{$new_calculation['salary_hour_payable']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>OT Hour Payable</td>
                            <td>{{$old_calculation->ot_hour_payable}}</td>
                            <td>{{$new_calculation['ot_hour_payable']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>Basic Salary</td>
                            <td>{{$old_calculation->basic_salary}}</td>
                            <td>{{$new_calculation['basic_salary']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>Dearness Allowance</td>
                            <td>{{$old_calculation->dearness_allowance}}</td>
                            <td>{{$new_calculation['dearness_allowances']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>Other Allowance</td>
                            <td>{{$old_calculation->special_allowance}}</td>
                            <td>{{$new_calculation['special_allowances']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>Extra Allowance</td>
                            <td>{{$old_calculation->extra_allowance}}</td>
                            <td>{{$new_calculation['extra_allowance']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>Incentive</td>
                            <td>{{$old_calculation->incentive}}</td>
                            <td>{{$new_calculation['incentive']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>Redeem Home Leave/ Sick Leave</td>
                            <td>{{$old_calculation->home_sick_redeem_amount}}</td>
                            <td>{{$new_calculation['redeem_home_leave_amount']+$new_calculation['redeem_sick_leave_amount']}}</td>
                        </tr>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>Earned Home Leave</td>
                            <td>{{$old_calculation->payrollConfirmLeaveInfos->where('leaveMast.leave_code',3)->first()->earned ?? 0}}</td>
                            <td>{{$new_calculation['total_home_leave_earned_this_month']}}</td>
                        </tr>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>Earned Sick Leave</td>
                            <td>{{$old_calculation->payrollConfirmLeaveInfos->where('leaveMast.leave_code',4)->first()->earned ?? 0}}</td>
                            <td>{{$new_calculation['total_sick_leave_earned_this_month']}}</td>
                        </tr>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>Earned Substitute Leave</td>
                            <td>{{$old_calculation->payrollConfirmLeaveInfos->where('leaveMast.leave_code',8)->first()->earned ?? 0}}</td>
                            <td>{{$new_calculation['total_substitute_leave_earned_this_month']}}</td>
                        </tr>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>Provident Fund</td>
                            <td>{{$old_calculation->pro_fund}}</td>
                            <td>{{$new_calculation['profund_amount']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>Social Security Fund</td>
                            <td>{{$old_calculation->gratuity_amount}}</td>
                            <td>{{$new_calculation['gratuity_amount']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>Social Security Fund 1%</td>
                            <td>{{$old_calculation->social_security_fund_amount}}</td>
                            <td>{{$new_calculation['social_security_amount']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>OT Amount</td>
                            <td>{{$old_calculation->ot_amount}}</td>
                            <td>{{$new_calculation['ot_amount']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td><b>Gross Payable</b></td>
                            <td><b>{{$old_calculation->gross_payable}}</b></td>
                            <td><b>{{$new_calculation['gross_payment']}}</b></td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>Levy</td>
                            <td>{{$old_calculation->levy_amount}}</td>
                            <td>{{$new_calculation['levy_amount']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>Loan Amount</td>
                            <td>{{$old_calculation->loan_payment}}</td>
                            <td>{{$new_calculation['house_loan_installment']+$new_calculation['vehicle_loan_installment']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>Sundry Debtors</td>
                            <td>{{$old_calculation->sundry_dr}}</td>
                            <td>{{$new_calculation['sundry_dr_amount']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>Sundry Creditors</td>
                            <td>{{$old_calculation->sundry_cr}}</td>
                            <td>{{$new_calculation['sundry_cr_amount']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>Provident Fund Company's Contribution</td>
                            <td>{{$old_calculation->pro_fund_contribution}}</td>
                            <td>{{$new_calculation['profund_contribution_amount']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>Tax Amount</td>
                            <td>{{$old_calculation->tax}}</td>
                            <td>{{$new_calculation['income_tax']+$new_calculation['tds']}}</td>
                        </tr>
                        <tr>
                            <td>{{$i++}}</td>
                            <td><b>Salary Payable</b></td>
                            <td><b>{{$old_calculation->net_payable}}</b></td>
                            <td><b>{{$new_calculation['net_payment']}}</b></td>
                        </tr>
                        <tr>
                            <td colspan="3"><b>Sundry Difference</b></td>
                            <td><b><i>Rs. {{$new_sundry_amount}}</i></b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="basic-info card">
                        <h5 class="card-header">Grant Leaves</h5>
                        <div class="card-block">
                            <table class="table table-bordered">
                                <thead>
                                <th>SN</th>
                                <th>Leave Title</th>
                                <th>Previous</th>
                                <th>New</th>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{$leave_counter++}}</td>
                                    <td>Home Leave</td>
                                    <td>{{$old_calculation_data->grant_home_leave}}</td>
                                    <td>{{$new_calculation['approved_home_leave']}}</td>
                                </tr>
                                <tr>
                                    <td>{{$leave_counter++}}</td>
                                    <td>Sick Leave</td>
                                    <td>{{$old_calculation_data->grant_sick_leave}}</td>
                                    <td>{{$new_calculation['approved_sick_leave']}}</td>
                                </tr>
                                <tr>
                                    <td>{{$leave_counter++}}</td>
                                    <td>Substitute Leave</td>
                                    <td>{{$old_calculation_data->grant_substitute_leave}}</td>
                                    <td>{{$new_calculation['approved_substitute_leave']}}</td>
                                </tr>
                                <tr>
                                    <td>{{$leave_counter++}}</td>
                                    <td>Maternity Leave</td>
                                    <td>{{$old_calculation_data->grant_maternity_leave}}</td>
                                    <td>{{$new_calculation['approved_maternity_leave']}}</td>
                                </tr>
                                <tr>
                                    <td>{{$leave_counter++}}</td>
                                    <td>Maternity Care Leave</td>
                                    <td>{{$old_calculation_data->grant_maternity_care_leave}}</td>
                                    <td>{{$new_calculation['approved_maternity_care_leave']}}</td>
                                </tr>
                                <tr>
                                    <td>{{$leave_counter++}}</td>
                                    <td>Funeral Leave</td>
                                    <td>{{$old_calculation_data->grant_funeral_leave}}</td>
                                    <td>{{$new_calculation['approved_funeral_leave']}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="basic-info card">
                        <h5 class="card-header">Difference</h5>
                        <div class="card-block">
                            <h5><b>Salary Difference</b>: Rs {{$new_sundry_amount}} @if($new_sundry_amount>0)
                                    <i>({{ucfirst($new_sundry_amount_in_words)}} Rupees Only) </i> @endif</h5>
                            <form action="{{ route('payroll-difference-single-confirm') }}" method="get" id="payroll_difference">
                            <div class="row">
                                <label for="sundry_type" class="col-3 col-form-label">
                                    Sundry Type
                                </label>
                                <div class="col-md-9 form-group">
                                    {!! Form::select('sundry_type',$sundryTypes,null,['class'=>'','id'=>'sundry_type']) !!}
                                </div>
                                <input type="hidden" name="payroll_id" value="{{$payrollDetails->id}}">
                                <input type="hidden" name="staff_central_id" value="{{$staff->id}}">
                            </div>
                            @if($new_sundry_amount!=0)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-primary">Confirm Differences</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
