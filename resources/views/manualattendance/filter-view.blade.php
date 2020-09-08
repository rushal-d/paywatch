<head>

    <link rel="stylesheet" href="{{asset('assets/css/bundle.min.css')}}">
    <style>
        td {
            font-size: 12px;
            padding: 0px;
        }

        table {
            border-collapse: collapse;
            margin: auto;

        }

        .payroll_table {
            border: 0px solid black;
            box-shadow: 0 1px 6px 0 rgba(32, 33, 36, 0.28);
            border-color: rgba(223, 225, 229, 0);
            margin: 25px auto;
            font-family: helvetica;
            width: 94%;
            background: #fff;
        }

        .payroll_table_label {
            border: 0px solid black;
            box-shadow: 0 1px 6px 0 rgba(32, 33, 36, 0.28);
            border-color: rgba(223, 225, 229, 0);
            margin: 25px auto;
            font-family: helvetica;
            width: 40%;
            background: #fff;
        }

        .custom-theader {
            color: #3e3e3e;
            border: 0px;
            border-bottom: 1px solid #c3c3c3;
        }

        .custom-theader th {
            padding: 10px 0px;
        }

        .payroll_table td {
            padding: 5px 10px;
            border: 1px solid #dadada;
        }

        td a {
            text-decoration: underline;
        }

        .button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 5px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            margin: 2px 1px;
            cursor: pointer;
            background: linear-gradient(45deg, #1de099, #1dc8cd);
        }

        .button:hover {
            background: linear-gradient(18deg, #1dc8cd, #1de099);
        }

        .attendance-information {
            background: #008cba;
            color: #fff;
            background: #ffffff;
            color: #149691;
        }

        .attendance-information td {
            padding: 6px 15px;
        }

        @media print {
            body {
                font-size: 9px;
            }


            .payroll_table {
                border: 1px solid black;
                box-shadow: none;
                font-family: initial;
            }

            .payroll_table td {
                padding: 0px 1px;
                border: 1px solid #000;
            }

            table, th, td {
                font-size: 12px;
                color: black !important;
            }

            .custom-theader {
                color: #000;
                border: 1px solid #000;
            }

            .custom-theader th {
                padding: 1px 0px;
            }

            .is-force, .present-on-public-holiday, .present-on-weekend {
                color: black;
            }

            .print-buttons {
                display: none;
            }

        }

        .present-on-weekend {
            background-color: #71f5af;
            color: #000;
        }

        .present-on-public-holiday {
            background-color: #29b974;
            color: #fff;

        }

        .is-force {
            background-color: #dadada;
            color: #000;
        }

        .button-print {
            background-color: #008CBA;
        }

        .Weekend {
            background-color: #d3ebff;
            color: #007DE8;
        }

        .Absent {
            background-color: #ffd8d996;
            color: #C52F31;
            font-weight: bolder;
        }

        .Approved {
            background-color: #FFDE82;
            color: #000;
        }

        .adjust-width {
            width: 100%;
            margin-left: 10px;
        }

        .display-flex {
            display: flex;
            margin: 10px 0px;
        }

        /*new*/
        table {
            background: white;
            box-shadow: 0 1px 6px 0 rgba(32, 33, 36, 0.28);
        }

        .table-bottom td {
            padding: 5px;
        }

        .table-bottom {
            border: 1px solid #eee;
        }

        .print-buttons a, .print-buttons button {
            display: block;
            min-width: 77px;
            width: 77px;
            max-width: 567px;
        }

        .print-buttons {
            position: fixed;
            top: 7%;
            background: white;
            box-sizing: 0 1px 6px 0 rgba(32, 33, 36, 0.28);
            padding: 5px;
            box0sha: 0 1px 6px 0 rgba(32, 33, 36, 0.28);
            box-shadow: 0 1px 6px 0 rgba(32, 33, 36, 0.28);
            left: 0px;
        }

        .jss434 {
            background-image: linear-gradient(-45deg, #2196F3 0%, #2196F3 33%, #00BFA5 100%);
            background-attachment: fixed;
            position: absolute;
            width: 100%;
            top: 0px;
            left: 0;
            z-index: -99999;
        }

        .custom-input {
            border: 1px solid #ccc;
            padding: 6px 12px;
            display: inline-block;
            width: 100%;
            overflow: hidden;
            position: relative;
            z-index: 1;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            -webkit-box-shadow: none;
            box-shadow: none;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
        }

        .selectize-input {
            padding: 6px;
        }

        @media only screen and (max-width: 768px) {
            .display-flex {
                display: block;
            }

            .print-buttons {
                position: initial;
                padding: 10px 10px;
                background: #fafafa;
                width: 130px;
                margin: 0 auto;

            }

            .print-buttons a {
                display: inline;
            }

            .adjust-width {
                width: initial;
                margin-left: 0px;
                display: inline;
            }
        }

        .Absent {
            background-color: rgba(255, 216, 217, 0.5882352941176471);
            color: #C52F31;
            font-weight: bolder;
        }

        #printableArea {
            margin: 60px 0 10px 0;
        }

        #manualattendance-form {
            overflow-x: scroll;
        }


        #parent {
            max-height: 70%;
        }

        #fixTable {
            width: 1800px !important;
        }

    </style>
