@extends('layouts.default', ['crumbroute' => 'officeedit'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => array('systemofficeupdate',$office->office_id), 'class' => 'educationform' ))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Office Edit</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="office_name" class="col-3 col-form-label">
                                Office Name
                            </label>
                            {{ Form::text('office_name', $office->office_name, array('class' => 'form-control', 'placeholder' => 'Office  Name',
                            'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a office_name')) }}
                        </div>

                        <div class="form-group row">
                            <label for="estd_date_np" class="col-3 col-form-label">
                                Estd Date(BS)
                            </label>
                            <input class="form-control" value="{{ $office->estd_date_np }}" type="text" id="nep-date"
                                   name="estd_date_np" required>
                        </div>

                        <div class="form-group row">
                            <label for="paywatch_implementation_date_np" class="col-3 col-form-label">
                                Paywatch Installation Date(BS)

                            </label>
                            <input class="form-control" type="text" id="paywatch_implementation_date_np"
                                   name="paywatch_implementation_date_np" placeholder="Pick A Date"
                                   value="{{ $office->paywatch_implementation_date_np}}">

                        </div>

                        <div class="form-group row">
                            <label for="office_location" class="col-3 col-form-label">
                                Office Location
                            </label>
                            {{ Form::text('office_location', $office->office_location, array('class' => 'form-control', 'placeholder' => 'Input Office Location',
                            'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Office Location')) }}
                        </div>
                        <div class="form-group row">
                            <label for="office_location" class="col-3 col-form-label">
                                Manual Weekend Enable
                            </label>
                            {{ Form::select('manual_weekend_enable', [1 => 'Yes', 0 => 'No'], $office->manual_weekend_enable, array('class' => 'form-control', 'placeholder' => 'Please select',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please select a manual weekend enable'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="office_location" class="col-3 col-form-label">
                                Order Staff IDs
                            </label>
                            {{ Form::textarea('order_staff_ids',  $office->order_staff_ids, array('class' => 'form-control', 'placeholder' => 'Order IDs'))  }}
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

        $('#nep-date,#paywatch_implementation_date_np').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20 // Options | Number of years to show
        });
    </script>
@endsection
