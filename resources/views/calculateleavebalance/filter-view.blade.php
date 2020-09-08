@extends('layouts.default', ['crumbroute' => 'calculate-leave-balance-filter'])
@section('title', $title)

@section('style')
@endsection

@section('content')
    {{ Form::open(array('route' => 'calculate-leave-balance-index', 'method' => 'GET'))  }}

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Filter</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            {!! Form::label('fiscal_year_id', 'Fiscal Year ', ['class' => 'col-3 col-form-label']) !!}
                            {!! Form::select('fiscal_year_id', $fiscal_year, $activeFiscalYear, ['class' => 'input-sm', 'id' => 'fiscal_year', 'required' => 'required', 'placeholder' => 'Fiscal Year']) !!}
                        </div>
                        <div class="form-group row">
                            {!! Form::label('month_id', 'Month ', ['class' => 'col-3 col-form-label']) !!}
                            {!! Form::select('month_id', $months, null, ['class' => 'input-sm', 'id' => 'month_id', 'required' => 'required']) !!}
                        </div>
                        <div class="form-group row">
                            {!! Form::label('branch_id', 'Branche ', ['class' => 'col-3 col-form-label']) !!}
                            {!! Form::select('branch_id', $branches, null, ['class' => 'input-sm', 'id' => 'branch_id', 'required' => 'required']) !!}
                        </div>
                    </div>
                </div>

                {{--  Save --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-right form-control">
                            {{ Form::submit('Filter',array('id' => 'submit-form', 'class'=>'btn btn-success btn-lg'))}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close()  }}
@endsection

@section('script')
    <script>
        // $('#branch_id').selectize();
    </script>
@endsection
