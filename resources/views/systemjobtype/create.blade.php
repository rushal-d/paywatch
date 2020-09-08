@extends('layouts.default', ['crumbroute' => 'jobtypecreate'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'system-jobtype-save'))  }}

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Job Type Category</h5>

                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="jobtype_name" class="col-3 col-form-label">
                                Job Type
                                {{--<span class="badge badge-pill badge-danger">*</span>--}}
                            </label>
                            {{--{{ Form::label('jobtype_name', 'Job Type') }}--}}
                            {{ Form::text('jobtype_name', null, array('class' => 'form-control', 'placeholder' => 'Input Job Type ',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Job Type Name'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="jobtype_name" class="col-3 col-form-label">
                                Job Type Code
                                {{--<span class="badge badge-pill badge-danger">*</span>--}}
                            </label>
                            {{--{{ Form::label('jobtype_name', 'Job Type') }}--}}
                            {{ Form::text('jobtype_code', null, array('class' => 'form-control', 'placeholder' => 'Input Job Type Code',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Job Type Code'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="effect_date" class="col-3 col-form-label">
                                Effect Date
                                {{--<span class="badge badge-pill badge-danger">*</span>--}}
                            </label>

                            {{--{{ Form::label('effect_date', 'Effect Date') }}--}}
                            {{ Form::text('effect_date', null, array('class' => 'form-control'  ,'required' => 'required','id'=>'nep-date', 'placeholder' => 'Input Effect Date'
                           ))  }}
                        </div>

                        <div class="form-group row">
                            <label for="profund_per" class="col-3 col-form-label">
                                Profund (%)

                            </label>

                                {{ Form::number('profund_per',Config::get('constants.profund_default'), array('class' => 'form-control',
                                 'data-validation' => 'required','step'=>'0.01',
                                 'data-validation-error-msg' => 'Please enter a Profund (%)'))  }}

                        </div>
                        <div class="form-group row">
                            <label for="profund_contri_per" class="col-3 col-form-label">
                                Profund Contribution(%)
                            </label>
                                {{ Form::number('profund_contri_per', Config::get('constants.contribution_default'), array('class' => 'form-control' ,
                                 'data-validation' => 'required','step'=>'0.01',
                                 'data-validation-error-msg' => 'Please enter a Profund  Contri(%)'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="gratuity" class="col-3 col-form-label">
                                Gratuity(%)
                            </label>

                            {{ Form::number('gratuity',  Config::get('constants.gratuity_default'), array('class' => 'form-control',
                             'data-validation' => 'required','step'=>'0.01',
                             'data-validation-error-msg' => 'Please enter a Gratuity'))  }}

                        </div>

                        <div class="form-group row">
                            <label for="gratuity" class="col-3 col-form-label">
                                Social Security Fund(%)
                            </label>

                            {{ Form::number('social_security_fund_per',  Config::get('constants.social_security_fund'), array('class' => 'form-control',
                             'data-validation' => 'required','step'=>'0.01',
                             'data-validation-error-msg' => 'Please enter a Social Security Fund Per'))  }}

                        </div>

                        {{--  Save --}}
                        <div class="text-right form-control no-border">
                            {{ Form::submit('Save',array('class'=>'btn btn-success btn-lg btn-save'))}}
                        </div>
                    </div>
                </div>
            </div>


        </div>
        {{-- Right Sidebar  --}}
        <div class="col-md-5 col-sm-12">
        </div>
        {{-- End of sidebar --}}

    </div>


    {{ Form::close()  }}

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>

        $('#nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20 // Options | Number of years to show
        });
    </script>
@endsection
