<html>
<head>
    <title>
        Attendance Print
    </title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
    <link href="{{ asset('assets/css/selectize.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/print.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">


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


        .is-force {
            background-color: #dadada;
            color: #000;
        }

        .present-on-weekend {
            background-color: #71f5af;
            color: #000;
        }

        .present-on-public-holiday {
            background-color: #607d8b;
            color: #fff;

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


        .present-on-leave {
            background-color: red;
            color: #fff !important;
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
            top: 22%;
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
    <link href="{{ asset('assets/css/selectize.css') }}" rel="stylesheet">

</head>
<body style="background: #fafafa;
background-image: linear-gradient(180deg, rgb(250, 250, 250),rgb(250, 250, 250) ,rgba(29, 200, 205, 0.15));">
<div class="jss433 jss434 jss431"><img
        src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIyLjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAzMDAgNDcuMSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzAwIDQ3LjE7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCS5zdDB7ZmlsbDojRkFGQUZBO30KPC9zdHlsZT4KPHBhdGggY2xhc3M9InN0MCIgZD0iTTMwMCw0Ni45TDAsNDcuMVY4LjljMCwwLDIxLjEsMTQuMyw2NS4yLDE0LjFjNDAuNi0wLjIsNzYuNC0yMywxMjgtMjNDMjQzLjMsMCwzMDAsMTYuNCwzMDAsMTYuNFY0Ni45eiIvPgo8L3N2Zz4K"
        alt="decoration" class="jss436" style="margin-bottom: -2px;"></div>
<div class="print-buttons">
    <a href="{{route('localattendance')}}" class="button">Go Back</a>
    <a href="{{route('localattendance-excel-export',$_GET)}}" class="button">Excel Export</a>
    <button class="button button-print" onclick="printDiv('printableArea')">Print</button>
    @if($previous_staff_id > 0)
        <a href="{{route('localattendance-print', [
           'branch_id'=>request('branch_id'),
           'fiscal_year_id'=>request('fiscal_year_id'),
           'department_id'=>request('department_id'),
           'from_date'=>request('from_date'),
           'from_date_np'=>request('from_date_np'),
           'month_id'=>request('month_id'),
            'staff_central_id' => $previous_staff_id,
           'to_date'=>request('to_date'),
           'to_date_np'=>request('to_date_np')
        ])}}" class="button">Previous</a>
    @endif

    @if($next_staff_id > 0)
        <a href="{{route('localattendance-print', [
           'branch_id'=>request('branch_id'),
           'fiscal_year_id'=>request('fiscal_year_id'),
           'department_id'=>request('department_id'),
           'from_date'=>request('from_date'),
           'from_date_np'=>request('from_date_np'),
           'month_id'=>request('month_id'),
            'staff_central_id' => $next_staff_id,
           'to_date'=>request('to_date'),
           'to_date_np'=>request('to_date_np')
        ])}}" class="button button-print">Next</a>
    @endif
</div>


