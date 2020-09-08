@extends('layouts.default', ['crumbroute' => 'appversioncreate'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'appversion.save', 'files' => true))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">App Version Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="app_version_name" class="col-3 col-form-label">
                                App Version Name:
                            </label>
                            {{ Form::text('app_version_name', null, array('class' => 'form-control', 'placeholder' => 'App Version Title',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a app version name'))  }}
                        </div>
                    </div>
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="app_version_name" class="col-3 col-form-label">
                                App Version Description:
                            </label>
                            {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="card-text">
                        <div class="form-group row">
                            <label for="app_version_name" class="col-3 col-form-label">
                                Upload A Zip File:
                            </label>
                            {{--{!! Form::file('zip_file', ['required', 'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a app version name']) !!}--}}

                            {!! Form::file('zip_file', null) !!}
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
        {{-- Right Sidebar  --}}
        <div class="col-md-5 col-sm-12">


        </div>
        {{-- End of sidebar --}}

    </div>
    {{ Form::close()  }}
@endsection
