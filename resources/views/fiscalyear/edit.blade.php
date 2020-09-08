@extends('layouts.default', ['crumbroute' => 'fiscalyearedit'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => array('fiscal-year-update',$fiscalyear->id), 'class' => 'educationform' ))  }}

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Fiscal Year Edit</h5>

                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="fiscal_start_date_np" class="col-3 col-form-label">
                                From
                            </label>
                            {{ Form::text('fiscal_start_date_np', $fiscalyear->fiscal_start_date_np, array('class' => 'form-control nep-date','id'=>'nep-date1' , 'placeholder' => 'Please enter starting Date',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter Starting date'))  }}
                            <input type="hidden" id="fiscal_start_date" name="fiscal_start_date" value="{{$fiscalyear->fiscal_start_date}}">

                        </div>
                        <div class="form-group row">
                            <label for="fiscal_end_date_np" class="col-3 col-form-label">
                                To
                            </label>
                            {{ Form::text('fiscal_end_date_np', $fiscalyear->fiscal_end_date_np, array('class' => 'form-control nep-date','id'=>'nep-date2' , 'placeholder' => 'Please enter end Date',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Ending Date'))  }}
                            <input type="hidden" id="fiscal_end_date" name="fiscal_end_date" value="{{ $fiscalyear->fiscal_end_date }}">
                        </div>
                        <div class="form-group row">
                            <label for="fiscal_code" class="col-3 col-form-label">
                                Code
                            </label>
                            {{ Form::text('fiscal_code', $fiscalyear->fiscal_code, array('class' => 'form-control', 'id' => 'fiscal_code', 'placeholder' => 'Please Enter Fiscal Code',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Fiscal Code'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="present_days" class="col-3 col-form-label">
                                Work Days
                            </label>
                            {{ Form::number('present_days', $fiscalyear->present_days, array('class' => 'form-control', 'id' => 'present_days', 'placeholder' => 'Please Enter Present Days',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a present Days'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="fiscal_status" class="col-3 col-form-label">
                                Status
                            </label>
                            {{ Form::select('fiscal_status', $status_options, $fiscalyear->fiscal_status, array('placeholder' => 'Select One...', 'required' => 'required'))  }}
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Update',array('class'=>'btn btn-success btn-lg'))}}
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
@endsection


@section('script')
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function(e){
                $('#nep-date1').next().val(BS2AD($('#nep-date1').val()))
                $('#nep-date2').next().val(BS2AD($('#nep-date2').val()))
                generateFiscalYearCode()
            }
        });

        $('#fiscal_code').focus(function(){
            generateFiscalYearCode()
        });

        function generateFiscalYearCode(){
            var from_date = $('#nep-date1').val();
            var to_date = $('#nep-date2').val();
            var fiscal_code = '';
            var fiscal_code_first = '';
            var fiscal_code_last = '';
            if(from_date != ''){
                fiscal_code_first = from_date.split('-')[0];
            }
            if(to_date != ''){
                var fiscal_code_last_split = to_date.split('-')[0];
                fiscal_code_last = fiscal_code_last_split.substr(2,4);
            }
            fiscal_code= fiscal_code_first + '/' +  fiscal_code_last;
            $('#fiscal_code').val(fiscal_code);
        }
    </script>
@endsection