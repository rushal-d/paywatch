@extends('layouts.default', ['crumbroute' => 'punchout-warning-index'])
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

        #staff_types-selectized {
            min-width: 200px !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@endsection
@section('content')
    <div class="row">
        <div class="col-md-5 col-sm-12">
            {{-- Basic Info --}}
            {{ Form::open(array('route' => 'localattendance-punch-out-warning','method'=>'GET'))  }}
            <div class="basic-info card">
                <h5 class="card-header">Local Attendance Punch Out Warning</h5>
                <div class="card-block">
                    <div class="card-text">

                        @if(!auth()->user()->hasRole('Employee'))
                            <div class="form-group row">
                                <label for="title" class="col-3 col-form-label">
                                    Branch<span class="required-field">*</span>
                                </label>
                                {!! Form::select('branch_id', $branches , $_GET['branch_id'] ?? null,array('id'=>'branch_id','required'=>'required','data-validation' => 'required',
                                         'data-validation-error-msg' => 'Please Select Branch') ) !!}
                            </div>

                            <div class="form-group row">
                                <label for="title" class="col-3 col-form-label">
                                    Staff Types
                                </label>
                                {!! Form::select('staff_types[]', $staff_types , $_GET['staff_types'] ?? null,array('id'=>'staff_types','multiple'=>true) ) !!}
                            </div>
                            <div class="form-group row">
                                <label for="title" class="col-3 col-form-label" id="staff">
                                    Staff Name
                                </label>
                                @if(empty($staff_data))
                                    <input type="text" id="staff_central_id" name="staff_central_id" class="input-sm"
                                           value="{{$_GET['staff_central_id'] ?? null}}" disabled>
                                @else
                                    {!! Form::select('staff_central_id', $staff_data , $_GET['staff_central_id'] ?? null, array('id'=>'staff_central_id ','placeholder'=>'Select a Staff') ) !!}

                                @endif
                            </div>
                        @else
                            {!! Form::hidden('branch_id', auth()->user()->branch_id) !!}
                            {!! Form::hidden('staff_central_id', auth()->user()->staff_central_id) !!}
                        @endif

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Select Year
                            </label>
                            {!! Form::select('fiscal_year_id', $fiscal_years , $current_fiscal_year_id ?? null, array('id'=>'fiscal_year_id ','placeholder'=>'Select a Year', 'class' => 'fiscal_year_id') ) !!}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Select Month
                            </label>
                            {!! Form::select('month_id', $months , isset($_GET['month_id']) ? $_GET['month_id'] : $currentNepaliDateMonth, array( 'id'=>'month_id', 'placeholder'=>'Select a Month') ) !!}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Date From (BS)<span class="required-field">*</span>
                            </label>
                            {{ Form::text('from_date_np', $_GET['from_date_np'] ?? null, array('class' => 'form-control nep-date','id'=>'nep-date1' , 'placeholder' => 'Date From Nep','readonly'=>'readonly','data-validation' => 'required',
                                     'data-validation-error-msg' => 'Please enter date from' ))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Date From (AD)<span class="required-field">*</span>
                            </label>
                            {{ Form::text('from_date', $_GET['from_date'] ?? null, array('class' => 'date form-control','id' => 'date1', 'placeholder' => 'Date From','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                                     'data-validation-error-msg' => 'Please enter date from' ))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Date To (BS)<span class="required-field">*</span>
                            </label>
                            {{ Form::text('to_date_np', $_GET['to_date_np'] ?? null, array('class' => 'form-control nep-date','id'=>'nep-date2' , 'placeholder' => 'Date To','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                                     'data-validation-error-msg' => 'Please enter date to')) }}
                            {{--                            <input type="hidden" id="to_date" name="to_date">--}}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Date To (AD)<span class="required-field">*</span>
                            </label>
                            {{ Form::text('to_date', $_GET['to_date'] ?? null, array('class' => 'date form-control','id' => 'date2', 'placeholder' => 'Date From','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                                     'data-validation-error-msg' => 'Please enter date to' ))  }}
                        </div>

                        <div class="form-group row">
                            <label for="should_not_display_today_date" class="col-3 col-form-label">Do Not Display Today
                                Date</label>
                            {{Form::select('should_not_display_today_date', [1 => 'Yes', 0 => 'No'], request('should_not_display_today_date') ?? 1, ['id' => 'should-not-display-today-date'])}}
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
            {{ Form::close()  }}
        </div>
        @if(!empty($punchout_warning_staffs))
            <div class="col-md-12 col-sm-12">
                <div class="basic-info card">
                    <h5 class="card-header">Punch Out Warning</h5>
                    <div class="card-block">
                        <div class="card-text">
                            <table class="table table-bordered table-responsive">
                                <thead>
                                <th>Branch ID</th>
                                <th>Staff Name</th>
                                <th>Warning Days</th>
                                <th>Shift</th>
                                <th>Department</th>
                                </thead>
                                <tbody>
                                @foreach($punchout_warning_staffs as $punchout_warning_staff)
                                    <tr>
                                        <td>{{$punchout_warning_staff->main_id}}</td>
                                        <td>
                                            <a href="{{route('localattendance-print',['staff_central_id'=>$punchout_warning_staff->id,'branch_id'=>$punchout_warning_staff->branch_id,'from_date'=>$_GET['from_date'],'to_date'=>$_GET['to_date']])}}"
                                               target="_blank">{{$punchout_warning_staff->name_eng ?? ''}}</a>
                                        </td>
                                        <td>{{$punchout_warning_staff->fetchAttendances->count()}}</td>
                                        <td>{{$punchout_warning_staff->shift->shift_name ?? ''}}</td>
                                        <td>{{$punchout_warning_staff->getDepartment->department_name ?? ''}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection


@section('script')
    <script>
        //responsive table
        $(function () {
            $('.table-all').stacktable();
        });
    </script>

    <script>
        $('.fiscal_year_id').change(function () {
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
            var selected_year = $('.fiscal_year_id').val();
            const selected_month = $('#month_id').val();
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
                        $('#date1').val(BS2AD(data.start_date_np));
                        $('#date2').val(BS2AD(data.end_date_np))
                    }
                }
            });
        });

        $('#month_id').trigger('change');
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
        var staffs = new Array();

        function onChangeBranchIdForStaff() {
            branch = $('#branch_id').val();
            if (branch !== '') {


                $.ajax({
                    url: '{{route('get-staff-by-branch')}}',
                    type: 'post',
                    data: {
                        'branch': branch,
                        '_token': '{{csrf_token()}}'
                    },
                    success: function (data) {
                        //console.log(data);
                        staffs = data;
                        $('#staff').siblings('.selectize-control').remove();
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
                                    url: '{{ route('get-staff') }}?search=' + encodeURIComponent(query) + '&branch_id=' + branch,
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
                    }
                });
            } else {

            }
        }


        $(document).ready(onChangeBranchIdForStaff);

        $('#branch_id').change(onChangeBranchIdForStaff);
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

@endsection
