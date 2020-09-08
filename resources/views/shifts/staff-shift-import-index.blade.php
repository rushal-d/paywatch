@extends('layouts.default', ['crumbroute' => 'educationcreate'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'staff-shift-import','files'=>'true'))  }}
    {{csrf_field()}}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Shift Excel Import</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch
                            </label>
                            {{ Form::select('branch_id',$branches, null, array( 'placeholder' => 'Branch',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a branch'))  }}

                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                File
                            </label>
                            {{ Form::file('excel_file', null, array('class' => 'form-control', 'placeholder' => 'Excel File',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please upload a file'))  }}
                        </div>
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
