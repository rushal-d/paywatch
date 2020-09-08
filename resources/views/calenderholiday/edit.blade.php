@extends('layouts.default', ['crumbroute' => 'calenderholidayedit'])
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

    {{ Form::open(array('route' => array('calender-holiday-update',$calendarholiday->id), 'id'=>'grant-leave-form' ))  }}


    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Approved Holiday Information</h5>

                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Staff Name
                            </label>
                            {{$calendarholiday->staff->name_eng ?? ''}}

                        </div>

                        <div class="form-group row">
                            <label for="leave_id" class="col-3 col-form-label">
                                Leave Name
                            </label>
                            {{$calendarholiday->leave->leave_name ?? ''}}
                        </div>

                        <div class="form-group row">
                            <label for="from_leave_day_np" class="col-3 col-form-label">
                                Leave From
                            </label>
                            <div>
                                {{ Form::text('from_leave_day_np', old('from_leave_day_np',$calendarholiday->from_leave_day_np), array('class' => 'form-control nep-date','required' => 'required','id'=>'nep-date1' , 'placeholder' => 'From Date (BS)', 'readonly'
                             ))  }}
                            </div>
                            <div>
                                {{Form::text('from_leave_day', old('from_leave_day',$calendarholiday->from_leave_day),['class'=>'form-control flatpickr','id'=>'from_leave_day','placeholder'=>'From Date (AD)'])}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="to_leave_day_np" class="col-3 col-form-label">
                                Request Days
                            </label>
                            <div>
                                {{ Form::text('request_days', old('request_days',ceil($calendarholiday->leave_days)), array('class' => 'form-control','id'=>'request_days' , 'placeholder' => 'Enter Days'))  }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="to_leave_day_np" class="col-3 col-form-label">
                                Leave To
                            </label>
                            <div>
                                {{ Form::text('to_leave_day_np', old('to_leave_day_np',$calendarholiday->to_leave_day_np), array('class' => 'form-control nep-date','id'=>'nep-date2' , 'placeholder' => 'Enter Leave Day', 'readonly'
                          ))  }}
                            </div>
                            <div>
                                {{Form::text('to_leave_day',old('to_leave_day',$calendarholiday->to_leave_day),['class'=>'form-control flatpickr','id'=>'to_leave_day','placeholder'=>'To Date (AD)'])}}
                            </div>
                        </div>
                        @if($organization->organization_structure==2)
                            <div class="form-group row half-day-allow"
                                 @if(ceil($calendarholiday->leave_days)!=1 || (old('request_days')!=null && old('request_days')!=1)) style="display: none" @endif>
                                <label for="half_day" class="col-3 col-form-label">Half Day</label>
                                <input type="checkbox" readonly id="is_half" name="is_half" value="1"
                                       @if($calendarholiday->leave_days==0.5 || old('is_half')==1) checked @endif>
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
                        <div class="form-group row">
                            @foreach($file_types as $file)
                                <label for="result" class="col-3 col-form-label">
                                    {{$file->file_type}}
                                </label>
                                <div class="col-9">
                                    <div class="upload-file" id="upload-file-{{$file->id}}">
                                        <div class="fallback">
                                            <input type="file" name="file">
                                        </div>
                                    </div>
                                    <div class="row">
                                        @foreach($calendarholiday->calenderHolidayFiles->where('staffFile.file_type_id',$file->id) as $calenderHolidayFile)

                                            <div class="col-md-6">
                                                <div class="text-center">
                                                    <div>
                                                        <a href="{{route('staff-file-download',$calenderHolidayFile->staffFile->file_name)}}">
                                                            <i class="fas fa-file-alt"></i>
                                                        </a>
                                                    </div>
                                                    <p> {{$calenderHolidayFile->staffFile->file_name}}</p>
                                                    <input type="hidden" name="upload[]" multiple
                                                           value="{{$calenderHolidayFile->staffFile->id}}">
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger remove-file"
                                                            data-id="{{$calenderHolidayFile->staffFile->id}}"><i
                                                            class="far fa-trash-alt"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
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
                            {{ Form::submit('Save',array('class'=>'btn btn-success btn-lg'))}}
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
            $("#upload-file-{{$file->id}}").dropzone({

                url: '{{route('staff-file-upload')}}',
                maxFiles: 100,
                paramName: 'track',
                _token: '{{csrf_token()}}',
                init: function () {
                    this.on("sending", function (file, xhr, formData) {
                        formData.append("staff_id", '{{$calendarholiday->staff_central_id}}');
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
                    $('#grant-leave-form').append('<input type="hidden" name="upload[]" multiple value="' + response + '">');
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
            $('.remove-file').click(function () {
                $this = $(this);
                let id = ($this.data('id'));
                $.ajax({
                    url: '{{route('staff-file-remove')}}',
                    type: 'POST',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'id': id
                    },
                    success: function (data) {
                        if (data) {
                            $this.parent().parent().remove();
                        }
                    }
                })
            })

        </script>
    @endforeach
    <script>
        $('.flatpickr').flatpickr()
        toastr.options = {
            "showDuration": "1500",
            "hideDuration": "1000",
            "timeOut": "5000",
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
                calculateDays()
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
        });

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
            let staff_central_id = '{{$calendarholiday->staff_central_id}}';
            let leave_id = '{{$calendarholiday->leave_id}}';
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
                        'calenderholiday': '{{$calendarholiday->id}}',

                    },
                    success: function (data) {
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

        $(document).ready(function () {
            calculateDays()
        });

    </script>
@endsection
