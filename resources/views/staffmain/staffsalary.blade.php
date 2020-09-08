@extends('layouts.default', ['crumbroute' => 'staffedit'])
@section('title', $title)
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <style>
        .mydrop {
            width: 305px;
            margin-left: -49px;
        }

        .required-field {
            color: red;
        }


    </style>
@endsection
@section('content')

    @include('staffmain.staff-edit-nav')
    <form method="post" action="{{ route('staff-salary-store',$staffmain->id) }}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{csrf_token()}}">

        <div class="row">
            <div class="col-md-6 col-sm-12">
                {{--start staff salary--}}
                <div>
                    <div class="basic-info card">
                        <h5 class="card-header">Staff Salary : {{$staffmain->name_eng}} -
                            [CID: {{$staffmain->staff_central_id}}] - [Branch ID: {{$staffmain->main_id}}] </h5>
                        <div class="card-block">
                            <div class="card-text">
                                <div class="form-group row">
                                    <label for="basic_salary" class="col-3 col-form-label">
                                        Basic Salary
                                    </label>
                                    @if(!empty($staffmain->jobtype) && strcasecmp($staffmain->jobtype->jobtype_code,"Con")!=0 && strcasecmp($staffmain->jobtype->jobtype_code,"Con1")!=0)
                                        {{ Form::text('basic_salary', $staffmain->jobposition->basic_salary ?? '', array('class' => 'form-control','id' => 'basic_salary', 'readonly' => 'true', 'placeholder' => 'Enter Salary Amount'
                                      ))  }}
                                    @else
                                        {{ Form::text('basic_salary', $staffmain->additionalSalary->last()->basic_salary ?? '', array('class' => 'form-control','id' => 'basic_salary', 'placeholder' => 'Enter Salary Amount'
                                    ))  }}
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <label for="add_salary_amount" class="col-3 col-form-label">
                                        Additional Salary<span class="required-field">*</span>
                                    </label>
                                    {{ Form::number('add_salary_amount', $staff_salary->add_salary_amount ?? 0, array('class' => 'form-control', 'placeholder' => 'Enter Salary Amount', 'required' => 'required'))  }}
                                </div>

                                <div class="form-group row">
                                    <label for="salary_effected_date_np" class="col-3 col-form-label">
                                        Effected Date<span class="required-field">*</span>
                                    </label>
                                    {{ Form::text('salary_effected_date_np', $staff_salary->salary_effected_date_np ?? null, array('class' => 'form-control nep-date','id'=>'nep-date6' ,'required' => 'required', 'placeholder' => 'Enter   Effected Date','readonly','data-validation'=>'required'
                                   ))  }}
                                </div>

                                <div class="form-group row">
                                    <label for="salary_payment_status" class="col-3 col-form-label">
                                        Payment Status
                                    </label>
                                    {{ Form::select('salary_payment_status', array('A' => 'Active', 'D' => 'Deactive'), $staff_salary->salary_payment_status ?? 'A') }}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--end staff salary--}}


                <div class="row">
                    <div class="col-md-12">
                        <div class="text-right form-control">
                            {{ Form::submit('Save',array('class'=>'btn btn-success btn-lg'))}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div>
                    <div class="basic-info card">
                        <h5 class="card-header">Staff Salary History : {{$staffmain->name_eng}} -
                            [CID: {{$staffmain->staff_central_id}}] - [Branch
                            ID: {{$staffmain->main_id}} {{$staffmain->branch->office_name ?? ''}}]</h5>
                        <div class="card-block">
                            <div class="card-text">
                                <table class="table table-bordered">
                                    <thead>
                                    <th>SN</th>
                                    <th>Salary Effective Date</th>
                                    <th>Basic Salary</th>
                                    <th>Additional Salary</th>
                                    <th>Grade</th>
                                    <th>Added Grade</th>
                                    <th>Fiscal Year</th>
                                    <th>Action</th>
                                    </thead>
                                    <tbody>

                                    @foreach($staff_salaries as $staffSalary)
                                        <tr>
                                            @if($staffSalary->salary_id==$staff_salary->salary_id)
                                                <td><b>{{$i++}} <i class="fas fa-check-circle"></i></b></td>
                                                <td><b>{{$staffSalary->salary_effected_date_np}}</b></td>
                                                <td><b>{{$staffSalary->basic_salary}}</b></td>
                                                <td><b>{{$staffSalary->add_salary_amount}}</b></td>
                                                <td><b>{{$staffSalary->total_grade_amount}}</b></td>
                                                <td><b>{{$staffSalary->add_grade_this_fiscal_year}}</b></td>
                                                <td><b>{{$staffSalary->fiscalyear->fiscal_code ?? ''}}</b></td>
                                                <td>
                                                    @if($staff_salaries->count()>0)
                                                        <a href="javascript:void(0);" class="text-danger delete"
                                                           data-id="{{$staffSalary->salary_id}}"> <i
                                                                class="fa fa-minus"></i></a>
                                                    @endif
                                                </td>
                                            @else
                                                <td>{{$i++}}</td>
                                                <td>{{$staffSalary->salary_effected_date_np}}</td>
                                                <td>{{$staffSalary->basic_salary}}</td>
                                                <td>{{$staffSalary->add_salary_amount}}</td>
                                                <td>{{$staffSalary->total_grade_amount}}</td>
                                                <td>{{$staffSalary->add_grade_this_fiscal_year}}</td>
                                                <td>{{$staffSalary->fiscalyear->fiscal_code ?? ''}}</td>
                                                <td>
                                                    @if($staff_salaries->count()>0)
                                                        <a href="javascript:void(0);" class="text-danger delete"
                                                           data-id="{{$staffSalary->salary_id}}"> <i
                                                                class="fa fa-minus"></i></a>
                                                    @endif
                                                </td>
                                            @endif


                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
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

        $('.nep-date').nepaliDatePicker({
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
                            url: '{{ route('staff-salary-delete') }}',
                            data: {_token: '{{ csrf_token() }}', id: $this.data('id')},
                            // send Blob objects via XHR requests:
                            success: function (response) {
                                if (response == 'Successfully Deleted') {
                                    toastr.success('Successfully Deleted');
                                    $this.parent().parent().remove();
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
