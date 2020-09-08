@extends('layouts.default', ['crumbroute' => 'allowanceedit'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => array('allowance-update',$allowance->allow_id), 'class' => 'educationform' ))  }}
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
                            {{ Form::text('allow_title', $allowance->allow_title, array('placeholder' => 'Allowance Title','class'=>'form-control' , 'required' => 'required'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="allow_amt" class="col-3 col-form-label">
                                Allowance Amount
                            </label>

                            {{ Form::number('allow_amt', $allowance->allow_amt, array('class' => 'form-control', 'placeholder' => 'Allowance Amount',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Allowance Amount','step'=>0.001))  }}

                        </div>
                        <div class="form-group row">
                            <label for="effect_date_np" class="col-3 col-form-label">
                                Effect Date
                            </label>
                            {{ Form::text('effect_date_np', $allowance->effect_date_np, array('class' => 'form-control nep-date'  ,'required' => 'required','id'=>'nep-date1' , 'placeholder' => 'Please enter Effect Date'
                        ))  }}
                            <input type="hidden" id="nep-date1" name="effect_date" >
                        </div>
                        <div class="form-group row">
                            <label for="allow_code" class="col-3 col-form-label">
                                Allowance Code
                            </label>

                            {{ Form::text('allow_code', $allowance->allow_code, array('class' => 'form-control', 'placeholder' => 'Allowance Code',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a allowance code'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="allowance_type" class="col-3 col-form-label">
                                Allowance Type
                            </label>

                            {{ Form::select('allowance_type',$allowance_types, old('allowance_type',$allowance->allowance_type), array('class' => 'form-control allowance_type', 'placeholder' => 'Allowance Type',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a allowance code'))  }}
                        </div>

                        <div class="form-group row include_payroll"
                             @if(old('allowance_type',$allowance->allowance_type)==2)  style="display: none" @endif>
                            <label for="allow_code" class="col-3 col-form-label">
                                Included on Payroll
                            </label>
                            {{Form::checkbox('include_in_payroll',1,$allowance->include_in_payroll==1,['id'=>'include_payroll'])}}
                        </div>

                        <div class="form-group row">
                            <label for="allow_code" class="col-3 col-form-label">
                                Show on Form
                            </label>
                            {{Form::checkbox('show_on_form',1,$allowance->show_on_form==1)}}
                        </div>

                        <div class="form-group row">
                            <label for="status_id" class="col-3 col-form-label">
                                Status
                            </label>

                            <select id="status_id" name="status_id" class="input-sm" >
                                <option value="1" {{ $allowance->status_id == '1' ? 'selected' :'' }}>Active
                                </option>
                                <option value="2" {{ $allowance->status_id == '2' ? 'selected' :'' }}>Deactive
                                </option>
                            </select>

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
            }
        });

        $('.allowance_type').change(function () {
            $this = $(this);
            if ($this.val() == 1) {
                $('.include_payroll').slideDown('slow');
            }else{
                $("#include_payroll").prop("checked", false);
                $('.include_payroll').hide('slow');
            }

        })
    </script>
@endsection
