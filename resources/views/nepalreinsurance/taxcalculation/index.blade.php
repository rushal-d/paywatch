@extends('layouts.default', ['crumbroute' => 'nepal-re-tax-calculation'])
@section('title', $title)
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
@endsection
@section('content')

    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            <form action="{{route('nepal-re-tax-calculation')}}" method="GET">
                {{-- Basic Info --}}
                <div class="basic-info card">
                    <h5 class="card-header">{{$title}}</h5>
                    <div class="card-block">
                        <div class="card-text">
                            <div class="form-group row">
                                <label for="branch_id" class="col-3 col-form-label">
                                    Branch
                                </label>
                                {!! Form::select('branch_id', $branches , null,array('id'=>'branch_id', 'placeholder' => 'Branches') ) !!}

                            </div>
                            <div class="form-group row">
                                <label for="fiscal_year" class="col-3 col-form-label">
                                    Fiscal Year
                                </label>
                                {!! Form::select('fiscal_year_id', $fiscal_years , $current_fiscal_id ?? null,array('id'=>'fiscal_year_id', 'placeholder' => 'Fiscal Year') ) !!}
                            </div>

                        </div>
                    </div>
                </div>
                {{--  Save --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-right form-control">
                            {!! Form::submit('Calculate',['class'=>'btn btn-success']) !!}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>



@endsection


@section('script')

    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script src="{{ asset('assets/js/vex.combined.js') }}"></script>
    <script>
        //apply vex dialog
        (function () {
            vex.defaultOptions.className = 'vex-theme-os'
            //vex.dialog.buttons.YES.text = 'Yes'
            vex.dialog.buttons.YES.className = 'btn btn-danger'
        })();
    </script>
@endsection
