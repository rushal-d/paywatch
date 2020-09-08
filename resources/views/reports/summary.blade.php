@extends('layouts.default', ['crumbroute' => 'summary'])
@section('title', $title)
@section('content')


    <form action="{{ route('summary') }}" method="get" enctype="multipart/form-data">

        <div class="row">
            <div class="col-md-6 col-sm-12">
                {{-- Basic Info --}}
                <div class="basic-info card">
                    <h5 class="card-header">{{ $title }} Reports</h5>
                    <div class="card-block">
                        <div class="card-text">
                            <div class="form-group row">
                                <label for="branch_id" class="col-3 col-form-label">
                                    Branch
                                </label>
                                <select id="branch_id" name="branch_id" class="input-sm" required>
                                    @foreach($branch as $office_id => $bran)
                                        <option value="{{$office_id}}">{{$bran}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="fiscal_year" class="col-3 col-form-label">
                                    Fiscal Year
                                </label>
                                <select id="fiscal_year" name="fiscal_year" class="input-sm" required>
                                    @foreach($fiscalyear as $fiscal)
                                        <option value="{{$fiscal->id}}" {{$fiscal->id == $fiscal_year->id ? 'selected' : ''}}>{{$fiscal->fiscal_code}}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{--<div class="form-group row">
                                <label for="month" class="col-3 col-form-label">
                                    Month
                                </label>
                                <select id="month" name="month" class="input-sm" required>
                                    <option value="">Select One</option>
                                    <option value="1">Baishakh</option>
                                    <option value="2">Jestha</option>
                                    <option value="3">Asar</option>
                                    <option value="4">Shrawan</option>
                                    <option value="5">Bhadau</option>
                                    <option value="6">Aswin</option>
                                    <option value="7">Kartik</option>
                                    <option value="8">Mansir</option>
                                    <option value="9">Poush</option>
                                    <option value="10">Magh</option>
                                    <option value="11">Falgun</option>
                                    <option value="12">Chaitra</option>
                                </select>
                            </div>--}}
                        </div>
                    </div>
                    {{--  Save --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-right form-control">
                                {{ Form::submit('Submit',array('class'=>'btn btn-success'))}}
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Right Sidebar  --}}
                <div class="col-md-5 col-sm-12">


                </div>
                {{-- End of sidebar --}}

            </div>
            {{--{{ Form::close()  }}--}}
        </div>
    </form>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i>
            <span>{{ $title }}</span>
        </div>
        <div class="card-block">
            <div class="table-responsive" style="overflow-y: hidden">
                @role('Administrator')
                Summary of All branch
                @endrole
                <table class="table table-responsive table-bordered table-hover table-all" width="100%" cellspacing="0">
                    <tr>
                        <td colspan="24">Fiscal Year- {{$fiscal_year->fiscal_code}}</td>
                    </tr>
                    <tr>
                        <td>S.No.</td>
                        <td>Month</td>
                        <td>No. of Staff Paid</td>
                        <td>Average Attendance</td>
                        <td>Salary</td>
                        <td>Dearness Allowance</td>
                        <td>Special Allowances</td>
                        <td>Extra Allowance</td>
                        <td>Co. contribution Pro. Fund</td>
                        <td>Home/Sick Leaves</td>
                        <td>OT</td>
                        <td>OutStation Facility</td>
                        <td>Dashai Kharch</td>
                        <td>Bonus</td>
                        <td>Special Incentive for 2074/75 only</td>
                        <td>Miscell. +/-</td>
                        <td>Total Expenses</td>
                        @foreach($banks as $bank)
                            <td>{{$bank->bank_name}}</td>
                        @endforeach
                        <td>Hand Cash</td>
                        <td>Net paid to Employees</td>
                        <td>Provision for Profund</td>
                        <td>Provision for Tax</td>
                        <td>Total Expenses</td>
                    </tr>
                    @foreach($month_names as $month_no=>$month_name)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$month_name}}</td>
                            <td>{{$details['no_of_staff_paid'][$month_no] ?? '-'}}</td>
                            <td>{{$details['average_attendance'][$month_no] ?? '-'}}</td>
                            <td>{{$details['basic_salary'][$month_no] ?? '-'}}</td>
                            <td>{{$details['dearness_allowance'][$month_no] ?? '-'}}</td>
                            <td>{{$details['special_allowance'][$month_no] ?? '-'}}</td>
                            <td>{{$details['extra_allowance'][$month_no] ?? '-'}}</td>
                            <td>{{$details['pro_fund_contribution'][$month_no] ?? '-'}}</td>
                            <td>{{$details['home_sick_redeem_amount'][$month_no] ?? '-'}}</td>
                            <td>{{$details['ot_amount'][$month_no] ?? '-'}}</td>
                            <td>{{$details['outstation_facility_amount'][$month_no] ?? '-'}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @foreach($banks as $bank)
                                <td>{{$details[$bank->id][$month_no] ?? '-'}}</td>
                            @endforeach
                            <td>{{$details['hand_cash'][$month_no] ?? '-'}}</td>
                            <td>{{$details['net_payment'][$month_no] ?? '-'}}</td>
                            <td>{{$details['pro_fund'][$month_no] ?? '-'}}</td>
                            <td>{{$details['tax'][$month_no] ?? '-'}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </table>

            </div>

        </div>
    </div>

@endsection
