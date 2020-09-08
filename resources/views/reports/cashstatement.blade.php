@extends('layouts.default', ['crumbroute' => 'bankstatement'])
@section('title', $title)
@section('content')


    <form action="{{ route('cashstatement') }}" method="get" enctype="multipart/form-data">

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
                                        <option
                                            value="{{$fiscal->id}}" {{$fiscal->id == $currentFiscalYear->id ? 'selected' : ''}}>{{$fiscal->fiscal_code}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row">
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
                            </div>
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
            <div class="float-right">
                <a href="{{route('cashstatement-export',$_GET)}}" class="btn btn-info btn-sm">Export</a>
            </div>
            <table class="table table-responsive table-striped table-hover table-all" width="100%" cellspacing="0">
                <tbody>
                <tr>
                    <td>S.No.</td>
                    <td>Staff_Name</td>
                    <td>Central Staff No. (CID)</td>
                    <td>Payroll Month</td>
                    <td>Branch Staff ID</td>
                    <td>Total Salary Payment</td>
                    <td>Remarks</td>
                    <td>Signature</td>
                </tr>
                @if($status)
                    @foreach($values as $key => $detail)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $detail['name_eng'] }}</td>
                            <td>{{ $detail['staff_central_id'] }}</td>
                            <td>{{ $detail['fiscal_code'] }}
                                :-{{$detail['salary_month']}}</td>
                            <td>{{ $detail['office_name']  }}</td>
                            <td>{{ $detail['total_payment']  }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                @endif
                </tbody>

            </table>
            @if($status)
                <div class="pagination-links">{{ $details->appends($_GET)->links()}}
                </div>
            @endif
        </div>
    </div>

@endsection
