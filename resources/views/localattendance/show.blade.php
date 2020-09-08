@extends('layouts.default', ['crumbroute' => 'localattendance-show'])
@section('title', $title)

@section('content')

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Local Attendance Show
        </div>
        <div class="card-block">
            <div class="row">
                <label for="name" class="col-md-2">Staff Name:</label>
                <div class="col-md-9">{{$localattendance->staff->name_eng}}</div>
            </div>

            <div class="row">
                <label for="name" class="col-md-2">Branch:</label>
                <div class="col-md-9">{{$localattendance->staff->branch->office_name}}</div>
            </div>

            <div class="row">
                <label for="name" class="col-md-2">Punch In Time:</label>
                <div class="col-md-9">{{$localattendance->punchin_datetime_np}} ({{$localattendance->punchin_datetime}}
                    )
                </div>
            </div>

            <div class="row">
                <label for="name" class="col-md-2">Punch Out Time:</label>
                <div class="col-md-9">{{$localattendance->punchout_datetime_np}}
                    ({{$localattendance->punchout_datetime}})
                </div>
            </div>

            <div class="row">
                <label for="name" class="col-md-2">Tiffin In Time:</label>
                <div class="col-md-9">{{date('h:i:s a',strtotime($localattendance->tiffinin_datetime))}}</div>
            </div>

            <div class="row">
                <label for="name" class="col-md-2">Tiffin Out Time:</label>
                <div class="col-md-9">{{date('h:i:s a',strtotime($localattendance->tiffinout_datetime))}}</div>
            </div>

            <div class="row">
                <label for="name" class="col-md-2">Lunch In Time:</label>
                <div class="col-md-9">{{date('h:i:s a',strtotime($localattendance->lunchin_datetime))}}</div>
            </div>

            <div class="row">
                <label for="name" class="col-md-2">Lunch Out Time:</label>
                <div class="col-md-9">{{date('h:i:s a',strtotime($localattendance->lunchout_datetime))}}</div>
            </div>

            <div class="row">
                <label for="name" class="col-md-2">Personal In Time:</label>
                <div class="col-md-9">{{date('h:i:s a',strtotime($localattendance->personalin_datetime))}}</div>
            </div>

            <div class="row">
                <label for="name" class="col-md-2">Personal Out Time:</label>
                <div class="col-md-9">{{date('h:i:s a',strtotime($localattendance->personalout_datetime))}}</div>
            </div>

        </div>
    </div>


@endsection

@section('script')

@endsection
