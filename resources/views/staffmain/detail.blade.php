@extends('layouts.default', ['crumbroute' => 'staffview'])
@section('title', $title)
@section('content')
    <style>
        .table th, .table td {
            padding: 4px;
        }
    </style>
    <form>
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="row">
            <div class="col-md-7 col-sm-12">
                {{-- Basic Info --}}
                <div class="basic-info card">
                    <h5 class="card-header">Staff Detail View </h5>

                    <div class="card-block">
                        <div class="card-text">
                            <div class="form-group row">
                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Staff Image :</b>
                                    </p>
                                    <?php
                                    $image = asset('assets/images/user.png');
                                    if (!empty($staffmain->image)) {
                                        $image = asset("Images/$staffmain->image");
                                    }
                                    ?>
                                    <div id="staffimage">
                                        <img src="{{ $image }}" height="150" width="150">
                                    </div>


                                </div>
                                <div class="col-md-3">
                                    <p>
                                        <b>Branch ID :</b>
                                        {{$staffmain->main_id}}
                                    </p>
                                </div>

                                <div class="col-md-3">
                                    <p>
                                        <b>Staff Central ID:</b> {{$staffmain->staff_central_id}}
                                    </p>
                                </div>
                            </div>

                            <div class="row no-gutters two-fields">

                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Full Name:</b> {{$staffmain->name_eng}}
                                    </p>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Father's Name:</b> {{$staffmain->FName_Eng ?? 'N/A'}}
                                    </p>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Spouse:</b> {{$staffmain->spname_eng ?? 'N/A'}}
                                    </p>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Grand Father's Name:</b> {{$staffmain->gfname_eng ?? 'N/A'}}
                                    </p>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Gender:</b>
                                        <?php
                                        if ($staffmain->Gender == '1') {
                                            echo "Male";
                                        } elseif ($staffmain->Gender == '2') {
                                            echo "Female";
                                        } elseif ($staffmain->Gender == '3') {
                                            echo "Other";
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Marital Status:</b> {{$martial_status[$staffmain->marrid_stat] ?? 'N/A'}}
                                    </p>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Phone Number:</b> {{$staffmain->phone_number ?? 'N/A'}}
                                    </p>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Emergency Phone Number:</b> {{$staffmain->emergency_phone_number ?? 'N/A'}}
                                    </p>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Caste:</b> {{$staffmain->caste->caste_name ?? 'N/A'}}
                                    </p>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Religion:</b> {{$staffmain->religion->religion_name ?? 'N/A'}}
                                    </p>
                                </div>


                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Date Of Birth:</b> {{$staffmain->staff_dob ?? 'N/A'}}
                                    </p>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Citizen Number:</b> {{$staffmain->staff_citizen_no ?? 'N/A'}}
                                    </p>
                                </div>


                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Issue
                                            Office:</b> {{$staffmain->staff_citizen_issue_office ?? 'N/A'}}
                                    </p>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Issue
                                            Date:</b> {{$staffmain->staff_citizen_issue_date_np ?? 'N/A'}}
                                    </p>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>District
                                            name:</b> {{$staffmain->district->district_name ?? 'N/A'}}
                                    </p>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>VDC Name:</b> {{$staffmain->district->mun_vdc ?? 'N/A'}}
                                    </p>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Ward No:</b> {{$staffmain->ward_no ?? 'N/A'}}
                                    </p>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Tole /Basti:</b> {{$staffmain->tole_basti ?? 'N/A'}}
                                    </p>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Sync:</b> {{$staffmain->sync ?? 'N/A'}}
                                    </p>
                                </div>
                            </div>
                            {{--ward tole end--}}
                            <div class="form-group row">
                                <label for="file_upload" class="col-3 col-form-label">
                                    File
                                </label>
                                <div class="col-9">

                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                {{--Nominnee Start --}}
                @php
                    $nomine=$staffmain->nominee->first();
                @endphp
                    <div class="basic-info card">
                        <h5 class="card-header">Staff Nominee</h5>
                        <div class="card-block">
                            <div class="card-text">
                                <div class="row no-gutters two-fields">
                                    <div class="col-md-6 col-sm-12">
                                        <p>
                                            <b>Applied Date :</b>
                                            {{$nomine->appli_date ?? 'N/A'}}
                                        </p>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <p>
                                            <b>Relation :</b>
                                            {{$nomine->relation ?? 'N/A'}}
                                        </p>
                                    </div>
                                </div>
                                <div class="row no-gutters two-fields">
                                    <div class="col-md-6 col-sm-12">
                                        <p>
                                            <b>Nominee Name :</b>
                                            {{$nomine->nominee_name ?? 'N/A'}}
                                        </p>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <p>
                                            <b>Date of Birth :</b>
                                            {{$nomine->dob ?? 'N/A'}}
                                        </p>
                                    </div>
                                </div>
                                <div class="row no-gutters two-fields">
                                    <div class="col-md-6 col-sm-12">
                                        <p>
                                            <b>Citizen Number :</b>
                                            {{$nomine->citizen_no ?? 'N/A'}}
                                        </p>
                                    </div>
                                </div>
                                <div class="row no-gutters two-fields">
                                    <div class="col-md-6 col-sm-12">
                                        <p>
                                            <b>Issue Office :</b>
                                            {{$nomine->issue_office ?? 'N/A'}}
                                        </p>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <p>
                                            <b>Issue Date :</b>
                                            {{$nomine->issue_date_np ?? 'N/A'}}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                {{--staff payment start--}}

                <div class="basic-info card">
                    <h5 class="card-header">Staff Payment</h5>
                    <div class="card-block">
                        <div class="card-text">
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Is Cash Payment :</b>
                                        {{empty($staffmain->bank_id) ? 'Yes':'No'}}
                                    </p>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Bank Name :</b>
                                        {{$staffmain->bankInformation->bank_name ?? 'N/A'}}
                                    </p>


                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Account Number :</b>
                                        {{$staffmain->acc_no ?? 'N/A'}}
                                    </p>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>PF Account :</b>
                                        {{$staffmain->profund_acc_no ?? 'N/A'}}
                                    </p>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>Social Security :</b>
                                        {{$staffmain->social_security_fund_acc_no ?? 'N/A'}}
                                    </p>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <p>
                                        <b>PAN Number :</b>
                                        {{$staffmain->pan_number ?? 'N/A'}}
                                    </p>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>

                {{--staff payment end--}}
                {{-- end nominee schedule  --}}
                {{--end --}}

            </div>
            {{-- Right Sidebar  --}}
            <div class="col-md-5 col-sm-12">
                <div class="basic-info card">
                    <h5 class="card-header">Job Information</h5>
                    <div class="card-block">
                        <div class="card-text">
                            <p>
                                <b> Staff Type: </b>
                                {{$staffmain->staffType->staff_type_title ?? 'N/A'}}
                            </p>
                            <p>
                                <b> Job Type :</b>
                                {{$staffmain->jobtype->jobtype_name ?? 'N/A'}}
                            </p>
                            <p>
                                <b> Education :</b>
                                {{$staffmain->education->edu_description ?? 'N/A'}}
                            </p>

                            <p>
                                <b> Appointment Date :</b>
                                {{$staffmain->appo_date ?? 'N/A'}} / {{$staffmain->appo_date_np ?? 'N/A'}}
                            </p>

                            <p>
                                <b> Appointment Office :</b>
                                {{$staffmain->appooffice->office_name ?? 'N/A'}}
                            </p>
                            <p>
                                <b> Temp Date :</b>
                                {{$staffmain->temporary_con_date ?? 'N/A'}} / {{$staffmain->temporary_con_date_np ?? 'N/A'}}
                            </p>

                            <p>
                                <b> Permanent Date :</b>
                                {{$staffmain->permanent_date ?? 'N/A'}} / {{$staffmain->permanent_date_np ?? 'N/A'}}
                            </p>
                            <p>
                                <b> Position / Designation :</b>
                                {{$staffmain->jobposition->post_title ?? 'N/A'}}
                            </p>

                            <p>
                                <b> Section :</b>
                                {{$staffmain->getSection->section_name ?? 'N/A'}}
                            </p>

                            <p>
                                <b> Department :</b>
                                {{$staffmain->getDepartment->department_name ?? 'N/A'}}
                            </p>

                            <p>
                                <b> Status :</b>
                                {{($staffmain->staff_status==1 ? 'Active': 'Deactive')}}
                            </p>

                            <p>
                                <b> Deduct Levy :</b>
                                {{($staffmain->deduct_levy==1 ? 'Yes': 'No')}}
                            </p>

                            <p>
                                <b> Created At :</b>
                                {{$staffmain->created_at ?? 'N/A'}}
                            </p>

                            <p>
                                <b> Updated At :</b>
                                {{$staffmain->updated_at ?? 'N/A'}}
                            </p>

                            <p>
                                <b> Created By :</b>
                                {{$staffmain->createdBy->name ?? 'N/A'}}
                            </p>

                            <p>
                                <b> Updated At :</b>
                                {{$staffmain->updatedBy->name ?? 'N/A'}}
                            </p>

                        </div>
                    </div>
                </div>
                {{--start staff work --}}
            </div>
            {{-- End of sidebar --}}


        </div>


    </form>

    <div class="row">
        <div class="col-md-12 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Work Schedule History </h5>

                <div class="card-block">
                    <table class="table table-bordered">
                        <thead>
                        <th>Created Date</th>
                        <th>Work Hour</th>
                        <th>Max Work Hour</th>
                        <th>Weekend Day</th>
                        <th>Effect Date</th>
                        <th>Created By</th>
                        <th>Work Status</th>
                        </thead>
                        <tbody>
                        @foreach($staffmain->workschedule as $workschedule_history)
                            <tr>
                                <td>{{$workschedule_history->created_at}}</td>
                                <td>{{$workschedule_history->work_hour}}</td>
                                <td>{{$workschedule_history->max_work_hour}}</td>
                                <td>{{$weekend_days[$workschedule_history->weekend_day]}}</td>
                                <td>{{$workschedule_history->effect_date_np}}</td>
                                <td>{{$workschedule_history->createdBy->name ?? 'N/A'}}</td>
                                <td>{{$workschedule_history->work_status=='A'?'Active':'Deactive'}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="col-md-12 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Shift History </h5>

                <div class="card-block">
                    <table class="table table-bordered">
                        <thead>
                        <th>Created Date</th>
                        <th>Shift Name</th>
                        <th>Shift Status</th>
                        <th>Effective From</th>
                        <th>Shift Time</th>
                        <th>Created By</th>
                        </thead>
                        <tbody>
                        @foreach($staffmain->shiftHistory as $staffShift)
                            <tr>
                                <td>{{$staffShift->created_at}}</td>
                                <td>{{$staffShift->shift->shift_name ?? ''}}</td>
                                <td>{{($staffShift->shift->active ?? null)==1?'Active':'Inactive'}}</td>
                                <td>{{$staffShift->effective_from}}</td>
                                <td>
                                    ({{date('h:i:s a',strtotime($staffShift->punch_in)-$staffShift->before_punch_in_threshold*60)}}
                                    -{{date('h:i:s a',strtotime($staffShift->punch_in)+$staffShift->after_punch_in_threshold*60)}}
                                    )
                                </td>
                                <td>{{$staffShift->createdBy->name ?? 'N/A'}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Nominee History </h5>

                <div class="card-block">
                    <table class="table table-bordered">
                        <thead>
                        <th>Created Date</th>
                        <th>Applied Date</th>
                        <th>Relation</th>
                        <th>Nominee Name</th>
                        <th>Date of Birth</th>
                        <th>Citizen Number</th>
                        <th>Issue Office</th>
                        <th>Issue Date</th>
                        <th>Created By</th>
                        </thead>
                        <tbody>
                        @foreach($staffmain->nominee as $nominee)
                            <tr>
                                <td>{{$nominee->created_at}}</td>
                                <td>{{$nominee->appli_date}}</td>
                                <td>{{$nominee->relation}}</td>
                                <td>{{$nominee->nominee_name}}</td>
                                <td>{{$nominee->dob}}</td>
                                <td>{{$nominee->citizen_no}}</td>
                                <td>{{$nominee->issue_office}}</td>
                                <td>{{$nominee->issue_date}}</td>
                                <td>{{$nominee->createdBy->name ?? 'N/A'}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Salary History </h5>

                <div class="card-block">
                    <table class="table table-bordered">
                        <thead>
                        <th>Created Date</th>
                        <th>Designation</th>
                        <th>Basic Salary</th>
                        <th>Additional Salary</th>
                        <th>Effective Date</th>
                        <th>Payment Status</th>
                        <th>Created By</th>
                        <th>Updated By</th>
                        </thead>
                        <tbody>
                        @foreach($staffmain->salary as $salary)
                            <tr>
                                <td>{{$salary->created_at}}</td>
                                <td>{{$salary->post->post_title ?? ''}}</td>
                                <td>{{$salary->basic_salary}}</td>
                                <td>{{$salary->add_salary_amount}}</td>
                                <td>{{$salary->salary_effected_date}}</td>
                                <td>{{$salary->salary_payment_status=='A'?'Active':'Deactive'}}</td>
                                <td>{{$salary->created_get->name ?? ''}}</td>
                                <td>{{$salary->updated_get->name ?? 'N/A'}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Transfer History </h5>

                <div class="card-block">
                    <table class="table table-bordered">
                        <thead>
                        <th>Created Date</th>
                        <th>Office From</th>
                        <th>Office To</th>
                        <th>Join Date</th>
                        <th>To Date</th>
                        <th>By</th>
                        </thead>
                        <tbody>
                        @foreach($staffmain->staffTransfer->where('office_id','<>',null) as $transfer)
                            <tr>
                                <td>{{$transfer->created_at}}</td>
                                <td>{{$transfer->office_from_get->office_name ?? ''}}</td>
                                <td>{{$transfer->office->office_name ?? ''}}</td>
                                <td>{{$transfer->from_date}}</td>
                                <td>{{$transfer->transfer_date}}</td>
                                <td>{{$transfer->author->name ?? ''}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Status History </h5>

                <div class="card-block">
                    <table class="table table-bordered">
                        <thead>
                        <th>Created Date</th>
                        <th>Date From</th>
                        <th>Date To</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Updated By</th>
                        </thead>
                        <tbody>
                        @foreach($staffmain->staffStatus as $status)
                            <tr>
                                <td>{{$status->created_at}}</td>
                                <td>{{$status->date_from_np}}</td>
                                <td>{{$status->date_to_np}}</td>
                                <td>{{$staff_status[$status->status]}}</td>
                                <td>{{$status->created_name->name ?? ''}}</td>
                                <td>{{$status->updated_name->name ?? ''}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Payment/Allowance History </h5>

                <div class="card-block">
                    <table class="table table-bordered">
                        <thead>
                        <th>Created Date</th>
                        <th>Allowance Title</th>
                        <th>Allowance Amount</th>
                        <th>Allow?</th>
                        <th>Effective From Date (AD)</th>
                        <th>Effective From Date (BS)</th>
                        <th>Effective To Date (BS)</th>
                        <th>Effective To Date (AD)</th>
                        <th>Created By</th>
                        <th>Updated By</th>
                        </thead>
                        <tbody>
                        @foreach($staffmain->payment->sortBy('allow_id') as $payment)
                            <tr>
                                <td>{{$payment->created_at}}</td>
                                <td>{{$payment->allowance->allow_title ?? ''}}</td>
                                <td>{{$payment->amount ?? ''}}</td>
                                <td>{{($payment->allow==1? "Yes":"No")}}</td>
                                <td>{{$payment->effective_from}}</td>
                                <td>{{$payment->effective_from_np}}</td>
                                <td>{{$payment->effective_to}}</td>
                                <td>{{$payment->effective_to_np}}</td>
                                <td>{{$payment->createdBy->name ?? ''}}</td>
                                <td>{{$payment->updatedBy->name ?? ''}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




    {{--{{ Form::close()  }}--}}
@endsection
@section('script')

@endsection