</head>
<body style="background: #fafafa;
background-image: linear-gradient(180deg, rgb(250, 250, 250),rgb(250, 250, 250) ,rgba(29, 200, 205, 0.15));">
<div id="loader" style="display:none;position: fixed; top:0; left:0; width:100%; height: 100%; background: url('https://i10.dainikbhaskar.com/cricketscoreboard/image/loader.gif') center center;z-index:9999;    background-repeat: no-repeat;
    ">
    <div style="font-size: 40px; position: relative; top: 60%; left: 40%;">Preparing Manual Attendance
    </div>
</div>
<div class="jss433 jss434 jss431"><img
        src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIyLjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAzMDAgNDcuMSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzAwIDQ3LjE7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCS5zdDB7ZmlsbDojRkFGQUZBO30KPC9zdHlsZT4KPHBhdGggY2xhc3M9InN0MCIgZD0iTTMwMCw0Ni45TDAsNDcuMVY4LjljMCwwLDIxLjEsMTQuMyw2NS4yLDE0LjFjNDAuNi0wLjIsNzYuNC0yMywxMjgtMjNDMjQzLjMsMCwzMDAsMTYuNCwzMDAsMTYuNFY0Ni45eiIvPgo8L3N2Zz4K"
        alt="decoration" class="jss436" style="margin-bottom: -2px;"></div>
<div class="print-buttons">
    <a class="button" href="{{route('manual-attendance')}}">Go Back</a>
</div>
<div id="printableArea">
    <h5 align="center">Manual Attendance Summary</h5>
    <h5 align="center">{{\App\Helpers\BSDateHelper::AdToBs('-',$from_date)}}
        to {{\App\Helpers\BSDateHelper::AdToBs('-',$to_date)}}</h5>

    <table class="attendance-information">
    </table>
