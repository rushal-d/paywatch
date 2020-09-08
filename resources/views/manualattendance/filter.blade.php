@extends('layouts.default', ['crumbroute' => 'manual-attendance-filter'])
@section('title', $title)
@section('style')
    <style>
        .button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 5px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            margin: 2px 1px;
            cursor: pointer;
        }

        .button-print {
            background-color: #008CBA;
        }


        .visibility {
            /*visibility: visible;*/
            display: block;
            /*visibility: hidden !important;*/
        }

        .non-visibility {
            display: none !important;
        }
    </style>
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                {{--                <div class="level">--}}

                <h5 class="card-header">Manual Attendance</h5>

                <div class="card">
                    <div class="search-box">
                        {{ Form::open(array('route' => 'manual-attendance-filter','method' => 'get', "id"=> 'manual-attendance-filter-form'))  }}
                        <div class="row">

                            <div class="col-md-3">
                                {!! Form::select('branch_id', $branches , request('branch_id'),array('id'=>'branch_id','class'=>'adjust-width','required'=>'required','data-validation' => 'required'
                                 ) ) !!}
                            </div>


                            <div class="col-md-3">
                                {!! Form::select('department_id[]', $departments , request('department_id'), array('id'=>'department_id', 'class'=> 'adjust-width','multiple'=>true, 'placeholder' => 'Select a department') ) !!}

                            </div>


                            <div class="col-md-3">
                                {!! Form::select('job_type_id[]', $jobTypes , request('job_type_id'), array( 'id'=>'job_type_id', 'class'=> 'adjust-width', 'placeholder'=>'Select a Job Type','multiple'=>true) ) !!}

                            </div>
                            <div class="col-md-3">
                                {!! Form::select('designation_id[]', $designations , request('designation_id'), array( 'id'=>'designation_id', 'class'=> 'adjust-width', 'placeholder'=>'Select a Designation','multiple'=>true) ) !!}

                            </div>
                            <div class="col-md-3">
                                {!! Form::select('shift_id[]', $shifts , request('shift_id'), array( 'id'=>'shift_id', 'class'=> 'adjust-width', 'placeholder'=>'Select a Shift','multiple'=>true) ) !!}

                            </div>
                            <div class="col-md-3">
                                <input type="hidden" name="from_date_np" value="{{request('from_date_np')}}">
                                <input type="hidden" name="to_date_np" value="{{request('to_date_np')}}">
                                <button class="button button-print adjust-width" type="submit">Filter</button>
                            </div>

                            {{ Form::close()  }}
                        </div>
                    </div>
                </div>

                <div class="background-color-brown">
                    <div class="level">
                        <div class="flex">
                            <button class="btn btn-primary btn-sm" id="check-all" type="button">Check All</button>
                            <button class="btn btn-danger btn-sm" id="uncheck-all" type="button">Uncheck All</button>
                        </div>
                    </div>
                </div>
                {{--                </div>--}}
                <form action="{{route('manual-attendance-filter-view')}}" method="POST">
                    @csrf
                    <div class="card-block">
                        <div class="form-group">

                            <input type="text" class="form-control" placeholder="Enter a staff name"
                                   id="staff-name-filter">

                            <button class="btn btn-success" id="bulk-select">Bulk Select</button>
                        </div>


                        <div class="card-text">
                            <div class="row">
                                <div class="col-md-4">
                                    @foreach($staffs as $staff)
                                        @php $increment++ @endphp
                                        <span class="visibility">
                                        <input type="checkbox" id="staff_central_id"
                                               value="{{$staff->id}}" class="staff_central_id"
                                               data-staff-main-id="{{$staff->main_id}}"
                                               name="staff_central_id[]" multiple="multiple">{{$staff->name_eng}}
                                        <span>({{$staff->main_id}})</span>
                                    </span>
                                        @if($increment==$breakCount)
                                </div>
                                <div class="col-md-4">
                                    @php $increment=1; @endphp
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-3">
                            <label for="">From Date</label>
                            <input type="text" class="nep-date" id="from-date" name="from_date"
                                   value="{{request('from_date_np')}}"
                                   readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="">To Date</label>
                            <input type="text" class="nep-date" id="to-date" name="to_date"
                                   value="{{request('to_date_np')}}" readonly>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-success btn-sm" style="width: 100%" id="filter" type="submit">Filter
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>


    </div>

    <div>
        <div class="col-md-12">
            <div id="form">

            </div>
        </div>
    </div>


