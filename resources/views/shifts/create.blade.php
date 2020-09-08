@extends('layouts.default', ['crumbroute' => 'shiftcreate'])
@section('title', $title)
@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        input.timepicker {
            width: 55% !important;
        }
    </style>
@endsection
@section('content')

    {{ Form::open(array('route' => 'shift-store'))  }}
    <div class="row">
        <div class="col-md-4 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Shift Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Shift Name <span class="required-field">*</span>
                            </label>
                            {{ Form::text('shift_name', old('shift_name'), array('class' => 'form-control', 'placeholder' => 'Shift Name',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter shift name'))  }}

                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch <span class="required-field">*</span>
                            </label>
                            {{ Form::select('branch_id', $branches,old('branch_id'), array('class' => '', 'placeholder' => 'Branch Name',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please select branch'))  }}

                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Tiffin Duration <span class="required-field">*</span>
                            </label>
                            <input type="number" id="tiffin_duration" name="tiffin_duration"
                                   class="input-sm form-control" value="{{old('tiffin_duration',30)}}"
                                   step="0.01" required>
                        </div>
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Lunch Duration <span class="required-field">*</span>
                            </label>
                            <input type="number" id="lunch_duration" name="lunch_duration"
                                   class="input-sm form-control"
                                   step="0.01" value="{{old('lunch_duration',30)}}" required>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Punch In</h5>
                <div class="card-block">
                    <div class="card-text">

                        <div class="form-group row">
                            <label for="punch_in" class="col-5 col-form-label">
                                Punch In <span class="required-field">*</span>
                            </label>
                            <input type="text" id="punch_in" name="punch_in"
                                   class="input-sm time form-control timepicker"
                                   data-validation="required">
                        </div>

                        <div class="form-group row">
                            <label for="punch_in_start" class="col-5 col-form-label">
                                Punch In From <span class="required-field">*</span>
                            </label>
                            <input type="text" id="punch_in_start" name="punch_in_start"
                                   class="timepicker input-sm time form-control"
                                   data-validation="required lessOrEqualToPunchIn">
                        </div>

                        <div class="form-group row">
                            <label for="punch_in_end" class="col-5 col-form-label">
                                Punch In To<span class="required-field">*</span>
                            </label>
                            <input type="text" id="punch_in_end" name="punch_in_end"
                                   class="input-sm time form-control timepicker"
                                   data-validation="required greaterThanPunchInFromAndLessThanPunchOutTo shouldBeGreaterOrEqualToPunchIn">
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="basic-info card">
                <h5 class="card-header">Lunch Time</h5>
                <div class="card-block">
                    <div class="card-text">

                        <div class="form-group row">
                            <label for="min_lunch_out" class="col-5 col-form-label">
                                Lunch Out <span class="required-field">*</span>
                            </label>
                            <input type="text" id="min_lunch_out" name="min_lunch_out"
                                   class="input-sm time form-control timepicker"
                                   data-validation="required greaterThanPunchInFromAndLessThanPunchOutTo">
                        </div>

                        <div class="form-group row">
                            <label for="max_lunch_in" class="col-5 col-form-label">
                                Lunch In <span class="required-field">*</span>
                            </label>
                            <input type="text" id="max_lunch_in" name="max_lunch_in"
                                   class="input-sm time form-control timepicker"
                                   data-validation="required lunchInValidation">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="basic-info card">
                <h5 class="card-header">Tiffin Time</h5>
                <div class="card-block">
                    <div class="card-text">

                        <div class="form-group row">
                            <label for="min_tiffin_out" class="col-5 col-form-label">
                                Tiffin Out <span class="required-field">*</span>
                            </label>
                            <input type="text" id="min_tiffin_out" name="min_tiffin_out"
                                   class="input-sm time form-control timepicker"
                                   data-validation="required greaterThanPunchInFromAndLessThanPunchOutTo">
                        </div>

                        <div class="form-group row">
                            <label for="max_tiffin_in" class="col-5 col-form-label">
                                Tiffin In <span class="required-field">*</span>
                            </label>
                            <input type="text" id="max_tiffin_in" name="max_tiffin_in"
                                   class="input-sm time form-control timepicker"
                                   data-validation="required tiffinInValidation">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Punch Out</h5>
                <div class="card-block">
                    <div class="card-text">

                        <div class="form-group row">
                            <label for="punch_out" class="col-5 col-form-label">
                                Punch Out <span class="required-field">*</span>
                            </label>
                            <input type="text" id="punch_out" name="punch_out"
                                   class="input-sm time form-control timepicker"
                                   data-validation="required shouldBeGreaterThanPunchInTo">
                        </div>

                        <div class="form-group row">
                            <label for="punch_out_start" class="col-5 col-form-label">
                                Punch Out From <span class="required-field">*</span>
                            </label>
                            <input type="text" id="punch_out_start" name="punch_out_start"
                                   class="input-sm time form-control timepicker"
                                   data-validation="required greaterThanPunchInFromAndLessThanPunchOutTo lessThanPunchOut">
                        </div>

                        <div class="form-group row">
                            <label for="punch_out_end" class="col-5 col-form-label">
                                Punch Out To <span class="required-field">*</span>
                            </label>
                            <input type="text" id="punch_out_end" name="punch_out_end"
                                   class="input-sm time form-control timepicker"
                                   data-validation="required shouldBeGreaterOrEqualToPunchOut">
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $('.time').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });
        $('#punch_in').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: "{{old('punch_in','9:00')}}"
        });
        $('#punch_in_start').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: "{{old('punch_in_start','9:00')}}"
        });
        $('#punch_in_end').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: "{{old('punch_in_end','9:00')}}"
        });
        $('#punch_out').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: "{{old('punch_out','17:00')}}"
        });
        $('#punch_out_start').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: "{{old('punch_out_start','17:00')}}"
        });
        $('#punch_out_end').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: "{{old('punch_out_end','17:00')}}"
        });
        $('#min_tiffin_out,#min_lunch_out').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: "11:00"
        });

        $('#max_tiffin_in,#max_lunch_in').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: "17:00"
        });

        $.formUtils.addValidator({
            name: 'lessOrEqualToPunchIn',
            validatorFunction: function (value, $el, config, language, $form) {
                if (value != '') {
                    let punchin = $('#punch_in').val();
                    return (value <= punchin);
                }
                return true;
            },
            errorMessage: 'Punch In From should be less than Punch In',
            errorMessageKey: ''
        });

        $.formUtils.addValidator({
            name: 'shouldBeGreaterOrEqualToPunchIn',
            validatorFunction: function (value, $el, config, language, $form) {
                if (value != '') {
                    let punchin = $('#punch_in').val();
                    return (value >= punchin);
                }
                return true;
            },
            errorMessage: 'Punch In To should be greater than or equal to Punch In',
            errorMessageKey: ''
        });

        $.formUtils.addValidator({
            name: 'greaterThanPunchInFromAndLessThanPunchOutTo',
            validatorFunction: function (value, $el, config, language, $form) {
                if (value != '') {
                    let punchinFrom = $('#punch_in_start').val();
                    let punchOutTo = $('#punch_out_end').val();
                    return (value >= punchinFrom && value <= punchOutTo);
                }
                return true;
            },
            errorMessage: 'Time should be greater than Punch In From and less than Punch Out To',
            errorMessageKey: ''
        });

        $.formUtils.addValidator({
            name: 'lunchInValidation',
            validatorFunction: function (value, $el, config, language, $form) {
                if (value != '') {
                    let lunchOutFrom = $('#min_lunch_out').val();
                    let punchOutTo = $('#punch_out_end').val();
                    return (value > lunchOutFrom && value <= punchOutTo);
                }
                return true;
            },
            errorMessage: 'Time should be greater than Lunch Out and less than Punch Out To',
            errorMessageKey: ''
        });

        $.formUtils.addValidator({
            name: 'tiffinInValidation',
            validatorFunction: function (value, $el, config, language, $form) {
                if (value != '') {
                    let tiffinOutFrom = $('#min_tiffin_out').val();
                    let punchOutTo = $('#punch_out_end').val();
                    return (value > tiffinOutFrom && value <= punchOutTo);
                }
                return true;
            },
            errorMessage: 'Time should be greater than Tiffin Out and less than Punch Out To',
            errorMessageKey: ''
        });

        $.formUtils.addValidator({
            name: 'shouldBeGreaterThanPunchInTo',
            validatorFunction: function (value, $el, config, language, $form) {
                if (value != '') {
                    let punch_in_end = $('#punch_in_end').val();
                    return (value > punch_in_end);
                }
                return true;
            },
            errorMessage: 'Punch Out Time Should be greater than punch in to',
            errorMessageKey: ''
        });

        $.formUtils.addValidator({
            name: 'shouldBeGreaterOrEqualToPunchOut',
            validatorFunction: function (value, $el, config, language, $form) {
                if (value != '') {
                    let punch_out = $('#punch_out').val();
                    return (value >= punch_out);
                }
                return true;
            },
            errorMessage: 'Punch Out To should be greater or equal to punch out',
            errorMessageKey: ''
        });

        $.formUtils.addValidator({
            name: 'lessThanPunchOut',
            validatorFunction: function (value, $el, config, language, $form) {
                if (value != '') {
                    let punch_out = $('#punch_out').val();
                    return (value <= punch_out);
                }
                return true;
            },
            errorMessage: 'Punch Out From should be less or equal to punch out',
            errorMessageKey: ''
        });
    </script>
@endsection
