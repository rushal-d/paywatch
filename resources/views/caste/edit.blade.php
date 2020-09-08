@extends('layouts.default', ['crumbroute' => 'casteedit'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => array('casteupdate',$caste->id), 'class' => 'casteform' ))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Education Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Caste Name <span class="required-field">*</span>
                            </label>
                            {{ Form::text('caste_name', $caste->caste_name, array('class' => 'form-control', 'placeholder' => 'Caste Name',
                            'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter caste name')) }}

                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Description
                            </label>
                            {{ Form::textarea('description', $caste->description, array('class' => 'form-control', 'placeholder' => 'Caste Description')) }}

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

        {{-- Right Sidebar  --}}
        <div class="col-md-5 col-sm-12">


        </div>

        {{-- End of sidebar --}}

    </div>
    {{ Form::close()  }}

@endsection
