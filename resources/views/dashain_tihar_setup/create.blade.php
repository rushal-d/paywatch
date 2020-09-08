@extends('layouts.default', ['crumbroute' => 'dashaintiharsetup'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'dashaintiharsetupstore'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Dashain Tihar Setup</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Min Incentive Payable Months
                            </label>
                            {{ Form::number('min_special_incentive_months', $min_special_incentive_months, array('class' => 'form-control', 'placeholder' => 'Min Incentive Payable Months',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Min Incentive Payable Months'))  }}

                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Extra Facility Rate
                            </label>
                            {{ Form::number('extra_facility_dashain_tihar_rate', $extra_facility_dashain_tihar_rate, array('class' => 'form-control', 'placeholder' => 'Extra Facility Rate',
                             'data-validation' => 'required','step'=>0.01,
                             'data-validation-error-msg' => 'Please enter a Min Incentive Payable Months'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Incentive Payment Amount
                            </label>
                            {{ Form::number('incentive_amount', $incentive_amount, array('class' => 'form-control', 'placeholder' => 'Incentive Amount',
                             'data-validation' => 'required','step'=>0.01,
                             'data-validation-error-msg' => 'Please enter Incentive Payable Amount'))  }}
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
    {{ Form::close()  }}
@endsection
