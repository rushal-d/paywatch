@extends('layouts.default', ['crumbroute' => 'leavebalancestatementshow'])
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
                <h6>Tihar Payment Details</h6>
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
                    <thead>
                    <th>SN</th>
                    <th>Staff Name</th>
                    <th>Staff Central ID</th>
                    <th>Branch ID</th>
                    <th>Tihar Bonus</th>
                    <th>Tax Amount</th>
                    <th>Net Payment</th>
                    </thead>
                    <tbody>
                    @foreach($data as $details)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$details['staff_name']}}</td>
                            <td>{{$details['staff_id']}}</td>
                            <td>{{$details['branch_id']}}</td>
                            <td>{{$details['tihar_bomus_before_tax']}}</td>
                            <td>{{$details['tds']}}</td>
                            <td>{{$details['net_payable']}}</td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right">
                        <form action="{{route('tihar-payment-confirm')}}" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="branch_id" value="{{$branch->office_id}}">
                            <input type="hidden" name="fiscal_year" value="{{$fiscal_year->id}}">
                            <input type="hidden" name="payment_date" value="{{$input['payment_date']}}">
                            <button type="submit" class="btn btn-success btn-lg confirm">Confirm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

