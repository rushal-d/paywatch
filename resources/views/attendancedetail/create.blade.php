@extends('layouts.default', ['crumbroute' => 'payrollcreate'])
@section('title', $title)
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <style>
        .popup {
            width: 100%;
            height: 100%;
            display: none;
            position: fixed;
            top: 0px;
            left: 0px;
            background: rgba(0, 0, 0, 0.75);
        }

        .popup {
            text-align: center;
            z-index: 99;
        }

        .popup:before {
            content: '';
            display: inline-block;
            height: 100%;
            margin-right: -4px;
            vertical-align: middle;
        }

        .popup-inner {
            display: inline-block;
            text-align: left;
            vertical-align: middle;
            position: relative;
            max-width: 700px;
            width: 90%;
            padding: 40px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 1);
            border-radius: 3px;
            background: #fff;
            max-height: 400px;
            overflow-y: scroll;
        }

        .popup-inner h1 {
            font-family: 'Roboto Slab', serif;
            font-weight: 700;
        }

        .popup-inner p {
            font-size: 24px;
            font-weight: 400;
        }

        .popup-close {
            width: 34px;
            height: 34px;
            padding-top: 4px;
            display: inline-block;
            position: absolute;
            top: 20px;
            right: 20px;
            -webkit-transform: translate(50%, -50%);
            transform: translate(50%, -50%);
            border-radius: 100%;
            background: transparent;
            border: solid 4px #808080;
        }

        .popup-close:after,
        .popup-close:before {
            content: "";
            position: absolute;
            top: 11px;
            left: 5px;
            height: 4px;
            width: 16px;
            border-radius: 30px;
            background: #808080;
            -webkit-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        .popup-close:after {
            -webkit-transform: rotate(-45deg);
            transform: rotate(-45deg);
        }

        .popup-close:hover {
            -webkit-transform: translate(50%, -50%) rotate(180deg);
            transform: translate(50%, -50%) rotate(180deg);
            background: #f00;
            text-decoration: none;
            border-color: #f00;
        }

        .popup-close:hover:after,
        .popup-close:hover:before {
            background: #fff;
        }
    </style>
@endsection
@section('content')
    <div id="loader" style="display:none;position: fixed;top: 0%;left: 0%;width: 100%;height: 100%;z-index: 9999999;">
        <div style="    height: 100%;    width: 100%;    background: white;    padding-top: 20%;">
            <h1 align="center">Preparing Staff Payroll
            </h1>
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
            </div>

        </div>
    </div>

    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Payroll Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="branch_id" class="col-3 col-form-label">
                                Branch
                            </label>
                            <select id="branch_id" name="branch_id" class="input-sm" required>
                                <option value="">Select Branch</option>
                                @foreach($branch as $bran)
                                    <option value="{{$bran->office_id}}">{{$bran->office_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="fiscal_year" class="col-3 col-form-label">
                                Fiscal Year
                            </label>
                            {!! Form::select('fiscal_year_id', $fiscal_years , $current_fiscal_year_id ?? null,array('id'=>'fiscal_year_id', 'placeholder' => 'Fiscal Year') ) !!}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Select Month
                            </label>
                            {!! Form::select('month_id', $months , null, array( 'id'=>'month_id', 'placeholder'=>'Select a Month') ) !!}
                        </div>

                        <div class="form-group row">
                            <label for="from_date_np" class="col-3 col-form-label">Date From</label>
                            {{ Form::text('from_date_np', null, array('autocomplete' => 'off', 'class' => 'form-control nep-date','required' => 'required','id'=>'nep-date1' , 'placeholder' => 'From', 'readonly' => 'readonly' ))  }}
                            <input type="hidden" id="from_date" name="from_date">
                        </div>

                        <div class="form-group row">
                            <label for="to_date_np" class="col-3 col-form-label">
                                Date To
                            </label>
                            {{ Form::text('to_date_np', null, array('autocomplete' => 'off', 'class' => 'form-control nep-date', 'required' => 'required', 'id'=>'nep-date2' , 'placeholder' => 'To', 'readonly' => 'readonly')) }}
                            <input type="hidden" id="to_date" name="to_date">
                        </div>

                        <div class="form-group row">
                            <label for="to_date_np" class="col-3 col-form-label">
                                Payroll Month
                            </label>
                            {{ Form::text('salary_month_name', null, array('readonly' => 'readonly', 'id'=> 'salary_month_name', 'class' => 'form-control'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="to_date_np" class="col-3 col-form-label">
                                Payroll Total Days
                            </label>
                            {{ Form::text('total_days', null, array('readonly' => 'readonly', 'id'=> 'total_days', 'class' => 'form-control'))  }}
                        </div>
                        <input type="hidden" name="salary_month" id="salary_month">
                    </div>
                </div>
            </div>
            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{--if day count payroll cal--}}
                        <a href="javascript:void(0);"
                           @if(strcasecmp($organization->organization_code,"NEPALRE")!=0) id="submit"
                           @else id="submit-nepal-re" @endif class="btn btn-success">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="popup" pd-popup="popupNew">
        <div class="popup-inner">
            <div class="row">
                <div class="col-6">
                    <h6 class="text-center">Punchout Warning</h6>
                    <div id="punchout-warning">

                    </div>
                </div>
                <div class="col-6">
                    <h6 class="text-center">No Attendance</h6>
                    <div id="no-attendance">

                    </div>
                </div>
            </div>
            <p>
                <button class="btn btn-success continue-payroll">Continue</button>
            </p>
            <a class="popup-close" pd-popup-close="popupNew" href="#"> </a>
        </div>
    </div>


@endsection


@section('script')

    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script src="{{ asset('assets/js/vex.combined.js') }}"></script>
    <script>
        //apply vex dialog
        (function () {
            vex.defaultOptions.className = 'vex-theme-os'
            //vex.dialog.buttons.YES.text = 'Yes'
            vex.dialog.buttons.YES.className = 'btn btn-danger'
        })();
    </script>
    <script>

        $('#submit').click(function (e) {

            e.preventDefault();
            let branch_id = $('#branch_id').val();
            let from_date_np = $('#nep-date1').val();
            let to_date_np = $('#nep-date2').val();
            let salary_month = $('#salary_month').val();
            $.ajax({
                url: '{{route('warning-before-payroll')}}',
                type: 'GET',
                data: {
                    '_token': '{{csrf_token()}}',
                    'branch_id': branch_id,
                    'from_date_np': from_date_np,
                    'to_date_np': to_date_np,
                },
                success: function (response) {
                    if (response.punchoutwarning.length > 0 || response.noPresentStaff.length > 0) {
                        if (response.punchoutwarning.length > 0) {
                            let list = '<ul>';
                            $.each(response.punchoutwarning, function (index, value) {
                                console.log(value);
                                list += '<li>' + value.name_eng + ' ' + value.main_id + ' (' + value.fetch_attendances.length +
                                    ' )</li>';
                            });
                            list += '</ul>';
                            $('#punchout-warning').html(list);
                        }
                        if (response.noPresentStaff.length > 0) {
                            let list = '<ul>';
                            $.each(response.noPresentStaff, function (index, value) {
                                list += '<li>' + value.name_eng + ' ' + value.main_id + '</li>';
                            });
                            list += '</ul>';
                            $('#no-attendance').html(list);

                        }
                    }

                    $('[pd-popup="popupNew"]').fadeIn(100);
                }
            });
        });
        $(document).on('click', '.continue-payroll', function (e) {
            e.preventDefault();
            let branch_id = $('#branch_id').val();
            let from_date_np = $('#nep-date1').val();
            let to_date_np = $('#nep-date2').val();
            let salary_month = $('#salary_month').val();
            if (branch_id != "" && from_date_np != "" && to_date_np != "") {
                $('#loader').show();
                $.ajax({
                    url: '{{route('payroll-create')}}',
                    type: 'POST',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'branch_id': branch_id,
                        'from_date_np': from_date_np,
                        'to_date_np': to_date_np,
                        'salary_month': salary_month
                    },
                    success: function (data) {
                        if (data['status']) {
                            let payroll_id = data['payroll']['id'];
                            $.ajax({
                                url: '{{route('get-payroll-staff-by-branch')}}',
                                type: 'POST',
                                data: {
                                    '_token': '{{csrf_token()}}',
                                    'branch_id': branch_id,
                                    'onlyBBSM': 1,
                                    'payrollStaffs': 1,
                                    'from_date_np': from_date_np,
                                    'to_date_np': to_date_np,
                                    'salary_month': salary_month
                                }, success: function (data) {
                                    let i = 0;
                                    let per = 0;
                                    let number_of_request = Math.ceil(data.length / 4);
                                    while (data.length > 0) {
                                        chunk = data.splice(0, 4)
                                        $.ajax({
                                            url: '{{route('fetch-to-detail')}}',
                                            type: 'POST',
                                            data: {
                                                '_token': '{{csrf_token()}}',
                                                'staff_central_ids': chunk,
                                                'branch_id': branch_id,
                                                'from_date_np': from_date_np,
                                                'to_date_np': to_date_np,
                                                'payroll_id': payroll_id
                                            }, success: function (data) {
                                                if (data == true) {
                                                    i = i + 1;
                                                    per = (i / number_of_request) * 100;
                                                    $('.progress-bar').css('width', per + '%')
                                                    if (i == number_of_request) {
                                                        window.location.replace('{{URL('/attendancedetail/payroll/action/')}}' + '/' + payroll_id);
                                                    }
                                                } else {
                                                    return false;
                                                    $('#loader').hide();
                                                    vex.dialog.alert("Error Occured! Please try again or contact the development team!")
                                                }

                                            }, error: function () {
                                                $('#loader').hide();
                                                vex.dialog.alert("Error Occured! Please try again or contact the development team!")
                                            }
                                        });
                                    }
                                }
                            })
                        } else {
                            //error message
                            $('#loader').hide();
                            vex.dialog.alert(data['mesg'])
                        }
                    }
                })
            }
        });

        $('#submit-nepal-re').click(function (e) {
            let branch_id = $('#branch_id').val();
            let from_date_np = $('#nep-date1').val();
            let to_date_np = $('#nep-date2').val();
            let salary_month = $('#salary_month').val();
            if (branch_id != "" && from_date_np != "" && to_date_np != "") {
                $('#loader').show();
                $.ajax({
                    url: '{{route('payroll-create')}}',
                    type: 'POST',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'branch_id': branch_id,
                        'from_date_np': from_date_np,
                        'to_date_np': to_date_np,
                        'salary_month': salary_month
                    },
                    success: function (data) {
                        if (data['status']) {
                            let payroll_id = data['payroll']['id'];
                            window.location.replace('{{URL('/nepalre/payroll')}}' + '/' + payroll_id);
                        } else {
                            //error message
                            $('#loader').hide();
                            vex.dialog.alert(data['mesg'])
                        }
                    }
                })
            }
        });

        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#nep-date1').next().val(BS2AD($('#nep-date1').val()))
                $('#nep-date2').next().val(BS2AD($('#nep-date2').val()))

                //calculate days
                calculateDays();
            }
        });

        function calculateDays() {
            //calculate days
            var date_from = $('#nep-date1').next().val();
            var date_to = $('#nep-date2').next().val();
            //also check if contains NaN
            if (date_from && date_from.indexOf('NaN') < 0 && date_to && date_to.indexOf('NaN') < 0) {
                var diff_days = daydiff(parseDate(date_from), parseDate(date_to)) + 1
                if (diff_days > 0) {
                    $('#total_days').val(diff_days);
                    var mdy = $('#nep-date1').val().split('-');
                    var month = mdy[1];
                    var months = new Array(
                        'Baishakh', 'Jestha', 'Asar', 'Shrawan', 'Bhadra', 'Ashwin', 'Kartik',
                        'Mangsir', 'Poush', 'Magh', 'Falgun', 'Chaitra'
                    );
                    $('#salary_month_name').val(months[month - 1]);
                    $('#salary_month').val(month);
                } else {
                    $('#total_days').val(0);
                    toastr.error('Please check date from and to!', 'Error!')
                }
            }
        }

        function parseDate(str) {
            var mdy = str.split('-');
            return new Date(mdy[0], mdy[1] - 1, mdy[2]);
        }

        function daydiff(first, second) {
            return Math.round((second - first) / (1000 * 60 * 60 * 24));
        }

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
                        if (data.status) {
                            $('#nep-date1').val(data.start_date_np);
                            $('#nep-date2').val(data.end_date_np);
                            $('#from_date').val(data.start_date_en);
                            $('#to_date').val(data.end_date_en);
                            calculateDays()
                        }
                    }
                });
            });

            $('#month_id').change(function () {
                //get the first day of the month
                const selected_month = $('#month_id').val();
                var selected_year = $('#fiscal_year_id').val();
                console.log(selected_year);
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
                            $('#to_date').val(data.end_date_en);
                            calculateDays()
                        }
                    }
                });
            });

        });

        $(function () {
            //----- OPEN
            $('[pd-popup-open]').on('click', function (e) {
                var targeted_popup_class = jQuery(this).attr('pd-popup-open');
                $('[pd-popup="' + targeted_popup_class + '"]').fadeIn(100);

                e.preventDefault();
            });

            //----- CLOSE
            $('[pd-popup-close]').on('click', function (e) {
                var targeted_popup_class = jQuery(this).attr('pd-popup-close');
                $('[pd-popup="' + targeted_popup_class + '"]').fadeOut(200);

                e.preventDefault();
            });
        });
    </script>
@endsection