</div>
<form action="{{route('manual-attendance-store')}}" id="manualattendance-form" method="POST">
    {{csrf_field()}}
    <input type="hidden" name="branch_id" value="{{$branch_id}}">

    <div id="parent">
        <table class="table table-responsive table-bordered manualattendance-table" id="fixTable">
            <thead>

            <tr class="heading-tr">
                <th>Staff Name</th>
                <th>Branch ID</th>
                @php
                    $temp_from_date=$from_date;
                    $temp_to_date=$to_date;
                @endphp
                @while(strtotime($temp_from_date) <= strtotime($temp_to_date))
                    <th style="min-width: 180px" data-attendance-date="{{$temp_from_date}}"
                        class="td-{{$temp_from_date}}">
                        {{\App\Helpers\BSDateHelper::AdToBs('-',$temp_from_date)}}
                        <div>

                            <input type="checkbox" class="is_absent_selected">
                            Absent
                        </div>
                        <a href="javascript:void(0)" class="remove-date-button"><i
                                class="text-danger fa fa-trash fa-2x"
                                aria-hidden="true"></i></a>
                    </th>
                    @php $temp_from_date=date('Y-m-d',strtotime('+1 day',strtotime($temp_from_date))) @endphp
                @endwhile
                <th scope="col">Total Work Hour</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            @foreach($staffs as $staff)
                <tr>
                    <td>
                        <b>{{$staff->name_eng}}</b><br>({{$staff->workschedule->last()->max_work_hour ?? config('constants.max_working_hour')}}
                        hours)
                        Absent on weekends <input type="checkbox" class="absent_on_weekends">
                    </td>
                    <td><b>{{$staff->main_id}}</b>
                        @if($staff->manual_attendance_enable)
                            <i class="fa fa-check text-success" title="Manual Attendance Enabled"></i>
                        @endif
                    </td>
                    @php
                        $temp_from_date=$from_date;
                        $temp_to_date=$to_date;
                        $total_work_hour_particular_staff = 0;
                        $total_work_minute_particular_staff = 0;
                    @endphp
                    @while(strtotime($temp_from_date) <= strtotime($temp_to_date))
                        @php $weekend_day=null; @endphp
                        @if(($staff->workschedule->last()->weekend_day ?? null)==date('N',strtotime($temp_from_date)))
                            @php $weekend_day='weekend_day'; @endphp
                        @endif
                        <td style="min-width: 180px" class="row-{{$temp_from_date}} {{$weekend_day}}">
                            <span class="display-attendance-date"
                                  data-attendance-date-en="{{$temp_from_date}}"></span>

                            <div>
                                @if(!empty($weekend_day))
                                    (Weekend Day)
                                @endif
                            </div>
                            <div class="status-form">
                                Status: {{Form::select("staffs[{$temp_from_date}][{$staff->id}][status]",config('constants.manual_attendance.status'),0,['class'=>'status'])}}
                            </div>
                            @php $attendanceRecord=$staff->fetchAttendances->where('punchin_datetime','>=',$temp_from_date.' 00:00')->where('punchin_datetime','<=',$temp_from_date.' 23:59:59')->last() @endphp
                            @if(($attendanceRecord->total_work_hour ?? 0)>0)

                                <div class="punchin_datetime_div">
                                    @php
                                        $punchin = date('H:i',strtotime($attendanceRecord->punchin_datetime));
                                    $punchout = date('H:i',strtotime($attendanceRecord->punchout_datetime));
                                    @endphp
                                    <span>Punch In:</span> {{Form::time("staffs[{$temp_from_date}][{$staff->id}][punchin_datetime_np]", $punchin ,['class'=>'punch_in', 'data-default-punchin' => date('H:i',strtotime($attendanceRecord->punchin_datetime))])}}
                                </div>
                                <div class="punchout_datetime_div">
                                    <span>Punch Out:</span> {{Form::time("staffs[{$temp_from_date}][{$staff->id}][punchout_datetime_np]",$punchout,['class'=>'punch_out', 'data-default-punchout' => date('H:i',strtotime($attendanceRecord->punchout_datetime))])}}
                                </div>

                            @else
                                <div>
                                    @php $punchin= date('H:i',strtotime($staff->latestShift->shift->punch_in)) ?? '9:00';@endphp
                                    <span>Punch In:</span> {{Form::time("staffs[{$temp_from_date}][{$staff->id}][punchin_datetime_np]", $punchin,['class'=>'punch_in', 'data-default-punchin' => $punchin])}}
                                </div>
                                <div>
                                    @php
                                        $work_hour=$staff->workschedule->last()->max_work_hour ?? config('constants.max_working_hour');

                                        $punchout= date('H:i',strtotime('+'.(int)($work_hour*60).'minutes',strtotime($punchin)))@endphp
                                    Punch
                                    Out: {{Form::time("staffs[{$temp_from_date}][{$staff->id}][punchout_datetime_np]",$punchout,['class'=>'punch_out', 'data-default-punchout' => $punchout])}}
                                </div>
                            @endif

                            @php
                                $work_hour_particular_staff_without_rounding = (strtotime($punchout) - strtotime($punchin)) / 60 / 60;
                                $work_hour_particular_staff= floor($work_hour_particular_staff_without_rounding);
                                $work_minute_particular_staff= ($work_hour_particular_staff_without_rounding - $work_hour_particular_staff) * 60;
                                $total_work_hour_particular_staff+= $work_hour_particular_staff;
                                $total_work_minute_particular_staff+= $work_minute_particular_staff;
                            @endphp
                            <div class="work-hour" data-work-hour="{{$work_hour_particular_staff}}">
                                (<span class="work-hour-amount">{{$work_hour_particular_staff}}</span> hours)
                            </div>
                            <div class="work-minute" data-work-minute="{{$work_minute_particular_staff}}">
                                (<span class="work-minute-amount">{{$work_minute_particular_staff}}</span> minutes)
                            </div>
                        </td>
                        @php $temp_from_date=date('Y-m-d',strtotime('+1 day',strtotime($temp_from_date))) @endphp

                    @endwhile

                    @php
                        $total_work_hours_of_converted_from_minutes = floor($total_work_minute_particular_staff / 60);
                        $total_work_hour_particular_staff+= $total_work_hours_of_converted_from_minutes;
                        $total_work_minute_after_converting_to_hours = $total_work_minute_particular_staff % 60;
                    @endphp
                    <td class="text-center total-work-hour"
                        data-total-work-hour="{{$total_work_hour_particular_staff}}">
                        <span
                            class="total-work-hour-particular">{{$total_work_hour_particular_staff}}</span> {{str_plural('hour', $total_work_hour_particular_staff)}}
                        <span
                            class="total-work-minute-particular">{{$total_work_minute_after_converting_to_hours}}</span> {{str_plural('minute', $total_work_minute_after_converting_to_hours)}}
                    </td>

                    <td class="text-center">
                        <a href="javascript:void(0)" class="remove-staff-button"><i
                                class="fa fa-trash fa-2x"
                                aria-hidden="true"></i></a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <button class="btn btn-success" id="manualattendance-submit-button">Submit</button>

