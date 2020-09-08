<html>
<head>
    <title>
        Attendance Print
    </title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
    <link href="{{ asset('assets/css/selectize.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/print.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/scss/backend-style.css').'?v='.rand(43454,994384) }}" rel="stylesheet">
    <link href="{{ asset('nepalidate/nepali.datepicker.v2.2.min.css') }}" rel="stylesheet">

    <style>
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
            color: #000;
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
    </style>
</head>
<body style="background: #fafafa;
background-image: linear-gradient(180deg, rgb(250, 250, 250),rgb(250, 250, 250) ,rgba(29, 200, 205, 0.15));">
<div class="jss433 jss434 jss431"><img
        src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIyLjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAzMDAgNDcuMSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzAwIDQ3LjE7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCS5zdDB7ZmlsbDojRkFGQUZBO30KPC9zdHlsZT4KPHBhdGggY2xhc3M9InN0MCIgZD0iTTMwMCw0Ni45TDAsNDcuMVY4LjljMCwwLDIxLjEsMTQuMyw2NS4yLDE0LjFjNDAuNi0wLjIsNzYuNC0yMywxMjgtMjNDMjQzLjMsMCwzMDAsMTYuNCwzMDAsMTYuNFY0Ni45eiIvPgo8L3N2Zz4K"
        alt="decoration" class="jss436" style="margin-bottom: -2px;"></div>
<div class="print-buttons">
    <a class="button" href="{{route('localattendance-summary')}}">Go Back</a>
    @if($organization->organization_code=="BBSM")
        <a class="button" href="{{route('localattendance-summary-export',$_GET)}}">BBSM Excel Export</a>
    @endif
    @php $_GET['regular']=1;@endphp
    <a class="button" href="{{route('localattendance-summary-export',$_GET)}}">Regular Excel Export</a>
    <button class="button button-print" onclick="printDiv('printableArea')">Print</button>
</div>

