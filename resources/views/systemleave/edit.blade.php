@extends('layouts.default', ['crumbroute' => 'leaveedit'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => array('systemleaveupdate',$leave->leave_id), 'class' => 'educationform' ))  }}

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Leave Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="leave_name" class="col-3 col-form-label">
                                Leave Name
                            </label>
                            {{ Form::text('leave_name', $leave->leave_name, array('class' => 'form-control','id'=>'leave_code', 'placeholder' => 'Leave  Name',
                            'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a leave_name')) }}
                        </div>
                        <div class="form-group row">
                            <label for="leave_code" class="col-3 col-form-label">
                                Leave Code
                            </label>
                            {{ Form::text('leave_code', $leave->leave_code, array('class' => 'form-control', 'placeholder' => 'Leave  Leave Code',
                            'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a leave_code')) }}
                        </div>
                        <div class="form-group row">
                            <label for="max_days" class="col-3 col-form-label">
                                No. of Days
                            </label>
                            {{ Form::number('no_of_days', $leave->no_of_days, array('class' => 'form-control', 'placeholder' => 'Input No of Days',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter No. Of Days'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="max_days" class="col-3 col-form-label">
                                Maximum Days
                            </label>
                            {{ Form::number('max_days', $leave->max_days, array('class' => 'form-control', 'placeholder' => 'Leave  Maximum Days',
                            'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Maxium Days')) }}
                        </div>
                        <div class="form-group row">
                            <label for="max_days" class="col-3 col-form-label">
                                Req. Initial Setup
                            </label>
                            {{ Form::checkbox('initial_setup', 1,($leave->initial_setup==1)?'checked':'') }}
                        </div>

                        <div class="form-group row">
                            <label for="leave_type" class="col-3 col-form-label">
                                Job Types
                            </label>
                            {{ Form::select('job_type_id', $job_types,old('job_type_id',$leave->job_type_id), array('class' => 'form-control','placeholder'=>'Select Job Type'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="max_days" class="col-3 col-form-label">
                                Allow Negative Balance
                            </label>
                            {{ Form::checkbox('allow_negative', 1,($leave->allow_negative==1)) }}
                        </div>

                        @if($organization->organization_structure==2)
                            <div class="form-group row">
                                <label for="leave_type" class="col-3 col-form-label">
                                    Leave Type
                                </label>
                                {{ Form::select('leave_type', $leave_types,old('leave_type',$leave->leave_type), array('class' => 'form-control'))  }}
                            </div>
                            <div class="form-group row">
                                <label for="leave_earnability" class="col-3 col-form-label">
                                    Earnable
                                </label>
                                {{ Form::select('leave_earnability', ['No','Yes'],old('leave_earnability',$leave->leave_earnability), array('class' => 'form-control','id'=>'leave_earnability'))  }}
                            </div>
                            <div class="earnable-yes" @if($leave->leave_earnability==0)style="display: none" @endif>
                                <hr>
                                <div class="form-group row">
                                    <label for="leave_earnable_balance" class="col-3 col-form-label">
                                        Earnable Balance
                                    </label>
                                    {{ Form::number('leave_earnable_balance',old('leave_earnable_balance',$leave->leave_earnable_balance), array('class' => 'form-control','step'=>0.01))  }}
                                </div>
                                <div class="form-group row">
                                    <label for="leave_earnable_period" class="col-3 col-form-label">
                                        Earnable Period
                                    </label>
                                    {{ Form::select('leave_earnable_period', $leave_earnable_periods,old('leave_earnable_period',$leave->leave_earnable_period), array('class' => 'form-control'))  }}
                                </div>
                                <div class="form-group row">
                                    <label for="leave_earnable_type" class="col-3 col-form-label">
                                        Earnable Type
                                    </label>
                                    {{ Form::select('leave_earnable_type', $leave_earnable_types,old('leave_earnable_type',$leave->leave_earnable_type), array('class' => 'form-control'))  }}
                                </div>
                                <div class="form-group row">
                                    <label for="threshold_for_earnability" class="col-3 col-form-label">
                                        Threshold Value
                                    </label>
                                    {{ Form::number('threshold_for_earnability',old('threshold_for_earnability',$leave->threshold_for_earnability), array('class' => 'form-control','step'=>0.01))  }}
                                </div>
                                <div class="form-group row">
                                    <label for="threshold_for_present_days" class="col-3 col-form-label">
                                        Threshold For Present Days
                                    </label>
                                    {{ Form::number('threshold_for_present_days',old('threshold_for_present_days',$leave->threshold_for_present_days), array('class' => 'form-control','step'=>0.01))  }}
                                </div>
                                <hr>
                            </div>


                            <div class="form-group row">
                                <label for="allow_half_day" class="col-3 col-form-label">
                                    Allow Half Day
                                </label>
                                {{ Form::checkbox('allow_half_day', 1,($leave->allow_half_day==1))  }}
                            </div>

                            <div class="form-group row">
                                <label for="min_no_of_days_allowed_at_time" class="col-3 col-form-label">
                                    Min Number of Days
                                </label>
                                {{ Form::number('min_no_of_days_allowed_at_time',old('min_no_of_days_allowed_at_time',$leave->min_no_of_days_allowed_at_time), array('class' => 'form-control','step'=>0.01))  }}
                            </div>

                            <div class="form-group row">
                                <label for="max_no_of_days_allowed_at_time" class="col-3 col-form-label">
                                    Max Number of Days
                                </label>
                                {{ Form::number('max_no_of_days_allowed_at_time',old('max_no_of_days_allowed_at_time',$leave->max_no_of_days_allowed_at_time), array('class' => 'form-control','step'=>0.01))  }}
                            </div>

                            <div class="form-group row">
                                <label for="inclusive_public_holiday_weekend" class="col-3 col-form-label">
                                    Inclusive Public Holiday and Weekend
                                </label>
                                {{ Form::checkbox('inclusive_public_holiday_weekend', 1,($leave->inclusive_public_holiday_weekend==1))  }}
                            </div>
                            <div class="form-group row">
                                <label for="applicable_gender" class="col-3 col-form-label">
                                    Applicable Gender
                                </label>
                                {{ Form::select('applicable_gender', $gender,old('applicable_gender',$leave->applicable_gender), array('class' => 'form-control','placeholder'=>'All'))  }}
                            </div>

                            <div class="form-group row">
                                <label for="act_as_present_days" class="col-3 col-form-label">
                                    Regard as present day?
                                </label>
                                {{ Form::checkbox('act_as_present_days', 1,($leave->act_as_present_days==1))  }}
                            </div>

                            <div class="form-group row">
                                <label for="is_paid" class="col-3 col-form-label">
                                    Is Paid
                                </label>
                                {{ Form::checkbox('is_paid', 1,($leave->is_paid==1),['id'=>'is_paid'])  }}
                            </div>

                            <div class="is-payable-yes" @if($leave->is_paid==0) style="display: none" @endif>
                                <hr>
                                <div class="form-group row">
                                    <label for="leave_extra_payment_amount" class="col-3 col-form-label">
                                        Additional Payment
                                    </label>
                                    {{ Form::number('leave_extra_payment_amount',old('leave_extra_payment_amount',$leave->leave_extra_payment_amount), array('class' => 'form-control','step'=>0.01))  }}
                                </div>

                                <div class="form-group row">
                                    <label for="basic_salary_ratio" class="col-3 col-form-label">
                                        Basic Salary Payment %
                                    </label>
                                    {{ Form::number('basic_salary_ratio',old('basic_salary_ratio',$leave->basic_salary_ratio), array('class' => 'form-control','step'=>0.01))  }}
                                </div>

                                <div class="form-group row">
                                    <label for="grade_ratio" class="col-3 col-form-label">
                                        Grade Payment %
                                    </label>
                                    {{ Form::number('grade_ratio',old('grade_ratio',$leave->grade_ratio), array('class' => 'form-control','step'=>0.01))  }}
                                </div>
                                <div class="form-group row">
                                    <label for="allowance_ratio" class="col-3 col-form-label">
                                        Allowance %
                                    </label>
                                    {{ Form::number('allowance_ratio',old('allowance_ratio',$leave->allowance_ratio), array('class' => 'form-control','step'=>0.01))  }}
                                </div>
                                <hr>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Save',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>

        </div>

        @if($organization->organization_structure==2)


            {{-- Right Sidebar  --}}
            <div class="col-md-5 col-sm-12">
                {{-- Basic Info --}}
                <div class="basic-info card">
                    <h5 class="card-header">Leave Use Ability</h5>
                    <div class="card-block">
                        <div class="card-text">

                            <a href="javascript:void(0)" id="add-useability-case"><i class="fa fa-plus"></i> Add New</a>

                            @if($leave->leaveUseability->count()==0)
                                <div class="useability-case">
                                    <div class="form-group row">
                                        <label for="useability_count" class="col-3 col-form-label">
                                            Use Ability Count
                                        </label>
                                        {{ Form::number('useability_count[]',null, array('class' => 'form-control','step'=>0.01))  }}
                                    </div>

                                    <div class="form-group row">
                                        <label for="useability_count_unit" class="col-3 col-form-label">
                                            Use Ability Count Unit
                                        </label>
                                        {{ Form::select('useability_count_unit[]', $useability_count_units,null, array('class' => 'form-control useability_count_unit'))  }}
                                    </div>

                                    <hr>
                                </div>
                            @else
                                @foreach($leave->leaveUseability as $leaveUseability)
                                    <div class="useability-case">
                                        <div class="form-group row">
                                            <label for="useability_count" class="col-3 col-form-label">
                                                Use Ability Count
                                            </label>
                                            {{ Form::number('useability_count[]',$leaveUseability->useability_count, array('class' => 'form-control','step'=>0.01))  }}
                                        </div>

                                        <div class="form-group row">
                                            <label for="useability_count_unit" class="col-3 col-form-label">
                                                Use Ability Count Unit
                                            </label>
                                            {{ Form::select('useability_count_unit[]', $useability_count_units,$leaveUseability->useability_count_unit, array('class' => 'form-control useability_count_unit'))  }}
                                            @if($loop->iteration!=1)
                                               <div style="width: 100%">
                                                   <div class="float-right">
                                                       <button type="button" class="btn btn-danger delete-useability">
                                                           <i class="fa fa-trash-alt"></i>
                                                       </button>
                                                   </div>
                                               </div>
                                            @endif
                                        </div>

                                        <hr>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {{-- End of sidebar --}}
        @endif

    </div>
    {{ Form::close()  }}

@endsection


@section('script')
    <script>
        $(document).on('change', '#leave_earnability', function () {
            $this = $(this);
            if ($this.val() == 1) {
                $('.earnable-yes').slideDown("slow");
            } else {
                $('.earnable-yes').hide("slow");
            }
        })
        $(document).on('click', '#add-useability-case', function () {
            $selected_value = $('.useability-case:first').find('.useability_count_unit').val();
            $('.useability-case:first').find('.useability_count_unit')[0].selectize.destroy();
            let useability = $('.useability-case:first').clone();
            useability.find('input').val('');
            $('.useability-case:last').after(useability);
            $('.useability-case:last').find('.useability_count_unit').selectize()
            var $select = $('.useability-case:first').find('.useability_count_unit').selectize();
            var selectize = $select[0].selectize;
            selectize.setValue($selected_value, false);

            $('.useability-case:last').find('.form-control:last').after('<div class="float-right">\n' +
                '                                <button type="button" class="btn btn-danger delete-useability">\n' +
                '                                    <i class="fa fa-trash-alt"></i>\n' +
                '                                </button>\n' +
                '                            </div>');

        })
        $(document).on('click', '.delete-useability', function () {
            $(this).parents('.useability-case').remove();
        })

        $(document).on('change', '#is_paid', function () {
            if ($('#is_paid').is(':checked')) {
                $('.is-payable-yes').slideDown("slow");
            } else {
                $('.is-payable-yes').hide("slow");
            }
        })

    </script>
@endsection
