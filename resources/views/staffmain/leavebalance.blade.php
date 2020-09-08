@extends('layouts.default', ['crumbroute' => 'staffedit'])
@section('title', $title)
@section('content')
    <style>
        .mydrop {
            width: 305px;
            margin-left: -49px;
        }

        .required-field {
            color: red;
        }
    </style>
    @include('staffmain.staff-edit-nav')

    <form method="post" action="{{ route('staff-leave-balance-store',$staffmain->id) }}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{csrf_token()}}">

        <div class="row">
            <div class="col-md-7 col-sm-12">

                {{--setup-hosiday--}}
                <div>
                    <div class="basic-info card">
                        <h5 class="card-header">Staff Holidays: {{$staffmain->name_eng}} -
                            [CID: {{$staffmain->staff_central_id}}] - [Branch
                            ID: {{$staffmain->main_id}} {{$staffmain->branch->office_name ?? ''}}]</h5>
                        <div class="card-block">
                            <div class="card-text">

                                @foreach($system_leaves as $system_leave)
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">
                                            {{$system_leave->leave_name}}<span class="required-field">*</span>
                                        </label>
                                        @php $leaveBalance=$staffmain->leaveBalance->where('leave_id',$system_leave->leave_id)->last() @endphp
                                        @if(!empty($leaveBalance))
                                            <a href="{{route('leavebalance-edit',$leaveBalance->id)}}"
                                               target="_blank"> {{$leaveBalance->balance}}</a>
                                        @else
                                            {{ Form::number('leave['.$system_leave->leave_id.']', $system_leave->no_of_days, array('class' => 'form-control', 'placeholder' => 'Enter No of Days',
                                             'data-validation' => 'required','step'=>0.01,
                                             'data-validation-error-msg' => 'Please enter No. Of Days'))  }}
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                {{--end-holiday-setup--}}

                <div class="row">
                    <div class="col-md-12">
                        <div class="text-right form-control">
                            {{ Form::submit('Save',array('class'=>'btn btn-success btn-lg'))}}
                        </div>
                    </div>
                </div>
            </div>


        </div>


    </form>

@endsection
@section('script')

    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>

        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 50 // Options | Number of years to show
        });
    </script>
@endsection
