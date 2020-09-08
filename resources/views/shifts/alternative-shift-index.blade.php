@extends('layouts.default', ['crumbroute' => 'alternative-shift'])
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

    </style>
@endsection
@section('content')

    {{ Form::open(array('route' => 'alternative-shift-create','method'=>'GET'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Alternative Staff Shift</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Branch
                            </label>
                            {{ Form::select('branch_id',$branches, null, array( 'placeholder' => 'Branch',
                             'data-validation' => 'required','id'=>'branch_id',
                             'data-validation-error-msg' => 'Please enter a branch'))  }}

                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label" id="staff">
                                Staff
                            </label>
                            <input type="text" id="staff_central_id" name="staff_central_id" class="input-sm"
                                   required
                                   disabled>

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


    <div class="card">
        <div class="card-header">


            <i class="fa fa-align-justify"></i> All Altenative Shifts
            <span class="tag tag-pill tag-success pull-right">{{ $staffs->total() }}</span>

            <div class="float-right">
                <a class="nav-link text-danger" id="delete-selected" href="javascript:void(0)"><i
                        class="fa fa-minus"></i> Delete Selected</a>
            </div>


        </div>

        <div class="search-box">
            {{ Form::open(array('route' => 'alternative-shift-index', 'method'=> 'GET' ,'class' => 'search-form')) }}
            <div class="row">
                <div class="col-md-6 col-sm-12 col-xs-12">
                    {{ Form::label('search', 'Search') }}
                    {{ Form::text('search', $_GET['search'] ?? null, array('class' => 'form-control search-field', 'placeholder' => 'Search...'))}}
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="records-per-page">
                        {{ Form::label('rpp', 'Per Page') }}
                        {{ Form::select('rpp', $records_per_page_options, $records_per_page , array('id' => 'records_per_page')) }}
                    </div>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <a class="btn btn-outline-success btn-reset" href="{{ route('systemoffice')}}"><i
                            class="icon-refresh"></i> Reset</a>
                </div>
            </div>
            {{ Form::close()  }}
        </div>

        <div class="card-block">
            <table class="table table-responsive table-striped table-hover table-all" width="100%" cellspacing="0">
                <tbody>
                <tr align="left">
                    <th width="2%"><input type="checkbox" class="check-all"></th>
                    <td>Staff Name</td>
                    <td>Sunday</td>
                    <td>Monday</td>
                    <td>Tuesday</td>
                    <td>Wednesday</td>
                    <td>Thursday</td>
                    <td>Friday</td>
                    <td>Saturday</td>
                    <td>Action</td>
                </tr>

                @foreach ($staffs as $staff)
                    <tr>
                        <td><input type="checkbox" class="check-id" data-id="{{ $staff->id }}"></td>
                        <td>
                            <a href="{{route('alternative-shift-create',['staff_central_id'=>$staff->id])}}">    {{$staff->name_eng ?? ''}}</a>
                        </td>
                        <td>{{$staff->AllstaffAlternativeShifts->where('day',7)->first()->shift->shift_name ?? ''}}</td>
                        <td>{{$staff->AllstaffAlternativeShifts->where('day',1)->first()->shift->shift_name ?? ''}}</td>
                        <td>{{$staff->AllstaffAlternativeShifts->where('day',2)->first()->shift->shift_name ?? ''}}</td>
                        <td>{{$staff->AllstaffAlternativeShifts->where('day',3)->first()->shift->shift_name ?? ''}}</td>
                        <td>{{$staff->AllstaffAlternativeShifts->where('day',4)->first()->shift->shift_name ?? ''}}</td>
                        <td>{{$staff->AllstaffAlternativeShifts->where('day',5)->first()->shift->shift_name ?? ''}}</td>
                        <td>{{$staff->AllstaffAlternativeShifts->where('day',6)->first()->shift->shift_name ?? ''}}</td>

                        <td>
                            <div class="actions">
                                <a class="btn btn-sm btn-outline-danger delete-btn"
                                   data-id="{{ $staff->id }}"
                                   href="javascript:void(0)"><i class="fa fa-remove"></i></a>
                            </div>
                        </td>

                    </tr>
                @endforeach


                </tbody>

            </table>
            {{$staffs->appends($_GET)->links()}}

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
                                url: '{{ route('alternative-shift-destroy-selected') }}',
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
                            url: '{{ route('alternative-shift-destroy') }}',
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

        function onChangeBranchId() {
            branch = $('#branch_id').val();
            department_id = $('#department_id').val();
            $.ajax({
                url: '{{route('get-staff-by-branch')}}',
                type: 'post',
                data: {
                    'branch': branch,
                    'department_id': department_id,
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
                        '                                required  ></div>')
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
                                    '<div> Branch: ' + item.branch.office_name + '</div></div>';
                            }
                        },
                        load: function (query, callback) {
                            if (!query.length) return callback();
                            $.ajax({
                                url: '{{ route('get-staff') }}?search=' + encodeURIComponent(query) + '&branch_id=' + branch,
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

    <script>
        $('#records_per_page').change(function () {
            $('.search-form').submit();
        });
    </script>
@endsection
