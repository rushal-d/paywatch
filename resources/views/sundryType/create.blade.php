@extends('layouts.default', ['crumbroute' => 'sundryTypeCreate'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'sundry-type-save'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Sundry Type Create</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Title
                            </label>
                            {{ Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'Sundry Type Title',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a title'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-3 col-form-label">
                                Description
                            </label>
                            {{ Form::textarea('description', null, array('class' => 'form-control', 'placeholder' => 'Sundry Type Description',))  }}
                        </div>
                        <div class="form-group row">
                            <label for="type" class="col-3 col-form-label">
                                Type
                            </label>
                            {{ Form::select('type', $types, null, array('placeholder' => 'Select One...', 'required' => 'required'))  }}
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
