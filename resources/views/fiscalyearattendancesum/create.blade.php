@extends('layouts.default', ['crumbroute' => 'fiscal-year-attendance-create'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'fiscalyearattendancesum-store'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Fiscal Year Attendance</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="branch_id" class="col-3 col-form-label">
                                Branch
                            </label>
                            <select id="branch_id" name="branch_id" class="input-sm" required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $bran)
                                    <option value="{{$bran->office_id}}">{{$bran->office_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="fiscal_year" class="col-3 col-form-label">
                                Fiscal Year
                            </label>
                            <select id="fiscal_year" name="fiscal_year" class="input-sm" required>
                                @foreach($fiscal_years as $fiscal)
                                    <option value="{{$fiscal->id}}">{{$fiscal->fiscal_code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Proceed',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-sm-12">


        </div>

    </div>
    {{ Form::close()  }}
@endsection
