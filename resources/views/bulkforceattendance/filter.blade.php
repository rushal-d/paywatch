@extends('layouts.default', ['crumbroute' => 'bulk-force-attendance-filter'])
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

        .adjust-width {
            max-width: 150px;
            width: 100%;
            margin-left: 10px;
            display: block;
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

                <h5 class="card-header">Filter Bulk Force Attendance</h5>

                <div class="card">
                    <div class="search-box">
                        {{ Form::open(array('route' => 'bulk-force-show-filter','method' => 'get', "id"=> 'bulk-force-attendance-filter-form'))  }}
                        <div class="row">


                            {!! Form::select('branch_id', $branches , request('branch_id'),array('id'=>'branch_id','class'=> 'adjust-width','required'=>'required','data-validation' => 'required',
                                     ) ) !!}

                            {!! Form::select('department_id', $departments , request('department_id'), array('id'=>'department_id', 'class'=> 'adjust-width') ) !!}

                            <div class="staff-form-group">
                                <span id="staff"></span>
                                {{--<input type="text" id="staff_central_id" name="staff_central_id" class="input-sm adjust-width"--}}
                                {{--placeholder="Staff Name"--}}
                                {{--value="{{request('staff_central_id') ? : null}}"--}}
                                {{--disabled>--}}
                            </div>
                            {{ Form::text('from_date_np', request('from_date_np'), array('class' => 'form-control nep-date adjust-width','id'=>'nep-date1', 'placeholder' => 'Date From','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                                     ))  }}
                            <input type="hidden" id="from_date" name="from_date"
                                   value="{{request('from_date' ?: null)}}">

                            {!! Form::select('job_type_id', $jobTypes , request('job_type_id'), array( 'id'=>'job_type_id', 'class'=> 'adjust-width', 'placeholder'=>'Select a Job Type') ) !!}

                            {!! Form::select('designation_id', $designations , request('designation_id'), array( 'id'=>'designation_id', 'class'=> 'adjust-width', 'placeholder'=>'Select a Designation') ) !!}

                            {!! Form::select('shift_id', $shifts , request('shift_id'), array( 'id'=>'shift_id', 'class'=> 'adjust-width', 'placeholder'=>'Select a Shift') ) !!}

                            <button class="button button-print adjust-width" type="submit">Filter</button>

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
                <div class="card-block">
                    <div class="form-group">

                        <input type="text" class="form-control" placeholder="Enter a staff name" id="staff-name-filter">
                    </div>


                    <div class="card-text">
                        <div class="row">
                            <div class="col-md-4">
                                @foreach($staffs as $staff)
                                    @php $increment++ @endphp
                                    <span class="visibility">
                                        <input type="checkbox" id="staff_central_id"
                                               value="{{$staff->id}}"
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
                <button class="btn btn-success btn-sm" id="filter" type="button">Filter</button>
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

        $('#filter').on('click', function () {
            $('#form').children().remove();

            // var staff_central_ids = $('input[name="staff_central_id[]"]').map(function () {
            var staff_central_ids = $('input[name="staff_central_id[]"]:checked').map(function () {
                return this.value; // $(this).val()
            }).get();

            if (staff_central_ids.length > 0) {
                $.post("<?php echo route("bulk-force-show-filter-view")?>", {
                    _token: '{{ csrf_token() }}',
                    from_date: '{{ request('from_date') }}',
                    from_date_np: '{{ request('from_date_np') }}',
                    staff_central_ids: staff_central_ids,
                    branch_id: '{{request('branch_id')}}'
                }).done(function (data) {
                    if (data.status == 'true')
                        $('#form').html(data.html);
                    else
                        $('#form').html();
                });
            } else {
                $('#form').html('<h3 class="alert alert-success text-center">Please select a staff</h3>')
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
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            closeOnDateSelect: true,
            onChange: function (e) {
                $('#nep-date1').next().val(BS2AD($('#nep-date1').val()));
                $("#bulk-force-attendance-filter-form").submit();
            }
        });

    </script>

    <script>
        $("#staff-name-filter").on('keyup', function () {
            var staff_name_filter_input = this.value.toLowerCase();
            var staff_names = $('input[name="staff_central_id[]"]').map(function (index, input_field) {
                var staff_name = this.nextSibling.nodeValue.toLowerCase().trim();
                var staff_main_id = this.dataset["staffMainId"].trim();
                var staff_name_and_main_id = staff_name + staff_main_id;
                if (staff_name_and_main_id.indexOf(staff_name_filter_input) === -1) {
                    input_field.parentNode.className = 'non-visibility';
                } else {
                    input_field.parentNode.className = 'visibility';
                }
            }).get();

        })
    </script>

    <script>
        $('#branch_id').change(function(){
            $("#bulk-force-attendance-filter-form").submit();
        });
    </script>

    <script>
        $('#department_id').change(function(){
            $("#bulk-force-attendance-filter-form").submit();
        });

        $('#job_type_id').change(function(){
            $("#bulk-force-attendance-filter-form").submit();
        });

        $('#designation_id').change(function(){
            $("#bulk-force-attendance-filter-form").submit();
        });

        $('#shift_id').change(function(){
            $("#bulk-force-attendance-filter-form").submit();
        });
    </script>
@endsection
