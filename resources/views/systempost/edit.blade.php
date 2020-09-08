@extends('layouts.default', ['crumbroute' => 'postedit'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => array('system-post-update',$post->post_id), 'class' => 'educationform' ))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Designation(Post) Edit</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="formub-group row">
                            <label for="post_title" class="col-3 col-form-label">
                                Designation Name
                            </label>
                            {{ Form::text('post_title', $post->post_title, array('class' => 'form-control', 'placeholder' => 'Designation Name',
                            'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Designation Name')) }}
                        </div>
                        <div class="form-group row">
                            <label for="basic_salary" class="col-3 col-form-label">
                                Salary
                            </label>
                            {{ Form::number('basic_salary', $post->basic_salary, array('class' => 'form-control', 'placeholder' => 'Basic Salary',
                            'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Basic Salary')) }}
                        </div>
                        <div class="form-group row">
                            <label for="effect_date" class="col-3 col-form-label">
                               Effect Date
                            </label>
                            {{ Form::text('effect_date', $post->effect_date_np, array('class' => 'form-control'  ,'required' => 'required','id'=>'nep-date','placeholder' => 'Input Date '
                                ))  }}
                        </div>
                        <div class="form-group row">
                            <label for="grade_amount" class="col-3 col-form-label">
                                Grade Amount
                            </label>
                            {{ Form::text('grade_amount', $post->grade_amount, array('class' => 'form-control','id'=>'nep-date','placeholder' => 'Input Grade Amount ',
                                'data-validation' => 'required',
                                'data-validation-error-msg' => 'Please enter a Grade Amount'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="grade" class="col-3 col-form-label">
                                Max Grade
                                {{--<span class="badge badge-pill badge-danger">*</span>--}}
                            </label>
                                <select id="grade_id" name="grade_id" class="input-sm" required>
                                    <option value="">Select Value</option>
                                    @foreach($grades as $grade)
                                        <option @if($post->grade_id == $grade->id ) selected
                                                @endif value="{{$grade->id}}">{{$grade->value}}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="form-group row">
                            <label for="grade_amount" class="col-3 col-form-label">
                                Grade Amount
                            </label>
                                {{ Form::number('grade_amount', $post->grade_amount, array('class' => 'form-control','id'=>'nep-date','placeholder' => 'Input Grade Amount ',
                                 'data-validation' => 'required',
                                 'data-validation-error-msg' => 'Please enter Grade Amount'))  }}
                        </div>


                    </div>
                </div>
            </div>

            {{-- Save --}}
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
