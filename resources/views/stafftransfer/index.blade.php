@extends('layouts.default', ['crumbroute' => 'transfer'])
@section('title', $title)

@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
@endsection
@section('content')

    <div class="card">
        <div class="quick-actions">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('staff-transfer-create') }}"><i class="fa fa-plus"></i> Add
                        New</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" id="delete-selected" href="javascript:void(0)"><i
                            class="fa fa-minus"></i> Delete Selected</a>
                </li>
            </ul>
        </div>

        <div class="search-box">
            {{ Form::open(array('route' => 'staff-transfer', 'method'=> 'GET' ,'class' => 'search-form')) }}
            <div class="row">
                @if(!auth()->user()->hasRole('Employee'))
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        {{ Form::label('staff_central_id', ' Staff') }}
                        {!! Form::text('staff_central_id',$_GET['staff_central_id'] ?? null,['class'=>'form-control','id'=>'staff_central_id','placeholder'=>'Select a Staff']) !!}
                    </div>
                @endif
                <div class="col-md-2 col-sm-6 col-xs-12">
                    {{ Form::label('from_branch', 'From Branch') }}
                    {{ Form::select('from_branch', $branches, request('from_branch'), array('id' => 'from_branch', 'placeholder' => 'Select a branch')) }}
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    {{ Form::label('to_branch', 'To Branch') }}
                    {{ Form::select('to_branch', $branches, request('to_branch'), array('id' => 'to_branch', 'placeholder' => 'Select a branch')) }}
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    {{ Form::label('date_from', 'Date From') }}
                    {{ Form::text('date_from', request('date_from'), array('class' => 'form-control nep-date','id'=>'nep-date1' , 'placeholder' => 'Please enter starting Date', 'readonly'
                            ))  }}
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    {{ Form::label('date_to', 'Date To') }}
                    {{ Form::text('date_to', request('date_to'), array('class' => 'form-control nep-date','id'=>'nep-date2' , 'placeholder' => 'Please enter Ending Date', 'readonly'
                                   ))  }}
                </div>
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <button class="btn btn-outline-success btn-reset" type="submit"> Search</button>
                    <a class="btn btn-danger btn-reset reset-inputs" href="{{route('staff-transfer')}}"> Reset</a>
                </div>
            </div>
            {{ Form::close()  }}
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> All Staff
            <span class="tag tag-pill tag-success pull-right">{{ $stafftransfers->total() }}</span>
        </div>
        <div class="card-block">
            <table class="table table-responsive table-striped table-hover table-all" width="100%" cellspacing="0">
                <tbody>
                <tr align="left">
                    <th width="2%"><input type="checkbox" class="check-all"></th>
                    <th width="20%">Staff Name</th>
                    <th width="20%">From Office Name</th>
                    <th width="10%">To Office Name</th>
                    <th width="10%">Joined Date</th>
                    <th width="10%">End Date</th>
                    <th width="10%">Created At</th>
                    <th width="8%">Action</th>
                </tr>

                @foreach($stafftransfers as $stafftransfer)
                    <tr>
                        <td><input type="checkbox" class="check-id" data-id="{{ $stafftransfer->transfer_id }}"></td>
                        <td>{{--<a href="{{ route('staff-transfer-edit',['id' => $stafftransfer->transfer_id]) }}">--}}{{ $stafftransfer->staff->name_eng ?? ''}}{{--</a>--}}</td>
                        <td> {{ $stafftransfer->office_from_get->office_name ?? '' }}</td>
                        <td> {{ $stafftransfer->office->office_name ?? '' }}</td>
                        <td>{{$stafftransfer->from_date}} ({{$stafftransfer->from_date_np}})</td>
                        <td>{{$stafftransfer->transfer_date}} ({{$stafftransfer->transfer_date_np}})</td>
                        <td>{{$stafftransfer->created_at}}</td>
                        <td class="actions-col">
                            <div class="actions">
                                <a class="btn btn-sm btn-outline-danger delete-btn"
                                   data-id="{{ $stafftransfer->transfer_id }}" href="javascript:void(0)"><i
                                        class="fa fa-remove"></i></a>
                            </div>
                        </td>
                    </tr>

                @endforeach

                </tbody>

            </table>
            <div class="pagination-links">{{ $stafftransfers->appends($_GET)->links()
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
                                url: '{{ route('staff-transfer-destroy-selected') }}',
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
                            url: '{{ route('staff-transfer-destroy') }}',
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
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>

    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,// Options | Number of years to show
        });

        $(document).ready(loadStaff())

        function loadStaff() {
            $.ajax({
                url: '{{ route('get-staff') }}',
                type: 'GET',
                dataType: 'json',
                data: {
                    'limit': 15,
                    'staff_central_id': '{{$_GET['staff_central_id'] ?? null}}'
                },
                success: function (data) {
                    $('#staff_central_id').selectize({
                        valueField: 'id',
                        labelField: 'name_eng',
                        searchField: ['name_eng', 'main_id'],
                        options: data['staffs'],
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
                                url: '{{ route('get-staff') }}',
                                data: {
                                    'search': encodeURIComponent(query),
                                    'limit': 15,
                                    'staff_central_id': '{{$_GET['staff_central_id'] ?? null}}'
                                },
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
                }
            });
        }
    </script>
@endsection
