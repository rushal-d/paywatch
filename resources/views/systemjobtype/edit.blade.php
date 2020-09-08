@extends('layouts.default', ['crumbroute' => 'jobtypeedit'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => array('system-jobtype-update',$jobtype->jobtype_id), 'class' => 'educationform' ))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Job Type Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="jobtype_name" class="col-3 col-form-label">
                                Job Type Name
                            </label>
                            {{ Form::text('jobtype_name', $jobtype->jobtype_name, array('class' => 'form-control', 'placeholder' => 'Job Type Name',
                            'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Job Type Name')) }}
                        </div>

                        <div class="form-group row">
                            <label for="jobtype_name" class="col-3 col-form-label">
                                Job Type Code
                            </label>
                            {{ Form::text('jobtype_code', $jobtype->jobtype_code, array('class' => 'form-control', 'placeholder' => 'Job Type Code',
                            'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Job Type Code')) }}
                        </div>
                        <div class="form-group row">
                            <label for="effect_date" class="col-3 col-form-label">
                                Effect Date
                            </label>
                            {{ Form::text('effect_date', $jobtype->effect_date, array('class' => 'form-control','id'=>'nep-date', 'placeholder' => 'Input Effect Date',
                                'data-validation' => 'required',
                                'data-validation-error-msg' => 'Please enter a effect_Date'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="profund_per" class="col-3 col-form-label">
                                Profund (%)
                            </label>
                            {{ Form::text('profund_per', $jobtype->profund_per, array('class' => 'form-control'  ,'required' => 'required','step'=>'0.01','id' => 'nepaliDate' ,'placeholder' => 'Input Profund (%)  '
                            ))  }}
                        </div>
                        <div class="form-group row">
                            <label for="profund_contri_per" class="col-3 col-form-label">
                                Profund Contri(%)
                            </label>
                            {{ Form::text('profund_contri_per', $jobtype->profund_contri_per, array('class' => 'form-control' ,'placeholder' => 'Input Profund Contri (%)  ',
                             'data-validation' => 'required','step'=>'0.01',
                             'data-validation-error-msg' => 'Please enter a Profund  Contri(%)'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="gratuity" class="col-3 col-form-label">
                                Gratuity(%)
                            </label>
                            {{ Form::number('gratuity', $jobtype->gratuity, array('class' => 'form-control', 'placeholder' => 'Gratuity',
                            'data-validation' => 'required','step'=>'0.01',
                             'data-validation-error-msg' => 'Please enter a Gratuity')) }}
                        </div>

                        <div class="form-group row">
                            <label for="social_security_fund_per" class="col-3 col-form-label">
                                Social Security Fund(%)
                            </label>
                            {{ Form::number('social_security_fund_per', $jobtype->social_security_fund_per, array('class' => 'form-control', 'placeholder' => 'Social Security Fund',
                            'data-validation' => 'required','step'=>'0.01',
                             'data-validation-error-msg' => 'Please enter a Social Security Fund %')) }}
                        </div>

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



@section('script')

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
