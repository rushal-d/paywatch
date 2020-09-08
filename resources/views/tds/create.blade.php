@extends('layouts.default', ['crumbroute' => 'tdscreate'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'system-tds-save'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Add TDS</h5>

                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="fy_year" class="col-3 col-form-label">
                                Fiscal year (FY)
                            </label>
                            {{ Form::select('fy', $fiscalyear, null, array('placeholder' => 'Select One...', 'required' => 'required'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="tds_stat" class="col-3 col-form-label">
                                Single / Couple
                            </label>

                            {{ Form::select('type', $tds_options, null, array('placeholder' => 'Select One...' , 'required' => 'required'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="tds_stat" class="col-3 col-form-label">
                                Slab
                            </label>
                            {{ Form::select('slab', $tds_slabs, null, array('placeholder' => 'Select One...', 'required' => 'required'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="income_range" class="col-3 col-form-label">
                                Amount
                            </label>
                                {{ Form::number('amount', null, array('class' => 'form-control', 'placeholder' => 'Slab Amount',
                                 'data-validation' => 'required', 'data-validation-error-msg' => 'Please enter Amount')) }}

                        </div>
                        <div class="form-group row">
                            <label for="deduct_per" class="col-3 col-form-label">
                                Percentage
                            </label>
                                {{ Form::number('percent', null, array('step' => 'any', 'class' => 'form-control', 'placeholder' => 'Deductable Percent',
                                 'data-validation' => 'required', 'data-validation-error-msg' => 'Please enter percentage'))  }}

                        </div>
                        <div class="form-group row">
                            <label for="tds_stat" class="col-3 col-form-label">
                                Status
                            </label>

                            {{ Form::select('status', $status_options, null, array('placeholder' => 'Select One...', 'required' => 'required'))  }}
                        </div>


                    </div>
                </div>
            </div>

            {{--  Save --}}
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
