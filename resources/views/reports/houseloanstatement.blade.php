@extends('layouts.default', ['crumbroute' => 'houseloanstatement'])
@section('title', $title)
@section('content')


    <form action="{{ route('houseloanstatement') }}" method="get" enctype="multipart/form-data">

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
                                    @foreach($branch as $id => $bran)
                                        <option value="{{$id}}">{{$bran}}</option>
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
                <table class="table table-responsive table-bordered table-hover table-all" width="100%" cellspacing="0">
                    <tr>
                        <td>Fiscal Year-{{$fiscal_year->fiscal_code}}</td>
                    </tr>
                    <tr>
                        <td rowspan="2">Staff_Name</td>
                        <td rowspan="2">Designation</td>
                        <td rowspan="2">Central Staff No. (CID)</td>
                        <td rowspan="2">Branch Staff ID</td>
                        <td colspan="4">Loan Details</td>
                        @foreach($months as $month)
                            <td rowspan="2">
                                {{$month_names[$month]}}
                            </td>
                        @endforeach
                        <td rowspan="2">Total Installement Received</td>
                        <td rowspan="2">Remainig Loan Amount</td>
                    </tr>
                    <tr>

                        <td>Loan No.</td>
                        <td>Amount</td>
                        <td>Granted Date</td>
                        <td>Previous Remaining Loan Balance</td>

                    </tr>

                    @foreach($details as $key => $detail)
                        <tr>
                            <td>{{$detail->name_eng}}</td>
                            <td>{{$detail->jobposition->post_title}}</td>
                            <td>{{$detail->id}}</td>
                            <td>{{$detail->branch_id}}</td>
                            <td>{{--loan id--}}</td>
                            <td>{{$loan_amt[$detail->id]}}</td>
                            <td>{{$loan_date[$detail->id]}}</td>
                            <td></td>
                            @foreach($months as $month)
                                <td>
                                    {{$loan_in_month[$detail->id][$month]}}
                                </td>
                            @endforeach
                            <td></td>
                            <td>{{$remaining_loan_amt[$detail->id]}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>

        </div>
    </div>

@endsection
