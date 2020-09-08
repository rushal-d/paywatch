@extends('layouts.default', ['crumbroute' => 'calculate-leave-balance-index'])
@section('title', $title)
@section('style')
    <style>
        #calculate-leave-balance-form {
            overflow-x: scroll;
        }

        #parent {
            max-height: 1000px !important;
        }
        .table th, .table td {
            padding: 5px;
            font-size: 9px;
        }
        .table-scrollable td{
         min-width: inherit;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Leave Balance For [<b>{{config('constants.month_name')[$month_id]}}</b>]
            of [<b>{{$fiscalYear->fiscal_code}}</b>] for [<b>{{$branchId->office_name}}</b>]
            <span class="tag tag-pill tag-success pull-right">{{ $staffs->count() }}</span>
        </div>
        <div class="card-block">
            {!! Form::open(['route' => 'calculate-leave-balance-store', 'id' => 'calculate-leave-balance-form']) !!}
            <div id="parent" class="table-scrollable dragscroll">

                <table class="table table-responsive table-bordered table-hover table-all" id="fixTable">
                    <thead>
                    <tr align="left">
                        <th width="15%">Staff Name</th>
                        @foreach($earnableSystemLeaves as $earnableSystemLeave)
                            <th>{{$earnableSystemLeave->leave_name}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    @foreach($staffs as $staff)
                        <tr>
                            <td>{{$staff->name_eng}}</td>
                            @foreach($earnableSystemLeaves as $earnableSystemLeave)
                                <td>
                                    <table>
                                        <tr>
                                            <th align="left">Pre Bal</th>
                                            <th align="left">Collapse</th>
                                            <th align="center">Earned</th>
                                            <th align="center">Balance</th>
                                        </tr>
                                        <tbody>
                                        <tr>
                                            <div class="form-group row">
                                                <td class="previous-container">
                                                    <?php
                                                    $leaveBalance = $staff->leaveBalance;
                                                    $previousBalance = $leaveBalance->where('leave_id', $earnableSystemLeave->leave_id)->first();
                                                    ?>
                                                    <span>{{$previousBalance->balance ?? 0}}</span>
                                                </td>
                                                <td>
                                                    @if($earnableSystemLeave->leave_type==1 && $_GET['month_id']==1) {{--collapsible--}}
                                                    {!! Form::number("calculate_balance[{$staff->id}][{$earnableSystemLeave->leave_id}][collapse]", $previousBalance->balance ?? 0, ['id' => 'earned','class' => 'form-control positive-integer-number','step'=>'0.01','style' => 'width:50px!important', 'readonly' => 'readonly']) !!}
                                                    @else
                                                        {!! Form::number("calculate_balance[{$staff->id}][{$earnableSystemLeave->leave_id}][collapse]",  0, ['id' => 'earned','class' => 'form-control positive-integer-number','step'=>'0.01','style' => 'width:50px!important', 'readonly' => 'readonly']) !!}
                                                    @endif
                                                </td>
                                                <td>
                                                    {!! Form::number("calculate_balance[{$staff->id}][{$earnableSystemLeave->leave_id}][earned]", $earnableStaffBalances[$staff->id][$earnableSystemLeave->leave_id] ?? 0, ['id' => 'earned','class' => 'form-control positive-integer-number','step'=>'0.01','style' => 'width:50px!important', 'readonly' => 'readonly']) !!}
                                                </td>
                                                <td>
                                                    @php
                                                        $newBalance=($previousBalance->balance ?? 0) + ($earnableStaffBalances[$staff->id][$earnableSystemLeave->leave_id] ?? 0);
                                                   if($earnableSystemLeave->leave_type==1 && $_GET['month_id']==1){
                                                       $newBalance-=($previousBalance->balance ?? 0);
                                                   }
                                                    @endphp
                                                    {!! Form::number("calculate_balance[{$staff->id}][{$earnableSystemLeave->leave_id}][balance]", $newBalance, ['id' => 'balance','class' => 'form-control positive-integer-number','step'=>'0.01','style' => 'width:50px!important', 'readonly' => 'readonly' ]) !!}
                                                    {!! Form::hidden('fiscal_year_id', $fiscal_year_id) !!}
                                                    {!! Form::hidden('month_id', $month_id) !!}
                                                    {!! Form::hidden('branch_id', $branch_id) !!}
                                                </td>
                                            </div>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </div>
            <button class="btn btn-success" id="calculate-leave-submit-button">Submit</button>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/tableHeadFixer/tableHeadFixer.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("#fixTable").tableHeadFixer({"left": 1});
        });
    </script>
@endsection
