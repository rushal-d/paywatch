@extends('layouts.default', ['crumbroute' => 'bonuses.create'])
@section('title', $title)
@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>

        .loan_id {
            width: 72%;
        }

        .selectize-control.input-sm.single {
            width: 100%;
        }
    </style>
@endsection
@section('content')

    {{ Form::open(array('route' => 'bonuses.listall'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">{{$title}}</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Fiscal Year<span class="required-field">*</span>
                            </label>
                            {!! Form::select('fiscal_year_id', $fiscalYears, null,array('id'=>'fiscal_year_id', 'placeholder' => 'Fiscal Year', 'required') ) !!}
                        </div>
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Branch <span class="required-field">*</span>
                            </label>
                            {!! Form::select('branch_id', $branches , null, array( 'id'=>'branch_id', 'required', 'placeholder' => 'Branch') ) !!}
                        </div>
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Received Date <span class="required-field">*</span>
                            </label>
                            {!! Form::text('received_date', null, array( 'id'=>'received_date', 'class'=>'date form-control', 'readonly' => 'readonly', 'required') ) !!}
                        </div>
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Received Date (NP) <span class="required-field">*</span>
                            </label>
                            {!! Form::text('received_date_np', null, array( 'id'=>'received_date_np', 'class' => 'date form-control', 'readonly' => 'readonly', 'required') ) !!}
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        <p id="msg"></p>
                        {{ Form::button('Filter',array('class'=>'btn btn-success btn-lg', 'type' => 'submit','id'=>'filter'))}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{ Form::close()  }}
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        function changeEnDate(){
            $('#received_date') ? $('#received_date_np').val(AD2BS($('#received_date').val())) : '';
        }

        $('#received_date').flatpickr({
            dateFormat: "Y-m-d",
            disableMobile: true,
            onChange: changeEnDate
        });
        $('#received_date_np').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#received_date_np').val() ? $('#received_date').val(BS2AD($('#received_date_np').val())) : '';
            }
        });
    </script>
@endsection
