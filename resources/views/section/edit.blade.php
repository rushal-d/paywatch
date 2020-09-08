@extends('layouts.default', ['crumbroute' => 'sectionedit'])
@section('title', $title)
@section('content')

        {{ Form::open(array('route' => array('sectionupdate',$section->id), 'class' => 'sectionform' ))  }}
        <div class="row">
            <div class="col-md-7 col-sm-12">
                {{-- Basic Info --}}
                <div class="basic-info card">
                    <h5 class="card-header">Section Information</h5>
                    <div class="card-block">
                        <div class="card-text">
                            <div class="form-group row">
                                <label for="section_name" class="col-3 col-form-label">
                                    Section
                                </label>
                                    {{ Form::text('section_name', $section->section_name, array('class' => 'form-control', 'placeholder' => 'Section Name',
                                    'data-validation' => 'required',
                                     'data-validation-error-msg' => 'Please enter a section')) }}

                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-3 col-form-label">
                                    Description
                                </label>
                                    {{ Form::textarea('description', $section->description, array('class' => 'form-control', 'placeholder' => 'Description')) }}

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
