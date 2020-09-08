@extends('layouts.default', ['crumbroute' => 'bonuses.create'])
@section('title', $title)

@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
    {!! Form::open(['route' => 'bonuses.bulkinsert']) !!}
    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Bulk Insert Bonuses
            <span class="tag tag-pill tag-success pull-right">{{ $staffs->count() }}</span>
        </div>
        <div class="card-block">
            <div class="top" style="text-align: center">
                <b>Branch:</b> {{$branch->office_name}}<br/>
                <b>Fiscal Year:</b> {{$fiscal_year->fiscal_code}}<br/>
                <b>Received Date:</b> {{$received_date}}<br/>
            </div>
            <table class="table table-responsive table-striped table-hover table-all mt-2" width="100%" cellspacing="0">
            <tbody>
            <tr align="left">
                <th>Staff Name</th>
                <th>Staff Central ID</th>
                <th>Branch</th>
                <th>Amount</th>
            </tr>
            {!! Form::hidden('received_date', $received_date) !!}
            {!! Form::hidden('received_date_np', $received_date_np) !!}
            {!! Form::hidden('branch_id', $branch_id) !!}
            {!! Form::hidden('fiscal_year_id', $fiscal_year_id) !!}
            @foreach($staffs as $loopKey => $staff)
                <tr>
                    <td>
                        {{$staff->name_eng}}
                        {!! Form::hidden("bonuses[$loopKey][staff_central_id]", $staff->id) !!}
                    </td>
                    <td>{{$staff->id}}</td>
                    <td>
                    {{$staff->branch->office_name}}
                    </td>
                    <td>
                        @php
                            $previousBonus = $previousBonuses->where('staff_central_id', $staff->id)->first();
                            $defaultPreviousValue = null;
                            if(!empty($previousBonus)){
                                $defaultPreviousValue = $previousBonus->received_amount;
                            }
                        @endphp
                        {!! Form::number("bonuses[$loopKey][received_amount]", $defaultPreviousValue ?? 0, ['class' => 'input-sm', 'id' => 'received_amount', 'placeholder' => 'Amount']) !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
    {{--  Save --}}
    <div class="row">
        <div class="col-md-12">
            <div class="text-right form-control">
                {{ Form::submit('Save',array('id' => 'submit-form', 'class'=>'btn btn-success btn-lg'))}}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        function changeEnDate(){
            $('#received_date') ? $('#received_date_np').val(AD2BS($('#received_date').val())) : '';
        }

        $('#received_date').flatpickr({
            dateFormat: "Y-m-d",
            disableMobile: true,
            onChange: changeEnDate
        });
        $('#received_date_np').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#received_date_np').val() ? $('#received_date').val(BS2AD($('#received_date_np').val())) : '';
            }
        });
    </script>
@endsection
