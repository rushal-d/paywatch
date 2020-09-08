@extends('layouts.default', ['crumbroute' => 'bankcreate'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'bank-save'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Bank Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="bank_name" class="col-3 col-form-label">
                               Bank Name
                            </label>
                            {{ Form::text('bank_name', null, array('class' => 'form-control', 'placeholder' => 'Bank Name',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter Bank Name'))  }}
                        </div>


                    </div>
                </div>


            </div>

            {{--  Save --}}
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
@endsection
