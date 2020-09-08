@extends('layouts.default', ['crumbroute' => 'staff-status-edit'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => array('staff-status-update',$employee_status->id), 'class' => 'employee_status' ))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Edit Staff Status</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Staff Name
                            </label>
                            <select id="staff_central_id" name="staff_central_id" class="input-sm" required>
                                @foreach($staffs as $staff)
                                    <option @if($employee_status->staff_central_id == $staff->id ) selected
                                            @endif value="{{$staff->id}}">{{$staff->name_eng}} {{$staff->main_id}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="date_from_np" class="col-3 col-form-label">Date From</label>
                            {{ Form::text('date_from_np', $employee_status->date_from_np, array('class' => 'form-control nep-date','required' => 'required','id'=>'nep-date1' , 'placeholder' => 'Date From', 'readonly')) }}
                            <input type="hidden" id="date_from" name="date_from"
                                   value="{{$employee_status->date_from}}">
                        </div>
                        <div class="form-group row">
                            <label for="date_to_np" class="col-3 col-form-label">Date To</label>
                            {{ Form::text('date_to_np', $employee_status->date_to_np, array('class' => 'form-control nep-date','id'=>'nep-date2' , 'placeholder' => 'Date To', 'readonly')) }}
                            <input type="hidden" id="date_to" name="date_to" value="{{$employee_status->date_to}}">
                        </div>
                        <div class="form-group row">
                            <label for="date_to_np" class="col-3 col-form-label">Status</label>
                            {{ Form::select('status', $status, $employee_status->status, array('placeholder' => 'Select One...', 'required' => 'required'))  }}

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

    </script>
@endsection
