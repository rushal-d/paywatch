@extends('layouts.default', ['crumbroute' => 'loan-deduct-edit'])
@section('title', $title)
@section('content')

    {{ Form::model($loanDeduct, array('route' => array('loan-deduct-update',$loanDeduct->id), 'class' => 'loan-deduct' ))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Edit Loan Deduct</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Fiscal Year
                            </label>
                            <p>{{$fiscalYear->fiscal_code}}</p>
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Month Name
                            </label>
                            <p>{{$month_name}}</p>
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Staff Name
                            </label>
                            <p>{{$staff->name_eng}}</p>
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Branch ID
                            </label>
                            <p>{{$staff->main_id}}</p>
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Staff Central ID
                            </label>
                            <p>{{$staff->staff_central_id}}</p>
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Branch Name
                            </label>
                            <p>{{$staff->payrollBranch->office_name ?? null}}</p>
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Loan Type
                            </label>
                            <p>{{config('constants.loan_types')[$loanDeduct->loan_type]}}</p>
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Loan ID
                            </label>
                            <p>{{$loanDeduct->id}}</p>
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Loan Deduct Amount
                            </label>
                            {!! Form::number('loan_deduct_amount', null, ['class' => 'form-control positive-integer-number', 'step' => 0.01]) !!}
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Previous Remarks
                            </label>
                            <p>{!! $loanDeduct->remarks !!}</p>
                        </div>
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Remarks
                            </label>
                            {!! Form::text('remarks', '', ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Update',array('class'=>'btn btn-success btn-lg'))}}
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


@section('script')
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
@endsection
