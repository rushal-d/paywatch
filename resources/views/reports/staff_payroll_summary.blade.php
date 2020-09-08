@extends('layouts.default', ['crumbroute' => 'staff_payroll_summary-show'])
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
                    <h5>Payroll Details</h5>
                </div>
                <table width="90%" align="center">
                    <tr>
                        <td><b>Staff Name</b> : {{$staff->name_eng}}</td>
                        <td><b>Staff Central Id</b> : {{$staff->staff_central_id}}</td>
                    </tr>
                    <tr>
                        <td><b>Date From</b> : {{$date_from}}</td>
                        <td><b>Date To</b> : {{$date_to}}</td>
                    </tr>

                </table>
                <table border="1px" width="90%" align="center">
                    <thead>
                    <th>SN</th>
                    <th>Year</th>
                    <th>Payroll Name</th>
                    <th>Months</th>
                    <th>Amount</th>
                    </thead>
                    @foreach($details as $detail)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$detail['year']}}</td>
                            <td>{{$detail['payroll_name']}}</td>
                            <td>{{$detail['month']}}</td>
                            <td>{{$detail['amount']}}</td>
                        </tr>
                    @endforeach
                    <tbody>

                    <tr>
                        <td colspan="4">Total</td>
                        <td>{{$total}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

