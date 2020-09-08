@extends('layouts.default', ['crumbroute' => 'departmentedit'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => array('department-update',$department->id), 'class' => 'departmentform' ))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Department Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Department Name
                            </label>

                            {{ Form::text('department_name', $department->department_name, array('class' => 'form-control', 'placeholder' => 'Department Name',
                            'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a department name')) }}

                        </div>

                    </div>
                </div>
            </div>

            {{-- Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Update',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>

        </div>


    </div>
    {{ Form::close()  }}

@endsection
