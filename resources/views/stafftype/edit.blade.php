@extends('layouts.default', ['crumbroute' => 'stafftypeedit'])
@section('title', $title)
@section('content')

    {{ Form::model($staffType, array('route' => ['staff-type-update', $staffType]))  }}

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Type Edit</h5>

                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="staff_type_title" class="col-3 col-form-label">
                                Staff Type Title <span class="required-field">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9">
                                {{ Form::text('staff_type_title', null, array('class' => 'form-control', 'placeholder' => 'Input Staff Type Title',
                                 'data-validation' => 'required',
                                 'data-validation-error-msg' => 'Please enter a Staff Type Title'))  }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staff_type_code" class="col-3 col-form-label">
                                Staff Type Code <span class="required-field">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9">
                                {{ Form::number('staff_type_code', null, array('class' => 'form-control', 'placeholder' => 'Staff Type Code',
                                 'data-validation' => 'required','step'=>'1',
                                 'data-validation-error-msg' => 'Please enter a Staff Type Code'))  }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}`
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

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>

@endsection
