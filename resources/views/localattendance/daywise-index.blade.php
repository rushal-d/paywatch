@extends('layouts.default', ['crumbroute' => 'daywise-attendance-index'])
@section('title', $title)

@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <style>
        .shift_container {
            width: 72%;
        }

        .selectize-control.input-sm.single.loading {
            width: 100%;
        }

    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@endsection
@section('content')
    {{-- <div class="card">
         <div class="card-header">
             <i class="fa fa-align-justify"></i> Local Attendance
             <form action="{{route('localattendance-daywise-show')}}">
                 <div class="form-group row">
                     <label for="branch_id" class="col-1 col-form-label">
                         Branch
                     </label>
                     <div class="col-md-4">
                         {!! Form::select('branch_id', $branches , null,array('id'=>'branch_id','placeholder'=>'Select a Branch') ) !!}
                     </div>

                     <label for="shift" id="shift" class="col-2 col-form-label">
                         Shift
                     </label>
                     <div class="col-md-4 shift_container">
                         <input type="text" id="shift_id" name="shift_id" class="input-sm" required
                                placeholder="Please Select Branch First"
                                disabled>
                     </div>

                 </div>
                 <div class="form-group row">
                     <label for="" class="col-1 col-form-label">
                         Date
                     </label>
                     <div class="col-md-4">
                         {{ Form::text('date', null, array('class' => 'form-control nep-date','id'=>'nep-date1' , 'placeholder' => 'Date','readonly'=>'readonly' ))  }}
                     </div>
                 </div>
                 <div class="row form-group">
                     <div class="col-md-10 text-right">
                         <button type="submit" class="btn btn-outline-success btn-sm">Filter</button>
                         <a href="{{ route('localattendance')}}">
                             <button type="button" class="btn btn-outline-danger btn-sm">Reset</button>
                         </a>
                     </div>
                 </div>
             </form>
         </div>
     </div>--}}

    {{ Form::open(array('route' => 'localattendance-daywise-show','method'=>'GET'))  }}
    <div class="row">
        <div class="col-md-5 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Filter Daywise Attendance</h5>
                <div class="card-block">
                    <div class="card-text">
                        @if(!auth()->user()->hasRole('Employee'))
                            <div class="form-group row">
                                <label for="title" class="col-3 col-form-label">
                                    Branch<span class="required-field">*</span>
                                </label>
                                {!! Form::select('branch_id', $branches , null,array('id'=>'branch_id') ) !!}
                            </div>

                            <div class="form-group row">
                                <label for="title" class="col-3 col-form-label">
                                    Select Department
                                </label>
                                {!! Form::select('department_id', $departments , null, array( 'id'=>'department_id') ) !!}
                            </div>

                            <div class="form-group row">
                                <label for="title" id="shift" class="col-3 col-form-label">
                                    Shift
                                </label>
                                <input type="text" id="shift_id" name="shift_id" class="input-sm" required
                                       placeholder="Please Select Branch First"
                                       disabled>
                            </div>
                        @else
                            {!! Form::hidden('branch_id', auth()->user()->branch_id) !!}
                            {!! Form::hidden('department_id', auth()->user()->staff->department ?? null) !!}
                            {!! Form::hidden('shift', auth()->user()->staff->shift_id ?? null) !!}
                        @endif

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Date (BS)<span class="required-field">*</span>
                            </label>
                            {{ Form::text('date', null, array('class' => 'form-control nep-date','id'=>'nep-date1', 'readonly' => 'readonly' ,'required' => 'true', 'placeholder' => 'Date' ))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Date<span class="required-field">*</span>
                            </label>
                            {{ Form::text('from_date', isset($_GET['from_date']) ? $_GET['from_date'] : $todayDate , array('class' => 'date form-control','id' => 'date1', 'placeholder' => 'Date From','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                                     'data-validation-error-msg' => 'Please enter date' ))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Show Phone Number
                            </label>
                            {{ Form::checkbox('show_phone_number',1, isset($_GET['show_phone_number']) && $_GET['show_phone_number']==1)  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Status
                            </label>
                            {{ Form::select('status',['Absent Only','Present Only'], $_GET['status'] ?? null , array('class' => 'form-control', 'placeholder' => 'Select One','readonly'=>'readonly'))  }}
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Filter',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close()  }}
@endsection


@section('script')
    <script>
        //responsive table
        $(function () {
            $('.table-all').stacktable();
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
        function onChangeBranchId() {
            branch = $('#branch_id').val();

            $.ajax({
                url: '{{route('get-shift-by-branch')}}',
                type: 'post',
                data: {
                    'branch': branch,
                    'send_all': 1,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data) {
                    let shifts = data;
                    $('#shift_id').remove();
                    $('.removeit').remove();
                    $('.shift_container').remove();
                    $('#shift').after('   <div class="shift_container"><input type="text" id="shift_id" name="shift_id" class="input-sm" \n' +
                        '                                   ></div>')
                    $('#shift_id').prop('disabled', false);
                    $('#shift_id').selectize({
                        valueField: 'id',
                        labelField: 'shift_name',
                        searchField: ['shift_name', 'id'],
                        options: shifts,
                        preload: true,
                        maxItems: 1,
                        create: false,
                        render: {
                            option: function (item, escape) {
                                let status = (item.active == 1) ? 'Active' : 'Inactive';
                                return '<div class="suggestions">' +
                                    '<div> Shift Name: ' + item.shift_name + '</div>' +
                                    '<div> ID: ' + item.id + '</div>' +
                                    '<div> Active: ' + status + '</div>' +
                                    '</div>';
                            }
                        },
                        load: function (query, callback) {

                        }
                    });
                    console.log($('#staff').next().next().addClass('removeit'));
                }
            });
        }

        onChangeBranchId();

        $('#branch_id').change(onChangeBranchId);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#nep-date1').val() ? $('#date1').val(BS2AD($('#nep-date1').val())) : '';
            }
        });
    </script>



    <script>
        $('#records_per_page').change(function () {
            $('.search-form').submit();
        });
    </script>

    <script>
        function changeEnDate() {
            $('#date1').val() ? $('#nep-date1').val(AD2BS($('#date1').val())) : '';
        }

        $('.date').flatpickr({
            dateFormat: "Y-m-d",
            disableMobile: "true",
            onChange: changeEnDate
        });


        changeEnDate();
    </script>
@endsection
