@extends('layouts.default', ['crumbroute' => 'staffedit'])
@section('title', $title)
@section('style')
    <link rel="stylesheet" href="{{asset('assets/css/uploadfiledropzone.css')}}">
    <style>

        .mydrop {
            width: 305px;
            margin-left: -49px;
        }

    </style>
@endsection
@section('content')

    @include('staffmain.staff-edit-nav')

    <form method="post" action="{{ route('training-detail-save',$staffmain->id) }}" id="training-form">
        <input type="hidden" name="_token" value="{{csrf_token()}}">

        <div class="row">
            <div class="col-md-9 col-sm-12">

                <div class="basic-info card">
                    <h5 class="card-header">Training Create : {{$staffmain->name_eng}} -
                        [CID: {{$staffmain->staff_central_id}}] - [Branch
                        ID: {{$staffmain->main_id}} {{$staffmain->branch->office_name ?? ''}}]</h5>
                    <div class="card-block">
                        <div class="card-text">
                            <div class="row">

                                <label for="training_organization_name" class="col-lg-2 col-form-label">
                                    Organization Name<span class="required-field">*</span>
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    {{ Form::text('training_organization_name', old('training_organization_name'), array('placeholder' => 'Enter Organization Name' , 'data-validation' => 'required','class'=>'form-control'))  }}
                                </div>
                                <label for="training_title" class="col-lg-2 col-form-label">
                                    Title<span class="required-field">*</span>
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    {{ Form::text('training_title', old('training_title'), array('placeholder' => 'Enter Training Title' , 'data-validation' => 'required','class'=>'form-control'))  }}
                                </div>

                                <label for="training_category" class="col-lg-2 col-form-label">
                                    Category
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    {{ Form::text('training_category', old('training_category'), array('placeholder' => 'Enter Training Category','class'=>'form-control'))  }}
                                </div>
                                <label for="result" class="col-lg-2 col-form-label">
                                    Result
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    {{ Form::text('result', old('result'), array('placeholder' => 'Enter Training Result','class'=>'form-control'))  }}
                                </div>

                                <label for="training_start_date_np" class="col-lg-2 col-form-label">
                                    Date From (BS)
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    {{ Form::text('training_start_date_np', old('training_start_date_np'), array('placeholder' => 'Training Start Date','class'=>'form-control nep-date','id'=>'training_start_date_np','readonly'))  }}
                                </div>
                                <label for="training_end_date_np" class="col-lg-2 col-form-label">
                                    Date To (BS)
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    {{ Form::text('training_end_date_np', old('training_end_date_np'), array('placeholder' => 'Training End Date','class'=>'form-control nep-date','id'=>'training_end_date_np','readonly'))  }}
                                </div>

                                <label for="result" class="col-lg-2 col-form-label">
                                    Major Subject
                                </label>
                                <div class="col-md-4 col-sm-4 form-group">
                                    {{ Form::text('training_main_subject', old('training_main_subject'), array('placeholder' => 'Enter Training Subject','class'=>'form-control'))  }}
                                </div>
                                @foreach($file_types as $file)
                                    <label for="result" class="col-lg-2 col-form-label">
                                        {{$file->file_type}}
                                    </label>
                                    <div class="col-md-4 col-sm-4 form-group">
                                        <div class="upload-file" id="upload-file-{{$file->id}}">
                                            <div class="fallback">
                                                <input type="file" name="file">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>


                            <div class="row">
                                <label for="result" class="col-lg-2 col-form-label">
                                    Training Description
                                </label>
                                <div class="col-md-10 col-sm-10 form-group">
                                    {{ Form::textarea('training_description', old('training_description'), array('placeholder' => 'Enter Training Description','class'=>'form-control'))  }}
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="text-right form-control">
                            {{ Form::submit('Save',array('class'=>'btn btn-success btn-lg'))}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
@section('script')

    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script src="{{asset('assets/js/dropzone.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/css/dropzone.css')}}">
    @foreach($file_types as $file)
        <script>
            $("#upload-file-{{$file->id}}").dropzone({

                url: '{{route('staff-file-upload')}}?staff_id={{$staffmain->id}}&file_type_id={{$file->id}}',
                maxFiles: 100,
                paramName: 'track',
                _token: '{{csrf_token()}}',
                staff_id: '{{$staffmain->id}}',
                file_type_id: '{{$file->id}}',
                responseType: 'id',
                // acceptedFiles: 'audio/*',
                addRemoveLinks: true,
                acceptedFiles: 'application/pdf,image/jpeg,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/docx',
                dictDefaultMessage: "Upload Your File Here",
                sending: function (file, xhr, formData) {
                    // Pass token. You can use the same method to pass any other values as well such as a id to associate the image with for example.
                    formData.append("_token", '{{csrf_token()}}'); // Laravel expect the token post value to be named _token by default
                },
                success: function (file, response) {
                    $('#training-form').append('<input type="hidden" name="upload[]" multiple value="' + response + '">');
                    $(file._removeLink).attr('filename', response);
                },
                removedfile: function (file) {
                    fileValue = $(file._removeLink).attr('filename');
                    $('input[value="' + fileValue + '"]').remove();
                    var _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                }, headers: {
                    'X-CSRFToken': $('meta[name="token"]').attr('content')
                }
            });


        </script>
    @endforeach
    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 50 // Options | Number of years to show
        });
    </script>
@endsection
