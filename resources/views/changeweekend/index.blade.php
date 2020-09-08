@extends('layouts.default', ['crumbroute' => 'change-bulk-weekend'])
@section('title', $title)
@section('style')
    <style>
        .shift_container {
            width: 72%;
        }

        .shift_container > .selectize-control {
            width: 100% !important;
        }
    </style>
@endsection
@section('content')

    {{ Form::open(array('route' => 'weekend-staff-list','method' => 'get'))  }}
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
                             'data-validation' => 'required','id'=>'branch_id',
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
                                Branch Ids
                            </label>
                            {{ Form::text('main_ids',null, array('class' => 'form-control', 'placeholder' => 'Branch ID Comma Separated'))}}
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

    </script>
@endsection
