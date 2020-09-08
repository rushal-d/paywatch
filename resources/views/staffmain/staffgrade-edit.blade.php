@extends('layouts.default', ['crumbroute' => 'staffedit'])
@section('title', $title)
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <style>.required-field {
            color: red;
        }
    </style>
@endsection
@section('content')
    @include('staffmain.staff-edit-nav')
    <form method="post" action="{{ route('staff-grade-update',$staff_grade->id) }}">
        @method('PATCH')
        <input type="hidden" name="_token" value="{{csrf_token()}}">

        <div class="row">
            <div class="col-md-6 col-sm-12">

                {{--staff grade start--}}
                <div class="basic-info card">
                    <h5 class="card-header">Staff Grade : {{$staffmain->name_eng}} -
                        [CID: {{$staffmain->staff_central_id}}] - [Branch
                        ID: {{$staffmain->main_id}} {{$staffmain->branch->office_name ?? ''}}]</h5>
                    <div class="card-block">
                        <div class="card-text">
                            <div class="row no-gutters two-fields">

                                <div class="col-md-12 col-sm-12">
                                    <label for="grade_id" class="col-form-label">Grade Value</label>
                                    {{ Form::select('grade_id', $grades,old('grade_id',$staff_grade->grade_id), array('class' => 'form-control', 'placeholder' => 'Select the grade value','required'))  }}
                                </div>

                                <div class="col-md-12 col-sm-12">
                                    <label for="effective_from_date_np" class="col-form-label">Effective From
                                        (BS)</label>
                                    {{ Form::text('effective_from_date_np',old('effective_from_date_np',$staff_grade->effective_from_date_np), array('class' => 'form-control','id'=>'effective_from_date_np', 'placeholder' => 'Effective From (BS)','data-validation'=>'required','readonly'))  }}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                {{--staff grade end--}}

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
    <script>
        $('#effective_from_date_np').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 50 // Options | Number of years to show
        });
        $('#effective_to_np').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 50 // Options | Number of years to show
        });
    </script>
@endsection
