@extends('layouts.default', ['crumbroute' => 'fiscal-year-attendance-edit'])
@section('title', $title)
@section('content')
    {{ Form::open(array('route' => array('fiscalyearattendancesum-update',$fiscal_year_attendance_sum->id)))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Fiscal Year Attendance</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="branch_id" class="col-3 col-form-label">
                                Staff Name
                            </label>
                            <p>   {{$fiscal_year_attendance_sum->staff->name_eng}}</p>
                        </div>
                        <div class="form-group row">
                            <label for="fiscal_year" class="col-3 col-form-label">
                                Attendance
                            </label>
                            <input type="number" value="{{$fiscal_year_attendance_sum->total_attendance}}"
                                   class="form-control" placeholder="Input Attendance" name="total_attendance"
                                   data-validation="required" data-validation-error-msg="Please enter a attendance">

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    @if(!empty($previous))
                        <div class="text-left form-control">
                            <a href="{{route('fiscalyearattendancesum-edit',$previous)}}"
                               class="btn btn-success btn-lg">Previous</a>
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="text-right form-control">
                        {{ Form::submit('Next',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{ Form::close()  }}
@endsection