<div class="">
    <div class="search-box">

        {{ Form::open(array('route' => 'localattendance-print','method'=>'GET'))  }}

        <div class="display-flex">
            @if(!auth()->user()->hasRole('Employee'))
                {!! Form::select('branch_id', $branches , request('branch_id'),array('id'=>'branch_id','class'=> 'adjust-width selectize-use','required'=>'required','data-validation' => 'required',
                         ) ) !!}

                {!! Form::select('department_id', $departments , request('department_id'),array('id'=>'department_id','class'=> 'adjust-width selectize-use',
                         ) ) !!}

                <div id="staff">
                </div>
                {{--            {!! Form::select('staff_central_id', $associateStaffInBranchArray, request('staff_central_id')?:'', ['class' => 'adjust-width input-sm use-selectize staff_central_id','id' => 'staff_central_id' ,'placeholder' => 'Select a staff']) !!}--}}
            @else
                {!! Form::hidden('branch_id', auth()->user()->branch_id ?? null) !!}
                {!! Form::hidden('department_id', auth()->user()->staff->department ?? null) !!}
                {!! Form::hidden('staff_central_id', auth()->user()->staff_central_id ?? null) !!}
            @endif
            {!! Form::select('fiscal_year_id', $fiscal_years , request('fiscal_year_id') ?? $current_fiscal_year_id, array('id'=>'fiscal_year_id', 'class'=> 'adjust-width selectize-use','placeholder'=>'Select a Year') ) !!}

            {!! Form::select('month_id', $months , request('month_id'), array( 'id'=>'month_id', 'class'=> 'adjust-width selectize-use', 'placeholder'=>'Select a Month') ) !!}

            {{ Form::text('from_date_np', request('from_date_np'), array('class' => 'nep-date adjust-width custom-input','id'=>'nep-date1', 'placeholder' => 'Date From','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                     ))  }}

            <input type="hidden" id="from_date" name="from_date" value="{{request('from_date' ?: null)}}">

            {{ Form::text('to_date_np', request('to_date_np'), array('class' => 'nep-date adjust-width custom-input','id'=>'nep-date2' , 'placeholder' => 'Date To','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                     )) }}
            <input type="hidden" id="to_date" name="to_date" value="{{request('to_date' ?: null)}}">

            <button class="btn btn-primary button button-print adjust-width" type="submit">Filter</button>
        </div>

        {{ Form::close()  }}
    </div>
</div>


<div id="printableArea">
    @include('localattendance.tablelocalattendance')

    <table class="table-bottom" align="center" border="1px" style="margin: 10px;">
        <tr>
            <td>Absent:</td>
            <td class="Absent"></td>
        </tr>

        <tr>
            <td>Weekend:</td>
            <td class="Weekend"></td>
        </tr>

        <tr>
            <td>Approved:</td>
            <td class="Approved" style="width: 100px;"></td>
        </tr>

        <tr>
            <td>Present On Weekend:</td>
            <td class="present-on-weekend" style="width: 100px;"></td>
        </tr>

        <tr>
            <td>Present On Public Holiday:</td>
            <td class="present-on-public-holiday" style="width: 100px;"></td>
        </tr>

        <tr>
            <td>Is Force:</td>
            <td class="is-force" style="width: 100px;"></td>
        </tr>

        <tr>
            <td>Working:</td>
            <td style="width: 100px;"></td>
        </tr>

        <tr>
            <td>Working on Leave</td>
            <td class="present-on-leave" style="width: 100px;"></td>
        </tr>
    </table>
</div>

<script src="{{asset('assets/js/print.js')}}"></script>
<!-- Nepali Date Picker-->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
{{--<script src="{{ asset('assets/js/bundle.min.js') }}"></script>--}}

<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/selectize/dist/js/standalone/selectize.js') }}"></script>
<script type="text/javascript"
        src="//cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.2.17/jquery.timepicker.min.js"></script>

<script>
    var staffs = new Array();

    $('.selectize-use').selectize({});

    function onChangeBranchIdForStaff() {
        branch = $('#branch_id').val();
        department = $('#department_id').val();
        $.ajax({
            url: '{{route('get-staff-by-branch')}}',
            type: 'post',
            data: {
                'branch': branch,
                'department_id': department,
                '_token': '{{csrf_token()}}'
            },
            success: function (data) {
                //console.log(data);
                staffs = data;
                // console.log(staffs);
                $('.staff_central_id').remove();
                $('#staff').after('<input type="text" id="staff_central_id" name="staff_central_id" class="adjust-width staff_central_id" required\n' +
                    '                                   >');
                $('#staff_central_id').prop('disabled', false);
                var $select = $('.staff_central_id').selectize({
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
                            data: {
                                'branch_id': branch,
                                'department_id': department,
                            },
                            error: function () {
                                callback();
                            },
                            success: function (res) {
                                callback(res.staffs);
                            }
                        });
                    }
                });
                var selectize = $select[0].selectize;
                selectize.setValue('{{ $_GET['staff_central_id'] ?? null}}', false);
            }
        });
    }

    $().ready(onChangeBranchIdForStaff);

    $('#branch_id').change(onChangeBranchIdForStaff);
    $('#department_id').change(onChangeBranchIdForStaff);

</script>

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
        $('#fiscal_year_id').change(function () {
            //get the first day of the month
            const selected_month = $('#month_id').val();
            var selected_year = $(this).val();
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
                    console.log(data);
                    if (data.status) {
                        $('#nep-date1').val(data.start_date_np);
                        $('#nep-date2').val(data.end_date_np);
                        $('#from_date').val(data.start_date_en);
                        $('#to_date').val(data.end_date_en)
                    }
                }
            });
        });


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
                    console.log(data);
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

    $('.use-selectize').selectize({});

</script>


<script src="{{ asset('assets/js/vex.combined.js') }}"></script>
<script>
    //apply vex dialog
    (function () {
        vex.defaultOptions.className = 'vex-theme-os'
        //vex.dialog.buttons.YES.text = 'Yes'
        vex.dialog.buttons.YES.className = 'btn btn-danger'
    })();
</script>

<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
    $('.delete-local-attendance-button').on('click', function () {
        $_this = $(this);
        let local_attendance_id = $_this.parent().parent().data('local-attendance-id');

        vex.dialog.confirm({
            message: 'Are you sure you want to delete?',
            callback: function (value) {
                if (value) { //true if clicked on ok
                    $.ajax({
                        url: "{{route('localattendance-destroy')}}",
                        data: {
                            _token: "{{csrf_token()}}",
                            id: local_attendance_id
                        },
                        type: 'DELETE',
                        success: function (response) {
                            if (response.status === 'false') {
                                vex.dialog.alert(response.data);
                            } else {
                                $_this.parent().parent().css('background-color', 'white').children().not(':nth-child(1)').empty().addClass('Absent').eq(0).html('Absent');
                                toastr.success('Successfully Deleted');
                            }
                        },
                        error: function (response) {
                            vex.dialog.alert(response)
                        }

                    });

                }
            }
        });
    });
</script>

</body>
</html>
