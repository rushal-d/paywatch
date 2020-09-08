@extends('layouts.default', ['crumbroute' => 'bonuses.edit'])
@section('title', $title)
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection
@section('content')

    {{ Form::open(['route' => ['bonuses.update', $bonus->id], 'method' => 'PATCH']) }}

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Bonuses Obtained</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            {!! Form::label('staff_central_id', 'Staff Name ', ['class' => 'col-3 col-form-label']) !!}
                            <input type="text" value="{{ $bonus->staff_central_id  }}" id="staff_central_id" name="staff_central_id" class="input-sm" required>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('fiscal_year_id', 'Fiscal Year ', ['class' => 'col-3 col-form-label']) !!}
                            {!! Form::select('fiscal_year_id', $fiscal_year, $bonus->fiscal_year_id ?? null, ['class' => 'input-sm', 'id' => 'fiscal_year', 'required' => 'required', 'placeholder' => 'Fiscal Year']) !!}
                        </div>
                        <div class="form-group row">
                            {!! Form::label('branch_id', 'Branch ', ['class' => 'col-3 col-form-label']) !!}
                            {!! Form::select('branch_id', $branch, $bonus->branch_id ?? null, ['class' => 'input-sm', 'id' => 'fiscal_year', 'required' => 'required', 'placeholder' => 'Branch']) !!}
                        </div>
                        <div class="form-group row">
                            {!! Form::label('received_date', 'Received Date ', ['class' => 'col-3 col-form-label']) !!}
                            {!! Form::text('received_date', $bonus->received_date ?? null, ['class' => 'input-sm col-8.5 form-control', 'id' => 'received_date', 'required' => 'required', 'placeholder' => 'Date', 'readonly' => 'readonly']) !!}
                        </div>
                        <div class="form-group row">
                            {!! Form::label('received_date_np', 'Received Date (NP) ', ['class' => 'col-3 col-form-label']) !!}
                            {!! Form::text('received_date_np', $bonus->received_date_np ?? null, ['class' => 'input-sm col-8.5 form-control', 'id' => 'received_date_np', 'required' => 'required', 'placeholder' => 'Date', 'readonly' => 'readonly']) !!}
                        </div>
                        <div class="form-group row">
                            {!! Form::label('received_amount', 'Received Amount ', ['class' => 'col-3 col-form-label']) !!}
                            {!! Form::number('received_amount', $bonus->received_amount ?? null, ['class' => 'input-sm col-8.5 form-control', 'id' => 'received_amount', 'required' => 'required', 'placeholder' => 'Amount']) !!}
                        </div>
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
            </div>
        </div>
    </div>
    {{ Form::close()  }}
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        // input_staff_central_id.on('change', onChangeStaffCentralId());
        var staffs = <?php echo $staffs ?>;
        console.log(staffs);
        $('#staff_central_id').selectize({
            valueField: 'id',
            labelField: 'name_eng',
            searchField: ['name_eng', 'main_id'],
            options: staffs,
            preload: true,
            maxItems: 1,
            create: false,
            render: {
                option: function (item, escape) {
                    return '<div class="suggestions"><div> Name: ' + item.name_eng + '</div>' +
                        '<div> Staff ID: ' + item.main_id + '</div>' +
                        '<div> Father Name: ' + item.FName_Eng + '</div></div>';
                }
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '{{ route('get-staff') }}?search=' + encodeURIComponent(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res.staffs);
                    }
                });
            }
        });

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
