@extends('layouts.default', ['crumbroute' => 'house-loan-diff-income'])
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
                        <a class="nav-link text-success" id="add-new"
                           href="{{route('house-loan-diff-income-modify', ['house_loan_id' => request('house_loan_id'), 'fiscal_year_id' => request('fiscal_year_id')])}}"><i
                                class="fa fa-plus"></i> Create</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" id="delete-selected" href="javascript:void(0)"><i
                            class="fa fa-minus"></i> Delete Selected</a>
                </li>
            </ul>
        </div>

        <div class="search-box">
            {{ Form::open(array('route' => 'house-loan-diff-income-index', 'method'=> 'GET' ,'class' => 'search-form')) }}
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    {{ Form::select('house_loan_id', $houseLoans,request('house_loan_id') , array('class' => 'form-control', 'id' => 'house_loan_id', 'placeholder' => 'Select House Loan'))}}
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    {{ Form::select('fiscal_year_id', $fiscalYears,request('fiscal_year_id') , array('class' => 'form-control', 'id' => 'fiscal_year_id', 'placeholder' => 'Select Fiscal Year'))}}
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="records-per-page">
                        {{ Form::label('rpp', 'Per Page') }}
                        {{ Form::select('rpp', $records_per_page_options, $records_per_page , array('id' => 'records_per_page')) }}
                    </div>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <button class="btn btn-outline-success btn-reset"><i
                            class="icon-refresh"></i> Search
                    </button>
                    <a href="{{route('house-loan-diff-income-index')}}" class="btn btn-outline-danger btn-reset"><i
                            class="icon-refresh"></i> Reset
                    </a>
                </div>
            </div>
            {{ Form::close()  }}
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> All House Loans
            <span class="tag tag-pill tag-success pull-right">{{ $houseLoanDiffIncomes->total() }}</span>
        </div>
        <div class="card-block">
            <table class="table table-responsive table-striped table-hover table-all" width="100%" cellspacing="0">
                <tbody>
                <tr align="left">
                    <th width="2%"><input type="checkbox" class="check-all"></th>
                    <th>Staff Central ID</th>
                    <th>Staff Name</th>
                    <th>Branch ID</th>
                    <th>Payroll Branch</th>
                    <th>House Loan Id</th>
                    <th>Fiscal Year</th>
                    <th>Diff. Income</th>
                    <th>Action</th>
                </tr>

                @foreach($houseLoanDiffIncomes as $houseLoanDiffIncome)
                    <tr>
                        <td><input type="checkbox" class="check-id" data-id="{{ $houseLoanDiffIncome->id }}"></td>
                        <td>{{ $houseLoanDiffIncome->houseLoan->staff->staff_central_id ?? '' }}</td>
                        <td>{{ $houseLoanDiffIncome->houseLoan->staff->name_eng ?? '' }}</td>
                        <td>{{ $houseLoanDiffIncome->houseLoan->staff->main_id ?? '' }}</td>
                        <td>{{ $houseLoanDiffIncome->houseLoan->staff->payrollBranch->office_name ?? '' }}</td>
                        <td>
                            <a href="{{ route('house-loan-diff-income-modify',['house_loan_id' => $houseLoanDiffIncome->house_loan_id, 'fiscal_year_id' => $houseLoanDiffIncome->fiscal_year_id]) }}">{{ $houseLoanDiffIncome->house_loan_id ?? '' }}</a>
                        </td>
                        <td>{{ $houseLoanDiffIncome->fiscalYear->fiscal_code ?? '' }}</td>
                        <td>{{ $houseLoanDiffIncome->diff_income }}</td>
                        {{--<td>
                            <a href="{{route('house-loan-diff-income-show',$houseLoanDiffIncome->house_id)}}"
                               class="btn btn-outline-primary">View Detals</a>
                        </td>--}}
                        <td class="actions-col">
                            <div class="actions">
                                <a class="btn btn-sm btn-success edit-btn"
                                   href="{{route('house-loan-diff-income-modify', ['fiscal_year_id' => $houseLoanDiffIncome->fiscal_year_id, 'house_loan_id' => $houseLoanDiffIncome->house_loan_id])}}"><i
                                        class="fa fa-edit"></i></a>
                                <a class="btn btn-sm btn-outline-danger delete-btn"
                                   data-id="{{ $houseLoanDiffIncome->id }}" href="javascript:void(0)"><i
                                        class="fa fa-remove"></i></a>
                            </div>
                        </td>
                    </tr>

                @endforeach

                </tbody>

            </table>
            <div class="pagination-links">{{ $houseLoanDiffIncomes->appends($_GET)->links()
	  		}}
            </div>
        </div>
    </div>


@endsection


@section('script')
    <script>
        $('#house_loan_id').selectize({});
        $('#house_loan_id').on('change', function () {
            $('.search-form').submit();
        });
    </script>

    <script>
        $('#fiscal_year_id').selectize();

        $('#fiscal_year_id').on('change', function () {
            $('.search-form').submit();
        });
    </script>

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
                                url: '{{ route('house-loan-diff-income-destroy-selected') }}',
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
                            url: '{{ route('house-loan-diff-income-destroy') }}',
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

@endsection
