@extends('layouts.default', ['crumbroute' => 'houseloan'])
@section('title', $title)

@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
@endsection
@section('content')

    <div class="card"><div class="quick-actions">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('houseloan-create') }}"><i class="fa fa-plus"></i> Add New</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" id="delete-selected" href="javascript:void(0)"><i class="fa fa-minus"></i> Delete Selected</a>
                </li>
            </ul>
        </div>

        <div class="search-box">
            {{ Form::open(array('route' => 'houseloan-index', 'method'=> 'GET' ,'class' => 'search-form')) }}
            <div class="row">
                <div class="col-md-6 col-sm-12 col-xs-12">
                    {{ Form::label('search', 'Search') }}
                    {{ Form::text('search', null, array('class' => 'form-control search-field', 'placeholder' => 'Search...'))}}
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="records-per-page">
                        {{ Form::label('rpp', 'Per Page') }}
                        {{ Form::select('rpp', $records_per_page_options, $records_per_page , array('id' => 'records_per_page')) }}
                    </div>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <a class="btn btn-outline-success btn-reset" href="{{ route('houseloan-index')}}"><i class="icon-refresh"></i> Reset</a>
                </div>
            </div>
            {{ Form::close()  }}
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> All House Loans
            <span class="tag tag-pill tag-success pull-right">{{ $houseloans->total() }}</span>
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
                    <th>Loan Issue Date</th>
                    <th>Loan Amount</th>
                    <th>No of Installment</th>
                    <th>Installment Amount</th>
                    <th>Remaining Amount</th>
                    <th>Details</th>
                    <th>Action</th>
                </tr>

                @foreach($houseloans as $houseloan)
                    <tr>
                        <td><input type="checkbox" class="check-id" data-id="{{ $houseloan->house_id }}"></td>
                        <td><a href="{{ route('houseloan-edit',['id' => $houseloan->house_id]) }}">{{ $houseloan->staff->staff_central_id ?? '' }}</a></td>
                        <td><a href="{{ route('houseloan-edit',['id' => $houseloan->house_id]) }}">{{ $houseloan->staff->name_eng ?? '' }}</a></td>
                        <td>{{ $houseloan->staff->main_id ?? '' }}</td>
                        <td>{{ $houseloan->staff->payrollBranch->office_name ?? '' }}</td>
                        <td>{{ $houseloan->trans_date }}</td>
                        <td>{{ $houseloan->loan_amount }}</td>
                        <td>{{ $houseloan->no_installment }}</td>
                        <td>{{ $houseloan->installment_amount }}</td>
                        <td>{{ $houseloan->loan_amount - ($houseloan->paid_amt ?? 0) }}</td>
                        <td>
                            <a href="{{route('houseloan-show',$houseloan->house_id)}}" class="btn btn-outline-primary">View Detals</a>
                        </td>
                        <td class="actions-col">
                            <div class="actions">
                                <a class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $houseloan->house_id }}" href="javascript:void(0)"><i class="fa fa-remove"></i></a>
                                <a class="btn btn-sm btn-success" href="{{route('house-loan-diff-income-index', ['house_loan_id' => $houseloan->house_id])}}" title="View House Loan Diff Income"><i class="fa fa-money"></i></a>
                            </div>
                        </td>
                    </tr>

                @endforeach

                </tbody>

            </table>
            <div class="pagination-links">{{ $houseloans->appends($_GET)->links()
	  		}}
            </div>
        </div>
    </div>


@endsection


@section('script')
    <script>
        //responsive table
        $(function(){
            $('.table-all').stacktable();
        });
    </script>

    <script src="{{ asset('assets/js/vex.combined.js') }}"></script>
    <script>
        //apply vex dialog
        (function(){
            vex.defaultOptions.className = 'vex-theme-os'
            //vex.dialog.buttons.YES.text = 'Yes'
            vex.dialog.buttons.YES.className = 'btn btn-danger'
        })();
    </script>
    <script>
        //check uncheck all
        $('.check-all').change(function(){
            console.log($(this).prop('checked'))
            var checked = $(this).prop('checked')
            if(checked){
                $('.check-id').prop('checked', true).trigger('change');
            }
            else{
                $('.check-id').prop('checked', false).trigger('change');
            }
        });

        //set id in array ids
        var ids = [];
        //individual checkbox change -- on change push to ids array for selected checkbox
        $(document).on('change', '.check-id', function(){
            console.log('checked');
            var checked = $(this).prop('checked');
            if(checked){
                //check if already in array
                if((ids.indexOf($(this).data('id')) > -1)){
                }
                else{
                    ids.push($(this).data('id'));
                }
            }
            else{
                ids.splice($.inArray($(this).data('id'),ids),1);
            }
        });
    </script>

    <script>

        //deleted bulk selected
        $('#delete-selected').click(function(e){
            e.preventDefault();
            //Check if checkbox is unchecked
            if(ids!=''){
                vex.dialog.confirm({
                    message: 'Are you sure you want to delete?',
                    callback: function(value) {
                        if(value){ //true if clicked on ok
                            $.ajax({
                                type: "POST",
                                url: '{{ route('houseloan-destroy-selected') }}',
                                data: {_token : '{{ csrf_token() }}',ids: ids },
                                // send Blob objects via XHR requests:
                                success: function(response) {
                                    if(response == 'Successfully Deleted'){
                                        toastr.success('Successfully Deleted')
                                        setTimeout(function(){
                                            window.location.reload();
                                        }, 600);
                                    }
                                    else{
                                        vex.dialog.alert(response)
                                    }
                                },
                                error:function(response){
                                    vex.dialog.alert(response)
                                }
                            });
                        }
                    }
                });
            }
            else{
                vex.dialog.alert('Please first make selection form the list')
            }
        });
    </script>

    <script>
        //delete
        $('body').on('click', '.delete-btn',function(){
            $this = $(this)
            vex.dialog.confirm({
                message: 'Are you sure you want to delete?',
                callback: function(value) {
                    console.log('Callback value: ' + value + $this.data('id'));
                    if(value){ //true if clicked on ok
                        $.ajax({
                            type: "POST",
                            url: '{{ route('houseloan-destroy') }}',
                            data: {_token : '{{ csrf_token() }}',id: $this.data('id') },
                            // send Blob objects via XHR requests:
                            success: function(response) {
                                if(response == 'Successfully Deleted'){
                                    toastr.success('Successfully Deleted')
                                    $this.parent().parent().parent().remove();
                                }
                                else{
                                    vex.dialog.alert(response)
                                }
                            },
                            error:function(response){
                                vex.dialog.alert(response)
                            }
                        });
                    }
                }
            });
        });
    </script>
    <script>
        $('#records_per_page').change(function(){
            $('.search-form').submit();
        });
    </script>
@endsection
