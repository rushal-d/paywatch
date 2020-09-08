@extends('layouts.default', ['crumbroute' => 'educationedit'])
@section('title', $title)
@section('content')

        {{ Form::open(array('route' => array('educationupdate',$education->edu_id), 'class' => 'educationform' ))  }}
        <div class="row">
            <div class="col-md-7 col-sm-12">
                {{-- Basic Info --}}
                <div class="basic-info card">
                    <h5 class="card-header">Education Information</h5>
                    <div class="card-block">
                        <div class="card-text">
                            <div class="form-group row">
                                <label for="title" class="col-3 col-form-label">
                                    Title
                                    {{--<span class="badge badge-pill badge-danger">*</span>--}}
                                </label>

                                    {{--{{ Form::label('title', 'Title') }}--}}
                                    {{ Form::text('title', $education->edu_description, array('class' => 'form-control', 'placeholder' => 'Education Title',
                                    'data-validation' => 'required',
                                     'data-validation-error-msg' => 'Please enter a title')) }}

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
