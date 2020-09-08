@extends('layouts.default', ['crumbroute' => 'staff-jobtype-alert-index'])
@section('title', $title)

@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <style>
        .staff_container {
            width: 72%;
        }

        .selectize-control.input-sm.single {
            width: 100%;
        }

        .level {
            display: flex;
        }

        .flex {
            flex: 1;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@endsection
@section('content')

    {{ Form::open(array('route' => 'staff-job-type-alert.show','method'=>'GET'))  }}
    <div class="row">
        <div class="col-md-6 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <div class="level">
                    <h5 class="card-header flex">Filter Staff Job Type Alert</h5>
                </div>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch
                            </label>
                            {!! Form::select('branch_id', $branches , null,array('id'=>'branch_id') ) !!}
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label" id="staff">
                                Staff Name
                            </label>
                            <input type="text" id="staff_central_id" name="staff_central_id" class="input-sm"
                                   disabled>
                        </div>


                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Select Job Type Alert <span class="required-field">*</span>
                            </label>
                            {!! Form::select('job_alert_type', $job_type_alerts , null,array('id'=>'job_type_alert', 'placeholder' => 'Select Job Type Alert', 'required' => 'required') ) !!}
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Filter',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close()  }}


@endsection


@section('script')
    <script>
        //responsive table
        $(function () {
            $('.table-all').stacktable();
        });
    </script>

    <script src="{{ asset('assets/js/vex.combined.js') }}"></script>
    <script>
        //apply vex dialog
        (function () {
            vex.defaultOptions.className = 'vex-theme-os'
            //vex.dialog.buttons.YES.text = 'Yes'
            vex.dialog.buttons.YES.className = 'btn btn-danger'
        })();
    </script>

    <script>

        function onChangeBranchId() {
            branch = $('#branch_id').val();
            $.ajax({
                url: '{{route('get-staff-by-branch')}}',
                type: 'post',
                data: {
                    'branch': branch,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data) {
                    //console.log(data);
                    staffs = data;
                    console.log(staffs);
                    $('#staff_central_id').remove();
                    $('.removeit').remove();
                    $('.staff_container').remove();
                    $('#staff').after('   <div class="staff_container"><input type="text" id="staff_central_id" name="staff_central_id" class="input-sm" \n' +
                        '                                   ></div>')
                    $('#staff_central_id').prop('disabled', false);
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
                                    '<div> Branch ID: ' + item.main_id + '</div>' +
                                    '<div> Branch: ' + item.branch.office_name + '</div>' +
                                    '<div> CID: ' + item.staff_central_id + '</div>' +
                                    '</div>';
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
                    console.log($('#staff').next().next().addClass('removeit'));
                }
            });
        }

        onChangeBranchId();

        var staffs = new Array();
        $('#branch_id').change(onChangeBranchId);
    </script>

@endsection
