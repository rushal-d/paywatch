@extends('layouts.default', ['crumbroute' => 'religioncreate'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'religionsave'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Religion Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Religion Name <span class="required-field">*</span>
                            </label>
                            {{ Form::text('religion_name', null, array('class' => 'form-control', 'placeholder' => 'Religion Name',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a religion'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Description
                            </label>
                            {{ Form::textarea('description', null, array('class' => 'form-control', 'placeholder' => 'Religion Description'))  }}
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
