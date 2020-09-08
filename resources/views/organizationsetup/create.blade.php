@extends('layouts.default', ['crumbroute' => 'organization'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'organizationsetupstore'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Organization Setup</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Organization Name
                            </label>
                            {{ Form::text('organization_name', $setup->organization_name ?? null, array('class' => 'form-control', 'placeholder' => 'Organization Name',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please Enter Organization Name'))  }}

                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Organization Code
                            </label>
                            {{ Form::text('organization_code', $setup->organization_code ?? null, array('class' => 'form-control', 'placeholder' => 'Organization Code',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please Enter Organization Name'))  }}

                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Organization Address
                            </label>
                            {{ Form::text('organization_address', $setup->organization_address ?? null, array('class' => 'form-control', 'placeholder' => 'Organization Address'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Organization Payroll Type
                            </label>
                            {{ Form::select('organization_type', $organization_types,$setup->organization_type ?? null,  array('class' => 'form-control'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Organization Payroll Type
                            </label>
                            {{ Form::select('organization_structure', $organization_structures,$setup->organization_structure ?? null, array('class' => 'form-control'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Organization Contact
                            </label>
                            {{ Form::text('organization_contact', $setup->organization_contact ?? null, array('class' => 'form-control', 'placeholder' => 'Organization Contact'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Organization Website
                            </label>
                            {{ Form::text('organization_website', $setup->organization_website ?? null, array('class' => 'form-control', 'placeholder' => 'Organization Website'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Organization Email
                            </label>
                            {{ Form::text('organization_email', $setup->organization_email ?? null, array('class' => 'form-control', 'placeholder' => 'Organization Email'))  }}
                        </div>

                        {{--settings--}}
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Absent on weekend if absent on consecutive previous and next day.
                            </label>
                            {{ Form::checkbox('absent_weekend_on_cons_absent', 1, (($setup->absent_weekend_on_cons_absent ?? null)==1)?true:false)  }}
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Absent on public holiday if absent on consecutive previous and next day.
                            </label>
                            {{ Form::checkbox('absent_publicholiday_on_cons_absent', 1, ($setup->absent_weekend_on_cons_absent ?? null==1)?true:false)  }}
                        </div>

                        <div class="form-group row">
                            <label for="max_overtime_hour" class="col-3 col-form-label">
                                Maximum Overtime Work Hours Payable (in a Month)
                            </label>
                            {{ Form::number('max_overtime_hour', old('max_overtime_hour',$setup->max_overtime_hour ?? null), ['class'=>'form-control','placeholder'=>'Maximum Overtime Work Hours Payable','step'=>0.01])  }}
                        </div>
                        <div class="form-group row">
                            <label for="overtime_calculation_types" class="col-3 col-form-label">
                               Overtime Calculation Type
                            </label>
                            {{ Form::select('overtime_calculation_type', $overtime_calculation_types,$setup->overtime_calculation_type ?? null, array('class' => 'form-control'))  }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Save',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close()  }}
@endsection
