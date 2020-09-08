@extends('layouts.default', ['crumbroute' => 'citledger-personal'])
@section('title', $title)
@section('content')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i>
            <span>{{ $title }}</span>
            <form action="{{ route('citledger-personal') }}" method="get">
                <div class="row">
                    <div class="col-md-4">
                        <label for="" class="col-4 col-form-label">
                            Fiscal Year
                        </label>
                        <input type="hidden" name="staff_central_id" value="{{$staff->id}}">
                        <select id="fiscal_year" name="fiscal_year" class="input-sm" required>
                            @foreach($fiscalyear as $fiscal)
                                <option value="{{$fiscal->id}}"
                                        @if($fiscal->id==$fiscal_year->id) selected @endif>{{$fiscal->fiscal_code}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="" class="col-3 col-form-label">
                            From
                        </label>
                        <select id="month" name="from_month" class="input-sm" required>
                            <option value="">Select One</option>
                            @foreach($month_names as $key=>$month_name)
                                <option value="{{$key}}"
                                        @if($key==$from_month) selected @endif
                                >{{$month_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="" class="col-3 col-form-label">
                            To
                        </label>
                        <select id="month" name="to_month" class="input-sm" required>
                            <option value="">Select One</option>
                            @foreach($month_names as $key=>$month_name)
                                <option value="{{$key}}"
                                        @if($key==$to_month) selected @endif
                                >{{$month_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        {{ Form::submit('Filter',array('class'=>'btn btn-success btn-sm'))}}
                    </div>
                    <div class="col-md-1">
                        <button type="button" id="reset" class="btn-primary btn btn-sm">Reset</button>
                    </div>

                </div>
            </form>
            <form action="{{ route('citledger-personal') }}" method="get" id="reset-form">
                <input type="hidden" name="staff_central_id" value="{{$staff->id}}">
                <input type="hidden" name="fiscal_year" value="{{$fiscal_year->id}}">
                <input type="hidden" name="from_month" value="4">
                <input type="hidden" name="to_month" value="3">
            </form>

        </div>
        <div class="card-block">
            <p class="text-right">
                <input type="button" onclick="printDiv('printableArea')" value="Print"
                       class="btn btn-primary btn-sm" id="print">
            </p>
            <div id="printableArea">
                <div class="col-md-12 text-center">
                    <h5>CIT Statement</h5>
                </div>
                <table width="80%" align="center">
                    <tr>
                        <td><b> Name of Staff :</b> {{$staff->name_eng}}</td>
                        <td><b> Staff Central ID :</b> {{$staff->id}}</td>
                    </tr>
                </table>
                <table width="80%" cellspacing="0" class="text-center" border="1px" align="center">
                    <thead>
                    <th colspan="7">Fiscal Year-{{$fiscal_year->fiscal_code}}</th>
                    </thead>
                    <thead class="th-size">
                    <th>S.No.</th>
                    <th>Month</th>
                    <th>Payroll Date</th>
                    <th>Amount</th>
                    <th>Total</th>
                    </thead>
                    @foreach($months as $month)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$month_names[$month]}}</td>
                            <td>{{$details['date'][$month] ?? '-'}}</td>
                            <td>{{$details['cit_amount'][$month] ?? '-'}}</td>
                            <td>{{$details['total'][$month] ?? '-'}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>

        </div>
    </div>

@endsection
@section('script')
    <script>
        $('#reset').click(function () {
            $('#reset-form').submit();
        })
    </script>

@endsection
