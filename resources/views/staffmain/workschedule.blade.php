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
    </style>
    @include('staffmain.staff-edit-nav')
    <form method="post" action="{{ route('staff-work-schedule-store',$staffmain->id) }}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{csrf_token()}}">

        <div class="row">
            <div class="col-md-7 col-sm-12">

                {{--start staff work --}}
                <div>
                    <div class="basic-info card">
                        <h5 class="card-header">Staff Work Schedule: {{$staffmain->name_eng}} -
                            [CID: {{$staffmain->staff_central_id}}] - [Branch
                            ID: {{$staffmain->main_id}} {{$staffmain->branch->office_name ?? ''}}]</h5>
                        <div class="card-block">
                            <div class="card-text">
                                <div class="form-group row">
                                    <label for="work_hour" class="col-3 col-form-label">
                                        Work Hour<span class="required-field">*</span>
                                    </label>
                                    {{ Form::number('work_hour', $staff_workschedule->work_hour ?? Config::get('constants.working_hour'), array('class' => 'form-control', 'placeholder' => 'Enter Work Hour',
                                     'data-validation' => 'required',
                                     'data-validation-error-msg' => 'Please enter a Work Hour'))  }}
                                </div>
                                <div class="form-group row">
                                    <label for="work_hour" class="col-3 col-form-label">
                                        Max Work Hour<span class="required-field">*</span>
                                    </label>
                                    {{ Form::number('max_work_hour', $staff_workschedule->max_work_hour ?? Config::get('constants.max_working_hour'), array('class' => 'form-control', 'placeholder' => 'Enter Max Work Hour',
                                     'data-validation' => 'required','step'=>0.01,
                                     'data-validation-error-msg' => 'Please enter a Max Work Hour'))  }}
                                </div>

                                <div class="form-group row">
                                    <label for="effect_date_np" class="col-3 col-form-label">
                                        Current Effect Date
                                    </label>
                                    {{$staff_workschedule->effect_date_np ?? ''}}
                                </div>

                                <div class="form-group row">
                                    <label for="effect_date_np" class="col-3 col-form-label">
                                        Current Weekend
                                    </label>
                                    {{!empty($staff_workschedule) ?($weekend_days[$staff_workschedule->weekend_day] ?? ''): ''}}
                                </div>

                                <div class="form-group row">
                                    <label for="weekend_day" class="col-3 col-form-label">
                                        Weekend Day<span class="required-field">*</span>
                                    </label>
                                    {{ Form::select('weekend_day', $weekend_days, null, array('placeholder' => 'Select One...', 'required' => 'required'))  }}

                                </div>
                                <div class="form-group row">
                                    <label for="effect_date_np" class="col-3 col-form-label">
                                        Effect Date<span class="required-field">*</span>
                                    </label>
                                    {{ Form::text('effect_date_np', null, array('class' => 'form-control nep-date','id'=>'nep-date2','data-validation' => 'required', 'placeholder' => 'Enter Effect Date','readonly'
                                    ))  }}
                                </div>


                                <div class="form-group row">
                                    <label for="work_status" class="col-3 col-form-label">
                                        Work Status
                                        {{--<span class="badge badge-pill badge-danger">*</span>--}}
                                    </label>
                                    {{ Form::select('work_status', array('A' => 'Active', 'D' => 'Deactive'),$staff_workschedule->work_status ?? 'A') }}
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

@endsection
@section('script')

    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>

        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 50 // Options | Number of years to show
        });
    </script>
@endsection
