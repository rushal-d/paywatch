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
            of [<b>{{$fiscalYear->fiscal_code}}</b>] for [<b>{{$branch->office_name}}</b>]
        </div>
        <div class="card-block">
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
                            <td>{{$staff->name_eng ?? ''}}</td>
                            @foreach($earnableSystemLeaves as $earnableSystemLeave)
                                <td>
                                    <table>
                                        <tr>
                                            <th align="left">Pre Bal</th>
                                            <th align="center">Consumed</th>
                                            <th align="center">Earned</th>
                                            <th align="center">Balance</th>
                                        </tr>
                                        <tbody>
                                        <tr>
                                            <div class="form-group row">
                                                <?php
                                                $previousLeaveBalance = $previousLeaveBalances->where('staff_central_id', $staff->id)->where('leave_id', $earnableSystemLeave->leave_id)->first();
                                                ?>
                                                <td class="previous-container">
                                                    <span>{{$previousLeaveBalance->consumption+$previousLeaveBalance->balance - $previousLeaveBalance->earned}}</span>
                                                </td>

                                                <td class="previous-container">
                                                    {!! Form::number(null, $previousLeaveBalance->consumption ?? null, ['id' => 'earned','class' => 'form-control positive-integer-number','step'=>'0.01','style' => 'width:50px!important', 'readonly' => 'readonly']) !!}

                                                </td>
                                                <td>
                                                    {!! Form::number(null, $previousLeaveBalance->earned ?? null, ['id' => 'earned','class' => 'form-control positive-integer-number','step'=>'0.01','style' => 'width:50px!important', 'readonly' => 'readonly']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::number(null, ($previousLeaveBalance->balance ?? 0), ['id' => 'balance','class' => 'form-control positive-integer-number','step'=>'0.01','style' => 'width:50px!important', 'readonly' => 'readonly' ]) !!}
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
