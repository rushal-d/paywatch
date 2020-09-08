<html>
<head>
    <title>
        Attendance Print
    </title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
    <link href="{{ asset('nepalidate/nepali.datepicker.v2.2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/selectize.css') }}" rel="stylesheet">


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

        .present-on-leave {
            background-color: red;
            color: #fff;
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

        .selectize-input {
            max-width: 200px;
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

        @media only screen and (max-width: 768px) {
            .display-flex {
                display: block;
            }

            .print-buttons {
                position: initial;
                padding: 10px 10px;
                background: #fafafa;
                width: 250px;
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
    <a class="button" href="{{route('localattendance-daywise')}}">Go Back</a>
    <a class="button" onclick="printDiv('printableArea')">Print</a>
    @php
        $params=$_GET;
            $params['export']=1
    @endphp
    <a class="button" href="{{route('localattendance-daywise-show',$params)}}">Excel Export</a>
</div>
<div class="card">

    <div class="search-box">
        {{ Form::open(array('route' => 'localattendance-daywise-show','method'=>'GET'))  }}
        <div class="row display-flex">

            @if(!auth()->user()->hasRole('Employee'))
                {!! Form::select('branch_id', $branches , request('branch_id'),array('id'=>'branch_id','class'=> 'adjust-width selectize-use','required'=>'required','data-validation' => 'required',
                         ) ) !!}

                {!! Form::select('department_id', $departments, request('department_id')?:'', ['class' => 'adjust-width selectize-use']) !!}

                <div id="shift"></div>
                {!! Form::select('shift_id', $shifts , request('shift_id')? : '', ['id'=>'shift_id', 'class'=> 'adjust-width selectize-use shift_id', 'placeholder'=>'Select a Shift' ]) !!}

                <label for="">Show Phone
                    Number</label>{{ Form::checkbox('show_phone_number',1, isset($_GET['show_phone_number']) && $_GET['show_phone_number']==1)  }}

                {{ Form::select('status',['Absent Only','Present Only'], $_GET['status'] ?? null , array('class' => 'adjust-width selectize-use', 'placeholder' => 'Select One','readonly'=>'readonly'))  }}

            @else
                {!! Form::hidden('branch_id', auth()->user()->branch_id) !!}
                {!! Form::hidden('department_id', auth()->user()->staff->department ?? null) !!}
                {!! Form::hidden('shift_id', auth()->user()->shift_id) !!}
            @endif
            {{ Form::text('date', request('date'), array('class' => 'form-control nep-date adjust-width ndp-nepali-calendar custom-input', 'readonly' => 'readonly','id'=>'nep-date', 'placeholder' => 'Date', 'required' => 'true'
                     ))  }}

            <button class="button button-print adjust-width" type="submit">Filter</button>
            {{ Form::close()  }}
        </div>
    </div>
</div>
<div id="printableArea">

    <h2 align="center">{{$organization->organization_name ?? 'Organization Name'}}</h2>
    <h5 align="center">Daywise Attendance Report</h5>

    @include('localattendance.daywise-table')

    <table class="table-bottom" align="center" border="1px" style="margin:0% 3%;">
        <tbody>
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
            <td>Present On Leave:</td>
            <td class="present-on-leave" style="width: 100px;"></td>
        </tr>

        <tr>
            <td>Is Force:</td>
            <td class="is-force" style="width: 100px;"></td>
        </tr>

        <tr>
            <td>Working:</td>
            <td style="width: 100px;"></td>
        </tr>
        </tbody>
    </table>
</div>

<script src="{{asset('assets/js/print.js')}}"></script>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/selectize.js') }}"></script>

<script>
    $('.selectize-use').selectize();

    $(document).ready(function () {
        $('#branch_id').trigger('change');
    });

    $('#branch_id').change(function () {
        branch = $(this).val();

        $.ajax({
            url: '{{route('get-shift-by-branch')}}',
            type: 'post',
            data: {
                'branch': branch,
                'send_all': 1,
                '_token': '{{csrf_token()}}'
            },
            success: function (data) {
                let shifts = data;
                $('.shift_id').remove();
                $('.removeit').remove();
                $('.shift_container').remove();
                $('#shift').after('<select id="shift_id" name="shift_id" class="adjust-width selectize-use shift_id" \n' +
                    '                                   ></select>');
                // $('#shift_id').prop('disabled', false);

                var $select = $('#shift_id').selectize({
                    valueField: 'id',
                    labelField: 'shift_name',
                    searchField: ['shift_name', 'id'],
                    options: shifts,
                    preload: true,
                    maxItems: 1,
                    create: false,
                    render: {
                        option: function (item, escape) {
                            let status = (item.active == 1) ? 'Active' : 'Inactive';
                            return '<div class="suggestions">' +
                                '<div> Shift Name: ' + item.shift_name + '</div>' +
                                '<div> ID: ' + item.id + '</div>' +
                                '<div> Active: ' + status + '</div>' +
                                '</div>';
                        }
                    },
                    load: function (query, callback) {

                    }
                });

                var selectize = $select[0].selectize;
                selectize.setValue('{{ $_GET['shift_id'] ?? null}}', false);
            }
        });
    });
</script>

<script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
<script>
    $('.nep-date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        npdYearCount: 20,
        onChange: function (e) {
            $('#nep-date').next().val(BS2AD($('#nep-date').val()));
        }
    });

</script>

</body>
</html>
