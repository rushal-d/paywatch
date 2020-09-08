@extends('layouts.default', ['crumbroute' => 'houseloanedit'])
@section('title', $title)
@section('content')
    <div class="card">
        <div class="card-header">
           <div class="float-left">
               <i class="fa fa-align-justify"></i> House Loan Transaction
               &nbsp; Staff Name: <b>{{$house_loan->staff->name_eng ?? ''}}</b>
               &nbsp; Staff Central ID: <b>{{$house_loan->staff->staff_central_id ?? ''}}</b>
               &nbsp; Payroll Branch: <b>{{$house_loan->staff->payrollBranch->office_name ?? ''}}</b>
           </div>
            <div class="float-right">
                <a href="{{route('house-loan-diff-income-index',['house_loan_id' => $house_loan->house_id])}}" class="btn btn-success">Add Diff Income</a>
                <a href="{{route('houseloan-excel-export',$house_loan->house_id)}}" class="btn btn-primary">Export</a>
            </div>
        </div>
        <div class="card-block">
           @include('houseloantrans.table')
        </div>
    </div>
@endsection


@section('script')

@endsection
