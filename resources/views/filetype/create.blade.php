@extends('layouts.default', ['crumbroute' => 'filetypecreate'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'file-typesave'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">File Type Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="file_type" class="col-3 col-form-label">
                                File Type
                            </label>
                            {{ Form::text('file_type', old('file_type'), array('class' => 'form-control', 'placeholder' => 'File Type',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a file type title'))  }}

                        </div>

                        <div class="form-group row">
                            <label for="file_section" class="col-3 col-form-label">
                                File Section
                            </label>
                            {{ Form::select('file_section', $file_sections,old('file_section'), array('class' => 'form-control', 'placeholder' => 'File Section',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a file type section'))  }}

                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-3 col-form-label">
                                Description
                            </label>
                            {{ Form::textarea('description', null, array('class' => 'form-control', 'placeholder' => 'Description'))  }}

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
