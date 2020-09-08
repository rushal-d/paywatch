@extends('layouts.default', ['crumbroute' => 'tiharsetup'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'tihar-payment-show'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Fiscal Year Attendance</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="branch_id" class="col-3 col-form-label">
                                Branch
                            </label>
                            {{ Form::select('branch_id', $branches, null, array('placeholder' => 'Select One...' , 'required' => 'required'))  }}

                        </div>
                        <div class="form-group row">
                            <label for="fiscal_year" class="col-3 col-form-label">
                                Fiscal Year
                            </label>
                            {{ Form::select('fiscal_year', $fiscal_years, $current_fiscal_year_id, array('placeholder' => 'Select One...' , 'required' => 'required'))  }}

                        </div>

                        <div class="form-group row">
                            <label for="month" class="col-3 col-form-label">
                                Payment Date
                            </label>
                            {{ Form::text('payment_date', null, array('class' => 'form-control nep-date','required' => 'required','id'=>'nep-date1' , 'placeholder' => 'Payment Date', 'readonly' => 'readonly' ))  }}

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Proceed',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-sm-12">


        </div>

    </div>
    {{ Form::close()  }}
@endsection
@section('script')
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#nep-date1').next().val(BS2AD($('#nep-date1').val()));
                $('#nep-date2').next().val(BS2AD($('#nep-date2').val()));
            }
        });


    </script>
@endsection
