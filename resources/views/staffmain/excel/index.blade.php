@extends('layouts.default', ['crumbroute' => 'staff-excel-import'])
@section('title', $title)
@section('content')


    <div class="row">
        <div class="col-md-6 col-sm-12">
            {{ Form::open(array('route' => 'excel-store','files'=>'true'))  }}
            {{csrf_field()}}
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Excel Import</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch
                            </label>
                            {{ Form::select('branch_id',$branches, null, array( 'placeholder' => 'Branch',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a branch'))  }}

                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                File
                            </label>
                            {{ Form::file('excel_file', null, array('class' => 'form-control', 'placeholder' => 'Excel File',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please upload a file'))  }}
                        </div>
                    </div>
                </div>
            </div>
            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Upload',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
            {{ Form::close()  }}
        </div>

        <div class="col-md-6 col-sm-12">
            {{ Form::open(array('route' => 'company-excel-store','files'=>'true'))  }}
            {{csrf_field()}}
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Company Staff Excel Import</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch
                            </label>
                            {{ Form::select('branch_id',$branches, null, array( 'placeholder' => 'Branch',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a branch'))  }}

                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                File
                            </label>
                            {{ Form::file('excel_file', null, array('class' => 'form-control', 'placeholder' => 'Excel File',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please upload a file'))  }}
                        </div>
                    </div>
                </div>
            </div>
            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Upload',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
            {{ Form::close()  }}
        </div>
        <div class="col-md-6 col-sm-12">
            {{ Form::open(array('route' => 'excel-update','files'=>'true'))  }}
            {{csrf_field()}}
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Excel Update Import</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch
                            </label>
                            {{ Form::select('branch_id',$branches, null, array( 'placeholder' => 'Branch',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a branch'))  }}

                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                File
                            </label>
                            {{ Form::file('excel_file', null, array('class' => 'form-control', 'placeholder' => 'Excel File',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please upload a file'))  }}
                        </div>
                    </div>
                </div>
            </div>
            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Upload',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
            {{ Form::close()  }}
        </div>

        <div class="col-md-6 col-sm-12">
            {{ Form::open(array('route' => 'staff-leave-import','files'=>'true'))  }}
            {{csrf_field()}}
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Leave Balance Excel Import</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch
                            </label>
                            {{ Form::select('branch_id',$branches, null, array( 'placeholder' => 'Branch',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a branch'))  }}

                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                File
                            </label>
                            {{ Form::file('excel_file', null, array('class' => 'form-control', 'placeholder' => 'Excel File',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please upload a file'))  }}
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Upload',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
            {{ Form::close()  }}
        </div>

        <div class="col-md-6 col-sm-12">
            {{ Form::open(array('route' => 'staff-sundry-import','files'=>'true'))  }}
            {{csrf_field()}}
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Sundry Excel Import</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch
                            </label>
                            {{ Form::select('branch_id',$branches, null, array( 'placeholder' => 'Branch',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a branch'))  }}

                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                File
                            </label>
                            {{ Form::file('excel_file', null, array('class' => 'form-control', 'placeholder' => 'Excel File',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please upload a file'))  }}
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Upload',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
            {{ Form::close()  }}
        </div>

        <div class="col-md-6 col-sm-12">
            {{ Form::open(array('route' => 'staff-grade-revision-import','files'=>'true'))  }}
            {{csrf_field()}}
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Grade Revision Import</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch
                            </label>
                            {{ Form::select('branch_id',$branches, null, array( 'placeholder' => 'Branch',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a branch'))  }}

                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                File
                            </label>
                            {{ Form::file('excel_file', null, array('class' => 'form-control', 'placeholder' => 'Excel File',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please upload a file'))  }}
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Upload',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
            {{ Form::close()  }}
        </div>

        <div class="col-md-6 col-sm-12">
            {{ Form::open(array('route' => 'staff-tally-import','files'=>'true'))  }}
            {{csrf_field()}}
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Tally Import</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch
                            </label>
                            {{ Form::select('branch_id',$branches, null, array( 'placeholder' => 'Branch',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a branch'))  }}

                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                File
                            </label>
                            {{ Form::file('excel_file', null, array('class' => 'form-control', 'placeholder' => 'Excel File',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please upload a file'))  }}
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Tally',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
            {{ Form::close()  }}
        </div>

        <div class="col-md-6 col-sm-12">
            {{ Form::open(array('route' => 'staff-allowance-import','files'=>'true'))  }}
            {{csrf_field()}}
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Allowance Import</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch
                            </label>
                            {{ Form::select('branch_id',$branches, null, array( 'placeholder' => 'Branch',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a branch'))  }}

                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                File
                            </label>
                            {{ Form::file('excel_file', null, array('class' => 'form-control', 'placeholder' => 'Excel File',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please upload a file'))  }}
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Tally',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
            {{ Form::close()  }}
        </div>


    </div>

@endsection
