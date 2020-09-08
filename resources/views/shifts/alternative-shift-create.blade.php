@extends('layouts.default', ['crumbroute' => 'alternative-shift-create'])
@section('title', $title)
@section('style')

@endsection
@section('content')

    {{ Form::open(array('route' => 'alternative-shift-store'))  }}
    {{csrf_field()}}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Alternative Staff Shift</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Staff
                            </label>
                            {{$staff->name_eng}}
                            <input type="hidden" name="staff_central_id" value="{{$staff->id}}">
                        </div>

                        @foreach($days_key as $day_key)
                            <div class="form-group row">
                                <label for="title" class="col-3 col-form-label">
                                    {{$days[$day_key]}}
                                </label>
                                {!! Form::select('shift_id['.$day_key.']',$shifts,$previous_alternative_shift->where('day',$day_key)->first()->shift_id ?? $staff->latestShift->shift_id,['class'=>'form-control','placeholder'=>'Select Shift']) !!}
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
    {{ Form::close()  }}
@endsection
@section('script')

@endsection
