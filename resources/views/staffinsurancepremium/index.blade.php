@extends('layouts.default', ['crumbroute' => 'staff-insurance-premium-index'])
@section('title', $title)

@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .adjust-width {
            max-width: 170px;
            width: 100%;
            margin-left: 10px;
            display: block;
        }
    </style>
@endsection
@section('content')
    <style>

        @media print {
            .cards {
                display: none;
            }

            .print_this {
                display: block;
            }
        }
    </style>
    <div class="card">
        <div class="quick-actions">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a style="display: inline-block" class="nav-link" href="{{ route('staff-insurance-premium-create') }}"><i class="fa fa-plus"></i>
                        Add New</a>
                    <a style="display: inline-block" class="nav-link text-danger" id="delete-selected" href="javascript:void(0)"><i
                            class="fa fa-minus"></i> Delete Selected</a>
                </li>
            </ul>
        </div>
        <div class="search-box">
            {{ Form::open(array('route' => 'staff-insurance-premium-index', 'method'=> 'GET' ,'class' => 'search-form')) }}
            <div class="row">
                {!! Form::text('staff_central_id',$_GET['staff_central_id'] ?? null,['class'=>'adjust-width form-control','id'=>'staff_central_id','placeholder'=>'Select a Staff']) !!}
                {!! Form::select('fiscal_year_id', $fiscal_year, $_GET['fiscal_year_id'] ?? null,['class'=>'adjust-width form-control','id'=>'fiscal_year_id','placeholder'=>'Select a fiscal year']) !!}
                {!! Form::select('branch_id', $branch, $_GET['branch_id'] ?? null,['class'=>'adjust-width form-control','id'=>'fiscal_year_id','placeholder'=>'Select a branch']) !!}
                {!! Form::select('rpp', $records_per_page_options, $records_per_page,['class'=>'adjust-width form-control','id'=>'records_per_page', 'placeholder' => 'Select']) !!}
                <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                    <button type="submit" class="btn btn-outline-primary btn-reset">Filter</button>
                    <a class="btn btn-outline-success btn-reset" href="{{ route('staff-insurance-premium-index')}}"><i
                            class="icon-refresh"></i> Reset</a>
                </div>
            </div>
            {{ Form::close()  }}
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Staff Insurance Premium
            <span class="tag tag-pill tag-success pull-right">{{ $staffInsurances->total() }}</span>
        </div>
        <div class="card-block">
            <table class="table table-responsive table-striped table-hover table-all" width="100%" cellspacing="0">
                <tbody>
                <tr align="left">
                    <th><input type="checkbox" class="check-all"></th>
                    <th>Staff Name</th>
                    <th>Staff Central ID</th>
                    <th>Fiscal Year</th>
                    <th>Branch</th>
                    <th>Premium Amount</th>
                    <th>Action</th>
                </tr>

                @foreach($staffInsurances as $staffInsurance)
                    <tr>
                        <td>
                            <input type="checkbox" class="check-id" data-id="{{ $staffInsurance->id }}">
                        </td>
                        <td>{{$staffInsurance->staff->name_eng}}</td>
                        <td>{{$staffInsurance->staff->id}}</td>
                        <td>{{$staffInsurance->fiscal_year->fiscal_code}}</td>
                        <td>{{$staffInsurance->branch->office_name}}</td>
                        <td>{{$staffInsurance->premium_amount}}</td>
                        <td>
                            <a href="{{route('staff-insurance-premium-edit', $staffInsurance->id)}}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <a href="javascript:void(0)" data-id="{{$staffInsurance->id}}" class="delete-btn btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination-links">{{ $staffInsurances->appends($_GET)->links()
	  		}}
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        $('.date').flatpickr();
    </script>
    <script>
        // input_staff_central_id.on('change', onChangeStaffCentralId());
        var staffs = <?php echo $staffs ?>;
        console.log(staffs);
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
            if(checked){
                $('.check-id').prop('checked', true).trigger('change');
            }else{
                $('.check-id').prop('checked', false).trigger('change');
            }
        });

        //set id in array ids
        var ids = [];
        //individual checkbox change -- on change push ids array for selected checkbox
        $(document).on('change', '.check-id', function () {
            console.log('checked');
            var checked = $(this).prop('checked');
            if(checked) {
                //check if already in array
                if ((ids.indexOf($(this).data('id')) > -1)) {
                } else {
                    ids.push($(this).data('id'));
                }
            }else{
                ids.splice($.inArray($(this).data('id'), ids), 1);
            }
        });
    </script>

    <script>
        //delete bulk selected
        $('#delete-selected').click(function (e) {
            e.preventDefault();
            //Check if checkbox is unchecked
            console.log(ids);
            if(ids != ''){
                vex.dialog.confirm({
                    message: 'Are you sure you want to delete?',
                    callback: function (value){
                        if (value) { //true if clicked on ok
                            $.ajax({
                                type: "POST",
                                url: '{{route('staff-insurance-destroy-selected')}}',
                                data: {
                                    _token: '{{csrf_token()}}',
                                    ids: ids
                                },
                                //send Blob objects via XHR requests:
                                success: function (response) {
                                    if(response == 'Successfully Deleted') {
                                        toastr.success('Successfully Deleted')
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 600);
                                    }else{
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
            }else{
                vex.dialog.alert('Please first make selection from the list')
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
                            url: '{{ route('staff-insurance-premium-destroy') }}',
                            data: {_token: '{{ csrf_token() }}', id: $this.data('id')},
                            // send Blob objects via XHR requests:
                            success: function (response) {
                                if (response == 'Successfully Deleted') {
                                    toastr.success('Successfully Deleted')
                                    $this.parent().parent().remove();
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
