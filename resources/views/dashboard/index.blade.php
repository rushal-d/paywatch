@extends('layouts.default' , ['crumbroute' => 'dashboard'])
@section('title', 'Dashboard')
@section('content')

    @role('Administrator')
    @include('dashboard.searchengine')

    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card-group">
                    <div class="card">
                        <div class="card-block">
                            <div class="h1 text-muted text-right mb-2">
                                <i class="icon-people"></i>
                            </div>
                            <div class="h4 mb-0">
                                <a href="{{route('staff-main')}}"> {{$staff_count}}</a>
                            </div>
                            <small class="text-muted text-uppercase font-weight-bold">Staffs</small>
                            <div class="progress progress-xs mt-1 mb-0">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 25%"
                                     aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-block">
                            <div class="h1 text-muted text-right mb-2">
                                <i class="icon-user-follow"></i>
                            </div>
                            <div class="h4 mb-0">
                                <a href="{{route('systemoffice')}}">{{$branchesCount}}</a>
                            </div>
                            <small
                                class="text-muted text-uppercase font-weight-bold">{{str_plural('branch', $branchesCount)}}</small>
                            <div class="progress progress-xs mt-1 mb-0">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 25%"
                                     aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-block">
                            <div class="h1 text-muted text-right mb-2">
                                <i class="icon-basket-loaded"></i>
                            </div>
                            <div class="h4 mb-0">
                                <a href="{{route('fiscal-year')}}">{{$fiscal_year->fiscal_code ?? '-'}}</a>
                            </div>
                            <small class="text-muted text-uppercase font-weight-bold">Current Fiscal Year</small>
                            <div class="progress progress-xs mt-1 mb-0">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 25%"
                                     aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-block">
                            <div class="h1 text-muted text-right mb-2">
                                <i class="icon-pie-chart"></i>
                            </div>
                            <div class="h4 mb-0">
                                <a href="{{route('system-holiday')}}">{{$public_holiday->holiday_descri ?? ''}}</a>
                            </div>
                            <small class="text-muted text-uppercase font-weight-bold">Upcoming Public Holiday</small>
                            <div class="progress progress-xs mt-1 mb-0">
                                <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-block">
                            <div class="h1 text-muted text-right mb-2">
                                <i class="icon-speedometer"></i>
                            </div>
                            <div class="h4 mb-0" id="demo"></div>
                            <small class="text-muted text-uppercase font-weight-bold">Time</small>
                            <div class="progress progress-xs mt-1 mb-0">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 25%"
                                     aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--<div class="row">
            <div class="col-xs-6 col-lg-3">
                <div class="card">
                    <div class="card-block p-a-1 clearfix">
                        <i class="fa fa-list bg-primary p-a-1 font-2xl m-r-1 pull-left"></i>
                        <div class="h5 text-primary m-b-0 m-t-h">{{$user_count}}</div>
                        <div
                            class="text-muted text-uppercase font-weight-bold font-xs">{{str_plural('user', $user_count)}}</div>
                    </div>
                    <div class="card-footer p-x-1 p-y-h">
                        <a class="font-weight-bold font-xs btn-block text-muted" href="{{route('user-index')}}">View
                            More <i
                                class="fa fa-angle-right pull-right font-lg"></i></a>
                    </div>

                </div>
            </div>
            <div class="col-xs-6 col-lg-3">
                <div class="card">
                    <div class="card-block p-a-1 clearfix">
                        <i class="fa fa-list bg-primary p-a-1 font-2xl m-r-1 pull-left"></i>
                        <div class="h5 text-primary m-b-0 m-t-h">{{$payroll->count()}}</div>
                        <div class="text-muted text-uppercase font-weight-bold font-xs">Payrolls</div>
                    </div>
                    <div class="card-footer p-x-1 p-y-h">
                        <a class="font-weight-bold font-xs btn-block text-muted"
                           href="{{route('attendance-detail-payroll')}}">View More <i
                                class="fa fa-angle-right pull-right font-lg"></i></a>
                    </div>

                </div>
            </div>
        </div>--}}
        @endrole
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::select('branch_id', $branches, null ,['id'=>'branch_id','class'=> 'selectize-use branch_id','required'=>'required','data-validation' => 'required'
                         ]) !!}
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-block">
                                        <small class="text-muted text-uppercase font-weight-bold">Staff Present
                                            Today
                                        </small>
                                        <div class="h4 mb-0"
                                             id="staff-on-present-count">{{$staffsPresentCount}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-block">
                                        <small class="text-muted text-uppercase font-weight-bold">Staff Absent
                                            Today
                                        </small>
                                        <div class="h4 mb-0"
                                             id="staff-on-absent-count">{{$staffsPresentCount}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-block">
                                        <small class="text-muted text-uppercase font-weight-bold">Staff on Leave
                                            Today
                                        </small>
                                        <div class="h4 mb-0" id="staff-on-leave-count">{{$staffsPresentCount}}</div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-block">
                                        <small class="text-muted text-uppercase font-weight-bold">Staff on Weekend
                                            Today
                                        </small>
                                        <div class="h4 mb-0"
                                             id="staff-on-weekend-count">{{$staffsPresentCount}}</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        <h5>Punch Out Warning Yesterday!</h5>({{$punch_out_warnings_yesterday->count()}}
                        /{{$punch_out_warnings_yesterday->total()}})
                        <table class="table table-bordered">
                            <tr>
                                <td>SN</td>
                                <td>Staff Name</td>
                                <td>Branch ID</td>
                                <td>Punch In</td>
                            </tr>
                            @if($punch_out_warnings_yesterday->count()>0)
                                @foreach($punch_out_warnings_yesterday as $punch_out_warning)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            <a href="{{route('localattendance-edit', ['id' => $punch_out_warning->id])}}"
                                               target="_blank">
                                                {{$punch_out_warning->staff->name_eng ?? ''}}
                                            </a>
                                        </td>
                                        <td>{{$punch_out_warning->staff->main_id ?? ''}}</td>
                                        <td>{{date('H:i:s',strtotime($punch_out_warning->punchin_datetime))}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        No Punch Out Warning
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <a href="{{route('localattendance-punch-out-warning')}}">View All</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        <h5>Recently Added Staffs</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td>SN</td>
                                <td>Staff Name</td>
                                <td>Branch ID</td>
                            </tr>
                            @if($recently_added_staffs->count()>0)
                                @foreach($recently_added_staffs as $recently_added_staff)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            <a href="{{route('staff-main-edit', ['id' => $recently_added_staff->id])}}"
                                               target="_blank">
                                                {{$recently_added_staff->name_eng ?? ''}}
                                            </a>
                                        </td>
                                        <td>{{$recently_added_staff->main_id ?? ''}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        No Recently Added Staffs
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <a href="{{route('staff-main')}}">View All</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        <h5>Most Work Hour</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td>SN</td>
                                <td>Staff Name</td>
                                <td>Work Hours</td>
                            </tr>
                            @if($most_work_hours->count()>0)
                                @foreach($most_work_hours as $most_work_hour)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            <a href="{{route('staff-main-edit', ['id' => $most_work_hour->staff_central_id])}}"
                                               target="_blank">
                                                {{$most_work_hour->staff->name_eng ?? ''}}
                                            </a>
                                        </td>
                                        <td>{{$most_work_hour->total_work_hour_sum ?? ''}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        No Data
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <a href="{{route('localattendance-summary')}}">View All</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        <h5>Most Absent Staff</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td>SN</td>
                                <td>Staff Name</td>
                                <td>Absent Days</td>
                            </tr>
                            @if($most_absent_staffs->count()>0)
                                @foreach($most_absent_staffs as $most_absent_staff)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            <a href="{{route('staff-main-edit', ['id' => $most_absent_staff->staff_central_id])}}"
                                               target="_blank">
                                                {{$most_absent_staff->staff->name_eng ?? ''}}
                                            </a>
                                        </td>
                                        <td>{{$interval_days-$most_absent_staff->present_count ?? ''}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        No Data
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <a href="{{route('localattendance-summary')}}">View All</a>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        <h5>Recent Transfer Out!</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td>SN</td>
                                <td>Staff Name</td>
                                <td>Transferred To</td>
                                <td>Transfer Date</td>
                            </tr>
                            @if($transfer_out->count()>0)
                                @foreach($transfer_out as $trans_out)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            {{$trans_out->staff->name_eng ?? ''}}
                                        </td>
                                        <td>{{$trans_out->office->office_name ?? ''}}</td>
                                        <td>{{$trans_out->transfer_date}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        No Staff Transfer Out
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <a href="{{route('staff-transfer')}}">View All</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-block">
                        <h5>Recent Transfer In!</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td>SN</td>
                                <td>Staff Name</td>
                                <td>Transferred From</td>
                                <td>Transfer Date</td>
                            </tr>
                            @if($transfer_in->count()>0)
                                @foreach($transfer_in as $trans_out)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            {{$trans_out->staff->name_eng ?? ''}}
                                        </td>
                                        <td>{{$trans_out->office_from_get->office_name ?? ''}}</td>
                                        <td>{{$trans_out->transfer_date}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        No Staff Transfer In
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <a href="{{route('staff-transfer')}}">View All</a>
                    </div>
                </div>
            </div>
        </div>
        @role('Administrator')
        <div class="row">
            <div class="col-xs-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        Payroll Details
                        <div class="card-header-actions">
                            <a href="{{route('attendance-detail-payroll')}}" class="card-header-action" target="_blank">
                                <small class="text-muted">View More</small>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrapper">
                            <div class="chartjs-size-monitor"
                                 style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                <div class="chartjs-size-monitor-expand"
                                     style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink"
                                     style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
                            <canvas id="canvas-1" width="493" height="246" class="chartjs-render-monitor"
                                    style="display: block; width: 493px; height: 246px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    @endrole

@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script>
        $(document).ready(function () {
            payroll_data = new Array();
            $.ajax({
                url: '{{route('payroll-details')}}',
                type: 'post',
                data: {
                    '_token': '{{csrf_token()}}'
                },
                success: function (data) {
                    $.each(data['data'], function (i, value) {
                        payroll_data.push(value);
                    });

                    var random = function random() {
                        return Math.round(Math.random() * 100);
                    };
                    var lineChart = new Chart($('#canvas-1'), {
                        type: 'line',
                        data: {
                            labels: ['Baishakh', 'Jestha', 'Asar', 'Shrawan', 'Bhadau', 'Aswin', 'Kartik', 'Mangsir', 'Poush', 'Magh', 'Falgun', 'Chaitra'],
                            datasets: [{
                                label: 'Payroll Chart',
                                backgroundColor: 'rgba(220, 220, 220, 0.2)',
                                borderColor: 'rgba(220, 220, 220, 1)',
                                pointBackgroundColor: 'rgba(220, 220, 220, 1)',
                                pointBorderColor: '#fff',
                                data: payroll_data
                            }
                            ]
                        },
                        options: {responsive: true}
                    });

                }
            });


        })
    </script>
    <script>
        $('document').ready(function () {
            var myVar = setInterval(myTimer, 1000);

            function myTimer() {
                var d = new Date();
                document.getElementById("demo").innerHTML = d.toLocaleTimeString();
            }
        });
    </script>

    <script>
        $('#branch_id').change(function () {
            const branch_id = $('#branch_id').val();

            $.ajax({
                url: '{{route('get-attendance-detail-number-of-staffs')}}',
                type: 'GET',
                data: {
                    branch_id: branch_id
                },
                success: function (response) {
                    if (response.status === 'success') {
                        $('#staff-on-present-count').html(response.data.staffsPresentCount);
                        $('#staff-on-absent-count').html(response.data.staffsAbsentCount);
                        $('#staff-on-leave-count').html(response.data.staffsLeaveCount);
                        $('#staff-on-weekend-count').html(response.data.staffsWeekendCount);
                    }
                }

            })
        });
        $('#branch_id').trigger('change');
    </script>
@endsection
