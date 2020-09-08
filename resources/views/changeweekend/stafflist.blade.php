@extends('layouts.default', ['crumbroute' => 'change-bulk-weekend-staff-list'])
@section('title', $title)
@section('style')
    <style>
        .shift_container {
            width: 72%;
        }

        .shift_container > .selectize-control {
            width: 100% !important;
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

    {{ Form::open(array('route' => 'change-weekend-store'))  }}
    {{csrf_field()}}
    <div class="row">
        <div class="col-md-8 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff List
                    <button class="btn btn-primary btn-sm" id="check-all" type="button">Check All</button>
                    <button class="btn btn-danger btn-sm" id="uncheck-all" type="button">Uncheck All</button>
                </h5>
                <div class="card-block">
                    <div class="form-group">

                        <input type="text" class="form-control" placeholder="Enter a staff name" id="staff-name-filter">
                    </div>
                    <div class="card-text">
                        <div class="row">
                            <div class="col-md-4">
                                @foreach($staffs as $staff)
                                    @php $i++ @endphp
                                    <span class="visibility">

                                    <input type="checkbox" id="staff_central_id"
                                           value="{{$staff->id}}"
                                           data-staff-main-id="{{$staff->main_id}}"
                                           name="staff_central_id[]" multiple="multiple">{{$staff->name_eng}}
                                        <span>({{$staff->main_id}})</span>
                                    </span>
                                    @if($i==$break_count)
                            </div>
                            <div class="col-md-4">
                                @php $i=1; @endphp
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Weekend</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="row">

                            <label for="title" id="shift" class="col-3 col-form-label">
                                Weekend
                            </label>
                            <div class="shift_container">
                                {{ Form::select('weekend', $weekends,null, array('class' => '', 'placeholder' => 'Weekend',
                         'data-validation' => 'required',
                         'data-validation-error-msg' => 'Please select shift'))  }}

                            </div>

                            <label for="effective_from" class="col-3 col-form-label">
                                Effective From
                            </label>
                            {{ Form::text('effective_from', \App\Helpers\BSDateHelper::AdToBs('-',date('Y-m-d')), array('class' => '', 'placeholder' => 'Effective From',
                            'id'=>'effective-from','readonly'
                            ))  }}
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Change Weekend',array('class'=>'btn btn-success btn-lg'))}}
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
        var start_date = $('#effective-from').val();
        var new_date = start_date.split('-');
        yes_date = new_date[1] + '/' + (new_date[2] - 2) + '/' + new_date[0];


        $('#effective-from').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 10,
        });
        $('#check-all').click(function () {
            $('.visibility input[type=checkbox]').prop('checked', true);
        });

        $('#uncheck-all').click(function () {
            $('.visibility input[type=checkbox]').prop('checked', false);
        });


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
@endsection
