@extends('layouts.default', ['crumbroute' => 'staffcreate'])
@section('title', $title)
@section('content')
    <link rel="stylesheet" href="{{asset('assets/css/uploadfiledropzone.css')}}">
    <style>
        .mydrop {
            width: 305px;
            margin-left: -49px;
        }

        .required-field {
            color: red;
        }
    </style>

    <form method="post" action="{{ route('staff-main-save') }}" id="staff-form" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{csrf_token()}}">

        <div class="row">
            <div class="col-xl-7 col-md-12 col-sm-12">
                {{-- Basic Info --}}
                <div class="basic-info card">
                    <h5 class="card-header">Basic Information</h5>

                    <div class="card-block">
                        <div class="card-text">
                            @role('Administrator')
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="staff-central-id" class="col-form-label">Staff Central ID <span
                                            class="required-field">*</span></label>
                                    {{ Form::number('staff_central_id', null, array('class' => 'form-control', 'placeholder' => 'Staff Central ID','id'=>'staff-central-id'))  }}
                                </div>
                            </div>
                            @endrole

                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="staff_type" class="col-form-label">
                                        Staff Type<span class="required-field">*</span>
                                    </label>
                                    {{ Form::select('staff_type', $staff_types,  null, array('placeholder' => 'Select One...','id'=>'staff-type', 'data-validation' => 'required'))  }}

                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="main_id" class="col-form-label">Branch ID <span
                                            class="required-field">*</span></label>
                                    {{ Form::number('main_id', $new_branch_id ?? null, array('class' => 'form-control', 'placeholder' => 'Branch ID',
                                         'data-validation' => 'required','data-validation-error-msg' => 'Please enter a Branch ID','id'=>'main-id'))  }}
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="name_eng" class="col-form-label">Full name <span class="required-field">*</span></label>
                                    {{ Form::text('name_eng', null, array('class' => 'form-control', 'placeholder' => 'Input Full Name',
                                         'data-validation' => 'required','data-validation-error-msg' => 'Please enter a Full Name'))  }}
                                </div>

                            </div>
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="FName_Eng" class="col-form-label">Father name</label>
                                    {{ Form::text('FName_Eng', null, array('class' => 'form-control', 'placeholder' => 'Input Father Name','data-validation-error-msg' => 'Please enter a Father Name'))  }}
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="gfname_eng" class="col-form-label">Grand Father</label>
                                    {{ Form::text('gfname_eng', null, array('class' => 'form-control', 'placeholder' => 'Grand Father Name',
                                    'data-validation-error-msg' => 'Please enter a GrandFather Name'))  }}
                                </div>
                            </div>
                            <div class="row no-gutters two-fields">

                                <div class="col-md-6 col-sm-12">
                                    <label for="image" class="col-form-label">
                                        Staff Photo
                                    </label>
                                    <input type="file" class="form-control" id="image" aria-describedby="fileHelp"
                                           name="image" accept="image/*">
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="spname_eng" class="col-form-label">Spouse</label>
                                    {{ Form::text('spname_eng', null, array('class' => 'form-control', 'placeholder' => 'Spouse Name'))  }}
                                </div>
                            </div>
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="gender" class="col-form-label">Gender <span
                                            class="required-field">*</span></label>
                                    <div class="radio-inline">
                                        <label class="custom-control custom-radio radio-inline-label">
                                            <input id="radio1" name="Gender" type="radio" class="custom-control-input"
                                                   value="1" checked>
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Male</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input id="radio2" name="Gender" type="radio" class="custom-control-input"
                                                   value="2">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Female</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input id="radio2" name="Gender" type="radio" class="custom-control-input"
                                                   value="3">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Other</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="marrid_stat" class="col-form-label">Marital Status <span
                                            class="required-field">*</span></label>
                                    {{ Form::select('marrid_stat', array('0' => 'Single', '1' => 'Married'), '1') }}
                                </div>
                            </div>


                            {{--staff citizen ship--}}
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="staff_dob" class="col-form-label">Date Of Birth <span
                                            class="required-field">*</span></label>
                                    {{ Form::text('staff_dob', null, array('class' => 'form-control nep-date','id'=>'nep-date11','placeholder' => 'Enter Date Of Birth','readonly','data-validation'=>'required','data-validation-error-msg' => 'Please enter Date of Birth'
                                    ))  }}
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="phone_number" class="col-form-label">Phone Number</label>
                                    {{ Form::text('phone_number', null, array('class' => 'form-control','placeholder' => 'Enter Phone Number'
                                    ))  }}
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="phone_number" class="col-form-label">Emergency Contact Number</label>
                                    {{ Form::text('emergency_phone_number', null, array('class' => 'form-control','placeholder' => 'Enter Emergency Contact Number'
                                    ))  }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="religion_id" class="col-form-label">Religion</label>
                                    {{ Form::select('religion_id', $religions, null, array('placeholder' => 'Select One...' ))  }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="caste_id" class="col-form-label">Caste</label>
                                    {{ Form::select('caste_id', $castes, null, array('placeholder' => 'Select One...'))  }}
                                </div>
                            </div>
                            <strong>Job Information</strong>
                            <hr>

                            <div class="row no-gutters two-fields">


                                <div class="col-md-6 col-sm-12">
                                    <label for="edu_id" class="col-form-label">
                                        Branch<span
                                            class="required-field">*</span>
                                    </label>
                                    {{ Form::select('branch_id', $offices,  Auth::user()->branch_id ?? '', array( 'data-validation' => 'required','id'=>'branch_id'))  }}

                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="shift" id="shift" class="col-form-label">
                                        Shift<span class="required-field">*</span>
                                    </label>


                                    <input type="text" id="shift_id" name="shift_id" class="input-sm"
                                           data-validation="required" value="{{old('shift_id')}}"
                                           placeholder="Please Select Branch First"
                                           disabled>

                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="section" class="col-form-label">Section</label>
                                    {{ Form::select('section', $sections, null, array('placeholder' => 'Select One...'))  }}
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="section" class="col-form-label">
                                        Department
                                    </label>
                                    {{ Form::select('department', $departments, null, array('placeholder' => 'Select One...'))  }}
                                </div>


                                <div class="col-md-6 col-sm-12">
                                    <label for="post_id" class="col-form-label">
                                        Post<span class="required-field">*</span>
                                    </label>
                                    {{ Form::select('post_id', $posts, null, array('placeholder' => 'Select One...', 'data-validation' => 'required'))  }}
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="post_id" class="col-form-label">
                                        Job Type<span class="required-field">*</span>
                                    </label>
                                    {{ Form::select('jobtype_id', $job_types, null, array('placeholder' => 'Select One...','id' => 'jobtype_id', 'data-validation' => 'required'))  }}
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="edu_id" class="col-form-label">
                                        Education
                                    </label>
                                    {{ Form::select('edu_id', $educations, null, array('placeholder' => 'Select One...'))  }}
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="edu_id" class="col-form-label">
                                        Weekend<span class="required-field">*</span>
                                    </label>
                                    {{ Form::select('weekend_day', $weekend_days,  null, array('placeholder' => 'Select One...', 'data-validation' => 'required'))  }}

                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="appo_date_np" class="col-form-label">Appointment Date <span
                                            class="required-field">*</span></label>
                                    {{ Form::text('appo_date_np', null, array('class' => 'form-control nep-date','id'=>'appo_date' , 'placeholder' => 'Enter Appointment Date','readonly', 'data-validation' => 'required'))  }}
                                </div>

                                {{--  Display basic salary only for bbsm and if the job type is con and con1--}}
                                @if(strcasecmp($organizationSetup->organization_code,'bbsm')==0)
                                    <div class="col-md-6 col-sm-12">
                                        <div class="basic-salary-div" style="display: none;">
                                            <label for="basic_salary" class="col-form-label">Basic Salary<span
                                                    class="required-field">*</span></label>
                                            {{ Form::number('basic_salary', null, array('class' => 'form-control positive-integer-number','id'=>'basic_salary' , 'placeholder' => 'Enter Basic Salary','data-validation' => 'required', 'step'=>1))  }}
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <strong>Citizenship Details</strong>
                            <hr>

                            <div class="row no-gutters two-fields">

                                <div class="col-md-6 col-sm-12">
                                    <label for="staff_citizen_no" class="col-form-label">Citizen Number</label>
                                    {{ Form::text('staff_citizen_no', null, array('class' => 'form-control', 'placeholder' => 'Enter Citizen Number'
                                    ))  }}
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="staff_citizen_issue_office" class="col-form-label">Issue Office</label>
                                    {{ Form::text('staff_citizen_issue_office', null, array('class' => 'form-control', 'placeholder' => 'Issue Office'
                                    ))  }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="staff_citizen_issue_date_np" class="col-form-label">Issue Date</label>
                                    {{ Form::text('staff_citizen_issue_date_np', null, array('class' => 'form-control nep-date','id'=>'nep-date10' , 'placeholder' => 'Enter Issue Date','readonly'))  }}
                                </div>
                            </div>
                            <hr>
                            {{--staff citizen end--}}

                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="add_dist" class="col-form-label">District name</label>
                                    <select id="add_dist" name="add_dist" class="input-sm">
                                        <option value="">Select District</option>
                                        @foreach($districts as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="FName_Eng" class="col-form-label">Vdc Name</label>
                                    <div id="vdc_mun_container">

                                    </div>
                                </div>
                            </div>


                            {{--tole start--}}
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="gender" class="col-form-label">Ward No</label>
                                    {{ Form::number('ward_no', null, array('class' => 'form-control', 'placeholder' => 'Input Ward No ','data-validation-error-msg' => 'Please enter a Ward No'))  }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="FName_Eng" class="col-form-label">Tole /Basti</label>
                                    {{ Form::text('tole_basti', null, array('class' => 'form-control', 'placeholder' => 'Input Place Holder ',
                                               'data-validation-error-msg' => 'Please enter a Tole Basti'))  }}
                                </div>
                            </div>
                            {{--tole end--}}
                            <div class="row no-gutters two-fields">
                                <div class="col-md-12">
                                    <label for="file_upload" class="col-form-label">
                                        File
                                    </label>
                                    <div class="upload-file" id="upload-file">
                                        <div class="fallback">
                                            <input type="file" name="file">
                                        </div>
                                    </div>
                                </div>
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





    {{--{{ Form::close()  }}--}}
@endsection
@section('script')
    <script src="{{asset('assets/js/dropzone.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/css/dropzone.css')}}">

    <script>
        $("#upload-file").dropzone({
            url: '{{route('staff-file-upload')}}',
            maxFiles: 100,
            paramName: 'track',
            _token: '{{csrf_token()}}',
            // acceptedFiles: 'audio/*',
            addRemoveLinks: true,
            dictDefaultMessage: "Upload Your File Here",
            sending: function (file, xhr, formData) {
                // Pass token. You can use the same method to pass any other values as well such as a id to associate the image with for example.
                formData.append("_token", '{{csrf_token()}}'); // Laravel expect the token post value to be named _token by default
            },
            success: function (file, response) {
                $('#staff-form').append('<input type="hidden" name="upload[]" multiple value="' + response + '">');
                $(file._removeLink).attr('filename', response);
            },
            removedfile: function (file) {
                fileValue = $(file._removeLink).attr('filename');
                console.log(fileValue);
                $('input[value="' + fileValue + '"]').remove();
                var _ref;
                return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
            }, headers: {
                'X-CSRFToken': $('meta[name="token"]').attr('content')
            }
        });
    </script>

    {{--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>--}}
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>

        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 80 // Options | Number of years to show
        });
    </script>
    <script>
        //        var salary=2500;
        $('#post_id').on('change', function () {
            var post_id = $(this).val();
//                $('#basic_salary').val(post_id);
//                $('#basic_salary').val(basic_salary);
//                console.log(post_id);
            $.ajax({
                url: '{{ route('staff-main-salary-show') }}',
                type: "POST",
                dataType: "json",
                data: {'id': post_id, 'csrf_token': '{{ csrf_token() }}'},
                success: function (data) {
                    console.log(data);
                    $('#basic_salary').val(data[0]);
//                  $('#basic_salary').html(data);
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#add_dist').on('change', function () {
//                alert("working ajax code");
                var distId = $(this).val();
//                alert(distId);
                if (distId) {
                    $.ajax({
                        url: '{{ route('staff-main-vdc-show') }}',
                        type: "POST",
                        dataType: "json",
                        data: {'id': distId, '_token': '{{ csrf_token() }}'},
                        success: function (data) {
                            $('#vdc_mun_container').html('');
                            var selectWrapper = '<select name="show_vdc" id="vdc-mun" style="width: 45%;"></select>';
                            $('#vdc_mun_container').append(selectWrapper);
                            $.each(data, function (key, value) {
                                $('#vdc-mun').append('<option value="' + key + '">' + value + '</option>');
                            });
                            /*$('#vdc-mun').selectize({
                                plugins: {
                                    'no-delete': {}
                                },
                            });*/
                        }
                    });
                } else {
                    $('#vdc-mun').remove();
                }
            });

            //toggle default allowances on checkbox tick untick
            $('.toggle-default-allowance').on('change', function () {
                _this = $(this);
                const textField = _this.parent().find('.allowance-field');
                textField.val(textField.data('default'));
                textField.attr('readonly', false);
                if (_this.prop("checked")) { //on checked put default value from data attribute to input box
                    textField.val(textField.data('default'));
                    textField.attr('readonly', true);
                }
            });
            $('.toggle-default-allowance').trigger('change');
        });
    </script>

    <script>

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
                    $('#shift_id').next().remove();
                    $('#shift_id').remove();
                    $('.removeit').remove();
                    $('.shift_container').remove();
                    $('#shift').after('   <input type="text" id="shift_id" name="shift_id" class="input-sm" data-validation="required" \n' +
                        '                                   >');
                    $('#shift_id').prop('disabled', false);
                    $('#shift_id').selectize({
                        valueField: 'id',
                        labelField: 'shift_name',
                        searchField: 'shift_name',
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

                    selectize.setValue('{{old('shift_id')}}', false);
                }
            });
        }

        $(document).ready(function () {
            onBranchChangeForShift();
        })

        $('#branch_id').change(onBranchChangeForShift);


        $('.cash_payment').change(function () {
            if ($(this).is(':checked')) {
                $('#pay_type')[0].selectize.disable();
                $('#acc_no').prop('disabled', true);
            } else {
                $('#pay_type')[0].selectize.enable();
                $('#acc_no').prop('disabled', false);
            }
        });

        $('#staff-type').change(function () {
            let staff_type = $(this).val();
            let branch_id = $('#branch_id').val();
            $.ajax({
                url: '{{route('last-main-id-of-branch')}}',
                type: 'POST',
                data: {
                    'staff_type': staff_type,
                    'branch_id': branch_id,
                    '_token': '{{csrf_token()}}'
                }, success: function (data) {
                    $('#main-id').val(data);

                }
            });
        })


        let jobTypeIdsForDisplayingBasicSalary = <?php echo json_encode($jobTypeIdsForDisplayingBasicSalary) ?>;

        $('#jobtype_id').change(function () {
            let $_jobtype_id = parseInt($(this).val());
            $('.basic-salary-div').hide();
            if (jobTypeIdsForDisplayingBasicSalary.includes($_jobtype_id)) {
                $('.basic-salary-div').show();
            }
            // if($(this).val());
        });

    </script>
    <?php /*
    <script>
         $('#main-id').blur(function (e) {
            e.preventDefault();
            setTimeout(
                function () {
                    $.ajax({
                        url: '{{route('check-main-id-unique')}}',
                        type: 'POST',
                        data: {
                            '_token': '{{csrf_token()}}',
                            'main_id': $('#main-id').val()
                        },
                        success: function (data) {
                            if(!data['valid'])
                            {
                                $('#main-id').parent().removeClass('has-success');
                                $('#main-id').parent().addClass('has-error');
                                $('#main-id').remove('.unique-staff');
                                console.log($('#main-id').siblings('input'));
                                $('#main-id').siblings('input').removeClass('valid');
                                $('#main-id').parent().append('<span class="help-block form-error unique-staff">Staff Central Id Already Taken</span>');

                            }
                        }
                    })
                }, 2000);
        })
    </script>
    */?>


@endsection
