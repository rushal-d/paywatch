@extends('layouts.default', ['crumbroute' => 'staffedit'])
@section('title', $title)
@section('content')
    <style>
        .mydrop {
            width: 305px;
            margin-left: -49px;
        }

        .required-field {
            color: red;
        }

        .erase-permanent_date {
            cursor: pointer;
        }
    </style>
    @include('staffmain.staff-edit-nav')

    <form method="post" action="{{ route('staff-job-information-store',$staffmain->id) }}" id="job-information-form">
        <input type="hidden" name="_token" value="{{csrf_token()}}">

        <div class="row">
            <div class="col-md-9 col-sm-12">

                <div class="basic-info card">
                    <h5 class="card-header">Job Information : {{$staffmain->name_eng}} -
                        [CID: {{$staffmain->staff_central_id}}] - [Branch
                        ID: {{$staffmain->main_id}} {{$staffmain->branch->office_name ?? ''}}]</h5>
                    <div class="card-block">
                        <div class="card-text">
                            <div class="row">
                                <label for="staff_type" class="col-2 col-form-label">
                                    Staff Type<span class="required-field">*</span>
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    {{ Form::text('staff_type', $staffmain->staff_type ?? null, array('placeholder' => 'Select One...' , 'required' => 'required', 'id' => 'staff_type'))  }}
                                </div>
                                <label for="edu_id" class="col-2 col-form-label">
                                    Education
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    {{Form::select('edu_id',$educations,$staffmain->edu_id ?? null,['class'=>'imput-sm','id'=>'edu_id','placeholder'=>'Select Education'])}}
                                </div>

                                <label for="jobtype_id" class="col-2 col-form-label">
                                    Job Type<span class="required-field">*</span>
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    {{Form::select('jobtype_id',$jobtypes,$staffmain->jobtype_id ?? null,['class'=>'imput-sm','id'=>'jobtype_id','placeholder'=>'Select JobType','required'])}}
                                </div>

                                <label for="appo_date" class="col-2 col-form-label">
                                    Appointment Date<span class="required-field">*</span>
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    {{ Form::text('appo_date_np', $staffmain->appo_date_np ?? null, array('class' => 'form-control nep-date' ,'id'=>'appo_date_np','data-validation' => 'required noFutureDate','placeholder' => 'Input Appoinment Date','readonly')) }}
                                </div>

                                <label for="appo_office" class="col-2 col-form-label">
                                    Appointment Office
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    {{Form::select('appo_office',$offices,$staffmain->appo_office ?? null,['class'=>'imput-sm','id'=>'appo_office','placeholder'=>'Select Office','required'])}}

                                </div>

                                <label for="appo_date" class="col-2 col-form-label">
                                    Temp/Contract Date
                                    @if($organization->organization_code=='BBSM')
                                        <span class="required-field">*</span>@endif
                                </label>
                                <div class="col-md-3 col-sm-3 form-group">
                                    {{ Form::text('temporary_con_date_np', $staffmain->temporary_con_date_np ?? null, array('class' => 'form-control nep-date' ,'id'=>'temporary_con_date_np','data-validation' => ($organization->organization_code==='BBSM')?'required noFutureDate tempContractDate':'tempContractDate noFutureDate','placeholder' => 'Input Temp/ Contract Date','readonly')) }}
                                </div>
                                <div class="col-md-1 col-sm-1 form-group">
                                    <i class="fas fa-backspace erase-temp_date"></i>
                                </div>
                                <label for="branch_id" class="col-2 col-form-label">
                                    Working Branch
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    <p>{{$staffmain->branch->office_name ?? ''}} <span>(Payroll Branch: {{$staffmain->payrollBranch->office_name ?? ''}})</span>
                                    </p>
                                    <input type="hidden" id="branch_id" value="{{$staffmain->branch_id ?? null}}">
                                    <a href="{{route('staff-transfer-create', ['staff_central_id' => $staffmain->id])}}"
                                       target="_blank">Click here to transfer the staff and link to staff
                                        transfer</a>

                                </div>

                                <label for="permanent_date_np" class="col-2 col-form-label">
                                    Permanent Date
                                </label>
                                <div class="col-md-3 col-sm-3 form-group">
                                    {{ Form::text('permanent_date_np', $staffmain->permanent_date_np ?? null, array('class' => 'form-control nep-date' ,'id'=>'permanent_date_np','placeholder' => 'Input Permanent Date','data-validation'=>'checkPermanent checkwithtempdate noFutureDate','readonly')) }}
                                </div>
                                <div class="col-md-1 col-sm-1 form-group">
                                    <i class="fas fa-backspace erase-permanent_date"></i>
                                </div>


                                <label for="post_id" class="col-2 col-form-label">
                                    Position / Designation<span class="required-field">*</span>
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    {{Form::select('post_id',$posts,$staffmain->post_id ?? null,['class'=>'imput-sm','id'=>'post_id','placeholder'=>'Select Position','required'])}}
                                </div>

                                <label for="section" class="col-2 col-form-label">
                                    Department
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    {{ Form::select('department', $departments, $staffmain->department ?? null, array('placeholder' => 'Select One...'))  }}
                                </div>

                                <label for="section" class="col-2 col-form-label">
                                    Section
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    {{ Form::select('section', $sections, $staffmain->section ?? null, array('placeholder' => 'Select One...'))  }}
                                </div>

                                <label for="shift" id="shift" class="col-2 col-form-label">
                                    Shift<span class="required-field">*</span>
                                </label>
                                <div class="col-md-4 col-sm-4 shift_container form-group">
                                    <select name="shift_id" id="shift_id" required>
                                        @foreach($shifts as $shift)
                                            <option value="{{$shift->id}}">
                                                <div class="suggestions">
                                                    <div> Shift Name: {{$shift->shift_name}}</div>
                                                    <div> Shift Time: {{$shift->shift_name}}</div>
                                                </div>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if(strcasecmp($organization->organization_code,'bbsm')==0)
                                    <label for="staff_status" class="col-2 col-form-label">
                                        Manual Attendance
                                    </label>
                                    <div class="col-md-4 col-sm-4 form-group">
                                        {{ Form::select('manual_attendance_enable', array('1' => 'Yes', '0' => 'No'), $staffmain->manual_attendance_enable) }}
                                    </div>

                                    <label for="staff_status" class="col-2 col-form-label">
                                        Holding Staff
                                    </label>
                                    <div class="col-md-4 col-sm-4 form-group">
                                        {{ Form::select('is_holding', array('1' => 'Yes', '0' => 'No'), $staffmain->is_holding) }}
                                    </div>
                                @else
                                    <label for="staff_status" class="col-2 col-form-label">
                                        Overtime Payable
                                    </label>
                                    <div class="col-md-4 col-sm-4 form-group">
                                        {{ Form::select('is_overtime_payable', array('1' => 'Yes', '0' => 'No'), $staffmain->is_overtime_payable) }}
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="text-right form-control">
                            {{ Form::submit('Save',array('class'=>'btn btn-success btn-lg'))}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
@section('script')

    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        $('.erase-permanent_date').click(function ($query) {
            $('#permanent_date_np').val('');
        })

        $('.erase-temp_date').click(function ($query) {
            $('#temporary_con_date_np').val('');
        })
        $.formUtils.addValidator({
            name: 'checkPermanent',
            validatorFunction: function (value, $el, config, language, $form) {
                if (value == '') {
                    let jobtype = $('#jobtype_id').val().toString();
                    return (jobtype != '{{$permanent_job_type_id}}');
                }
                return true;
            },
            errorMessage: 'Permanent Date Must Be Selected if Job Type Permanent',
            errorMessageKey: 'badEvenNumber'
        });
        $.formUtils.addValidator({
            name: 'checkwithtempdate',
            validatorFunction: function (value, $el, config, language, $form) {
                let temp_date_np = $('#temporary_con_date_np').val();
                if (temp_date_np != '' && value != '') {
                    let temp_date_en = BS2AD(temp_date_np);
                    let permanent_date_en = BS2AD(value);
                    console.log(temp_date_en, permanent_date_en);
                    if (permanent_date_en < temp_date_en) {
                        return false;
                    }
                }
                return true;
            },
            errorMessage: "Temp/Contract Date is before the appointment date!",
            errorMessageKey: ''
        });

        $.formUtils.addValidator({
            name: 'tempContractDate',
            validatorFunction: function (value, $el, config, language, $form) {
                let appo_date_np = $('#appo_date_np').val();
                if (appo_date_np != '' && value != '') {
                    let appo_date_en = BS2AD(appo_date_np);
                    let temp_date_en = BS2AD(value);
                    if (temp_date_en < appo_date_en) {
                        return false;
                    }
                }
                return true;
            },
            errorMessage: "Temp/Contract Date is before the appointment date!",
            errorMessageKey: ''
        });

        $.formUtils.addValidator({
            name: 'noFutureDate',
            validatorFunction: function (value, $el, config, language, $form) {
                if (value != '') {
                    let inputDate = BS2AD(value);

                    var CurrentDate = new Date();
                    inputDate = new Date(inputDate)
                    if (inputDate > CurrentDate) {
                        return false;
                    }
                }

                return true;
            },
            errorMessage: "Date can not be greater than today date.",
            errorMessageKey: ''
        });

        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 50 // Options | Number of years to show
        });

        function onBranchChangeForShift() {
            branch = $('#branch_id').val();


            $.ajax({
                url: '{{route('get-shift-by-branch')}}',
                type: 'post',
                data: {
                    'branch': branch,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data) {
                    let shifts = data;
                    $('#shift_id').remove();
                    $('.removeit').remove();
                    $('.shift_container').remove();
                    $('#shift').after('   <div class="col-md-4 shift_container"><input type="text" id="shift_id" name="shift_id" class="input-sm" required \n' +
                        '                                   ></div>');
                    $('#shift_id').prop('disabled', false);
                    $('#shift_id').selectize({
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
                                    '<div> Shift Name: ' + item.original_name + '</div>' +
                                    '<div> Shift Time: ' + item.shift_name + '</div>' +
                                    '<div> ID: ' + item.id + '</div>' +
                                    '<div> Active: ' + status + '</div>' +
                                    '</div>';
                            }
                        },
                        load: function (query, callback) {

                        }
                    });
                    console.log($('#staff').next().next().addClass('removeit'));

                    var $select = $('#shift_id').selectize();  // This initializes the selectize control
                    var selectize = $select[0].selectize; // This stores the selectize object to a variable (with name 'selectize')

                    selectize.setValue('{{$staffmain->shift_id}}', false);
                }
            });
        }

        $('#branch_id').change(onBranchChangeForShift);
        $(document).ready(function () {
            onBranchChangeForShift();
        })
    </script>

    <script>
        let staff_type_options = <?php echo $staffTypes ?>;

        $('#staff_type').selectize({
            valueField: 'staff_type_code',
            labelField: 'staff_type_title',
            options: staff_type_options,
            maxItems: 1,
            render: {
                option: function (item, escape) {
                    return '<div class="suggestions"><div> Title: ' + item.staff_type_title + '</div>' +
                        '<div> Code: ' + item.staff_type_code + '</div>';
                }
            },
            searchField: ['staff_type_title', 'staff_type_code']
        });
    </script>
@endsection
