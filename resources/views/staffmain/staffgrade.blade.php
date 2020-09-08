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
    <form method="post" action="{{ route('staff-grade-store',$staffmain->id) }}" enctype="multipart/form-data">
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
                                    {{ Form::select('grade_id', $grades,old('grade_id'), array('class' => 'form-control', 'placeholder' => 'Select the grade value','required'))  }}
                                </div>

                                <div class="col-md-12 col-sm-12">
                                    <label for="effective_from_date_np" class="col-form-label">Effective From
                                        (BS)</label>
                                    {{ Form::text('effective_from_date_np',old('effective_from_date_np'), array('class' => 'form-control','id'=>'effective_from_date_np', 'placeholder' => 'Effective From (BS)','data-validation'=>'required','readonly'))  }}
                                </div>

                                {{--<div class="col-md-12 col-sm-12">
                                    <label for="effective_to_np" class="col-form-label">Effective To (BS)</label>
                                    {{ Form::text('effective_to_np',old('effective_to_np'), array('class' => 'form-control','id'=>'effective_to_np', 'placeholder' => 'Effective To (BS)','readonly'))  }}
                                </div>--}}
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

            <div class="col-md-6">
                <div class="basic-info card">
                    <h5 class="card-header">Staff Grade History</h5>
                    <div class="card-block">
                        <div class="card-text">
                            <table class="table table-bordered">
                                <thead>
                                <th>SN</th>
                                <th>Grade Value</th>
                                <th>Effective From</th>
                                <th>Effective To</th>
                                <th>Action</th>
                                </thead>
                                <tbody>
                                @if($staff_grades->count()>0)
                                    @foreach($staff_grades as $staff_grade)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$staff_grade->grade->value ?? ''}}</td>
                                            <td>{{$staff_grade->effective_from_date_np}}</td>
                                            <td>{{$staff_grade->effective_to_date_np}}</td>
                                            <td>
                                                @if(empty($staff_grade->effective_to_date_np))
                                                    <a href="javascript:void(0);" class="text-danger delete"
                                                       data-id="{{$staff_grade->id}}"> <i
                                                            class="fa fa-minus"></i></a>

                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">
                                            No Grade Data
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
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
    <script src="{{ asset('assets/js/vex.combined.js') }}"></script>
    <script>
        //apply vex dialog
        (function () {
            vex.defaultOptions.className = 'vex-theme-os'
            //vex.dialog.buttons.YES.text = 'Yes'
            vex.dialog.buttons.YES.className = 'btn btn-danger'
        })();
    </script>
    <script>
        //delete
        $('body').on('click', '.delete', function () {
            $this = $(this)
            vex.dialog.confirm({
                message: 'Are you sure you want to delete?',
                callback: function (value) {
                    console.log('Callback value: ' + value + $this.data('id'));
                    if (value) { //true if clicked on ok
                        $.ajax({
                            type: "DELETE",
                            url: '{{ route('staff-grade-delete') }}',
                            data: {_token: '{{ csrf_token() }}', id: $this.data('id')},
                            // send Blob objects via XHR requests:
                            success: function (response) {
                                if (response == 'Successfully Deleted') {
                                    toastr.success('Successfully Deleted');
                                    window.location.reload()
                                } else {
                                    vex.dialog.alert(response)
                                }
                            },
                            error: function (response) {
                                vex.dialog.alert(response)
                            }
                        });
                    }
                }
            });
        });
    </script>
@endsection
