@extends('layouts.default', ['crumbroute' => 'force-entry-attendance-edit'])
@section('title', $title)
@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .staff_container {
            width: 72%;
        }

        .selectize-control.input-sm.single {
            width: 100%;
        }

        #overlay img {
            position: absolute;
            margin-left: auto;
            margin-right: auto;
            left: 0;
            right: 0;
            z-index: 99999;
        }

        #overlay {
            display: none;
            position: fixed;
            height: 100vh;
            overflow: hidden;
            width: 100%;
            z-index: 5000;
            top: 0;
            left: 0;
            text-align: center;
            color: #fff;
            padding-top: 25%;
            opacity: 1;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection
@section('content')
    <div id="overlay"><img src="{{asset('assets/gif/wait.gif')}}" alt="Be patient..."/></div>


    {{ Form::open(['route' => ['localattendance-store']])  }}

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Edit Local Attendance</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch<span class="required-field">*</span>
                            </label>
                            {!! Form::select('branch_id', $branches , $localAttendance->branch_id,array('id'=>'branch_id','placeholder'=>'Select a Branch','required'=>'required','data-validation' => 'required',
                                     'data-validation-error-msg' => 'Please Select Branch') ) !!}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label" id="staff">
                                Staff Name<span class="required-field">*</span>
                            </label>
                            <input type="text" id="staff_central_id" name="staff_central_id" class="input-sm"
                                   {{--                                   value="{{$staff_central_id ?? null}}"--}}
                                   disabled>
                        </div>

                        <div class="form-group row">
                            <label for="attendance_date_np" class="col-3 col-form-label">
                                Attendance Date<span class="required-field">*</span>
                            </label>
                            {{ Form::text('attendance_date_np', \App\Helpers\BSDateHelper::AdToBs('-',date('Y-m-d',strtotime($localAttendance->punchin_datetime)))?? null, ['class' => 'form-control nep-date','required' => 'true', 'readonly'  => 'readonly','id'=>'attendance_date_np' , 'placeholder' => 'Enter Date:']) }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Attendance Date<span class="required-field">*</span>
                            </label>
                            {{ Form::text('attendance_date', $_GET['attendance_date'] ?? null, array('class' => 'date form-control','id' => 'attendance_date', 'placeholder' => 'Date From','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                                     'data-validation-error-msg' => 'Please enter date from' ))  }}
                        </div>

                        <div class="form-group row">
                            <label for="punchin_datetime_np" class="col-3 col-form-label">
                                Punch In Time<span class="required-field">*</span>
                            </label>
                            {{ Form::time('punchin_datetime_np', $localAttendance->punchin_datetime ? date('H:i',strtotime($localAttendance->punchin_datetime)) : null, ['class' => 'form-control','required' => 'true','id'=>'punchin_datetime_np' , 'placeholder' => 'Enter Punch In Time:']) }}
                        </div>

                        <div class="form-group row">
                            <label for="tiffinout_datetime_np" class="col-3 col-form-label">
                                Tiffin Out Time
                            </label>
                            {{ Form::time('tiffinout_datetime_np', $localAttendance->tiffinout_datetime ? date('H:i',strtotime($localAttendance->tiffinout_datetime)) : null, ['class' => 'form-control', 'id'=>'tiffinout_datetime_np' , 'placeholder' => 'Enter Tiffin OutTime:']) }}
                        </div>

                        <div class="form-group row">
                            <label for="tiffinin_datetime_np" class="col-3 col-form-label">
                                Tiffin In Time
                            </label>
                            {{ Form::time('tiffinin_datetime_np', $localAttendance->tiffinin_datetime ? date('H:i',strtotime($localAttendance->tiffinin_datetime)) : null, ['class' => 'form-control', 'id'=>'tiffinin_datetime_np' , 'placeholder' => 'Enter Tiffin In Time:']) }}
                        </div>

                        <div class="form-group row">
                            <label for="personalout_datetime_np" class="col-3 col-form-label">
                                Personal Out Time
                            </label>
                            {{ Form::time('personalout_datetime_np', $localAttendance->personalout_datetime ? date('H:i',strtotime($localAttendance->personalout_datetime)) : null, ['class' => 'form-control', 'id'=>'personalout_datetime_np' , 'placeholder' => 'Enter Personal Out Time:']) }}
                        </div>

                        <div class="form-group row">
                            <label for="personalin_datetime_np" class="col-3 col-form-label">
                                Personal In Time
                            </label>
                            {{ Form::time('personalin_datetime_np', $localAttendance->personalin_datetime ? date('H:i',strtotime($localAttendance->personalin_datetime)) : null, ['class' => 'form-control', 'id'=>'personalin_datetime_np' , 'placeholder' => 'Enter Personal In Time:']) }}
                        </div>

                        <div class="form-group row">
                            <label for="lunchout_datetime_np" class="col-3 col-form-label">
                                Lunch Out Time
                            </label>
                            {{ Form::time('lunchout_datetime_np', $localAttendance->lunchout_datetime ? date('H:i',strtotime($localAttendance->lunchout_datetime)) : null, ['class' => 'form-control', 'id'=>'lunchout_datetime_np' , 'placeholder' => 'Enter Lunch Out Time:']) }}
                        </div>

                        <div class="form-group row">
                            <label for="lunchin_datetime_np" class="col-3 col-form-label">
                                Lunch In Time
                            </label>
                            {{ Form::time('lunchin_datetime_np', $localAttendance->lunchin_datetime ? date('H:i',strtotime($localAttendance->lunchin_datetime)) : null, ['class' => 'form-control', 'id'=>'lunchin_datetime_np' , 'placeholder' => 'Enter Lunch In Time:']) }}
                        </div>

                        <div class="form-group row">
                            <label for="punchout_datetime_np" class="col-3 col-form-label">
                                Punch Out Time
                            </label>
                            {{ Form::time('punchout_datetime_np', $localAttendance->punchout_datetime_np ? date('H:i',strtotime($localAttendance->punchout_datetime)) : null, ['class' => 'form-control', 'id'=>'punchout_datetime_np' , 'placeholder' => 'Enter Punch Out Time:']) }}
                        </div>

                        <div class="form-group row">
                            <label for="remarks" class="col-3 col-form-label">
                                Remarks
                            </label>
                            {{ Form::text('remarks', null, ['class' => 'form-control', 'id'=>'remarks' , 'placeholder' => 'Enter Remarks']) }}
                        </div>

                        <div class="form-group row">
                            <label for="remarks" class="col-3 col-form-label">
                                Previous Remarks
                            </label>
                            <div id="previous_remarks">

                                {!! $localAttendance->remarks !!}
                            </div>
                            <input type="hidden" name="previous_remarks" class="input_previous_remarks">

                        </div>
                    </div>
                </div>

                {{--  Save --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-right form-control">
                            {{ Form::submit('Save',['id' => 'submit-form', 'class'=>'btn btn-success btn-lg'])}}
                        </div>
                    </div>
                </div>
            </div>
            {{-- Right Sidebar  --}}
            <div class="col-md-5 col-sm-12">


            </div>
            {{-- End of sidebar --}}

        </div>
    </div>
    {{ Form::close()  }}
@endsection
@section('script')

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $('.time').flatpickr({
            enableTime: true,
            noCalendar: true,
            disableMobile: "true",
            dateFormat: "H:i",
            allowInput: true
        })
    </script>

    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>

    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#attendance_date').val(BS2AD($('#attendance_date_np').val()));
                getAjaxAttendanceDetailFromDateAndStaffCentralId();
            }
        });
        $('#attendance_date_np').val() ? $('#attendance_date').val(BS2AD($('#attendance_date_np').val())) : '';

    </script>

    <script>
        function getAjaxAttendanceDetailFromDateAndStaffCentralId() {
            $('#attendance_date').val() ? $('#attendance_date_np').val(AD2BS($('#attendance_date').val())) : '';
            let selected_staff_central_id = $('#staff_central_id').val();
            if (selected_staff_central_id !== '' && $('#attendance_date').val() !== '') {
                $.ajax({
                    url: '{{route('ajax-get-user-attendance-by-attendance-date-and-staff-central-id')}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        staff_central_id: selected_staff_central_id,
                        attendance_date: $('#attendance_date').val()
                    },
                    success: function (response) {
                        var data = response.data;
                        if (response.status === 'true') {
                            $('#punchin_datetime_np').val(data.punchin_datetime_np);
                            $('#tiffinout_datetime_np').val(data.tiffinout_datetime_np);
                            $('#tiffinin_datetime_np').val(data.tiffinin_datetime_np);
                            $('#personalout_datetime_np').val(data.personalout_datetime_np);
                            $('#personalin_datetime_np').val(data.personalin_datetime_np);
                            $('#lunchout_datetime_np').val(data.lunchout_datetime_np);
                            $('#lunchin_datetime_np').val(data.lunchin_datetime_np);
                            $('#punchout_datetime_np').val(data.punchout_datetime_np);
                            $('#previous_remarks').html(data.remarks);
                            $('.input_previous_remarks').val(data.remarks);

                        } else {
                            $('#punchin_datetime_np').val('');
                            $('#tiffinout_datetime_np').val('');
                            $('#tiffinin_datetime_np').val('');
                            $('#personalout_datetime_np').val('');
                            $('#personalin_datetime_np').val('');
                            $('#lunchout_datetime_np').val('');
                            $('#lunchin_datetime_np').val('');
                            $('#punchout_datetime_np').val('');
                            $('#previous_remarks').html('');
                            $('.input_previous_remarks').val('');

                        }
                    },
                    error: function (response) {

                    },
                    beforeSend: function () {
                        $('#overlay').show();
                        $('#submit-form').attr('disabled', 'true');
                    },
                    complete: function () {
                        $('#overlay').hide();
                        $('#submit-form').removeAttr('disabled');
                    }
                })
            } else {
                $('#punchin_datetime_np').val('');
                $('#tiffinout_datetime_np').val('');
                $('#tiffinin_datetime_np').val('');
                $('#personalout_datetime_np').val('');
                $('#personalin_datetime_np').val('');
                $('#lunchout_datetime_np').val('');
                $('#lunchin_datetime_np').val('');
                $('#punchout_datetime_np').val('');
            }
        }
    </script>

    <script>
        onChangeBranchId();

        function onChangeBranchId() {
            branch = $('#branch_id').val();
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
                    $('#staff_central_id').remove();
                    $('.removeit').remove();
                    $('.staff_container').remove();
                    $('#staff').after('   <div class="staff_container"><input type="text" required id="staff_central_id" name="staff_central_id" class="input-sm" \n' +
                        '                                   ></div>')
                    $('#staff_central_id').prop('disabled', false);
                    var $select = $('#staff_central_id').selectize({
                        valueField: 'id',
                        labelField: 'name_eng',
                        searchField: ['name_eng', 'main_id'],
                        options: staffs,
                        preload: true,
                        maxItems: 1,
                        plugins: ['remove_button'],
                        create: false,
                        render: {
                            option: function (item, escape) {
                                return '<div class="suggestions"><div> Name: ' + item.name_eng + '</div>' +
                                    '<div> Staff ID: ' + item.main_id + '</div>' +
                                    '<div> Father Name: ' + item.FName_Eng + '</div></div>';
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
                        },
                        onChange: function () {
                            getAjaxAttendanceDetailFromDateAndStaffCentralId();
                        }
                    });
                    var selectize = $select[0].selectize;
                    selectize.setValue('{{ $localAttendance->staff_central_id ?? null}}', false);
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $('.date').flatpickr({
            dateFormat: "Y-m-d",
            disableMobile: "true",
            onChange: function () {
                $('#attendance_date').val() ? $('#attendance_date_np').val(AD2BS($('#attendance_date').val())) : '';
                getAjaxAttendanceDetailFromDateAndStaffCentralId();
            }
        })
    </script>

    <script>
        var staffs = new Array();
        $('#branch_id').change(function () {
            onChangeBranchId();
            getAjaxAttendanceDetailFromDateAndStaffCentralId();
        });

    </script>


@endsection
