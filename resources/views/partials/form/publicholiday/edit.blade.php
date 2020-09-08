<div class="row">
    <div class="col-md-7 col-sm-12">
        {{-- Basic Info --}}
        <div class="basic-info card">
            <h5 class="card-header">Public Holiday Information</h5>
            <div class="card-block">
                <div class="card-text">
                    <div class="form-group row">
                        <label for="title" class="col-3 col-form-label">
                            Branch <span class="required-field">*</span>
                        </label>
                        {{ Form::select('branch_id', $branches, null, ['id' => 'branch_id', 'disabled' => 'disabled'])  }}
                        {{ Form::hidden('branch_id', $publicHoliday->branch_id)  }}
                    </div>

                    <div class="form-group row">
                        <label for="title" class="col-3 col-form-label">
                            Public Holiday Name <span class="required-field">*</span>
                        </label>
                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Public Holiday Name',
                         'data-validation' => 'required',
                         'id' => 'name',
                         'data-validation-error-msg' => 'Please enter a religion'])  }}
                    </div>

                    <div class="form-group row">
                        <label for="title" class="col-3 col-form-label">
                            Description
                        </label>
                        {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Public Holiday Description'])  }}
                    </div>

                    <label for="gender" class="col-form-label">Gender</label>
                    <div class="radio-inline">
                        @foreach($genders as $value => $gender)
                            <label class="custom-control custom-radio radio-inline-label">
                                <input id="radio{{$value}}" name="gender" type="radio" class="custom-control-input"
                                       value="{{$value}}" {{isset($publicHoliday) ? $publicHoliday->gender == $value ? 'checked' : '' : ''}}>
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">{{$gender}}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label for="title" class="col-3 col-form-label">
                        Religion
                    </label>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    {{ Form::checkbox('toggle_religion', null, false, ['multiple', 'id' => 'religion_toggle',  'class' => 'religion col-md-1', 'placeholder' => 'Select a religion', 'checked' => false])  }}
                                    {!! Form::label('Select / Unselect All Religion',  'Select / Unselect All Religion', ['class' => 'col-md-11']) !!}
                                </div>
                            </div>
                            @foreach($religions as $key => $religionName)
                                <div class="col-md-4">
                                    <div class="row">
                                        {{ Form::checkbox('religion_id[]', $key, in_array($key, $publicHoliday->getRelatedReligionsIds()->toArray()), ['multiple', 'id' => 'religion_id',  'class' => 'religion col-md-1', 'placeholder' => 'Select a religion'])  }}
                                        {!! Form::label($religionName,  $religionName, ['class' => 'col-md-11']) !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label for="title" class="col-3 col-form-label">
                        Caste
                    </label>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        {{ Form::checkbox('toggle_caste', null, false, ['multiple', 'id' => 'caste_toggle',  'class' => 'caste col-md-1', 'placeholder' => 'Select a caste', 'checked' => false])  }}
                                        {!! Form::label('Select / Unselect All Caste',  'Select / Unselect All Caste', ['class' => 'col-md-11']) !!}
                                    </div>
                                </div>
                                @foreach($castes as $key => $casteName)
                                    <div class="col-md-4">
                                        <div class="row">
                                            {{ Form::checkbox('caste_id[]', $key, in_array($key, $publicHoliday->getRelatedCastesIds()->toArray()), ['multiple', 'id' => 'caste_id',  'class' => 'caste col-md-2', 'placeholder' => 'Select a caste'])  }}
                                            {!! Form::label($casteName,  $casteName, ['class' => 'col-md-10']) !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="form-group row">
                    <label for="from_date_np" class="col-3 col-form-label">
                        From Date NP<span class="required-field">*</span>
                    </label>
                    {{ Form::text('from_date_np', $from_date_np ?? null, ['class' => 'form-control nep-date', 'id' => 'from_date_np','required' => 'true' ,'placeholder' => 'Holiday Date From NP:', 'readonly' => 'readonly', 'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter date from']) }}
                </div>

                <div class="form-group row">
                    <label for="title" class="col-3 col-form-label">
                        From Date<span class="required-field">*</span>
                    </label>
                    {{ Form::text('from_date', $_GET['from_date'] ?? null, ['class' => 'date form-control','id' => 'from_date', 'placeholder' => 'Holiday Date From:','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter date from' ])  }}
                </div>

                <div class="form-group row">
                    <label for="from_date_np" class="col-3 col-form-label">
                        To Date NP<span class="required-field">*</span>
                    </label>
                    {{ Form::text('to_date_np', $to_date_np ?? null, ['class' => 'form-control nep-date', 'id' => 'to_date_np','required' => 'true' ,'placeholder' => 'Holiday Date To:', 'readonly' => 'readonly', 'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter date to']) }}
                </div>

                <div class="form-group row">
                    <label for="title" class="col-3 col-form-label">
                        To Date<span class="required-field">*</span>
                    </label>
                    {{ Form::text('to_date', $_GET['to_date'] ?? null, ['class' => 'date form-control','id' => 'to_date', 'placeholder' => 'Holiday Date To:','readonly'=>'readonly','required'=>'required','data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter date to' ])  }}
                </div>

                <div class="form-group row">
                    <label for="Total holiday days" class="col-3 col-form-label">Total
                        Holiday Days</label>
                    <p id="total_holiday_days">0 days</p>
                </div>
            </div>
        </div>

        {{--  Save --}}
        <div class="row">
            <div class="col-md-12">
                <div class="text-right form-control">
                    {{ Form::submit($buttonName,['class'=>'btn btn-success btn-lg'])}}
                </div>
            </div>
        </div>
    </div>

</div>
