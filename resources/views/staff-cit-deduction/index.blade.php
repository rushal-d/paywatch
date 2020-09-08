@extends('layouts.default', ['crumbroute' => 'staff-cit-deduction-index'])
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
                    <a class="nav-link" href="{{ route('staff-cit-deduction-create') }}"><i class="fa fa-plus"></i> Add
                        New</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" id="delete-selected" href="javascript:void(0)"><i
                            class="fa fa-minus"></i> Delete Selected</a>
                </li>
            </ul>
        </div>

        {{ Form::open(array('route' => 'staff-cit-deduction-index', 'method'=> 'GET')) }}
        <div class="card">
            <div class="search-box">
                <div class="row">

                    {!! Form::select('branch_id', $branches , request('branch_id'),array('id'=>'branch_id','class'=> 'adjust-width','data-validation' => 'required',
                             ) ) !!}

                    <div class="staff-form-group">
                        <span id="staff"></span>
                    </div>
                    {!! Form::select('staff_central_id', [], request('staff_central_id')?:'', ['class' => 'adjust-width staffs', 'placeholder' => 'Select a staff']) !!}

                    {{ Form::select('rpp', $records_per_page_options, $records_per_page , array('id' => 'records_per_page', 'class'=> 'adjust-width', 'placeholder' => 'Select a Record Per Page')) }}

                    {!! Form::select('fiscal_year_id', $fiscal_years , $current_fiscal_year_id ?? null,array('id'=>'fiscal_year_id','class' => 'adjust-width', 'placeholder' => 'Fiscal Year') ) !!}

                    {!! Form::select('month_id', $months , request('month_id'), array( 'id'=>'month_id', 'class' => 'adjust-width', 'placeholder' => 'Select a month') ) !!}

                    {!! Form::number('cit_deduction_amount', request('cit_deduction_amount'), ['id' => 'cit_deduction_amount', 'class' => 'adjust-width', 'placeholder' => 'Select a amount']) !!}

                    <button class="button button-print adjust-width" type="submit">Filter</button>

                </div>
            </div>
        </div>
        {{ Form::close()  }}
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> All Staff Cit Deductions
            <span class="tag tag-pill tag-success pull-right">{{ $staffCitDeductions->total() }}</span>
        </div>
        <div class="card-block">
            <table class="table table-responsive table-striped table-hover table-all" width="100%" cellspacing="0">
                <tbody>
                <tr align="left">
                    <th width="2%"><input type="checkbox" class="check-all"></th>
                    <th width="7%">Fiscal Year</th>
                    <th width="7%">Month</th>
                    <th width="10%">Staff Central ID</th>
                    <th width="10%">Branch ID</th>
                    <th width="15%">Staff Name</th>
                    <th width="15%">Branch Name</th>
                    <th width="10%">Amount</th>
                    <th width="10%">Payroll</th>
                    <th width="10%">Action</th>
                </tr>

                @foreach($staffCitDeductions as $staffCitDeduction)
                    <tr>
                        <td>
                            <input type="checkbox" class="check-id" data-id="{{ $staffCitDeduction->id }}">
                        </td>
                        <td>{{$staffCitDeduction->fiscalYear->fiscal_code ?? null}}</td>
                        <td>{{config('constants.month_name')[$staffCitDeduction->month_id]}}</td>
                        <td>{{$staffCitDeduction->staff->staff_central_id ?? null}}</td>
                        <td>{{$staffCitDeduction->staff->main_id ?? null}}</td>
                        <td>{{$staffCitDeduction->staff->name_eng ?? null}}</td>
                        <td>{{$staffCitDeduction->branch->office_name ?? null}}</td>
                        <td>{{ $staffCitDeduction->cit_deduction_amount }}</td>
                        <td>{{ $staffCitDeduction->payroll->payroll_name ?? null }}</td>
                        <td class="actions-col">
                            <div class="actions">
                                @if(empty($staffCitDeduction->payroll_id))
                                    <a class="btn btn-sm btn-outline-success edit-btn"
                                       href="{{route('staff-cit-deduction-edit', $staffCitDeduction->id)}}"><i
                                            class="fa fa-edit"></i></a>
                                <a class="btn btn-sm btn-outline-danger delete-btn"
                                   data-id="{{ $staffCitDeduction->id }}" href="javascript:void(0)"><i
                                        class="fa fa-remove"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach

                </tbody>

            </table>
            <div class="pagination-links">{{ $staffCitDeductions->appends($_GET)->links()
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
                                url: '{{ route('staff-cit-deduction-destroy-selected') }}',
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
                    if (value) { //true if clicked on ok
                        $.ajax({
                            type: "POST",
                            url: '{{ route('staff-cit-deduction-destroy') }}',
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
        var staffs = new Array();

        function onChangeBranchIdForStaff() {
            var branch = $('#branch_id').val();
            var department_id = $('#department_id').val();
            if (branch.length > 0) {
                $.ajax({
                    url: '{{route('get-staff-by-branch')}}',
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
                        var selectize = $select[0].selectize;
                        selectize.setValue('{{ $_GET['staff_central_id'] ?? null}}', false);
                    }
                });
            } else {
                $.ajax({
                    url: '{{route('get-staff')}}',
                    type: 'GET',
                    data: {
                        'department_id': department_id,
                        '_token': '{{csrf_token()}}',
                        'limit': 25
                    },
                    success: function (data) {
                        staffs = data.staffs;
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
                        var selectize = $select[0].selectize;
                        selectize.setValue('{{ $_GET['staff_central_id'] ?? null}}', false);
                    }
                });
            }

        }

        $().ready(onChangeBranchIdForStaff);

        $('#branch_id').change(onChangeBranchIdForStaff);
        $('#department_id').change(onChangeBranchIdForStaff);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $('#fiscal_year_id').change(function () {
            const selected_month = $('#month_id').val();
            var selected_year = $(this).val();
            if (selected_year === '' || selected_year === undefined) {
                selected_year = '{{ $current_fiscal_year_id }}'
            }
        });

        //show date from date to by month id
        $('#month_id').change(function () {
            //get the first day of the month
            const selected_month = $('#month_id').val();
            var selected_year = $('#fiscal_year_id').val();
        });

        $('#month_id').trigger('change');

    </script>
@endsection
