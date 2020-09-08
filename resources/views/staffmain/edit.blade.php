@extends('layouts.default', ['crumbroute' => 'staffedit'])
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
    <style>
        #editimage {
            margin-left: -25px;
        }

        .required-field {
            color: red;
        }

        .text-for-changing-working-branch {
            font-size: 10.8px;
        }

        .fa-file-alt {
            font-size: 40px;
        }
    </style>
    @include('staffmain.staff-edit-nav')
    <form action="{{ route('staff-main-update', $staffmain->id) }}" method="POST" id="staff-form"
          enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="row">
            <div class="col-md-7 col-sm-12">
                {{-- Basic Info --}}
                <div class="basic-info card">
                    <h5 class="card-header">Basic Information: {{$staffmain->name_eng}} -
                        [CID: {{$staffmain->staff_central_id}}] - [Branch
                        ID: {{$staffmain->main_id}} {{$staffmain->branch->office_name ?? ''}}]</h5>

                    <div class="card-block">
                        <div class="card-text">
                            {{--@role('Administrator')--}}
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="staff-central-id" class="col-form-label">Staff Central ID</label>
                                    {{ Form::number('staff_central_id', $staffmain->staff_central_id, array('class' => 'form-control', 'placeholder' => 'Staff Central ID', 'id'=>'staff-central-id'))  }}
                                </div>
                            </div>
                            {{--@endrole--}}
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="main_id" class="col-form-label">Branch ID <span
                                            class="required-field">*</span></label>
                                    {{ Form::number('main_id', $staffmain->main_id, array('class' => 'form-control', 'placeholder' => 'Staff Central ID',
                                         'data-validation' => 'required','data-validation-error-msg' => 'Please enter a Staff Central ID','id'=>'main-id'))  }}
                                </div>
                                <input type="hidden" name="branch_id" value="{{$staffmain->branch_id}}">

                                <div class="col-md-6 col-sm-12">
                                    <label for="name_eng" class="col-form-label">Full name<span
                                            class="required-field">*</span></label>

                                    {{ Form::text('name_eng', $staffmain->name_eng, array('class' => 'form-control', 'placeholder' => 'Input Full Name',
                                         'data-validation' => 'required','data-validation-error-msg' => 'Please enter a Full Name'))  }}
                                </div>

                            </div>
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="FName_Eng" class="col-form-label">Father name<span
                                            class="required-field">*</span></label>
                                    {{ Form::text('FName_Eng', $staffmain->FName_Eng, array('class' => 'form-control', 'placeholder' => 'Input Father Name',
                                    ))  }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="gfname_eng" class="col-form-label">Grand Father<span
                                            class="required-field">*</span></label>
                                    {{ Form::text('gfname_eng', $staffmain->gfname_eng, array('class' => 'form-control', 'placeholder' => 'Grand Father Name',
                                  ))  }}
                                </div>
                            </div>

                            <div class="form-group no-gutters two-fields row">

                                <label for="image" class="col-2 col-form-label">
                                    Staff Photo
                                    {{--<span class="badge badge-pill badge-danger">*</span>--}}
                                </label>
                                {{--<label for="image" class="col-2 col-form-label">Staff Image</label>--}}
                                <div class="col-8" id="editimage">
                                    Previous Image
                                    <br>
                                    <?php
                                    $image = asset('assets/images/user.png');
                                    if (!empty($staffmain->image)) {
                                        $image = asset("Images/$staffmain->image");
                                    }
                                    ?>
                                    <img src="{{ $image }}" height="150" width="150">

                                    {{--                                    <img src="{{ asset("Images/$staffmain->image") }}" height="150" width="150">--}}
                                    <br>
                                    <input type="file" class="form-control-file" id="image" aria-describedby="fileHelp"
                                           name="image" accept="image/*">
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="spname_eng" class="col-form-label">Spouse</label>
                                    {{ Form::text('spname_eng', $staffmain->spname_eng, array('class' => 'form-control', 'placeholder' => 'Spouse Name'))  }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="phone_number" class="col-form-label">Phone Number</label>
                                    {{ Form::text('phone_number', $staffmain->phone_number, array('class' => 'form-control','placeholder' => 'Enter Phone Number'
                                    ))  }}
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="phone_number" class="col-form-label">Emergency Contact Number</label>
                                    {{ Form::text('emergency_phone_number', $staffmain->emergency_phone_number, array('class' => 'form-control','placeholder' => 'Enter Emergency Contact Number'
                                    ))  }}
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="religion_id" class="col-form-label">Religion</label>
                                    {{ Form::select('religion_id', $religions, $staffmain->religion_id, array('placeholder' => 'Select One...', 'class' => 'input-sm' ))  }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="caste_id" class="col-form-label">Caste</label>
                                    {{ Form::select('caste_id', $castes, $staffmain->caste_id, array('placeholder' => 'Select One...'))  }}
                                </div>

                            </div>


                            {{--gender marital start--}}

                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="gender" class="col-form-label">Gender</label>
                                    <div class="radio-inline">
                                        <label class="custom-control custom-radio radio-inline-label">
                                            <input id="radio1" name="Gender" type="radio" class="custom-control-input"
                                                   value="1"
                                                {{ $staffmain->Gender=='1'?'checked':'' }} >
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Male</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input id="radio2" name="Gender" type="radio" class="custom-control-input"
                                                   value="2"
                                                {{ $staffmain->Gender=='2'?'checked':'' }} >
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Female</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input id="radio3" name="Gender" type="radio" class="custom-control-input"
                                                   value="3"
                                                {{ $staffmain->Gender=='3'?'checked':'' }} >
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Other</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="FName_Eng" class="col-form-label">Marital Status</label>
                                    <select id="marrid_stat" class="input-sm" name="marrid_stat">
                                        <option value="0" {{ $staffmain->marrid_stat == '0' ? 'selected' :'' }}>Single
                                        </option>
                                        <option value="1" {{ $staffmain->marrid_stat == '1' ? 'selected' :'' }}>Married
                                        </option>
                                    </select>
                                </div>
                            </div>
                            {{--gender marital end--}}.

                            {{--staff citizen ship--}}
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="staff_dob" class="col-form-label">Date Of Birth <span
                                            class="required-field">*</span></label>
                                    {{ Form::text('staff_dob', (!empty($staffmain->staff_dob) && $staffmain->staff_dob != '0000-00-00') ? (\App\Helpers\BSDateHelper::AdToBs('-',$staffmain->staff_dob)) : '' , array('data-validation' => 'required', 'class' => 'form-control nep-date','id'=>'nep-date11' , 'placeholder' => 'Enter Date Of Birth','readonly'
                                    ))  }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="staff_citizen_no" class="col-form-label">Citizen Number</label>
                                    {{ Form::text('staff_citizen_no', $staffmain->staff_citizen_no, array('class' => 'form-control', 'placeholder' => 'Enter Citizen Number'
                                    ))  }}
                                </div>

                            </div>
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="staff_citizen_issue_office" class="col-form-label">Issue Office</label>
                                    {{ Form::text('staff_citizen_issue_office', $staffmain->staff_citizen_issue_office, array('class' => 'form-control', 'placeholder' => 'Issue Office'
                                    ))  }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="staff_citizen_issue_date_np" class="col-form-label">Issue Date</label>
                                    {{ Form::text('staff_citizen_issue_date_np', $staffmain->staff_citizen_issue_date_np, array('class' => 'form-control nep-date','id'=>'nep-date10' , 'placeholder' => 'Enter Issue Date','readonly'
                                    ))  }}
                                </div>
                            </div>
                            {{--staff citizen end--}}
                            {{--district vdc start--}}

                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="add_dist" class="col-form-label">District name</label>
                                    <?php //dd($staffmain->district); ?>
                                    <select id="add_dist" name="add_dist" class="input-sm">
                                        <option value="">Select District</option>
                                        @foreach($districts as $district)
                                            <option
                                                @if( !empty($staffmain->district_id) && $staffmain->district->district_id == $district->district_id ) selected
                                                @endif value="{{$district->district_id}}">{{$district->district_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="FName_Eng" class="col-form-label">Vdc Name</label>
                                    <div id="vdc_mun_container">
                                    </div>
                                </div>
                            </div>

                            {{--ward tole start--}}
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="ward_no" class="col-form-label">Ward No</label>
                                    {{ Form::text('ward_no', $staffmain->ward_no, array('class' => 'form-control', 'placeholder' => 'Input Ward No ',
                                     'data-validation-error-msg' => 'Please enter a Ward No'))  }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="tole_basti" class="col-form-label">Tole /Basti</label>
                                    {{ Form::text('tole_basti', $staffmain->tole_basti, array('class' => 'form-control', 'placeholder' => 'Input Tole Basti ',
                             'data-validation-error-msg' => 'Please enter a Tole Basti'))  }}
                                </div>
                            </div>
                            {{--ward tole end--}}

                            <div class="form-group row">
                                <label for="file_upload" class="col-12 col-form-label">
                                    File
                                </label>
                                <div class="col-9">
                                    <div class="row">
                                        @foreach($staffmain->staffFiles->where('file_type_id',null) as $staffFile)
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <div>
                                                        <a href="{{route('staff-file-download',$staffFile->file_name)}}">
                                                            <i class="fas fa-file-alt"></i>
                                                        </a>
                                                    </div>
                                                    <p> {{$staffFile->file_name}}</p>
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger remove-file"
                                                            data-id="{{$staffFile->id}}"><i
                                                            class="far fa-trash-alt"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
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

        $('.remove-file').click(function () {
            $this = $(this);
            let id = ($this.data('id'));
            $.ajax({
                url: '{{route('staff-file-remove')}}',
                type: 'POST',
                data: {
                    '_token': '{{csrf_token()}}',
                    'id': id
                },
                success: function (data) {
                    if (data) {
                        $this.parent().parent().remove();
                    }
                }
            })
        })
    </script>

    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>

        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 80 // Options | Number of years to show
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            //toggle default allowances on checkbox tick untick
            $('.toggle-default-allowance').on('change', function () {
                _this = $(this);
                const textField = _this.parent().find('.allowance-field');
                textField.attr('readonly', false);
                if (_this.prop("checked")) { //on checked put default value from data attribute to input box
                    textField.val(textField.data('default'));
                    textField.attr('readonly', true);
                }
            });
            $('.toggle-default-allowance').trigger('change');

            //
            $('#post_id').on('change', function () {
                var post_id = $(this).val();
                $.ajax({
                    url: "{{ route('staff-main-salary-show') }}",
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
            $('#post_id').trigger('change');

        });
    </script>
    <script>

        $(function () {
            $('#add_dist').on('change', function () {
                var distId = $(this).val();
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
                                var selected = '';
                                if (key == '{{ $staffmain->district_id  }}') {
                                    selected = 'selected';
                                }
                                $('#vdc-mun').append('<option ' + selected + ' value="' + key + '">' + value + '</option>');
                            });
                            // $('#vdc-mun').selectize();
                        }
                    });
                } else {
                    $('#vdc-mun').remove();
                }
            });
            $('#add_dist').trigger('change');
        });
    </script>
    <script>

        $('#branch_id').ready(function () {
            $('#branch_id').trigger('change');
        });

        $('#branch_id').change(function () {
            branch = $(this).val();

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
                    $('#shift').after('   <div class="col-md-8 shift_container"><input type="text" id="shift_id" name="shift_id" class="input-sm" \n' +
                        '                                   ></div>')
                    $('#shift_id').prop('disabled', false);
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

                        },

                    });
                    var selectize = $select[0].selectize;
                    selectize.setValue($('#shift').attr('data-current-shift-id'), false);
                }
            });
        });

        $('.cash_payment').change(function () {
            if ($(this).is(':checked')) {
                $('#pay_type')[0].selectize.disable();
                $('#acc_no').prop('disabled', true);
            } else {
                $('#pay_type')[0].selectize.enable();
                $('#acc_no').prop('disabled', false);
            }
        });

        $(document).ready(function () {
            $('.cash_payment').trigger('change');
        });
    </script>

@endsection
