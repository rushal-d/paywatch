@extends('layouts.default', ['crumbroute' => 'payrollcreate'])
@section('title', $title)
@section('style')

@endsection
@section('content')
    {{-- Basic Info --}}
    <div class="basic-info card">
        <h5 class="card-header">Overtime Payroll Calculation
        </h5>
        <div>
            <div class="float-right">
                @php
                    $_GET['excel_export']=1;
                @endphp
                <a href="{{route('overtime-payroll-calculate',$_GET)}}" class="btn btn-success">Excel Export</a>
            </div>
        </div>

        <div class="card-block">
            <div class="card-text">
                <h6 class="text-center">Overtime Allowance For {{$salary_month_name}}
                    , {{$fiscal_year->fiscal_code}}</h6>
                @include('overtimepayroll.calculate-table')
            </div>
        </div>
    </div>
@endsection


@section('script')

@endsection