@endsection
@section('script')
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>

    <script>
        var form = $("#form");

        $('#filter').on('click', function (e) {
            $('#form').children().remove();

            let from_date = $('#from-date').val();
            let to_date = $('#to-date').val();

            if (from_date != '' && to_date != '') {

                // var staff_central_ids = $('input[name="staff_central_id[]"]').map(function () {
                var staff_central_ids = $('input[name="staff_central_id[]"]:checked').map(function () {
                    return this.value; // $(this).val()
                }).get();

                if (staff_central_ids.length > 0) {
                    $.post("<?php echo route("manual-attendance-filter-view")?>", {
                        _token: '{{ csrf_token() }}',
                        from_date: from_date,
                        to_date: to_date,
                        staff_central_ids: staff_central_ids,
                        branch_id: '{{request('branch_id')}}'
                    }).done(function (data) {
                        if (data.status == 'true')
                            $('#form').html(data.html);
                        else
                            $('#form').html();
                    });
                } else {
                    e.preventDefault();
                    $('#form').html('<h3 class="alert alert-success text-center">Please select a staff</h3>')
                }
            } else {
                e.preventDefault();
                console.log(from_date);

                $('#form').html('<h3 class="alert alert-success text-center">Please select dates</h3>')
            }


        });

        $('#check-all').click(function () {
            $('.visibility input[type=checkbox]').prop('checked', true);
        });

        $('#uncheck-all').click(function () {
            $('.visibility input[type=checkbox]').prop('checked', false);
        });

    </script>

    <script>
        start_date = '{{\App\Helpers\BSDateHelper::AdToBs('-', date('Y-m-d', strtotime('-2 days')))}}';
        var new_date = start_date.split('-');

        var today_date = new_date[1] + '/' + new_date[2] + '/' + new_date[0];

        $('#from-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            closeOnDateSelect: true,
            disableAfter: today_date,
        });

        $('#to-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            closeOnDateSelect: true,
            disableAfter: today_date,
        });

    </script>

    <script>
        $("#bulk-select").on('click', function (e) {
            e.preventDefault();
            $('#uncheck-all').trigger('click');
            var staff_name_filter_input = $("#staff-name-filter");
            var input_array = staff_name_filter_input.val().split(',');
            $.each(input_array, function (key, value) {
                $('[data-staff-main-id="' + value + '"]').prop('checked', true);
            });
        });

        $("#staff-name-filter").on('keyup', function () {
            var $_this = $(this);
            var staffs_inputs = $('input[name="staff_central_id[]"]');
            var staff_central_id = $('.staff_central_id');

            var staff_name_filter_input = this.value.toLowerCase();
            console.log($_this.val().indexOf(','));
            if (($_this.val().indexOf(',') == -1)) {
                var staff_names = staffs_inputs.map(function (index, input_field) {
                    var staff_name = this.nextSibling.nodeValue.toLowerCase().trim();
                    var staff_main_id = this.dataset["staffMainId"].trim();
                    var staff_name_and_main_id = staff_name + staff_main_id;
                    if (staff_name_and_main_id.indexOf(staff_name_filter_input) === -1) {
                        input_field.parentNode.className = 'non-visibility';
                    } else {
                        input_field.parentNode.className = 'visibility';
                    }
                }).get();

            } else {
                console.log('Visible All');
                let non_visibility_elements = $('.non-visibility');
                $.each(non_visibility_elements, function (index, value) {
                    $(value).removeClass('non-visibility');
                    $(value).addClass('visibility');
                })
            }
        })
    </script>


@endsection
