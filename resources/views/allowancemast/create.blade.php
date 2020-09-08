@extends('layouts.default', ['crumbroute' => 'allowancecreate'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'allowance-save'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Allowance Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="allow_title" class="col-3 col-form-label">
                                Allowance Title
                            </label>
                            {{ Form::text('allow_title', null, array('placeholder' => 'Allowance Title','class'=>'form-control' , 'required' => 'required'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="allow_amt" class="col-3 col-form-label">
                                Default Allowance Amount
                            </label>
                            {{ Form::number('allow_amt', null, array('class' => 'form-control', 'placeholder' => 'Allowance Amount',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Allowance Amount','step'=>0.0001))  }}

                        </div>
                        <div class="form-group row">
                            <label for="effect_date_np" class="col-3 col-form-label">
                                Effect Date
                            </label>
                            {{ Form::text('effect_date_np', null, array('class' => 'form-control nep-date','required' => 'required','id'=>'nep-date1' , 'placeholder' => 'Please enter Effect Date'
                           ))  }}
                            <input type="hidden" id="nep-date1" name="effect_date">
                        </div>
                        <div class="form-group row">
                            <label for="allow_code" class="col-3 col-form-label">
                                Allowance Code
                            </label>

                            {{ Form::text('allow_code', null, array('class' => 'form-control', 'placeholder' => 'Allowance Code',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a allowance code'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="allowance_type" class="col-3 col-form-label">
                                Allowance Type
                            </label>

                            {{ Form::select('allowance_type',$allowance_types, old('allowance_type'), array('class' => 'form-control allowance_type', 'placeholder' => 'Allowance Type',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a allowance code'))  }}
                        </div>

                        <div class="form-group row include_payroll"
                             @if(old('allowance_type')==2 || old('allowance_type')==null)  style="display: none" @endif>
                            <label for="allow_code" class="col-3 col-form-label">
                                Included on Payroll
                            </label>
                            {{Form::checkbox('include_in_payroll',1,false,['id'=>'include_payroll'])}}
                        </div>

                        <div class="form-group row">
                            <label for="allow_code" class="col-3 col-form-label">
                                Show on Form
                            </label>
                            {{Form::checkbox('show_on_form',1,true)}}
                        </div>
                        <div class="form-group row">
                            <label for="status_id" class="col-3 col-form-label">
                                Status
                            </label>
                            {{ Form::select('status_id', array('1' => 'Active', '2' => 'Deactive'), '1') }}

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
                $('#nep-date1').next().val(BS2AD($('#nep-date1').val()))
                $('#nep-date2').next().val(BS2AD($('#nep-date2').val()))
            }
        });

        $('.allowance_type').change(function () {
            $this = $(this);
            if ($this.val() == 1) {
                $('.include_payroll').slideDown('slow');
            } else {
                $("#include_payroll").prop("checked", false);
                $('.include_payroll').hide('slow');
            }

        })
    </script>
@endsection
