@extends('layouts.default', ['crumbroute' => 'dashain-payment'])
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
                <h6>Dashain Payment Details</h6>
                <table width="100%">
                    <tr>
                        <td>Branch : {{$branch->office_name}} </td>
                        <td>Fiscal Year: {{$fiscal_year->fiscal_code}}</td>
                    </tr>
                    <tr>
                        <td>Payroll Date : {{$input['payment_date']}}</td>
                    </tr>

                </table>
                <table border="1px" width="100%">
                    <tr>
                        <td><b>SN</b></td>
                        <td><b>Staff Name</b></td>
                        <td><b>Staff Central ID</b></td>
                        <td><b>Attendance</b></td>
                        <td><b>Dashian Kharcha</b></td>
                        <td><b>Dashain Bonus</b></td>
                        <td><b>Tax Amount</b></td>
                        <td><b>Special Incentive</b></td>
                        <td><b>Net Payment</b></td>
                    </tr>
                    <tbody>
                    @foreach($data as $detail)

                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$detail['staff_name']}}</td>
                            <td>{{$detail['staff_id']}}</td>
                            <td>{{$detail['total_attendance']}}</td>
                            <td>{{$detail['dashain_expense']}}</td>
                            <td>{{$detail['dashain_bonus']}}</td>
                            <td>{{$detail['tds']}}</td>
                            <td>{{$detail['special_incentive']}}</td>
                            <td>{{$detail['net_payable']}}</td>
                        </tr>

                    @endforeach
                    </tbody>

                </table>

            </div>


            @if(!$already_confirmed)
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-right">
                            <form action="{{route('dashain-payment-confirm')}}" method="post">
                                {{csrf_field()}}
                                <input type="hidden" name="branch_id" value="{{$branch->office_id}}">
                                <input type="hidden" name="fiscal_year" value="{{$fiscal_year->id}}">
                                <input type="hidden" name="payment_date" value="{{$input['payment_date']}}">
                                <button type="submit" class="btn btn-success btn-lg confirm">Confirm</button>
                            </form>
                        </div>
                    </div>
                </div>

            @endif

        </div>
    </div>

@endsection

