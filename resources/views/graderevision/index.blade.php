@extends('layouts.default', ['crumbroute' => 'fiscalyear'])
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
                    <a class="nav-link text-danger" id="revise-selected" href="javascript:void(0)"><i
                            class="fa fa-plus-circle"></i> Revise Selected</a>
                </li>
            </ul>
        </div>

        <div class="search-box">
            {{ Form::open(array('route' => 'grade-revision', 'method'=> 'GET' ,'class' => 'search-form')) }}
            <div class="row">
                {!! Form::select('branch_id', $branches , request('branch_id') ?? \Illuminate\Support\Facades\Auth::user()->branch_id ?? null,array('id'=>'branch_id','class'=> 'adjust-width','data-validation' => 'required',
                       ) ) !!}
                <div class="staff-form-group">
                    <span id="staff"></span>
                </div>
                {!! Form::select('staff_central_id', $staffmains, request('staff_central_id')?:'', ['class' => 'adjust-width staffs', 'placeholder' => 'Select a staff']) !!}

                {{ Form::select('rpp', $records_per_page_options, $records_per_page , array('id' => 'records_per_page','class'=> 'adjust-width')) }}

                <div class="col-md-2 col-sm-12 col-xs-12">
                    <button class="btn btn-outline-success btn-reset" type="submit">Submit</button>
                    <a class="btn btn-outline-success btn-reset" href="{{ route('grade-revision')}}"><i
                            class="icon-refresh"></i> Reset</a>
                </div>
            </div>
            {{ Form::close()  }}
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Grade Revision Staffs
            <span class="tag tag-pill tag-success pull-right">{{ $staffmains->total() }}</span>
        </div>
        <div class="card-block">
            <table class="table table-responsive table-striped table-hover table-all" width="100%" cellspacing="0">
                <thead>
                <th width="2%"><input type="checkbox" class="check-all"></th>
                <th>Central Id</th>
                <th>Branch Id</th>
                <th>Branch Name</th>
                <th>Staff Name</th>
                <th>Job Type</th>
                <th>Previous Grade</th>
                <th>Added Grade</th>
                <th>Effective Date</th>
                <th>Fiscal Year</th>
                <th>Action</th>
                </thead>
                <tbody>
                @foreach($staffmains as $staffmain)
                    <tr>
                        <td><input type="checkbox" class="check-id" data-id="{{ $staffmain->id }}"></td>
                        <td>{{$staffmain->staff_central_id}}</td>
                        <td>{{$staffmain->main_id}}</td>
                        <td>{{$staffmain->name_eng}}</td>
                        <td>{{$staffmain->branch->office_name ?? ''}}</td>
                        <td>{{$staffmain->jobtype->jobtype_code ?? ''}}</td>
                        <td><p class="total-grade-amount">{{$staffmain->latestsalary->total_grade_amount ?? 0}}</p></td>
                        <td>
                            <p class="add-grade-amount">{{$staffmain->latestsalary->add_grade_this_fiscal_year  ?? 0}}</p>
                        </td>
                        <td><p class="effective-date">{{$staffmain->latestsalary->salary_effected_date_np ?? 'N/A'}}</p>
                        </td>
                        <td>
                            <p class="fiscal-year-code">{{$staffmain->latestsalary->fiscalyear->fiscal_code ?? 'N/A'}}</p>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger grade-revision-single" type="button"
                                    data-staffid="{{$staffmain->id}}">Revise Grade
                            </button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <div class="pagination-links">{{ $staffmains->appends($_GET)->links()}}
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="grade-revision-multiple" tabindex="-1" role="dialog"
         aria-labelledby="gradeRevisionMultiple"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Grade Revision Bulk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="staff_central_ids">
                    <div class="form-group">
                        <label for="">Grade Amount</label>
                        <input type="number" step="0.01" class="form-control" id="multiple-staff-grade"
                               placeholder="Grade Amount">
                    </div>

                    <div class="form-group">
                        <label for="">Effective Date</label>
                        <input type="text" class="form-control effectiveDate" id="multiple-staff-effective-date"
                               placeholder="Effective Date"
                               readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary revise-grade-multiple">Revise Grade</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="grade-revision-single" tabindex="-1" role="dialog" aria-labelledby="gradeRevisionSingle"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Grade Revision</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="staff_central_id">
                    <div class="form-group">
                        <label for="">Grade Amount</label>
                        <input type="number" step="0.01" class="form-control" id="single-staff-grade"
                               placeholder="Grade Amount">
                    </div>

                    <div class="form-group">
                        <label for="">Effective Date</label>
                        <input type="text" class="form-control effectiveDate" id="single-staff-effective-date"
                               placeholder="Effective Date"
                               readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary revise-grade-single">Revise Grade</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
            integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
            crossorigin="anonymous"></script>
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
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
        $('#revise-selected').click(function (e) {
            e.preventDefault();
            $('#staff_central_ids').val(ids);
            $('#grade-revision-multiple').modal();
            $('#grade-revision-multiple').css('z-index', 9999)

        });
        $('.grade-revision-single').click(function (e) {
            $this = $(this);
            let staff_id = $this.data('staffid');
            $('#staff_central_id').val(staff_id);
            $('#grade-revision-single').modal()
            $('#grade-revision-single').css('z-index', 9999)
        });

        $('.effectiveDate').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
        });

        $('.revise-grade-single').click(function () {
            let staff_central_id = $('#staff_central_id').val();
            let add_grade_amount = $('#single-staff-grade').val();
            let effectiveDate = $('#single-staff-effective-date').val();

            $.ajax({
                url: '{{route('grade-revision-store')}}',
                type: 'GET',
                data: {
                    '_token': '{{csrf_token()}}',
                    'staff_central_id': staff_central_id,
                    'effective_date_np': effectiveDate,
                    'additional_grade': add_grade_amount,
                }, success: function (data) {
                    if (data['status']) {

                        $('#grade-revision-single').modal('hide');
                        let staffRevisionButton = $('[data-staffid=' + staff_central_id + ']');
                        let staff_latest_salary = data['staff']['latestsalary']
                        let parentTableRow = staffRevisionButton.parent().parent();
                        parentTableRow.find('.total-grade-amount').text(staff_latest_salary['total_grade_amount']);
                        parentTableRow.find('.add-grade-amount').text(staff_latest_salary['add_grade_this_fiscal_year']);
                        parentTableRow.find('.effective-date').text(staff_latest_salary['salary_effected_date_np']);
                        parentTableRow.find('.fiscal-year-code').text(staff_latest_salary['fiscalyear']['fiscal_code']);
                        $('#staff_central_id').val('');
                        $('#single-staff-grade').val('');
                        $('#single-staff-effective-date').val('');
                        vex.dialog.alert('Updated Grade')
                    } else {
                        vex.dialog.alert('Error Occured !');
                    }
                }
            })

        });

        $('.revise-grade-multiple').click(function () {
            let staff_central_ids = $('#staff_central_ids').val();
            let add_grade_amount = $('#multiple-staff-grade').val();
            let effectiveDate = $('#multiple-staff-effective-date').val();

            $.ajax({
                url: '{{route('grade-revision-multiple')}}',
                type: 'GET',
                data: {
                    '_token': '{{csrf_token()}}',
                    'staff_central_ids': staff_central_ids,
                    'effective_date_np': effectiveDate,
                    'additional_grade': add_grade_amount,
                }, success: function (data) {
                    if (data['status']) {
                        $.each(data['staffs'], function (index, staff) {
                            $('#grade-revision-multiple').modal('hide');
                            let staffRevisionButton = $('[data-staffid=' + staff['id'] + ']');
                            let staff_latest_salary = staff['latestsalary']
                            let parentTableRow = staffRevisionButton.parent().parent();
                            parentTableRow.find('.total-grade-amount').text(staff_latest_salary['total_grade_amount']);
                            parentTableRow.find('.add-grade-amount').text(staff_latest_salary['add_grade_this_fiscal_year']);
                            parentTableRow.find('.effective-date').text(staff_latest_salary['salary_effected_date_np']);
                            parentTableRow.find('.fiscal-year-code').text(staff_latest_salary['fiscalyear']['fiscal_code']);
                        })

                        $('#staff_central_id').val('');
                        $('#single-staff-grade').val('');
                        $('#single-staff-effective-date').val('');
                        vex.dialog.alert('Updated Grade')

                    } else {
                        vex.dialog.alert('Error Occured !');
                    }
                }
            })

        });

        var staffs = new Array();

        function onChangeBranchIdForStaff() {
            var branch = $('#branch_id').val();
            var department_id = $('#department_id').val();
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
        }

        $().ready(onChangeBranchIdForStaff);

        $('#branch_id').change(onChangeBranchIdForStaff);
    </script>

@endsection
