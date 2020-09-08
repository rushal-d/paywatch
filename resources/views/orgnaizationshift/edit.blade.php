@extends('layouts.default', ['crumbroute' => 'orgnaization-shiftedit'])
@section('title', $title)

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .erase-time {
            color: orangered;
            font-size: 18px;
            cursor: pointer;
            margin: 15px auto;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-6 col-sm-12">
            {{-- Basic Info --}}
            {{ Form::open(array('route' => array('organization-shift-update',$organizationShift->id)))  }}
            {{method_field('PATCH')}}
            <div class="basic-info card">
                <div class="card-header">
                    {{$title}}
                </div>
                <div class="card-block">
                    <div class="card-text">

                        <div class="row">
                            <label for="effective_from" class="col-3 col-form-label">
                                Effective From (AD) <span class="required-field">*</span>
                            </label>
                            <div class="col-8 form-group">
                                <input type="text" id="effective_from" name="effective_from"
                                       class="input-sm form-control" value="{{$organizationShift->effective_from}}"
                                       data-validation="required" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <label for="effective_from_np" class="col-3 col-form-label">
                                Effective From (BS) <span class="required-field">*</span>
                            </label>
                            <div class="col-8 form-group">
                                <input type="text" id="effective_from_np" name="effective_from_np"
                                       class="input-sm form-control" value="{{$organizationShift->effective_from_np}}"
                                       data-validation="required" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <label for="sunday_punch_in" class="col-3 col-form-label">
                                Sunday <span class="required-field">*</span>
                            </label>
                            <div class="col-4 form-group">
                                <p>Punch In</p>
                                <input type="text" id="sunday_punch_in" name="sunday_punch_in"
                                       class="input-sm form-control time"  value="{{$organizationShift->sunday_punch_in}}"
                                       data-validation="punchinValidation">
                            </div>
                            <div class="col-4 form-group">
                                <p>Punch Out</p>
                                <input type="text" id="sunday_punch_out" name="sunday_punch_out"
                                       class="input-sm form-control time" value="{{$organizationShift->sunday_punch_out}}"
                                       data-validation="punchoutValidation">
                            </div>
                            <div class="col-1">
                                <i class="fas fa-backspace erase-time"></i>
                            </div>
                        </div>

                        <div class="row">
                            <label for="monday_punch_in" class="col-3 col-form-label">
                                Monday <span class="required-field">*</span>
                            </label>
                            <div class="col-4 form-group">
                                <p>Punch In</p>
                                <input type="text" id="monday_punch_in" name="monday_punch_in"
                                       class="input-sm form-control time" value="{{$organizationShift->monday_punch_in}}"
                                       data-validation="punchinValidation">
                            </div>
                            <div class="col-4 form-group">
                                <p>Punch Out</p>
                                <input type="text" id="monday_punch_out" name="monday_punch_out"
                                       class="input-sm form-control time" value="{{$organizationShift->monday_punch_out}}"
                                       data-validation="punchoutValidation">
                            </div>
                            <div class="col-1">
                                <i class="fas fa-backspace erase-time"></i>
                            </div>
                        </div>

                        <div class="row">
                            <label for="tuesday_punch_in" class="col-3 col-form-label">
                                Tuesday <span class="required-field">*</span>
                            </label>
                            <div class="col-4 form-group">
                                <p>Punch In</p>
                                <input type="text" id="tuesday_punch_in" name="tuesday_punch_in"
                                       class="input-sm form-control time" value="{{$organizationShift->tuesday_punch_in}}"
                                       data-validation="punchinValidation">
                            </div>
                            <div class="col-4 form-group">
                                <p>Punch Out</p>
                                <input type="text" id="tuesday_punch_out" name="tuesday_punch_out"
                                       class="input-sm form-control time"  value="{{$organizationShift->tuesday_punch_out}}"
                                       data-validation="punchoutValidation">
                            </div>
                            <div class="col-1">
                                <i class="fas fa-backspace erase-time"></i>
                            </div>
                        </div>

                        <div class="row">
                            <label for="wednesday_punch_in" class="col-3 col-form-label">
                                Wednesday <span class="required-field">*</span>
                            </label>
                            <div class="col-4 form-group">
                                <p>Punch In</p>
                                <input type="text" id="wednesday_punch_in" name="wednesday_punch_in"
                                       class="input-sm form-control time" value="{{$organizationShift->wednesday_punch_in}}"
                                       data-validation="punchinValidation">
                            </div>
                            <div class="col-4 form-group">
                                <p>Punch Out</p>
                                <input type="text" id="wednesday_punch_out" name="wednesday_punch_out"
                                       class="input-sm form-control time" value="{{$organizationShift->wednesday_punch_out}}"
                                       data-validation="punchoutValidation">
                            </div>
                            <div class="col-1">
                                <i class="fas fa-backspace erase-time"></i>
                            </div>
                        </div>

                        <div class="row">
                            <label for="thursday_punch_in" class="col-3 col-form-label">
                                Thursday <span class="required-field">*</span>
                            </label>
                            <div class="col-4 form-group">
                                <p>Punch In</p>
                                <input type="text" id="thursday_punch_in" name="thursday_punch_in"
                                       class="input-sm form-control time" value="{{$organizationShift->thursday_punch_in}}"
                                       data-validation="punchinValidation">
                            </div>
                            <div class="col-4 form-group">
                                <p>Punch Out</p>
                                <input type="text" id="thursday_punch_out" name="thursday_punch_out"
                                       class="input-sm form-control time" value="{{$organizationShift->thursday_punch_out}}"
                                       data-validation="punchoutValidation">
                            </div>
                            <div class="col-1">
                                <i class="fas fa-backspace erase-time"></i>
                            </div>
                        </div>

                        <div class="row">
                            <label for="friday_punch_in" class="col-3 col-form-label">
                                Friday <span class="required-field">*</span>
                            </label>
                            <div class="col-4 form-group">
                                <p>Punch In</p>
                                <input type="text" id="friday_punch_in" name="friday_punch_in"
                                       class="input-sm form-control time" value="{{$organizationShift->friday_punch_in}}"
                                       data-validation="punchinValidation">
                            </div>
                            <div class="col-4 form-group">
                                <p>Punch Out</p>
                                <input type="text" id="friday_punch_out" name="friday_punch_out"
                                       class="input-sm form-control time" value="{{$organizationShift->friday_punch_out}}"
                                       data-validation="punchoutValidation">
                            </div>
                            <div class="col-1">
                                <i class="fas fa-backspace erase-time"></i>
                            </div>
                        </div>

                        <div class="row">
                            <label for="saturday_punch_in" class="col-3 col-form-label">
                                Saturday <span class="required-field">*</span>
                            </label>
                            <div class="col-4 form-group">
                                <p>Punch In</p>
                                <input type="text" id="saturday_punch_in" name="saturday_punch_in"
                                       class="input-sm form-control time"  value="{{$organizationShift->saturday_punch_in}}"
                                       data-validation="punchinValidation">
                            </div>
                            <div class="col-4 form-group">
                                <p>Punch Out</p>
                                <input type="text" id="saturday_punch_out" name="saturday_punch_out"
                                       class="input-sm form-control time"   value="{{$organizationShift->saturday_punch_out}}"
                                       data-validation="punchoutValidation">
                            </div>
                            <div class="col-1">
                                <i class="fas fa-backspace erase-time"></i>
                            </div>
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
            {!! Form::close() !!}
        </div>
    </div>


@endsection


@section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>

    <script !src="">
        $('.time').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });

        $('#effective_from').flatpickr()
        $('#effective_from_np').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#effective_from_np').val() ? $('#effective_from').val(BS2AD($('#effective_from_np').val())) : '';
            }
        });
        $('#effective_from').change(function(){
            $('#effective_from').val() ? $('#effective_from_np').val(AD2BS($('#effective_from').val())) : '';
        })

        $('.erase-time').click(function () {
            let inputs = $(this).parent().parent().find('.time');
            inputs.each(function (index, input) {
                $(input).val('');
            })
        })

        $.formUtils.addValidator({
            name: 'punchinValidation',
            validatorFunction: function (value, $el, config, language, $form) {
                let punchoutTime=($el.parent().next().find('.time').val());
                if(punchoutTime!='' && value==''){
                    return false
                }
                if (value != '') {
                    return (value <= punchoutTime);
                }
                return true;
            },
            errorMessage: 'Punch In should be less than Punch Out',
            errorMessageKey: ''
        });

        $.formUtils.addValidator({
            name: 'punchoutValidation',
            validatorFunction: function (value, $el, config, language, $form) {
                let punchinTime=($el.parent().prev().find('.time').val());
                if(punchinTime!='' && value==''){
                    return false
                }
                if (value != '') {
                    return (value >= punchinTime);
                }
                return true;
            },
            errorMessage: 'Punch Out should be greater than Punch In',
            errorMessageKey: ''
        });
    </script>
@endsection
