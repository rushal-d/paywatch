@extends('layouts.default', ['crumbroute' => 'bulk-force-attendance-index'])
@section('title', $title)
@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@endsection
@section('content')

    {{ Form::open(array('route' => 'bulk-force-show-filter','method' => 'get'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Filter Bulk Force Attendance</h5>
                <div class="card-block">
                    <div class="card-text">

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch <span class="required-field"> *</span>
                            </label>
                            {{ Form::select('branch_id', $branches,$branch_id ?? null, array('class' => '',
                            'required' => true,
                             'data-validation' => 'required',
                             'id'=>'branch_id',
                             'data-validation-error-msg' => 'Please select branch'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Department
                            </label>
                            {{ Form::select('department_id', $departments,$department_id ?? null, array('class' => '',
                             'id'=>'department_id'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="from" class="col-3 col-form-label">
                                Date (BS) <span class="required-field"> *</span>
                            </label>
                            {{ Form::text('from_date_np', null, array('class' => 'form-control nep-date','required' => 'required','id'=>'nep-date1', 'readonly'=> 'readonly', 'placeholder' => 'Select a Date (BS)' ))  }}
                            <input type="hidden" id="from_date" name="from_date">
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Date<span class="required-field">*</span>
                            </label>
                            {{ Form::text('from_date', $_GET['from_date'] ?? null, array('class' => 'date form-control','id' => 'date1', 'placeholder' => 'Select a Date','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                                     'data-validation-error-msg' => 'Please enter date' ))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Job Type
                            </label>
                            {{ Form::select('job_type_id', $jobTypes,null, array('class' => '', 'placeholder' => 'Job Type',
                             'data-validation-error-msg' => 'Please select job type'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Designation
                            </label>
                            {{ Form::select('designation_id', $designations,null, array('class' => '', 'placeholder' => 'Designation',
                             'data-validation-error-msg' => 'Please select designation'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" id="shift" class="col-3 col-form-label">
                                Shift
                            </label>
                            <div class="col-md-9 col-sm-9 shift_container">

                                <input type="text" id="shift_id" name="shift_id" class="input-sm" required
                                       placeholder="Please Select Branch First"
                                       disabled>
                            </div>
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

    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>

    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            closeOnDateSelect: true,
            onChange: function (e) {
                $('#nep-date1').val() ? $('#date1').val(BS2AD($('#nep-date1').val())): '';
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $('.date').flatpickr({
            dateFormat: "Y-m-d",
            disableMobile: "true",
            onChange: function () {
                $('#date1').val() ? $('#nep-date1').val(AD2BS($('#date1').val())) : '';
            }
        })
    </script>

    <script>
        function onChangeBranchId() {
            branch = $('#branch_id').val();

            $.ajax({
                url: '{{route('get-shift-by-branch')}}',
                type: 'post',
                data: {
                    'branch': branch,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data) {
                    let shifts = data;
                    $('.shift_id').remove();
                    $('.removeit').remove();
                    $('.shift_container').remove();
                    $('#shift').after('<input type="text" id="shift_id" name="shift_id" class="input-sm shift_id" \n' +
                        '                                   >');
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
                                    '<div> Active: ' + status + '</div>'+
                                    '</div>';
                            }
                        },
                        load: function (query, callback) {

                        }
                    });
                }
            });
        }

        onChangeBranchId();
        $('#branch_id').change(onChangeBranchId);
    </script>
@endsection
