@extends('layouts.default', ['crumbroute' => 'holidaycreate'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'system-holiday-save'))  }}
    <style>
        .selectize-control.input-sm.single {
            width: 27.45rem;
        }
    </style>
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Holiday Information</h5>

                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="fy_year" class="col-3 col-form-label">Fiscal year (FY)</label>
                            {{ Form::select('fy_year', $fiscalyear, $current_fiscal_year_id ?? null, array('placeholder' => 'Select One...', 'required' => 'required'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="holiday_descri" class="col-3 col-form-label">Holiday Description</label>
                            {{ Form::text('holiday_descri', null, array('class' => 'form-control', 'placeholder' => 'Input Description', 'data-validation' => 'required','data-validation-error-msg' => 'Please enter Description'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="from_date_np" class="col-3 col-form-label">Holiday From (Date)</label>
                            {{ Form::text('from_date_np', null, array('class' => 'form-control nep-date','required' => 'required','id'=>'nep-date1' , 'placeholder' => 'Holiday From', 'readonly' ))  }}
                            <input type="hidden" id="from_date" name="from_date">
                        </div>

                        <div class="form-group row">
                            <label for="to_date_np" class="col-3 col-form-label">
                                Holiday To (Date)
                            </label>
                            {{ Form::text('to_date_np', null, array('class' => 'form-control nep-date','id'=>'nep-date2' , 'placeholder' => 'Holiday To', 'readonly')) }}
                            <input type="hidden" id="to_date" name="to_date">
                        </div>

                        <div class="form-group row">
                            <label for="holiday_days" class="col-3 col-form-label">No Of Holidays</label>
                            {{ Form::number('holiday_days', null, array('id'=> 'holiday_days', 'min' => 1, 'class' => 'form-control',
                             'placeholder' => 'Input Days','data-validation' => 'required','data-validation-error-msg' => 'Please enter Days',
                             'data-validation' => 'number', 'data-validation-allowing' => 'range[1;100]' ))  }}
                        </div>
                        <div class="form-group row">
                            <label for="holiday_stat" class="col-3 col-form-label">Gender</label>
                            {{ Form::select('gender_id', $genders, null, array('placeholder' => 'All'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="branch" class="col-3 col-form-label">Branch</label>
                            <div class="col-9">
                                <div class="row">
                                    <div class="col-2">
                                        {!! Form::checkbox('branch_all',null,true,['class'=>'check-all-child']) !!}
                                        All
                                    </div>
                                    @foreach($branches as $branch)
                                        <div class="col-2">
                                            <div class="form-group">
                                                {!! Form::checkbox('branch[]',$branch->office_id,true,['class'=>'child-checkbox']) !!}
                                                {{$branch->office_name}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label for="branch" class="col-3 col-form-label">Religion</label>
                            <div class="col-9">
                                <div class="row">
                                    <div class="col-2">
                                        {!! Form::checkbox('religion_all',null,true,['class'=>'check-all-child']) !!}
                                        All
                                    </div>
                                    @foreach($religions as $religion)
                                        <div class="col-2">
                                            <div class="form-group">
                                                {!! Form::checkbox('religion[]',$religion->id,true,['class'=>'child-checkbox']) !!}
                                                {{$religion->religion_name}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="branch" class="col-3 col-form-label">Caste</label>
                            <div class="col-9">
                                <div class="row">
                                    <div class="col-2">
                                        {!! Form::checkbox('caste_all',null,true,['class'=>'check-all-child']) !!}
                                        All
                                    </div>
                                    @foreach($castes as $caste)
                                        <div class="col-2">
                                            <div class="form-group">
                                                {!! Form::checkbox('caste[]',$caste->id,true ,['class'=>'child-checkbox'])!!}
                                                {{$caste->caste_name}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="holiday_stat" class="col-3 col-form-label">Status</label>
                            {{ Form::select('holiday_stat', $status_options, 1, array('placeholder' => 'Select One...', 'required' => 'required'))  }}
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
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#nep-date1').next().val(BS2AD($('#nep-date1').val()))
                $('#nep-date2').next().val(BS2AD($('#nep-date2').val()))

                //calculate days
                var date_from = $('#nep-date1').next().val();
                var date_to = $('#nep-date2').next().val();
                //also check if contains NaN
                if (date_from && date_from.indexOf('NaN') < 0 && date_to && date_to.indexOf('NaN') < 0) {
                    var diff_days = daydiff(parseDate(date_from), parseDate(date_to)) + 1
                    if (diff_days > 0) {
                        $('#holiday_days').val(diff_days);
                    } else {
                        $('#holiday_days').val(0);
                        toastr.error('Please check start holiday date from and to! Holiday must be at least one day!', 'Error!')
                    }
                }
            }
        });

        function parseDate(str) {
            var mdy = str.split('-');
            return new Date(mdy[0], mdy[1] - 1, mdy[2]);
        }

        function daydiff(first, second) {
            return Math.round((second - first) / (1000 * 60 * 60 * 24));
        }

        $('.child-checkbox').change(function () {
            $this = $(this);
            let childCheckboxStatus = $(this).is(':checked');
            if (!childCheckboxStatus) {
                $this.parent().parent().parent().find('.check-all-child').prop('checked', false)
            }
        });
        $('.check-all-child').change(function () {
            $this = $(this);
            let childCheckboxes = $this.parent().parent().parent().find('.child-checkbox');
            let checkValue = false;
            if ($this.is(':checked')) {
                checkValue = true;
            }
            $.each(childCheckboxes, function (index, value) {
                $(value).prop('checked', checkValue);
            })
        });
    </script>
@endsection
