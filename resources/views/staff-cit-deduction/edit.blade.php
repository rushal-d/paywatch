@extends('layouts.default', ['crumbroute' => 'staff-cit-deduction-edit'])
@section('title', $title)
@section('content')

    {{ Form::model($staffCitDeduction, array('route' => array('staff-cit-deduction-update',$staffCitDeduction->id), 'class' => 'loan-deduct' ))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Edit Staff Cit Deduction</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Fiscal Year
                            </label>
                            <p>{{$staffCitDeduction->fiscalYear->fiscal_code}}</p>
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Month Name
                            </label>
                            <p>{{config('constants.month_name')[$staffCitDeduction->month_id] ?? null}}</p>
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Staff Name
                            </label>
                            <p>{{$staffCitDeduction->staff->name_eng ?? ''}}</p>
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Branch ID
                            </label>
                            <p>{{$staffCitDeduction->staff->main_id ?? ''}}</p>
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Staff Central ID
                            </label>
                            <p>{{$staffCitDeduction->staff->staff_central_id ?? ''}}</p>
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Branch Name
                            </label>
                            <p>{{$staffCitDeduction->branch->office_name ?? null}}</p>
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Staff Cit Deduction Amount
                            </label>
                            {!! Form::number('cit_deduction_amount', $staffCitDeduction->cit_deduction_amount, ['class' => 'form-control positive-integer-number', 'step' => 0.01]) !!}
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
