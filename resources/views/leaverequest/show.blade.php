@extends('layouts.default', ['crumbroute' => 'leave-request-show'])
@section('title', $title)
@section('style')
@endsection
@section('content')

    {{ Form::open(array('route' => 'leaverequest-save', 'id'=>'request-leave-form'))  }}

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Request Leave Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <p>
                                    Staff Name:
                                </p>
                            </div>
                            <div class="col-md-9">
                                <p>
                                    {{$leaveRequest->staff->name_eng ?? ''}}
                                </p>
                            </div>

                            <div class="col-md-3">
                                <p>
                                    Leave Name:
                                </p>
                            </div>
                            <div class="col-md-9">
                                <p>
                                    {{$leaveRequest->leave->leave_name ?? ''}}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p>
                                    Description:
                                </p>
                            </div>
                            <div class="col-md-9">
                                <p>
                                    {{$leaveRequest->description ?? ''}}
                                </p>
                            </div>

                            <div class="col-md-3">
                                <p>
                                    Leave Balance:
                                </p>
                            </div>
                            <div class="col-md-9">
                                <p>
                                    {{$leaveRequest->leave_balance}} at time of leave request.
                                    ({{$leaveRequest->staff->latestLeaveBalanceWithID($leaveRequest->leave_id)->balance ?? ''}}
                                    Current Balance)
                                </p>
                            </div>

                            <div class="col-md-3">
                                <p>
                                    Leave From:
                                </p>
                            </div>
                            <div class="col-md-9">
                                <p>
                                    {{$leaveRequest->from_leave_day_np ?? ''}} (BS)
                                </p>
                                <p>
                                    {{$leaveRequest->from_leave_day ?? ''}} (AD)
                                </p>
                            </div>

                            <div class="col-md-3">
                                <p>
                                    Leave To:
                                </p>
                            </div>
                            <div class="col-md-9">
                                <p>
                                    {{$leaveRequest->to_leave_day_np ?? ''}} (BS)
                                </p>
                                <p>
                                    {{$leaveRequest->to_leave_day ?? ''}} (AD)
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p>
                                    Leave Days:
                                </p>
                            </div>
                            <div class="col-md-9">
                                <p>
                                    {{$leaveRequest->holiday_days ?? ''}} Day(s)
                                </p>
                            </div>

                            @foreach($file_types as $file)
                                <div class="col-md-3">
                                    <p>
                                        {{$file->file_type}}
                                    </p>
                                </div>
                                <div class="col-md-9">
                                   <div class="row">
                                       @foreach($leaveRequest->leaveRequestFiles->where('staffFile.file_type_id',$file->id) as $leaveRequestFile)

                                           <div class="col-md-6">
                                               <div class="text-center">
                                                   <div>
                                                       <a href="{{route('staff-file-download',$leaveRequestFile->staffFile->file_name)}}">
                                                           <i class="fas fa-file-alt"></i>
                                                       </a>
                                                   </div>
                                                   <p> {{$leaveRequestFile->staffFile->file_name}}</p>
                                               </div>
                                           </div>
                                       @endforeach
                                   </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close()  }}
@endsection
@section('script')


@endsection
