@extends('layouts.default', ['crumbroute' => 'overtime-work-index'])
@section('title', $title)

@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <style>
        .staff_container {
            width: 72%;
        }

        .selectize-control.input-sm.single {
            width: 100%;
        }

        .level {
            display: flex;
        }

        .flex {
            flex: 1;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@endsection
@section('content')

    {{ Form::open(array('route' => 'overtime-work-show','method'=>'GET'))  }}
    <div class="row">
        <div class="col-md-5 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <div class="level">
                    <h5 class="card-header flex">Filter Staff Attendance</h5>

                </div>
                <div class="card-block">
                    <div class="card-text">
                        @if(!auth()->user()->hasRole('Employee'))
                            <div class="form-group row">
                                <label for="title" class="col-3 col-form-label">
                                    Branch <span class="required-field">*</span>

                                </label>
                                {!! Form::select('branch_id', $branches , null,array('id'=>'branch_id','required'=>'required') ) !!}
                            </div>

                            <div class="form-group row">
                                <label for="title" class="col-3 col-form-label" id="staff">
                                    Staff Name <span class="required-field">*</span>
                                </label>
                                <input type="text" id="staff_central_id" name="staff_central_id" class="input-sm"
                                       required
                                       disabled>
                            </div>
                        @else
                            {!! Form::hidden('branch_id', auth()->user()->branch_id ?? null) !!}
                            {!! Form::hidden('staff_central_id', auth()->user()->staff_central_id ?? null) !!}
                        @endif


                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Select Year
                            </label>
                            {!! Form::select('fiscal_year_id', $fiscal_years , $current_fiscal_year_id ?? null,array('id'=>'fiscal_year_id', 'placeholder' => 'Fiscal Year') ) !!}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Select Month
                            </label>
                            {!! Form::select('month_id', $months , $currentNepaliDateMonth, array( 'id'=>'month_id') ) !!}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Date From (BS)<span class="required-field">*</span>
                            </label>
                            {{ Form::text('from_date_np', null, array('class' => 'form-control nep-date','id'=>'nep-date1' , 'placeholder' => 'Date From','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                                     'data-validation-error-msg' => 'Please enter date from'))  }}
                            <input type="hidden" id="from_date" name="from_date">
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Date From<span class="required-field">*</span>
                            </label>
                            {{ Form::text('from_date', $_GET['from_date'] ?? null, array('class' => 'date form-control','id' => 'date1', 'placeholder' => 'Date From','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                                     'data-validation-error-msg' => 'Please enter date from' ))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Date To (BS)<span class="required-field">*</span>
                            </label>
                            {{ Form::text('to_date_np', null, array('class' => 'form-control nep-date','id'=>'nep-date2' , 'placeholder' => 'Date To','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                                     'data-validation-error-msg' => 'Please enter date to')) }}
                            <input type="hidden" id="to_date" name="to_date">
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Date To <span class="required-field">*</span>
                            </label>
                            {{ Form::text('to_date', $_GET['to_date'] ?? null, array('class' => 'date form-control','id' => 'date2', 'placeholder' => 'Date From','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                                     'data-validation-error-msg' => 'Please enter date to' ))  }}
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Filter',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close()  }}


@endsection


@section('script')
    <script>
        //responsive table
        $(function () {
            $('.table-all').stacktable();
        });
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

    <script>

        function onChangeBranchId() {
            branch = $('#branch_id').val();
            department_id = $('#department_id').val();

            if (branch.length > 0) {
                $.ajax({
                    url: '{{route('get-staff-by-branch')}}',
                    type: 'post',
                    data: {
                        'branch': branch,
                        'department_id': department_id,
                        '_token': '{{csrf_token()}}'
                    },
                    success: function (data) {
                        //console.log(data);
                        staffs = data;
                        console.log(staffs);
                        $('#staff_central_id').remove();
                        $('.removeit').remove();
                        $('.staff_container').remove();
                        $('#staff').after('   <div class="staff_container"><input type="text" id="staff_central_id" name="staff_central_id" class="input-sm" \n' +
                            '                                   ></div>')
                        $('#staff_central_id').prop('disabled', false);
                        $('#staff_central_id').selectize({
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
                        console.log($('#staff').next().next().addClass('removeit'));
                    }
                });
            } else {
            }

        }

        onChangeBranchId();

        var staffs = new Array();
        $('#branch_id').change(onChangeBranchId);
        $('#department_id').change(onChangeBranchId);
    </script>

    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#nep-date1').val() ? $('#date1').val(BS2AD($('#nep-date1').val())) : '';
                $('#nep-date2').val() ? $('#date2').val(BS2AD($('#nep-date2').val())) : '';
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $('.date').flatpickr({
            dateFormat: "Y-m-d",
            disableMobile: "true",
            onChange: function () {
                $('#date1').val() ? $('#nep-date1').val(AD2BS($('#date1').val())) : '';
                $('#date2').val() ? $('#nep-date2').val(AD2BS($('#date2').val())) : '';
            }
        })
    </script>

    <script>
        $('#fiscal_year_id').change(function () {
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
                        $('#date1').val(data.start_date_en);
                        $('#date2').val(data.end_date_en);
                    }
                }
            });
        });

        //show date from date to by month id
        $('#month_id').change(function () {
            //get the first day of the month
            const selected_month = $('#month_id').val();
            var selected_year = $('#fiscal_year_id').val();
            console.log(selected_year);
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
                        $('#date1').val(data.start_date_en);
                        $('#date2').val(data.end_date_en);
                    }
                }
            });
        });

        $('#month_id').trigger('change');

    </script>
@endsection
