@extends('layouts.default', ['crumbroute' => 'leavecreate'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'systemleavesave' ))  }}

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Leave Category</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="leave_name" class="col-3 col-form-label">
                                Leave Name
                            </label>
                            {{ Form::text('leave_name', null, array('class' => 'form-control', 'placeholder' => 'Input Leave Name',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a leave_name'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="leave_code" class="col-3 col-form-label">
                                Leave Code
                            </label>
                            {{ Form::text('leave_code', null, array('class' => 'form-control', 'placeholder' => 'Input Leave Code',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a leave_code'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="max_days" class="col-3 col-form-label">
                                No. of Days in FY <i class="fas fa-question-circle" data-toggle="tooltip"
                                                     data-placement="top"
                                                     title="The number of days staff receives leave in the fiscal year."></i>
                            </label>
                            {{ Form::number('no_of_days', null, array('class' => 'form-control', 'placeholder' => 'Input No of Days',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter No. Of Days'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="max_days" class="col-3 col-form-label">
                                Maximum Leave Balance
                                <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top"
                                   title="Maxmimum Leave Balance that staff can hold."></i>
                            </label>
                            {{ Form::number('max_days', null, array('class' => 'form-control', 'placeholder' => 'Input Maximum Days',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Maximum Days'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="max_days" class="col-3 col-form-label">
                                Req. Initial Setup
                            </label>
                            {{ Form::checkbox('initial_setup', 1) }}
                        </div>

                        <div class="form-group row">
                            <label for="leave_type" class="col-3 col-form-label">
                                Job Types
                            </label>
                            {{ Form::select('job_type_id', $job_types,old('job_type_id'), array('class' => 'form-control','placeholder'=>'Select Job Type'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="max_days" class="col-3 col-form-label">
                                Allow Negative Balance
                            </label>
                            {{ Form::checkbox('allow_negative', 1) }}
                        </div>

                        @if($organization->organization_structure==2)
                            <div class="form-group row">
                                <label for="leave_type" class="col-3 col-form-label">
                                    Leave Type <i class="fas fa-question-circle" data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="If the leave collapse on the change of fiscal year or not."></i>
                                </label>
                                {{ Form::select('leave_type', $leave_types,old('leave_type'), array('class' => 'form-control'))  }}
                            </div>
                            <div class="form-group row">
                                <label for="leave_earnability" class="col-3 col-form-label">
                                    Earnable
                                    <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top"
                                       title="If the leave balance increments according any given factors conditions."></i>
                                </label>
                                {{ Form::select('leave_earnability', ['No','Yes'],old('leave_earnability'), array('class' => 'form-control','id'=>'leave_earnability'))  }}
                            </div>
                            <div class="earnable-yes" style="display: none">
                                <hr>
                                <div class="form-group row">
                                    <label for="leave_earnable_balance" class="col-3 col-form-label">
                                        Earnable Balance
                                    </label>
                                    {{ Form::number('leave_earnable_balance',old('leave_earnable_balance',0), array('class' => 'form-control','step'=>0.01))  }}
                                </div>
                                <div class="form-group row">
                                    <label for="leave_earnable_period" class="col-3 col-form-label">
                                        Earnable Period
                                    </label>
                                    {{ Form::select('leave_earnable_period', $leave_earnable_periods,old('leave_earnable_period'), array('class' => 'form-control'))  }}
                                </div>
                                <div class="form-group row">
                                    <label for="leave_earnable_type" class="col-3 col-form-label">
                                        Earnable Type
                                    </label>
                                    {{ Form::select('leave_earnable_type', $leave_earnable_types,old('leave_earnable_type'), array('class' => 'form-control'))  }}
                                </div>
                                <div class="form-group row">
                                    <label for="threshold_for_earnability" class="col-3 col-form-label">
                                        Threshold Value
                                    </label>
                                    {{ Form::number('threshold_for_earnability',old('threshold_for_earnability',0), array('class' => 'form-control','step'=>0.01))  }}
                                </div>
                                <div class="form-group row">
                                    <label for="threshold_for_present_days" class="col-3 col-form-label">
                                        Threshold For Present Days
                                    </label>
                                    {{ Form::number('threshold_for_present_days',old('threshold_for_present_days',0), array('class' => 'form-control','step'=>0.01))  }}
                                </div>
                                <hr>
                            </div>


                            <div class="form-group row">
                                <label for="allow_half_day" class="col-3 col-form-label">
                                    Allow Half Day
                                </label>
                                {{ Form::checkbox('allow_half_day', 1)  }}
                            </div>

                            <div class="form-group row">
                                <label for="min_no_of_days_allowed_at_time" class="col-3 col-form-label">
                                    Min Number of Days
                                    <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top"
                                       title="Minimum number of days that staff can take leave at a time."></i>
                                </label>
                                {{ Form::number('min_no_of_days_allowed_at_time',old('min_no_of_days_allowed_at_time',0), array('class' => 'form-control','step'=>0.01))  }}
                            </div>

                            <div class="form-group row">
                                <label for="max_no_of_days_allowed_at_time" class="col-3 col-form-label">
                                    Max Number of Days
                                    <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top"
                                       title="Maximum number of days that staff can take leave at a time."></i>
                                </label>
                                {{ Form::number('max_no_of_days_allowed_at_time',old('max_no_of_days_allowed_at_time'), array('class' => 'form-control','step'=>0.01))  }}
                            </div>

                            <div class="form-group row">
                                <label for="inclusive_public_holiday_weekend" class="col-3 col-form-label">
                                    Inclusive Public Holiday and Weekend
                                    <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top"
                                       title="Check if the public holiday and weekend day are counted as leave day."></i>
                                </label>
                                {{ Form::checkbox('inclusive_public_holiday_weekend', 1)  }}
                            </div>
                            <div class="form-group row">
                                <label for="applicable_gender" class="col-3 col-form-label">
                                    Applicable Gender
                                </label>
                                {{ Form::select('applicable_gender', $gender,old('applicable_gender'), array('class' => 'form-control','placeholder'=>'All'))  }}
                            </div>

                            <div class="form-group row">
                                <label for="act_as_present_days" class="col-3 col-form-label">
                                    Regard as present day?
                                </label>
                                {{ Form::checkbox('act_as_present_days', 1,true)  }}
                            </div>

                            <div class="form-group row">
                                <label for="is_paid" class="col-3 col-form-label">
                                    Is Paid
                                </label>
                                {{ Form::checkbox('is_paid', 1,true,['id'=>'is_paid'])  }}
                            </div>

                            <div class="is-payable-yes">
                                <hr>
                                <div class="form-group row">
                                    <label for="leave_extra_payment_amount" class="col-3 col-form-label">
                                        Additional Payment
                                    </label>
                                    {{ Form::number('leave_extra_payment_amount',old('leave_extra_payment_amount'), array('class' => 'form-control','step'=>0.01))  }}
                                </div>

                                <div class="form-group row">
                                    <label for="basic_salary_ratio" class="col-3 col-form-label">
                                        Basic Salary Payment %
                                    </label>
                                    {{ Form::number('basic_salary_ratio',old('basic_salary_ratio',100), array('class' => 'form-control','step'=>0.01))  }}
                                </div>

                                <div class="form-group row">
                                    <label for="grade_ratio" class="col-3 col-form-label">
                                        Grade Payment %
                                    </label>
                                    {{ Form::number('grade_ratio',old('grade_ratio',100), array('class' => 'form-control','step'=>0.01))  }}
                                </div>
                                <div class="form-group row">
                                    <label for="allowance_ratio" class="col-3 col-form-label">
                                        Allowance %
                                    </label>
                                    {{ Form::number('allowance_ratio',old('allowance_ratio',100), array('class' => 'form-control','step'=>0.01))  }}
                                </div>
                                <hr>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{--  Save --}}`
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
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
        $(document).on('click', '#add-useability-case', function () {
            $selected_value = $('.useability-case:first').find('.useability_count_unit').val();
            $('.useability-case:first').find('.useability_count_unit')[0].selectize.destroy();
            let useability = $('.useability-case:first').clone();
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
