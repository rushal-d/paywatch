@extends('layouts.default', ['crumbroute' => 'transferedit'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => array('staff-transfer-update',$stafftransfer->transfer_id), 'class' => 'educationform' ))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Edit Staff Transfer Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Staff Name
                            </label>
                            <select id="staff_central_id" name="staff_central_id" required>
                                <option value="">Select Staff</option>
                                @foreach($staffs as $staff)
                                    <option @if($stafftransfer->staff_central_id == $staff->id ) selected
                                            @endif value="{{$staff->id}}">{{$staff->name_eng}}({{$staff->main_id}})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group row">
                            <label for="office_id" class="col-3 col-form-label">
                                Office Name
                            </label>
                            <select id="office_id" name="office_id" required>
                                <option value="">Select Office</option>
                                @foreach($offices as $office)
                                    <option @if($stafftransfer->office_id == $office->office_id ) selected
                                            @endif value="{{$office->office_id}}">{{$office->office_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group row">
                            <label for="office_id" class="col-3 col-form-label">
                                Transfer Branch Staff ID:
                            </label>
                            {{ Form::text('main_id', $stafftransfer->main_id, array('class' => 'form-control','id'=>'main_id' , 'placeholder' => 'Please enter Branch Staff ID',
                               'required' => 'required'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="from_date" class="col-3 col-form-label">
                                Transfer Date
                            </label>
                            {{ Form::text('from_date', $stafftransfer->from_date, array('class' => 'form-control nep-date','id'=>'nep-date1' , 'placeholder' => 'Please enter starting Date',
                             'required' => 'required'))  }}

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

    </div>
    {{ Form::close()  }}




@endsection



@section('script')


    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20
        });


        $('#office_id').change(function () {
            let branch_id = $(this).val();
            let main_id = $('#main_id').val();
            $.ajax({
                url: '{{route('last-main-id-of-branch')}}',
                type: 'POST',
                data: {
                    'branch_id': branch_id,
                    '_token': '{{csrf_token()}}'
                }, success: function (data) {
                    if (main_id === '') {
                        $('#main_id').val(data);
                    }
                }
            })
        })
    </script>
@endsection