<div>
    <div class="search-box">
        {{ Form::open(array('route' => 'localattendance-summary-show','method'=>'GET'))  }}
        <div class="display-flex">

            @if(!auth()->user()->hasRole('Employee'))
                {!! Form::select('branch_id', $branches , request('branch_id'),array('id'=>'branch_id','class'=> 'adjust-width','placeholder'=>'Select a Branch','required'=>'required','data-validation' => 'required',
                         ) ) !!}

                {!! Form::select('department_id', $departments , request('department_id'), array('id'=>'department_id', 'class'=> 'adjust-width') ) !!}

                <div class="staff-form-group">
                    <span id="staff"></span>
                </div>
                {!! Form::select('staff_central_id', $associateStaffInBranchArray, request('staff_central_id')?:'', ['class' => 'adjust-width staffs', 'placeholder' => 'Select a staff']) !!}
            @else
                {!! Form::hidden('branch_id', auth()->user()->branch_id) !!}
                {!! Form::hidden('department_id', auth()->user()->staff->department ?? null) !!}
            @endif

            {!! Form::select('fiscal_year_id', $fiscal_years , $current_fiscal_year_id ?? request('fiscal_year_id'), array('id'=>'fiscal_year_id', 'class'=> 'adjust-width','placeholder'=>'Select a Year') ) !!}

            {!! Form::select('staff_type[]', $staff_types , $_GET['staff_type']?? null, array('id'=>'staff_type', 'class'=> 'adjust-width','placeholder'=>'Select Staff Types','multiple') ) !!}

            {!! Form::select('month_id', $months , request('month_id'), array( 'id'=>'month_id', 'class'=> 'adjust-width', 'placeholder'=>'Select a Month') ) !!}


            {{ Form::text('from_date_np', request('from_date_np'), array('class' => 'form-control nep-date adjust-width','id'=>'nep-date1', 'placeholder' => 'Date From','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                     ))  }}
            <input type="hidden" id="from_date" name="from_date" value="{{request('from_date' ?: null)}}">


            {{ Form::text('to_date_np', request('to_date_np'), array('class' => 'form-control nep-date adjust-width','id'=>'nep-date2' , 'placeholder' => 'Date To','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                     )) }}
            <input type="hidden" id="to_date" name="to_date" value="{{request('to_date' ?: null)}}">

            <button class="button button-print adjust-width" type="submit">Filter</button>

            {{ Form::close()  }}
        </div>
    </div>
</div>


<div id="printableArea">
    <h2 align="center">{{$organization->organization_name ?? 'Organization Name'}}</h2>
    <h5 align="center">Branch Attendance Summary</h5>
    <table class="attendance-information">
        <tr>
            <td width="80%"><b>Branch: </b>{{$branches[$_GET['branch_id']]}}</td>
            <td><b>Public Holidays: </b>{{$public_holiday_count}}</td>
        </tr>
        <tr>
            <td width="80%"><b>Attendance From: </b>{{$_GET['from_date_np'] ?? ''}}</td>
            <td><b>Attendance To: </b>{{$_GET['to_date_np'] ?? ''}}</td>
        </tr>
    </table>
    <table class="payroll_table" border="1px" width="100%">

        <thead>
        <tr class="custom-theader" align="center">
            <th class="text-center">Staff ID</th>
            <th class="text-center">Staff Name</th>
            <th class="text-center">Total Days</th>
            <th class="text-center">Total Working Days</th>
            <th class="text-center">Present Days</th>
            <th class="text-center">Absent Days</th>
            <th class="text-center">Present On Public Holidays</th>
            <th class="text-center">Present on Weekend</th>
            <th class="text-center">Grant Leave</th>
            <th class="text-center">Absent on Weekend</th>
            <th class="text-center">Paid Leave</th>
            <th class="text-center">Unpaid Leave</th>
            <th class="text-center">Suspense Days</th>
            <th class="text-center">Total Work Time</th>
        </tr>
        </thead>
        <tbody>
        @php $isEmployee=!auth()->user()->hasRole('Employee') @endphp
        @foreach($localattendances as $localattendance)
            <tr>
                <td>{{$localattendance['main_id']}}</td>
                <td>
                    @if($isEmployee)
                        <a target="_blank" href="{{route('localattendance-print',
                [
                'branch_id' => $_GET['branch_id'], 'fiscal_year_id' => $_GET['fiscal_year_id'],
                'department_id' => $_GET['department_id'],
                'from_date' => $_GET['from_date'],
                'from_date_np' => $_GET['from_date_np'],
                'to_date' => $_GET['to_date'],
                'to_date_np' => $_GET['to_date_np'],
                'month_id' => $_GET['month_id'],
                'staff_central_id' => $localattendance['CID'],

                ])}}">{{$localattendance['name']}}
                        </a>
                    @else
                        {{$localattendance['name']}}
                    @endif
                </td>
                <td>{{$localattendance['total_days']}}</td>
                <td>{{$localattendance['total_working_days']}}</td>
                <td>{{$localattendance['present_days']}}</td>
                <td>{{$localattendance['absent_days']}}</td>
                <td>{{$localattendance['present_on_public_holidays']}}[{{$localattendance['public_holiday_work_hour']}}
                    Hours]
                </td>
                <td>{{$localattendance['present_on_weekend']}} ({{$localattendance['weekend_days']}}
                    )[{{$localattendance['weekend_holiday_work_hour']}} Hours]
                </td>
                <td>{{$localattendance['grant_leave']}}</td>
                <td>{{$localattendance['absent_on_weekend']}}</td>
                <td>{{$localattendance['paid_leave']}}</td>
                <td>{{$localattendance['unpaid_leave']}}</td>
                <td>{{$localattendance['suspense_days']}}</td>
                <td>{{\App\Helpers\DateHelper::convertHourToHourAndMinutesFormat($localattendance['total_work_hour'])}}
                    ({{$localattendance['total_work_hour']}} hrs)
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>

</div>

<script src="{{asset('assets/js/print.js')}}"></script>
<!-- Bootstrap and necessary plugins -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
{{--for hiearchy menu--}}

<script src="{{ asset('assets/js/selectize.js') }}"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>

<!-- Custom scripts required by this view -->
<script src="{{ asset('assets/js/main.js').'?v='.rand(8989,78799) }}"></script>


<!-- Nepali Date Picker-->
<script>
    $('select').selectize();
</script>

<script>


    var staffs = new Array();

    function onChangeBranchIdForStaff() {
        var branch = $('#branch_id').val();
        var department_id = $('#department_id').val();
        $.ajax({
            url: '{{route('get-staff-by-branch')}}',
            type: 'post',
            data: {
                'branch': branch,
                'department_id': department_id,
                '_token': '{{csrf_token()}}'
            },
            success: function (data) {
                staffs = data;
                $('#staff_central_id').remove();
                $('.staffs').remove();
                $('.removeit').remove();
                $('.staff_container').remove();

                $('#staff').after('   <div class="staff_container"><input type="text" id="staff_central_id" style="width: 150px" name="staff_central_id" class="input-sm adjust-width" \n' +
                    '                                   ></div>')
                $('#staff_central_id').prop('disabled', false);
                var $select = $('#staff_central_id').selectize({
                    valueField: 'id',
                    labelField: 'name_eng',
                    searchField: ['name_eng', 'main_id'],
                    options: staffs,
                    preload: true,
                    maxItems: 1,
                    create: false,
                    render: {
                        option: function (item, escape) {
                            return '<div class="suggestions"><div> Name: ' + item.name_eng + '</div>' +
                                '<div> Branch ID: ' + item.main_id + '</div>' +
                                '<div> Branch: ' + item.branch.office_name + '</div>' +
                                '<div> CID: ' + item.staff_central_id + '</div>' +
                                '</div>';
                        }
                    },
                    load: function (query, callback) {
                        if (!query.length) return callback();
                        $.ajax({
                            url: '{{ route('get-staff') }}?search=' + encodeURIComponent(query),
                            type: 'GET',
                            error: function () {
                                callback();
                            },
                            success: function (res) {
                                callback(res.staffs);
                            }
                        });
                    }
                });
                console.log($('#staff').next().next().addClass('removeit'));
                var selectize = $select[0].selectize;
                selectize.setValue('{{ $_GET['staff_central_id'] ?? null}}', false);
            }
        });
    }

    $().ready(onChangeBranchIdForStaff);

    $('#branch_id').change(onChangeBranchIdForStaff);
    $('#department_id').change(onChangeBranchIdForStaff);
</script>

<script type="text/javascript"
        src="//cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.2.17/jquery.timepicker.min.js"></script>

<script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
<script>
    $('.nep-date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        npdYearCount: 20,
        onChange: function (e) {
            $('#nep-date1').next().val(BS2AD($('#nep-date1').val()));
            $('#nep-date2').next().val(BS2AD($('#nep-date2').val()));
        }
    });

</script>

<script>
    //show date from date to by month id

    $(function () {

        $('#month_id').change(function () {
            //get the first day of the month
            const selected_month = $('#month_id').val();
            var selected_year = $('#fiscal_year_id').val();
            if (selected_year === '' || selected_year === undefined) {
                selected_year = '{{ $current_fiscal_year_id }}'
            }
            $.ajax({
                url: '{{route('getmonthdatefromto')}}',
                type: 'post',
                data: {
                    'selected_month': selected_month,
                    'selected_year': selected_year,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data) {
                    if (data.status) {
                        $('#nep-date1').val(data.start_date_np);
                        $('#nep-date2').val(data.end_date_np);
                        $('#from_date').val(data.start_date_en);
                        $('#to_date').val(data.end_date_en)
                    }
                }
            });
        });

        $('#fiscal_year_id').change(function () {
            //get the first day of the month
            const selected_month = $('#month_id').val();
            var selected_year = $('#fiscal_year_id').val();
            if (selected_year === '' || selected_year === undefined) {
                selected_year = '{{ $current_fiscal_year_id }}'
            }
            $.ajax({
                url: '{{route('getmonthdatefromto')}}',
                type: 'post',
                data: {
                    'selected_month': selected_month,
                    'selected_year': selected_year,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data) {
                    if (data.status) {
                        $('#nep-date1').val(data.start_date_np);
                        $('#nep-date2').val(data.end_date_np);
                        $('#from_date').val(data.start_date_en);
                        $('#to_date').val(data.end_date_en)
                    }
                }
            });
        });

    });


</script>

</body>
</html>