</form>

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
<script src="{{ asset('assets/js/customdatetime.js')  }}"></script>

<script>
    $(document).on('change', '.status', function (e) {
        $this = $(this);
        var punch_in = $this.parent().siblings().find('.punch_in');
        var punch_out = $this.parent().parent().find('.punch_out');
        var work_hour = $this.parent().parent().find('.work-hour');
        var work_minute = $this.parent().parent().find('.work-minute');
        var work_hour_amount = work_hour.find('.work-hour-amount');
        var work_minute_amount = work_minute.find('.work-minute-amount');
        var total_work_hour = $this.parent().parent().parent().find('.total-work-hour');


        var total_work_hour_sum = 0;
        var total_work_minute_sum = 0;
        if ($this.val() == 0) {
            punch_in.val(punch_in.data('default-punchin'));
            punch_out.val(punch_out.data('default-punchout'));
            work_hour_amt = getDiffHoursFromTwoTime(punch_in.val(), punch_out.val());
            work_minute_amt = getDiffMinutesFromTwoTime(punch_in.val(), punch_out.val());
            work_hour_amount.html(work_hour_amt);
            work_minute_amount.html(work_minute_amt);
        } else {
            punch_in.val('');
            punch_out.val('');
            total_work_hour_sum = 0;
            total_work_minute_sum = 0;
            work_hour_amount.html(0);
            work_minute_amount.html(0);
        }
        $this.parent().parent().parent().find('.work-hour').each(function () {
            total_work_hour_sum += parseInt($(this).find('.work-hour-amount').html());
        });
        $this.parent().parent().parent().find('.work-minute').each(function () {
            total_work_minute_sum += parseInt($(this).find('.work-minute-amount').html());
        });

        sum_hours_from_minute = Math.floor(total_work_minute_sum / 60);
        total_work_minute_sum = total_work_minute_sum % 60;
        total_work_hour_sum += sum_hours_from_minute;

        total_work_hour.find('.total-work-hour-particular').html(total_work_hour_sum);
        total_work_hour.find('.total-work-minute-particular').html(total_work_minute_sum % 60);
    });

    /*$('.is_absent_selected').click(function () {
        $(function () {
            var type = this.id.split('_')[1];
            $('[id^="client_type_' + type + '"]').prop('checked', this.checked);
        });
        $('.manualattendance-table tr').each(function () {
            // $(this).find("td:eq(2)").trigger();
        });
    });*/

    $(".btn-success").click(function (e) {

        var is_validation_error = false;
        var current_date = '{{date('Y-m-d H:i:s')}}';
        $('.punch_in').each(function () {
            $_punch_in = $(this);
            var selected_attendance_date = $_punch_in.parent().parent().find(".display-attendance-date").data('attendance-date-en');
            if (parseInt($_punch_in.parent().siblings('.status-form').find('.status').val()) === 0) {
                if ($(this).val()) {
                    var punchin_datetime_value = $(this).val();
                    var punchout_datetime_value = $(this).parent().siblings('.punchout_datetime_div').find('.punch_out').val();
                    var selected_attendance_punchin_datetime = (selected_attendance_date) + ' ' + punchin_datetime_value;
                    if (selected_attendance_punchin_datetime >= current_date) {
                        e.preventDefault();
                        is_validation_error = true;
                        $(this).siblings('p').remove();
                        $(this).parent().parent().css("color", "black");
                        if (selected_attendance_punchin_datetime !== '' && selected_attendance_punchin_datetime >= current_date) {
                            $(this).parent().append("<p class='validation-punch-in'>Punchin date should be less than current date</p>");
                        }

                        $(this).parent().parent().css("color", "red");
                    } else {
                        if (punchout_datetime_value === '') {
                            $(this).siblings('p').remove();
                            $(this).parent().parent().css("color", "black");
                        } else {
                            if ((punchin_datetime_value >= punchout_datetime_value)) {
                                e.preventDefault();
                                is_validation_error = true;
                                $(this).siblings('p').remove();
                                $(this).parent().append("<p class='validation-punch-in'>Punchout date should be greater than punchin date</p>");
                                $(this).parent().parent().css("color", "red");
                            } else {
                                $(this).siblings('p').remove();
                                $(this).parent().parent().css("color", "black");
                            }
                        }
                    }
                } else {
                    e.preventDefault();
                    is_validation_error = true;
                    $(this).siblings('p').remove();
                    $(this).parent().append("<p class='validation-punch-out'>Please enter punchin date</p>");
                    $(this).parent().parent().css("color", "red");

                }
            } else {

            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        if (is_validation_error === false) {
            $('#manualattendance-form').serialize;
            e.preventDefault();
            $('#loader').show();
            let data = $("#manualattendance-form").serializeArray();
            let i = 0;
            let number_of_request = Math.ceil((data.length - 2) / 30);
            let number_of_data = data.length - 2;
            let number_of_data_processed = 0;
            let completed_request = 0;
            let chunk = [];
            console.log(data);

            $.each(data, function (index, value) {
                if (value['name'] != "_token" && value['name'] != "branch_id") {
                    chunk.push(value);
                    i++;
                    number_of_data_processed++;
                    if (number_of_data == number_of_data_processed) {
                        if (number_of_request != completed_request) {
                            insertToFetchAttendance(chunk);
                        } else {
                            window.location.href = '{{route('manual-attendance')}}';
                        }
                    } else if (i == 30) {
                        insertToFetchAttendance(chunk);
                        i = 0;
                        chunk = [];
                    }
                }
            });

            function insertToFetchAttendance(chunk) {
                $.ajax({
                    data: chunk,
                    url: $('#manualattendance-form').attr('action') + '?_token=' + '{{ csrf_token() }}',
                    type: 'POST',
                    success: function (response) {
                        completed_request += 1;
                        if (completed_request == number_of_request) {
                            window.location.href = '{{route('manual-attendance')}}';
                        }
                    }
                });
            }
        }
    });

    $('.is_absent_selected').click(function () {
        $_this = $(this);
        var is_checked_absent = false;
        if ($(this).is(':checked')) {
            is_checked_absent = true;
        }

        var date_selected = $_this.parent().parent().data('attendance-date');
        selected_rows = $('.row-' + date_selected);

        if (is_checked_absent) {
            selected_rows.each(function () {
                $_selected_row = $(this);
                $_selected_row.find('.status').val(1);
                $_selected_row.find('.punch_in').val('');
                $_selected_row.find('.punch_out').val('');
                $_selected_row.find('.work-hour-amount').html(0);
                $_selected_row.find('.work-minute-amount').html(0);
            });
        } else {
            selected_rows.each(function () {
                $_selected_row = $(this);
                $_selected_row.find('.status').val(0);
                $_selected_row.find('.punch_in').val($_selected_row.find('.punch_in').data('default-punchin'));
                $_selected_row.find('.punch_out').val($_selected_row.find('.punch_out').data('default-punchout'));
                work_hour_amt = getDiffHoursFromTwoTime($_selected_row.find('.punch_in').val(), $_selected_row.find('.punch_out').val());
                work_minute_amt = getDiffMinutesFromTwoTime($_selected_row.find('.punch_in').val(), $_selected_row.find('.punch_out').val());
                $_selected_row.find('.work-hour-amount').html(work_hour_amt);
                $_selected_row.find('.work-minute-amount').html(work_minute_amt);
            });
        }

        $('.total-work-hour').each(function () {
            var $_this = $(this);
            var sum = 0;
            var sum_minute = 0;
            $_this.parent().find('.work-hour-amount').each(function () {
                $__this = $(this);
                sum += parseInt($__this.html());
            });
            $_this.parent().find('.work-minute-amount').each(function () {
                $__this = $(this);
                sum_minute += parseInt($__this.html());
            });

            sum_hours_from_minute = Math.floor(sum_minute / 60);
            sum_minute = sum_minute % 60;
            sum += sum_hours_from_minute;
            $('.total-work-hour-particular').html(sum);
            $('.total-work-minute-particular').html(sum_minute);

        });

        /*$('#punchin_time_for_all_inputs').on('change', function (e) {
            $(".punchin_datetime_np").val(e.target.value);
        });*/
    });

    $(".remove-staff-button").on('click', function () {
        $(this).parent().parent().remove();
        var number_of_staff_inputs_rows = $('.manualattendance-table tr').length;
        if (number_of_staff_inputs_rows === 1) {
            $('#manualattendance-table').remove();
            $('#manualattendance-submit-button').remove();
            window.location.href = "{{route('manual-attendance')}}";
        }
    });

    $(".remove-date-button").on('click', function () {
        $_this = $(this);
        var date_selected = $_this.parent().data('attendance-date');

        var selected_rows = $('.row-' + date_selected);
        $_total_work_hour_in_minutes = 0;
        $_work_hour_selected_amount = 0;
        $_after_deducated_work_minute = 0;
        selected_rows.each(function () {
            $_selected_row = $(this);
            $_work_hour_amount = $_selected_row.find('.work-hour-amount');
            $_work_minute_amount = $_selected_row.find('.work-minute-amount');
            $_total_work_hour_particular = $_selected_row.siblings('.total-work-hour').find('.total-work-hour-particular');
            $_total_work_minute_particular = $_selected_row.siblings('.total-work-hour').find('.total-work-minute-particular');
            $_total_work_hour_in_minutes = getMinutesFromHours($_total_work_hour_particular.html()) + parseInt($_total_work_minute_particular.html());
            $_work_hour_selected_amount = getMinutesFromHours($_work_hour_amount.html()) + parseInt($_work_minute_amount.html());
            $_after_deducated_work_minute = parseInt($_total_work_hour_in_minutes) - parseInt($_work_hour_selected_amount);
            $_total_work_hour_particular.html(getHoursFromMinutes($_after_deducated_work_minute));
            $_total_work_minute_particular.html(getRemainingMinutesSubtractingForHours($_after_deducated_work_minute));
        });

        var selected_td = $('.td-' + date_selected).remove();
        var selected_row = $('.row-' + date_selected).remove();

        if ($(".heading-tr").children('th').length < 5) {
            $('#manualattendance-submit-button').remove();
            window.location.href = "{{route('manual-attendance')}}";

        }
    })

    $('.punch_in').change(function () {
        var $_this = $(this);
        var $_punchout = $_this.parent().siblings().find('.punch_out');


        let work_hour_amount = getDiffHoursFromTwoTime($_this.val(), $_punchout.val());
        let work_minute_amount = getDiffMinutesFromTwoTime($_this.val(), $_punchout.val());
        $_this.parent().siblings().find('.work-hour-amount').html(work_hour_amount);
        $_this.parent().siblings().find('.work-minute-amount').html(work_minute_amount);
        let sum = 0;
        let sum_minute = 0;
        $_this.parent().parent().parent().find('.work-hour-amount').each(function () {
            sum += parseInt($(this).html());
        });
        $_this.parent().parent().parent().find('.work-minute-amount').each(function () {
            sum_minute += parseInt($(this).html());
        });

        sum += getHoursFromMinutes(sum_minute);

        $_this.parent().parent().parent().find('.total-work-hour-particular').html(parseInt(sum));
        $_this.parent().parent().parent().find('.total-work-minute-particular').html(parseInt(getRemainingMinutesSubtractingForHours(sum_minute)));
    });

    $('.punch_out').change(function () {
        var $_this = $(this);
        var $_punchin = $_this.parent().siblings().find('.punch_in');

        let work_hour_amount = getDiffHoursFromTwoTime($_punchin.val(), $_this.val());
        let work_minute_amount = getDiffMinutesFromTwoTime($_punchin.val(), $_this.val());
        $_this.parent().siblings().find('.work-hour-amount').html(work_hour_amount);
        $_this.parent().siblings().find('.work-minute-amount').html(work_minute_amount);
        let sum = 0;
        let sum_minute = 0;
        $_this.parent().parent().parent().find('.work-hour-amount').each(function () {
            sum += parseInt($(this).html());
        });
        $_this.parent().parent().parent().find('.work-minute-amount').each(function () {
            sum_minute += parseInt($(this).html());
        });

        sum += getHoursFromMinutes(sum_minute);

        $_this.parent().parent().parent().find('.total-work-hour-particular').html(parseInt(sum));
        $_this.parent().parent().parent().find('.total-work-minute-particular').html(parseInt(getRemainingMinutesSubtractingForHours(sum_minute)));
    });

    $('.absent_on_weekends').change(function (e) {
        $this = $(this);
        let siblings = $this.parent().siblings();
        $weekends = siblings.filter('.weekend_day');
        if ($this.is(':checked')) {
            $.each($weekends, function (index, value) {
                $(value).find('.status').val(1);
                $(value).find('.status').trigger('change')
            })
        } else {
            $.each($weekends, function (index, value) {
                $(value).find('.status').val(0);
                $(value).find('.status').trigger('change')
            })
        }
    })
</script>
<script src="{{asset('assets/tableHeadFixer/tableHeadFixer.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $("#fixTable").tableHeadFixer({"left": 2});
    });
</script>
