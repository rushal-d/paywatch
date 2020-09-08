@extends('layouts.default', ['crumbroute' => 'vehicalloan'])
@section('title', $title)

@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
@endsection
@section('content')

    <div class="card"><div class="quick-actions">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('vehicalloan-create') }}"><i class="fa fa-plus"></i> Add New</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" id="delete-selected" href="javascript:void(0)"><i class="fa fa-minus"></i> Delete Selected</a>
                </li>
            </ul>
        </div>

        <div class="search-box">
            {{ Form::open(array('route' => 'vehicalloan-index', 'method'=> 'GET' ,'class' => 'search-form')) }}
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
                    <a class="btn btn-outline-success btn-reset" href="{{ route('vehicalloan-index')}}"><i class="icon-refresh"></i> Reset</a>
                </div>
            </div>
            {{ Form::close()  }}
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> All Vehical Loans
            <span class="tag tag-pill tag-success pull-right">{{ $vehicalloans->total() }}</span>
        </div>
        <div class="card-block">
            <table class="table table-responsive table-striped table-hover table-all" width="100%" cellspacing="0">
                <tbody>
                <tr align="left">
                    <th width="2%"><input type="checkbox" class="check-all"></th>
                    <th width="10%">Staff Central ID</th>
                    <th width="10%">Staff Name</th>
                    <th width="10%">Branch ID</th>
                    <th width="10%">Payroll Branch</th>
                    <th width="10%">Loan Issue Date</th>
                    <th width="10%">Loan Amount</th>
                    <th width="10%">No of Installment</th>
                    <th width="10%">Installment Amount</th>
                    <th width="8%">Action</th>
                </tr>

                @foreach($vehicalloans as $vehicalloan)
                    <tr>
                        <td><input type="checkbox" class="check-id" data-id="{{ $vehicalloan->vehical_id }}"></td>
                        <td><a href="{{route('vehicalloan-edit',$vehicalloan->vehical_id)}}">{{ $vehicalloan->staff->staff_central_id ?? '' }}</a></td>
                        <td><a href="{{route('vehicalloan-edit',$vehicalloan->vehical_id)}}">{{ $vehicalloan->staff->name_eng ?? '' }}</a></td>
                        <td>{{ $vehicalloan->staff->main_id ?? '' }}</td>
                        <td>{{ $vehicalloan->staff->payrollBranch->office_name ?? '' }}</td>
                        <td>{{ $vehicalloan->trans_date }}</td>
                        <td>{{ $vehicalloan->loan_amount }}</td>
                        <td>{{ $vehicalloan->no_installment }}</td>
                        <td>{{ $vehicalloan->installment_amount }}</td>
                        <td class="actions-col">
                            <div class="actions">
                                <a class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $vehicalloan->vehical_id }}" href="javascript:void(0)"><i class="fa fa-remove"></i></a>
                                <a class="btn btn-sm btn-success" href="{{route('vehicle-loan-diff-income-index', ['vehicle_loan_id' => $vehicalloan->vehical_id])}}" title="View Vehicle Loan Diff Income"><i class="fa fa-money"></i></a>
                            </div>
                        </td>
                    </tr>

                @endforeach

                </tbody>

            </table>
            <div class="pagination-links">{{ $vehicalloans->appends($_GET)->links()
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
                                url: '{{ route('vehicalloan-destroy-selected') }}',
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
                            url: '{{ route('vehicalloan-destroy') }}',
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
