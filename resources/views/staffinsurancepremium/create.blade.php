@extends('layouts.default', ['crumbroute' => 'staff-insurance-premium-create'])
@section('title', $title)
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
@endsection
@section('content')
    {{ Form::open(array('route' => 'staff-insurance-premium-store'))  }}

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Insurance Premium</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            {!! Form::label('staff_central_id', 'Staff Name ', ['class' => 'col-3 col-form-label']) !!}
                            {!! Form::text('staff_central_id', null, ['class' => 'input-sm', 'id' => 'staff_central_id', 'required' => 'required']) !!}
                        </div>
                        <div class="form-group row">
                            {!! Form::label('fiscal_year_id', 'Fiscal Year ', ['class' => 'col-3 col-form-label']) !!}
                            {!! Form::select('fiscal_year_id', $fiscal_year, null, ['class' => 'input-sm', 'id' => 'fiscal_year_id', 'required' => 'required', 'placeholder' => 'Fiscal Year']) !!}
                        </div>
{{--                        <div class="form-group row">--}}
{{--                            {!! Form::label('branch_id', 'Branch ', ['class' => 'col-3 col-form-label']) !!}--}}
{{--                            {!! Form::select('branch_id', $branch, null, ['class' => 'input-sm', 'id' => 'branch_id', 'required' => 'required', 'placeholder' => 'Branch']) !!}--}}
{{--                        </div>--}}
                        <div class="form-group row">
                            {!! Form::label('premium_amount', 'Premium Amount ', ['class' => 'col-3 col-form-label']) !!}
                            {!! Form::text('premium_amount', null, ['class' => 'input-sm col-8.5 form-control', 'id' => 'Premium Amount', 'required' => 'required']) !!}
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
    </script>
@endsection
