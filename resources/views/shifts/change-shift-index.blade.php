@extends('layouts.default', ['crumbroute' => 'shiftcreate'])
@section('title', $title)
@section('style')
    <style>
        .shift_container{
            width: 72%;
        }
        .shift_container>.selectize-control{
            width: 100% !important;
        }
    </style>
@endsection
@section('content')

    {{ Form::open(array('route' => 'change-shift-filter','method' => 'get'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Filter Staff List</h5>
                <div class="card-block">
                    <div class="card-text">

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch
                            </label>
                            {{ Form::select('branch_id', $branches,null, array('class' => '', 'placeholder' => 'Branch Name',
                             'data-validation' => 'required','id'=>'branch_id','required',
                             'data-validation-error-msg' => 'Please select branch'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Job Type
                            </label>
                            {{ Form::select('job_type', $job_types,null, array('class' => '', 'placeholder' => 'Job Type',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please select job type'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Designation
                            </label>
                            {{ Form::select('designation', $designations,null, array('class' => '', 'placeholder' => 'Designation',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please select designation'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Department
                            </label>
                            {{ Form::select('department', $departments,null, array('class' => '', 'placeholder' => 'Department',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please select department'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch IDs
                            </label>
                            {{ Form::text('main_ids',null, array('class' => 'form-control', 'placeholder' => 'Comma Separated Branch ID'))}}
                        </div>

                        <div class="form-group row">
                            <label for="title" id="shift" class="col-3 col-form-label">
                                Shift
                            </label>
                            <div class="shift_container">

                                <input type="text" id="shift_id" name="shift_id" class="input-sm form-control" required
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
    <script>
        $('#branch_id').change(function () {
            branch = $(this).val();

            $.ajax({
                url: '{{route('get-shift-by-branch')}}',
                type: 'post',
                data: {
                    'branch': branch,
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
                                    '<div> Active: ' + status + '</div>'+
                                    '</div>';
                            }
                        },
                        load: function (query, callback) {

                        }
                    });
                    console.log($('#staff').next().next().addClass('removeit'));
                }
            });
        });
    </script>
@endsection
