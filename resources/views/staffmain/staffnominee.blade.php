@extends('layouts.default', ['crumbroute' => 'staffedit'])
@section('title', $title)
@section('content')
    <link rel="stylesheet" href="{{asset('assets/css/uploadfiledropzone.css')}}">
    <style>

        .mydrop {
            width: 305px;
            margin-left: -49px;
        }

        .required-field {
            color: red;
        }

    </style>
    @include('staffmain.staff-edit-nav')
    <form method="post" action="{{ route('staff-nominee-store',$staffmain->id) }}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{csrf_token()}}">

        <div class="row">
            <div class="col-md-7 col-sm-12">

                {{--Nominnee Start --}}
                <div class="basic-info card">
                    <h5 class="card-header">Staff Nominee (Nominate in case of
                        demise): {{$staffmain->name_eng ?? ''}} - [CID: {{$staffmain->staff_central_id}}] - [Branch
                        ID: {{$staffmain->main_id}} {{$staffmain->branch->office_name ?? ''}}]</h5>
                    <div class="card-block">
                        <div class="card-text">
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="appli_date" class="col-form-label">Applied Date</label>
                                    {{ Form::text('appli_date_np', $staff_nominee->appli_date_np ?? null, array('class' => 'form-control nep-date','id'=>'nep-date4' , 'placeholder' => 'Applied Date','readonly'))  }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="relation" class="col-form-label">Relation</label>
                                    {{ Form::text('relation', $staff_nominee->relation ?? null, array('class' => 'form-control', 'placeholder' => 'Input Relation'))  }}
                                </div>
                            </div>
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="nominee_name" class="col-form-label">Nominee Name</label>
                                    {{ Form::text('nominee_name', $staff_nominee->nominee_name ?? null, array('class' => 'form-control', 'placeholder' => 'Nominee Name'))  }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="dob" class="col-form-label">Date of Birth</label>
                                    {{ Form::text('dob', $staff_nominee->dob ?? null, array('class' => 'form-control nep-date','id'=>'nep-date3' ,'placeholder' => 'Date Of Birth','readonly'))  }}
                                </div>
                            </div>
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="citizen_no" class="col-form-label">Citizen Number</label>
                                    {{ Form::text('citizen_no', $staff_nominee->citizen_no ?? null, array('class' => 'form-control', 'placeholder' => 'Enter Citizen Number'))  }}
                                </div>
                            </div>
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="issue_office" class="col-form-label">Issue Office</label>
                                    {{ Form::text('issue_office', $staff_nominee->issue_office ?? null, array('class' => 'form-control', 'placeholder' => 'Issue Office'))  }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="issue_date_np" class="col-form-label">Issue Date</label>
                                    {{ Form::text('issue_date_np', $staff_nominee->issue_date_np ?? null, array('class' => 'form-control nep-date','id'=>'nep-date5' , 'placeholder' => 'Enter Issue Date','readonly'
                                    ))  }}
                                </div>
                            </div>

                            <div class="row">
                                @foreach($file_types as $file)

                                    <div class="col-md-12">
                                        <div class="row">
                                            @foreach($staffmain->staffFiles->where('file_type_id',$file->id) as $staffFile)
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <div>
                                                            <a href="{{route('staff-file-download',$staffFile->file_name)}}">
                                                                <i class="fas fa-file-alt"></i>
                                                            </a>
                                                        </div>
                                                        <p> {{$staffFile->file_name}}</p>
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-danger remove-file"
                                                                data-id="{{$staffFile->id}}"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <label for="file_upload" class="col-form-label">
                                            {{$file->file_type}}
                                        </label>
                                        <div class="upload-file" id="upload-file-{{$file->id}}">
                                            <div class="fallback">
                                                <input type="file" name="file">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
                // acceptedFiles: 'audio/*',
                addRemoveLinks: true,
                acceptedFiles: 'application/pdf,image/jpeg,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/docx',
                dictDefaultMessage: "Upload Your File Here",
                sending: function (file, xhr, formData) {
                    // Pass token. You can use the same method to pass any other values as well such as a id to associate the image with for example.
                    formData.append("_token", '{{csrf_token()}}'); // Laravel expect the token post value to be named _token by default
                },
                success: function (file, response) {
                    $('#staff-form').append('<input type="hidden" name="upload[]" multiple value="' + response + '">');
                    $(file._removeLink).attr('filename', response);
                },
                removedfile: function (file) {
                    fileValue = $(file._removeLink).attr('filename');
                    console.log(fileValue);
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
