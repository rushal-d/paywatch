@extends('layouts.default', ['crumbroute' => 'leave-request-create'])
@section('title', $title)
@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{asset('assets/css/uploadfiledropzone.css')}}">
    <style>
        .mydrop {
            width: 305px;
            margin-left: -49px;
        }
    </style>
@endsection
@section('content')

    {{ Form::open(array('route' => 'leaverequest-save', 'id'=>'request-leave-form'))  }}

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Request Leave Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            {!! Form::label('staff_central_id', 'Staff Name ', ['class' => 'col-3 col-form-label']) !!}
                            <input type="text" id="staff_central_id" name="staff_central_id"
                                   value="{{old('staff_central_id')}}" class="input-sm" required>                        </div>

                        <div class="form-group row">
                            {!! Form::label('leave_id', 'Leave Name', ['class' => 'col-3 col-form-label']) !!}
                            {{ Form::select('leave_id', $leavetypes, null, array('id' => 'leave_id' ,'placeholder' => 'Select One...', 'required' => 'required'))  }}
                        </div>

                        <div class="form-group row">
                            {!! Form::label('description', 'Description', ['class' => 'col-3 col-form-label']) !!}

                            {{ Form::textarea('description', old('description'), ['id' => 'description','class' => 'form-control' ,'placeholder' => 'Description...', 'required' => 'required'])  }}
                        </div>

                        <div class="form-group row">
                            <label for="leave_id" class="col-3 col-form-label">
                                Leave Balance
                            </label>
                            <input type="text" readonly id="balance_details" name="leave_balance">
                        </div>

                        <div class="form-group row">
                            <label for="from_leave_day_np" class="col-3 col-form-label">
                                Leave From
                            </label>
                            <div>
                                {{ Form::text('from_leave_day_np', old('from_leave_day_np'), array('class' => 'form-control nep-date','required' => 'required','id'=>'nep-date1' , 'placeholder' => 'From Date (BS)', 'readonly' => 'readonly')) }}
                            </div>
                            <div>
                                {{Form::text('from_leave_day',old('from_leave_day'),['class'=>'form-control flatpickr','id'=>'from_leave_day','placeholder'=>'From Date (AD)'])}}
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="to_leave_day_np" class="col-3 col-form-label">
                                Request Days
                            </label>
                            <div>
                                {{ Form::text('request_days', old('request_days'), array('class' => 'form-control','id'=>'request_days' , 'placeholder' => 'Enter Days'))  }}

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="to_leave_day_np" class="col-3 col-form-label">
                                Leave To
                            </label>
                            <div>
                                {{ Form::text('to_leave_day_np', old('to_leave_day_np'), array('class' => 'form-control nep-date','id'=>'nep-date2' , 'placeholder' => 'To Date (BS)', 'readonly' => 'readonly'
                          ))  }}
                            </div>
                            <div>
                                {{Form::text('to_leave_day',old('to_leave_day'),['class'=>'form-control flatpickr','id'=>'to_leave_day','placeholder'=>'To Date (AD)'])}}
                            </div>
                        </div>

                        @if($organization->organization_structure==2)
                            <div class="form-group row half-day-allow"
                                 @if(old('request_days')!=1) style="display: none" @endif>
                                <label for="half_day" class="col-3 col-form-label">Half Day</label>
                                <input type="checkbox" readonly id="is_half" name="is_half" value="1"
                                       @if(old('is_half')==1) checked @endif>
                            </div>
                        @endif

                        <div class="form-group row">
                            <label for="public_holidays" class="col-3 col-form-label">Public Holidays</label>
                            <input type="text" readonly id="public_holidays" name="public_holidays">
                        </div>
                        <div class="form-group row">
                            <label for="weekend_days" class="col-3 col-form-label">Weekend Days</label>
                            <input type="text" readonly id="weekend_days" name="weekend_days">
                        </div>
                        <div class="form-group row">
                            <label for="holiday_days" class="col-3 col-form-label">Leave Days</label>
                            <input type="text" readonly id="holiday_days" name="holiday_days">
                        </div>
                        <div class="form-group row file-upload"
                             @if(old('staff_central_id')==null) style="display: none" @endif>
                            @foreach($file_types as $file)
                                <label class="col-3 col-form-label">
                                    {{$file->file_type}}
                                </label>
                                <div class="col-9">
                                    <div class="upload-file" id="upload-file-{{$file->id}}">
                                        <div class="fallback">
                                            <input type="file" name="file">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

                {{--  Save --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-right form-control">
                            {{ Form::submit('Save',array('id' => 'submit-form', 'class'=>'btn btn-success btn-lg'))}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close()  }}
@endsection
@section('script')
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{asset('assets/js/dropzone.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/css/dropzone.css')}}">

    @foreach($file_types as $file)
        <script>
            $('#staff_central_id').change(function () {
                let staffCentralId = $('#staff_central_id').val();
                if (staffCentralId != '') {
                    $('.file-upload').slideDown("slow");
                } else {
                    $('.file-upload').hide("slow");
                }
            })


            $("#upload-file-{{$file->id}}").dropzone({

                url: '{{route('staff-file-upload')}}',
                maxFiles: 100,
                paramName: 'track',
                _token: '{{csrf_token()}}',
                init: function () {
                    this.on("sending", function (file, xhr, formData) {
                        formData.append("staff_id", $('#staff_central_id').val());
                        formData.append("file_type_id", '{{ $file->id}}');
                        formData.append("responseType", 'id');
                    });
                },
                // acceptedFiles: 'audio/*',
                addRemoveLinks: true,
                acceptedFiles: 'application/pdf,image/jpeg,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/docx',
                dictDefaultMessage: "Upload Your File Here",
                sending: function (file, xhr, formData) {
                    // Pass token. You can use the same method to pass any other values as well such as a id to associate the image with for example.
                    formData.append("_token", '{{csrf_token()}}'); // Laravel expect the token post value to be named _token by default
                },
                success: function (file, response) {
                    $('#request-leave-form').append('<input type="hidden" name="upload[]" multiple value="' + response + '">');
                    $(file._removeLink).attr('filename', response);
                },
                removedfile: function (file) {
                    fileValue = $(file._removeLink).attr('filename');
                    $('input[value="' + fileValue + '"]').remove();
                    var _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                }, headers: {
                    'X-CSRFToken': $('meta[name="token"]').attr('content')
                }
            });


        </script>
    @endforeach


    <script>
        $('.flatpickr').flatpickr()
        toastr.options = {
            "showDuration": "10000",
            "hideDuration": "1000",
            "timeOut": "10000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        $('#nep-date1').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                if ($('#nep-date1').val() != '') {
                    $('#from_leave_day').val(BS2AD($('#nep-date1').val()))
                }

                if ($('#from_leave_day').val() != '' && $('#request_days').val() != '') {
                    $('#request_days').trigger('keyup');
                } else if ($('#from_leave_day').val() != '' && $('#to_leave_day').val() != '') {
                    daysDifference($('#from_leave_day').val(), $('#to_leave_day').val())
                }
                calculateDays();
            }
        });
        $('#nep-date2').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {

                if ($('#nep-date2').val() != '') {
                    $('#to_leave_day').val(BS2AD($('#nep-date2').val()))
                }
                if ($('#from_leave_day').val() != '' && $('#to_leave_day').val() != '') {
                    daysDifference($('#from_leave_day').val(), $('#to_leave_day').val())
                }
                calculateDays();
            }
        });
        $('#from_leave_day').change(function () {
            $this = $(this);
            $('#nep-date1').val(AD2BS($this.val()));
            if ($('#from_leave_day').val() != '' && $('#request_days').val() != '') {
                $('#request_days').trigger('keyup');
            } else if ($('#from_leave_day').val() != '' && $('#to_leave_day').val() != '') {
                daysDifference($('#from_leave_day').val(), $('#to_leave_day').val())
            }
            calculateDays();
        });
        $('#to_leave_day').change(function () {
            $this = $(this);
            $('#nep-date2').val(AD2BS($this.val()));
            if ($('#from_leave_day').val() != '' && $('#to_leave_day').val() != '') {
                daysDifference($('#from_leave_day').val(), $('#to_leave_day').val())
            }
            calculateDays();
        });

        var delay = (function () {
            var timer = 0;
            return function (callback, ms) {
                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
        })();

        ;(function ($, window, document, undefined) {
            $("#request_days").on("keyup", function () {
                var date = new Date($("#from_leave_day").val()),
                    days = parseInt($("#request_days").val(), 10);
                @if($organization->organization_structure==2)
                if (days == 1) {
                    $('.half-day-allow').slideDown("slow")
                } else {
                    $("#is_half").prop("checked", false);
                    $('.half-day-allow').hide("slow");
                }
                @endif

                if (!isNaN(date.getTime())) {
                    date.setDate(date.getDate() + days - 1);
                    $("#to_leave_day").val(date.toInputFormat());
                    $('#nep-date2').val(AD2BS($("#to_leave_day").val()));
                } else {

                }
                delay(function () {
                    calculateDays()
                }, 1000)
            });


            //From: http://stackoverflow.com/questions/3066586/get-string-in-yyyymmdd-format-from-js-date-object
            Date.prototype.toInputFormat = function () {
                var yyyy = this.getFullYear().toString();
                var mm = (this.getMonth() + 1).toString(); // getMonth() is zero-based
                var dd = this.getDate().toString();
                return yyyy + "-" + (mm[1] ? mm : "0" + mm[0]) + "-" + (dd[1] ? dd : "0" + dd[0]); // padding
            };
        })(jQuery, this, document);

        $('#is_half').change(function () {
            calculateDays()
        })

        function daysDifference(from_date_en, to_date_en) {
            var date1 = new Date(from_date_en);
            var date2 = new Date(to_date_en);
            var diffDays = parseInt((date2 - date1) / (1000 * 60 * 60 * 24)) + 1;
            @if($organization->organization_structure==2)
            if (diffDays == 1) {
                $('.half-day-allow').slideDown("slow")
            } else {
                $("#is_half").prop("checked", false);
                $('.half-day-allow').hide("slow");
            }
            @endif
            $('#request_days').val(diffDays);
            calculateDays()
        }

        function calculateDays() {
            let staff_central_id = $('#staff_central_id').val();
            let leave_id = $('#leave_id').val();
            let date_from = $('#from_leave_day').val();
            let date_to = $('#to_leave_day').val();
            let date_from_np = $('#nep-date1').val();
            let date_to_np = $('#nep-date2').val();
            let is_half_day = $('#is_half').is(':checked') ? 1 : 0;

            if (staff_central_id != '' && leave_id != "" && date_from != "" && date_to != "" && date_from.indexOf('NaN') == -1 && date_to.indexOf('NaN') == -1) {
                @if($organization->organization_structure==1)
                $.ajax({
                    url: '{{route('check-public-holiday')}}',
                    type: 'POST',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'staff_id': staff_central_id,
                        'date_from': date_from,
                        'date_to': date_to,
                    },
                    success: function (data) {
                        toastr.clear();
                        $('#holiday_days').val(data.holiday_days);
                        $('#public_holidays').val(data.public_holidays);
                        $('#weekend_days').val(data.weekend);

                        let balance = $('#balance_details').val();
                        if (data.holiday_days > balance) {
                            toastr.error('Leave Days Exceed Leave Balance')
                        }
                    }
                });
                @else
                $.ajax({
                    url: '{{route('check-calender-holiday-conditions')}}',
                    type: 'POST',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'staff_id': staff_central_id,
                        'date_from_np': date_from_np,
                        'date_to_np': date_to_np,
                        'leave_id': leave_id,
                        'is_half_day': is_half_day,

                    },
                    success: function (data) {
                        toastr.clear();
                        if (data['status']) {
                            toastr.success(data['message'])
                            $('#holiday_days').val(data.holiday_days);
                            $('#public_holidays').val(data.public_holiday);
                            $('#weekend_days').val(data.weekend_day);
                        } else {
                            toastr.error(data['message'])
                            $('#holiday_days').val('');
                            $('#public_holidays').val('');
                            $('#weekend_days').val('');
                        }
                    }
                });
                @endif
            }

        }


    </script>
    <script>


        $('#leave_id,#staff_central_id').on('change', function () {
            loadLeaveBalance()
        });
        $(document).ready(function () {
            loadStaff();
            loadLeaveBalance();
        });

        function loadLeaveBalance() {
            var leave_id = $('#leave_id').val();
            var staff_central_id = $('#staff_central_id').val();
            $.ajax({
                url: '{{ route('get-leave-balance-details') }}?leave_id=' + encodeURIComponent(leave_id) + '&staff_central_id=' + encodeURIComponent(staff_central_id),
                type: 'GET',
                error: function () {
                    toastr.clear();
                    toastr.error('Error Occured! Please try again!')
                },
                success: function (res) {
                    if (res) {
                        var balance_details = 0;
                        if (res.balance != null) {
                            balance_details = (res.balance.balance)
                        }

                        $('#balance_details').val(balance_details);

                    } else {
                        $('#balance_details').val('0');
                    }
                }
            });
            calculateDays();
        }

        function loadStaff() {
            $.ajax({
                url: '{{ route('get-staff') }}',
                type: 'GET',
                dataType: 'json',
                data: {
                    'limit': 15,
                    'staff_central_id': '{{old('staff_central_id')}}',
                },
                success: function (data) {
                    $('#staff_central_id').selectize({
                        valueField: 'id',
                        labelField: 'name_eng',
                        searchField: ['name_eng', 'main_id'],
                        options: data['staffs'],
                        preload: true,
                        maxItems: 1,
                        create: false,
                        render: {
                            option: function (item, escape) {
                                return '<div class="suggestions"><div> Name: ' + item.name_eng + '</div>' +
                                    '<div> Branch ID: ' + item.main_id + '</div>' +
                                    '<div> Branch: ' + item.branch.office_name + '</div>' +
                                    '<div> CID: ' + item.staff_central_id + '</div>' +
                                    '</div>';
                            }
                        },
                        load: function (query, callback) {
                            if (!query.length) return callback();
                            $.ajax({
                                url: '{{ route('get-staff') }}?search=' + encodeURIComponent(query) + '&limit=15',
                                type: 'GET',
                                error: function () {
                                    callback();
                                },
                                success: function (res) {
                                    callback(res.staffs);
                                }
                            });
                        }
                    });
                }
            });
        }

    </script>

@endsection
