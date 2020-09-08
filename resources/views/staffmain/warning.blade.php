@extends('layouts.default', ['crumbroute' => 'staff-warning'])
@section('title', $title)
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <style>
        .button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 5px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            margin: 2px 1px;
            cursor: pointer;
        }

        .adjust-width {
            max-width: 170px;
            width: 100%;
            margin-left: 10px;
            display: block;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="quick-actions">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <button class="nav-link text-danger btn-link" id="staff-main-warning" type="submit"><i
                            class="fa fa-exclamation-circle"></i> Warning Staff ({{$staffmains->total()}})
                    </button>
                </li>
            </ul>
        </div>
        {{ Form::open(array('route' => 'staff-main-warning','method' => 'get', 'id' => 'staff-main-form-filter'))  }}
        <div class="card">
            <div class="search-box">
                <div class="row">
                    {!! Form::select('branch_id', $branches , request('branch_id'),array('id'=>'branch_id','class'=> 'adjust-width','data-validation' => 'required',
                             ) ) !!}

                    {!! Form::select('department_id', $departments , request('department_id'), array('id'=>'department_id', 'class'=> 'adjust-width') ) !!}

                    <div class="staff-form-group">
                        <span id="staff"></span>
                    </div>
                    {!! Form::select('staff_central_id', $staffmains, request('staff_central_id')?:'', ['class' => 'adjust-width staffs', 'placeholder' => 'Select a staff']) !!}

                    {{--                    {!! Form::select('staff_type', $staff_types , request('job_type_id'), array( 'id'=>'staff_type', 'class'=> 'adjust-width', 'placeholder'=>'Select a Staff Type') ) !!}--}}

                    {!! Form::select('job_type_id', $jobTypes , request('job_type_id'), array( 'id'=>'job_type_id', 'class'=> 'adjust-width', 'placeholder'=>'Select Job Type') ) !!}

                    {!! Form::select('designation_id', $designations , request('designation_id'), array( 'id'=>'designation_id', 'class'=> 'adjust-width', 'placeholder'=>'Select a Designation') ) !!}

                    {!! Form::select('warning_option', $warning_options , request('warning_option'), array( 'id'=>'warning_option', 'class'=> 'adjust-width', 'placeholder'=>'Select an warning option') ) !!}

                    {!! Form::select('shift_id', $shifts , request('shift_id'), array( 'id'=>'shift_id', 'class'=> 'adjust-width', 'placeholder'=>'Select a Shift') ) !!}

                    {{ Form::select('rpp', $records_per_page_options, $records_per_page , array('id' => 'records_per_page', 'class'=> 'adjust-width', 'placeholder' => 'Select a Record Per Page')) }}

                    <div class="adjust-width">
                        <label for="">Show Inactive</label>
                        {{Form::checkbox('show_inactive',1,isset($_GET['show_inactive'])? true:false)}}
                    </div>

                    <button class="button button-print adjust-width" type="submit">Filter</button>

                    {{ Form::close()  }}
                </div>
            </div>
        </div>

    </div>


    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> All Staff
            <span class="tag tag-pill tag-success pull-right">{{ $staffmains->total() }}</span>
        </div>


        <div class="card-block">
            <table class="table table-responsive table-striped table-hover table-all">
                <tbody>
                <tr align="left">
                    <th><input type="checkbox" class="check-all"></th>
                    <th>Central ID</th>
                    <th>Branch ID</th>
                    <th>Image</th>
                    <th>Full Name</th>
                    <th>Weekend</th>
                    <th>Designation (Post)</th>
                    <th>Job Type</th>
                    <th>Branch</th>
                    <th>Warning Detail</th>
                    <th>Staff Status</th>
                    <th>Action</th>
                </tr>
                @foreach($staffmains as $staffmain)
                    <tr style="color: red">
                        <td><input type="checkbox" class="check-id" data-id="{{ $staffmain->id }}"></td>
                        <td>
                            <a href="{{ route('staff-main-edit',['id' => $staffmain->id]) }}">{{ $staffmain->staff_central_id  }}</a>
                        </td>
                        <td>
                            {{$staffmain->main_id}}
                        </td>
                        <td>
                            <?php
                            $image = asset('assets/images/user.png');
                            if (!empty($staffmain->image)) {
                                $image = asset("Images/" . $staffmain->image);
                            }
                            ?>
                            <img src="{{ $image }}" height="50">
                        </td>
                        <td>
                            <a href="{{ route('staff-main-edit',['id' => $staffmain->id]) }}">{{ $staffmain->name_eng  }}</a>
                        </td>

                        <td>
                            {{ $weekend_days[$staffmain->workschedule->last()->weekend_day ?? ''] ?? ''}}
                        </td>

                        <td>{{ $staffmain->jobposition->post_title ?? ''}}</td>


                        <td>
                            {{ $staffmain->jobtype->jobtype_name ?? ''}}
                        </td>
                        <td>
                            {{ $staffmain->branch->office_name ?? ''}}
                        </td>
                        <td>
                            <ul>
                                @if($staffmain->workschedule->count() == 0)
                                    <li>No Work Hour</li>
                                @endif

                                @if(empty($staffmain->workschedule->last()->weekend_day))
                                    <li>No Weekend</li>
                                @endif

                                @if(empty($staffmain->workschedule->last()->work_hour))
                                    <li>No Work Hour</li>
                                @endif

                                @if(empty($staffmain->jobposition->post_title))
                                    <li>No Post</li>
                                @endif

                                @if(empty($staffmain->jobtype->jobtype_name))
                                    <li>No Job Type</li>
                                @endif

                                @if(empty($staffmain->branch_id))
                                    <li>No Branch</li>
                                @endif

                                @if(empty($staffmain->staff_dob))
                                    <li>No DOB</li>
                                @endif

                                @if(empty($staffmain->appo_date))
                                    <li>No Appointment Date</li>
                                @endif

                                @if(empty($staffmain->staff_central_id))
                                    <li>No Staff Central ID</li>
                                @endif

                                @if($staffmain->jobtype_id == \App\SystemJobTypeMastModel::JOB_TYPE_FOR_PERMANENT && empty($staffmain->permanent_date))
                                    <li>No Permanent Date For Permanent Staff</li>
                                @endif

                                @if(empty($staffmain->bank_id) && !empty($staffmain->acc_no))
                                    <li>No Bank Selected For Bank Account Staff</li>
                                @endif

                                @if(empty($staffmain->temporary_con_date))
                                    <li>No Temporary Contract Date Staff</li>
                                @endif
                            </ul>
                        </td>
                        <td>
                            <span class="badge badge-{{  ($staffmain->staff_status == '1') ? 'success' : 'danger' }}">
                                {{ $staffmain->staff_status=='1'? 'Active':'Deactive' }}
                            </span>
                        </td>
                        <td>
                            <a class="btn btn-sm btn-outline-info"
                               href="{{ route('staff-main-viewdetail',['id' => $staffmain->id]) }}"><i class="fa fa-eye"
                                                                                                       aria-hidden="true"></i></a></li>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination-links">{{ $staffmains->appends($_GET)->links()
	  		}}
            </div>
        </div>
    </div>


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
        //check uncheck all
        $('.check-all').change(function () {
            console.log($(this).prop('checked'))
            var checked = $(this).prop('checked')
            if (checked) {
                $('.check-id').prop('checked', true).trigger('change');
            } else {
                $('.check-id').prop('checked', false).trigger('change');
            }
        });

        //set id in array ids
        var ids = [];
        //individual checkbox change -- on change push to ids array for selected checkbox
        $(document).on('change', '.check-id', function () {
            console.log('checked');
            var checked = $(this).prop('checked');
            if (checked) {
                //check if already in array
                if ((ids.indexOf($(this).data('id')) > -1)) {
                } else {
                    ids.push($(this).data('id'));
                }
            } else {
                ids.splice($.inArray($(this).data('id'), ids), 1);
            }
        });
    </script>

    <script>
        //deleted bulk selected
        $('#delete-selected').click(function (e) {
            e.preventDefault();
            //Check if checkbox is unchecked
            if (ids != '') {
                vex.dialog.confirm({
                    message: 'Are you sure you want to delete?',
                    callback: function (value) {
                        if (value) { //true if clicked on ok
                            $.ajax({
                                type: "POST",
                                url: '{{ route('staff-main-destroy-selected') }}',
                                data: {_token: '{{ csrf_token() }}', ids: ids},
                                // send Blob objects via XHR requests:
                                success: function (response) {
                                    if (response == 'Successfully Deleted') {
                                        toastr.success('Successfully Deleted')
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 600);
                                    } else {
                                        vex.dialog.alert(response)
                                    }
                                },
                                error: function (response) {
                                    vex.dialog.alert(response)
                                }
                            });
                        }
                    }
                });
            } else {
                vex.dialog.alert('Please first make selection form the list')
            }
        });
    </script>

    <script>
        //delete
        $('body').on('click', '.delete-btn', function () {
            $this = $(this)
            vex.dialog.confirm({
                message: 'Are you sure you want to delete?',
                callback: function (value) {
                    console.log('Callback value: ' + value + $this.data('id'));
                    if (value) { //true if clicked on ok
                        $.ajax({
                            type: "POST",
                            url: '{{ route('staff-main-destroy') }}',
                            data: {_token: '{{ csrf_token() }}', id: $this.data('id')},
                            // send Blob objects via XHR requests:
                            success: function (response) {
                                if (response == 'Successfully Deleted') {
                                    toastr.success('Successfully Deleted')
                                    $this.parent().parent().parent().remove();
                                } else {
                                    vex.dialog.alert(response)
                                }
                            },
                            error: function (response) {
                                vex.dialog.alert(response)
                            }
                        });
                    }
                }
            });
        });
    </script>
    <script>
        $('#records_per_page').change(function () {
            $('.search-form').submit();
        });
    </script>

    <script>
        var staffs = new Array();

        function onChangeBranchIdForStaff() {
            var branch = $('#branch_id').val();
            var department_id = $('#department_id').val();
            $.ajax({
                url: '{{route('get-warning-staff-by-branch')}}',
                type: 'post',
                data: {
                    'branch': branch,
                    'department_id': department_id,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data) {
                    staffs = data;
                    $('#staff_central_id').remove();
                    $('.staffs').remove();
                    $('.removeit').remove();
                    $('.staff_container').remove();

                    $('#staff').after('   <div class="staff_container"><input type="text" id="staff_central_id" placeholder="Select a staff" style="width: 150px" name="staff_central_id" class="input-sm adjust-width" \n' +
                        '                                   ></div>')
                    $('#staff_central_id').prop('disabled', false);
                    var $select = $('#staff_central_id').selectize({
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
                    console.log($('#staff').next().next().addClass('removeit'));
                    var selectize = $select[0].selectize;
                    selectize.setValue('{{ $_GET['staff_central_id'] ?? null}}', false);
                }
            });
        }

        $().ready(onChangeBranchIdForStaff);

        $('#branch_id').change(onChangeBranchIdForStaff);
        $('#department_id').change(onChangeBranchIdForStaff);
    </script>
@endsection
