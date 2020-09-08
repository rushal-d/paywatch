@extends('layouts.default', ['crumbroute' => 'shiftvisual'])
@section('title', $title)

@section('style')
    <style>
        .wrapper {
            overflow: hidden;
        }

        .hori-child {
            width: 100%;
            padding-left: 5px;
            background: #ddd;
            position: relative;
        }

        .hori-child:after {
            content: '';
            background: #000;
            height: 10000px;
            width: 1px;
            position: absolute;
            left: 0;
        }

        .hori-child:before {
            content: '';
            background: #eee;
            height: 10000px;
            width: 1px;
            position: absolute;
            left: 50%;
            top: 21px;
        }

        .verticle-title {
            background: #eee;
            padding: 5px 0px;
            margin-bottom: 3px;
        }

        .color-box {
            text-align: center;
            margin-top: 5px;
            color: #fff;
            font-weight: bold;
        }
        .color-box a{
            text-align: center;
            margin-top: 5px;
            color: #fff;
            font-weight: bold;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card">

                <div class="search-box">
                    {{ Form::open(array('route' => 'shift-visual', 'method'=> 'GET' ,'class' => 'search-form')) }}
                    <div class="row">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            {{ Form::label('branch_id', 'Branch') }}
                            {!! Form::select('branch_id', $branches , request('branch_id'),array('id'=>'branch_id','class'=> 'adjust-width','data-validation' => 'required',
                                     ) ) !!}
                        </div>


                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <button class="btn btn-outline-success btn-reset"><i
                                    class="fa fa-search"></i> Search</button>
                            <a class="btn btn-outline-success btn-reset" href="{{ route('shift-visual')}}"><i
                                    class="icon-refresh"></i> Reset</a>
                        </div>
                    </div>
                    {{ Form::close()  }}
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="wrapper">
                        <div class="row no-gutters">
                            <div class="col-2"></div>
                            <div class="col">
                                <div class="hori-child">7 A.M.</div>
                            </div>
                            <div class="col">
                                <div class="hori-child">8 A.M.</div>
                            </div>
                            <div class="col">
                                <div class="hori-child">9 A.M.</div>
                            </div>
                            <div class="col">
                                <div class="hori-child">10 A.M.</div>
                            </div>
                            <div class="col">
                                <div class="hori-child">11 A.M.</div>
                            </div>
                            <div class="col">
                                <div class="hori-child">12 P.M.</div>
                            </div>
                            <div class="col">
                                <div class="hori-child">1 P.M.</div>
                            </div>
                            <div class="col">
                                <div class="hori-child">2 P.M.</div>
                            </div>
                            <div class="col">
                                <div class="hori-child">3 P.M.</div>
                            </div>
                            <div class="col">
                                <div class="hori-child">4 P.M.</div>
                            </div>
                            <div class="col">
                                <div class="hori-child">5 P.M.</div>
                            </div>
                            <div class="col">
                                <div class="hori-child">6 P.M.</div>
                            </div>
                            <div class="col">
                                <div class="hori-child">7 P.M.</div>
                            </div>
                            <div class="col">
                                <div class="hori-child">8 P.M.</div>
                            </div>
                            <div class="col">
                                <div class="hori-child">9 P.M.</div>
                            </div>
                            <div class="col">
                                <div class="hori-child">10 P.M.</div>
                            </div>

                        </div><!--  subrow subrow subrow close  -->
                        @foreach($data as $shift_record)
                            <div class="row no-gutters">
                                <div class="col-2">
                                    <div class="verticle-title">{{$shift_record['shift_name']}}</div>
                                </div>
                                <div class="col-10">
                                    <div class="verticle-box">
                                        <div class="color-box" style="width: {{$shift_record['duration_percentage']}}%;margin-left: {{$shift_record['margin_percentage']}}%;background:{{$shift_record['color']}} ">
                                            <a href="{{route('staff-main',['shift_id'=>$shift_record['shift_id']])}}" target="_blank">{{$shift_record['staff_count']}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')

@endsection
