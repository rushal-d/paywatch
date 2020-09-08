@extends('layouts.default', ['crumbroute' => 'postcreate'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'system-post-save'))  }}

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Designation(Post) Create</h5>

                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="post_title" class="col-3 col-form-label">
                                Designation Name
                                {{--<span class="badge badge-pill badge-danger">*</span>--}}
                            </label>
                            <div class="col-md-9 col-sm-9">
                                {{--{{ Form::label('post_title', 'Post name') }}--}}
                                {{ Form::text('post_title', null, array('class' => 'form-control', 'placeholder' => 'Input Designation Name',
                                 'data-validation' => 'required',
                                 'data-validation-error-msg' => 'Please enter a Designation Name'))  }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="basic_salary" class="col-3 col-form-label">
                                Basic Salary
                                {{--<span class="badge badge-pill badge-danger">*</span>--}}
                            </label>
                            <div class="col-md-9 col-sm-9">
                                {{--{{ Form::label('basic_salary', 'Basic Salary') }}--}}
                                {{ Form::number('basic_salary', null, array('class' => 'form-control', 'placeholder' => 'Input Basic Salary',
                                 'data-validation' => 'required','step'=>'0.001',
                                 'data-validation-error-msg' => 'Please enter a Basic Salary'))  }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="effect_date" class="col-3 col-form-label">
                                Effect Date
                                {{--<span class="badge badge-pill badge-danger">*</span>--}}
                            </label>
                            <div class="col-md-9 col-sm-9">
                                {{--{{ Form::label('effect_date', 'Effect Date') }}--}}
                                {{ Form::text('effect_date', null, array('class' => 'form-control','id'=>'nep-date'  ,'required' => 'required','placeholder' => 'Input Date'
                                 ))  }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="grade" class="col-3 col-form-label">
                                Max Grade
                                {{--<span class="badge badge-pill badge-danger">*</span>--}}
                            </label>
                            <div class="col-md-9 col-sm-9">
                                <select id="grade_id" name="grade_id" class="input-sm" required>
                                    <option value="">Select Value</option>
                                    @foreach($grades as $grade)
                                        <option value="{{$grade->id}}">{{$grade->value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="grade_amount" class="col-3 col-form-label">
                                Grade Amount
                            </label>
                            <div class="col-md-9 col-sm-9">
                                {{ Form::number('grade_amount', null, array('class' => 'form-control','id'=>'nep-date'  ,'required' => 'required','placeholder' => 'Input Grade Amount ',
                                 'data-validation' => 'required','step'=>'0.001',
                                 'data-validation-error-msg' => 'Please enter Grade Amount'))  }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{--  Save --}}`
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

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        $('#nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20 // Options | Number of years to show
        });
    </script>
@endsection
