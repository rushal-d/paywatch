@extends('layouts.default', ['crumbroute' => 'staff-jobtype-alert-warning-trainee-to-non-permanent'])
@section('title', $title)
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/css/jquery-ui.css')}}"/>

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
                            class="fa fa-exclamation-circle"></i> Non Permanent To Permanent ({{$staffmains->total()}})
                    </button>

                </li>
                <li class="nav-item">
                    <button class="nav-link text-primary btn-link change-contract-bulk-button">
                        Change to Non Permanent
                    </button>
                </li>
            </ul>
        </div>
        {{ Form::open(array('route' => 'staff-job-type-alert.trainee-to-non-permanent','method' => 'get', 'id' => 'staff-get-promotion-alert'))  }}
        <div class="card">
            <div class="search-box">
                <div class="row">
                    {!! Form::select('branch_id', $branches , request('branch_id'),array('id'=>'branch_id','class'=> 'adjust-width','data-validation' => 'required',
                             ) ) !!}

                    <div class="staff-form-group">
                        <span id="staff"></span>
                    </div>
                    {!! Form::select('staff_central_id', $staffmains, request('staff_central_id')?:'', ['class' => 'adjust-width staffs', 'placeholder' => 'Select a staff']) !!}

                    {{ Form::select('rpp', $records_per_page_options, $records_per_page , array('id' => 'records_per_page', 'class'=> 'adjust-width', 'placeholder' => 'Select a Record Per Page')) }}

                    <button class="button button-print adjust-width" type="submit">Filter</button>

                    {{ Form::close()  }}
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> All Staff
            <span class="text-danger">[Minimum Work Days Limit: {{config('constants.staff_trainee_to_non_permanent_promotion.minimum_work_days')}} days]</span>
            <span class="tag tag-pill tag-success pull-right">{{ $staffmains->total() }}</span>
        </div>


        <div class="card-block">
            <table class="table table-responsive table-striped table-hover table-all">
                <tbody>
                <tr align="left">
                    <th><input type="checkbox" class="check-all"></th>
                    <th>Branch ID</th>
                    <th>Central ID</th>
                    <th>Full Name</th>
                    <th>Appointment Date</th>
                    <th>Days from Appointment</th>
                    <th>Worked Days</th>
                    <th>Staff Status</th>
                    <th class="text-center">Action</th>
                </tr>
                @foreach($staffmains as $staffmain)
                    <tr style="color: red">
                        <td><input type="checkbox" class="check-id" data-id="{{ $staffmain->id }}"
                                   data-staff-name="{{$staffmain->name_eng}}"></td>
                        <td>
                            {{$staffmain->branch->office_name ?? ''}}
                        </td>
                        <td>
                            <a href="{{ route('staff-job-information',['id' => $staffmain->id]) }}">{{ $staffmain->staff_central_id  }}</a>
                        </td>

                        <td>
                            <a href="{{ route('staff-job-information',['id' => $staffmain->id]) }}">{{ $staffmain->name_eng  }}</a>
                        </td>
                        @if(!empty($staffmain->appo_date))
                            <td>{{$staffmain->appo_date}}</td>
                        @else
                            <td><a href="{{ route('staff-job-information',['id' => $staffmain->id]) }}" target="_blank">No
                                    Appo Date</a></td>
                        @endif

                        <td>{{ \Carbon\Carbon::now()->diffInDays($staffmain->appo_date) }}</td>

                        <td>{{$staffmain->fetch_attendances_count}}</td>
                        <td>
                            <span class="badge badge-{{  ($staffmain->staff_status == '1') ? 'success' : 'danger' }}">
                                {{ $staffmain->staff_status=='1'? 'Active':'Deactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('staff-job-information',['id' => $staffmain->id]) }}" target="_blank"
                               class="btn btn-sm btn-outline-success" data-toggle="tooltip" data-placement="top"
                               title="Change Status"
                            ><i class="fa fa-directions"></i></a>
                            <button type="button"
                                    class="btn btn-primary change-contract-button"
                                    data-staff-central-id="{{$staffmain->id}}"
                                    data-appointment-date="{{$staffmain->appo_date_np}}"
                            >
                                Change to Non Permanent
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Modal -->
            <div class="modal fade" id="singleStaffModal" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Change Non Permanent to Permanent</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        {!! Form::open(['method' => 'post', 'route' => 'staff-job-type-alert.change-trainee-to-non-permanent','id' => 'modal-single-form']) !!}
                        <div class="modal-body">
                            <p>Staff Name: <span
                                    class="staff-name-modal"></span></p>
                            <p class="appointment-date-div">Appointment Date: <span
                                    class="appointment-date-modal">{{\Carbon\Carbon::now()->toDateString()}}</span></p>
                            <div class="form-group">
                                {!! Form::hidden('staff_central_id_modal', '', ['id' => 'staff_central_id_modal']) !!}

                                {!! Form::label('non_permanent_modal', 'Temporary Date:',  ['class' => 'control-label']) !!}
                                {!! Form::text('non_permanent_modal', '', ['class' => 'form-control nep-date custom-modal col-md-5', 'readonly' => 'readonly', 'data-validation' => 'required',]) !!}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="modal-single-submit">Save changes</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="pagination-links">{{ $staffmains->appends($_GET)->links()
	  		}}
            </div>
        </div>
    </div>
@endsection


@section('script')
    {{--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>--}}
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
            integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
            crossorigin="anonymous"></script>

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
        //responsive table
        $(function () {
            $('.table-all').stacktable();
        });
    </script>

    {{-- For single contract change   --}}
    <script>
        $(document).on('click', '.change-contract-button', function () {
            $_this = $(this);
            let $_staff_central_id = $_this.data('staff-central-id');
            let $_staff = null;
            $('#singleStaffModal').modal('show');
            $.ajax({
                type: 'GET',
                url: '{{route('ajax-get-staff-by-id')}}',
                data: {
                    id: $_staff_central_id
                },
                success: function (response) {
                    if (response.status === 'false') {
                        $('#singleStaffModal').modal('hide');
                    } else {
                        $_staff = response.data;
                        $('.staff-name-modal').html($_staff.name_eng);
                        $('.appointment-date-div').show();
                        $('.appointment-date-modal').html($_staff.appo_date);
                        $('#staff_central_id_modal').val($_staff_central_id);
                    }
                }
            })
        });
        $('#modal-single-form').submit(function (e) {
            var $_this = $(this);
            var $_non_permanent_input = $('#non_permanent_modal');
            var $_staff_central_id_input = $('#staff_central_id_modal');

            if ($_non_permanent_input.val() !== '') {
                e.preventDefault();

                $.ajax({
                    method: $_this.attr('method'),
                    url: $_this.attr('action'),
                    data: {
                        _token: '{{ csrf_token() }}',
                        non_permanent: $_non_permanent_input.val(),
                        staff_central_id: $_staff_central_id_input.val()
                    },
                    success: function (response) {
                        if (response.status === 'true') {
                            $('#singleStaffModal').modal('hide');
                            toastr.success('Successfully changed');
                            $.each($_staff_central_id_input.val().split(', '), function (index, value) {
                                $('[data-staff-central-id="' + value + '"]').parent().parent().remove();
                            });
                        } else {
                            vex.dialog.alert(response.message);
                        }
                    },
                    error: function (response) {

                    }

                })
            }
        })
    </script>

    {{-- For bulk contract change   --}}
    <script>
        //check uncheck all
        $('.check-all').change(function () {
            var checked = $(this).prop('checked')
            if (checked) {
                $('.check-id').prop('checked', true).trigger('change');
            } else {
                $('.check-id').prop('checked', false).trigger('change');
            }
        });

        //set id in array ids
        var ids = [];
        var staff_names = [];
        //individual checkbox change -- on change push to ids array for selected checkbox
        $(document).on('change', '.check-id', function () {
            var checked = $(this).prop('checked');
            if (checked) {
                //check if already in array
                if ((ids.indexOf($(this).data('id')) > -1)) {
                } else {
                    ids.push($(this).data('id'));
                    staff_names.push($(this).data('staff-name'))
                }
            } else {
                ids.splice($.inArray($(this).data('id'), ids), 1);
                staff_names.splice($.inArray($(this).data('staff-name'), staff_names), 1);
            }
        });
    </script>

    <script>
        $('.change-contract-bulk-button').click(function () {
            if (ids.length > 0) {
                $('#singleStaffModal').modal('show');

                $('.staff-name-modal').html(staff_names.join(', '));
                $('.appointment-date-div').hide();
                $('#staff_central_id_modal').val(ids.join(', '));
            } else {
                vex.dialog.alert('Please select atleast one staff');
            }
        });
    </script>


    <script>
        $('#records_per_page').change(function () {
            $('.search-form').submit();
        });
    </script>

    <script>
        function onChangeBranchIdForStaff() {
            var branch = $('#branch_id').val();
            $.ajax({
                url: '{{route('get-staff-by-branch')}}',
                type: 'post',
                data: {
                    'branch': branch,
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
        }

        $().ready(onChangeBranchIdForStaff);

        $('#branch_id').change(onChangeBranchIdForStaff);
    </script>

    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>

    <script>
        $(document).ready(function () {
            $('.nep-date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 20,
                onChange: function (e) {
                }
            });
        });
    </script>
@endsection
