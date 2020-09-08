@extends('layouts.default', ['crumbroute' => 'loan-deduct-show'])
@section('title', $title)
@section('style')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">{{$title}}</h5>
                <div class="card-block">
                    <div class="card-text">
                        <table class="table table-bordered">
                            <thead>
                            <th>SN</th>
                            <th>Staff Name</th>
                            <th>Loan Type</th>
                            <th>Installment Amount</th>
                            <th>Payroll Detail</th>
                            <th>Remarks</th>
                            </thead>
                            <tbody>
                            @foreach($loanDeducts as $loanDeduct)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$loanDeduct->staff->name_eng ?? ''}}</td>
                                    <td>{{$loan_types[$loanDeduct->loan_type] ?? ''}}</td>
                                    <td>{{$loanDeduct->loan_deduct_amount}}</td>
                                    <td>{{$payrollDetail->payroll_name ?? ''}}</td>
                                    <td>{{$loanDeduct->remarks ?? ''}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
