@extends('layouts.default', ['crumbroute' => 'shift'])
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
                    <a class="nav-link" href="{{ route('shift-create') }}"><i class="fa fa-plus"></i> Add
                        New</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-success" href="{{ route('shift-visual') }}"><i
                            class="fa fa-street-view"></i> Visual Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" id="delete-selected" href="javascript:void(0)"><i
                            class="fa fa-minus"></i> Delete Selected</a>
                </li>
                <li class="nav-item">
                    <a class="excelimport nav-link text-success " href="{{route('shift-import-index')}}">
                        Import from Excel <i class="far fa-file-excel"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="search-box">
            {{ Form::open(array('route' => 'shift-index', 'method'=> 'GET' ,'class' => 'search-form')) }}
            <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-12">
                    {{ Form::label('branch_id', 'Branch') }}
                    {!! Form::select('branch_id', $branches , request('branch_id'),array('id'=>'branch_id','class'=> 'adjust-width','data-validation' => 'required',
                             ) ) !!}
                </div>

                <div class="col-md-3 col-sm-12 col-xs-12">
                    <div class="records-per-page">
                        {{ Form::label('rpp', 'Per Page') }}
                        {{ Form::select('rpp', $records_per_page_options, $records_per_page , array('id' => 'records_per_page')) }}
                    </div>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <div class="show-all">
                        {{ Form::label('show_all', 'Show All') }}
                        {{ Form::checkbox('show_all',1 , ($_GET['show_all']?? false),array('id' => 'show_all')) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-12 col-xs-12">
                    <a class="btn btn-outline-success btn-reset" href="{{ route('shift-index')}}"><i
                            class="fa fa-search"></i> Search</a>
                    <a class="btn btn-outline-success btn-reset" href="{{ route('shift-index')}}"><i
                            class="icon-refresh"></i> Reset</a>
                </div>
            </div>
            {{ Form::close()  }}
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> All Shifts
            <span class="tag tag-pill tag-success pull-right">{{ $shifts->total() }}</span>


        </div>
        <div class="card-block">
            <table class="table table-responsive table-striped table-hover table-all" width="100%" cellspacing="0">
                <tbody>
                <tr align="left">
                    <th width="2%"><input type="checkbox" class="check-all"></th>
                    <th>Shift Name</th>
                    <th>Nos. of Staff</th>
                    <th>Branch Name</th>
                    <th>Punch In</th>
                    <th>Punch Out</th>
                    <th>Tiffin</th>
                    <th>Lunch</th>
                    <th>Personal In Out</th>
                    <th>Status</th>
                    <th width="8%">Action</th>
                </tr>

                @foreach($shifts as $shift)
                    <tr>
                        <td><input type="checkbox" class="check-id" data-id="{{ $shift->id }}"></td>
                        <td>
                            <a href="{{ route('shift-edit',['id' => $shift->id]) }}">{{ $shift->shift_name  }}</a>
                        </td>
                        <td>
                            <a target="blank"
                               href="{{route('change-shift-filter',['shift_id'=>$shift->id,'branch_id'=>$shift->branch_id])}}">{{$shift->staff->count()}}</a>
                        </td>
                        <td>
                            {{$branches[$shift->branch_id] ?? ''}}
                        </td>
                        <td>{{date('h:i:s a',strtotime($shift->punch_in))}} <br>
                            ({{date('h:i:s a',strtotime($shift->punch_in)-$shift->before_punch_in_threshold*60)}}
                            -{{date('h:i:s a',strtotime($shift->punch_in)+$shift->after_punch_in_threshold*60)}})
                        </td>
                        <td>{{date('h:i:s a',strtotime($shift->punch_out))}} <br>
                            ({{date('h:i:s a',strtotime($shift->punch_out)-$shift->before_punch_out_threshold*60)}}
                            -{{date('h:i:s a',strtotime($shift->punch_out)+$shift->after_punch_out_threshold*60)}})
                        </td>
                        <td>{{date('h:i:s a',strtotime($shift->min_tiffin_out))}}
                            -{{date('h:i:s a',strtotime($shift->max_tiffin_in))}} ({{$shift->tiffin_duration}}
                            min)<br>
                            ({{date('h:i:s a',strtotime($shift->min_tiffin_out)-$shift->before_tiffin_threshold*60)}}
                            -{{date('h:i:s a',strtotime($shift->max_tiffin_in)+$shift->after_tiffin_threshold*60)}})
                        </td>
                        <td>{{date('h:i:s a',strtotime($shift->min_lunch_out))}}
                            -{{date('h:i:s a',strtotime($shift->max_lunch_in))}} ({{$shift->lunch_duration}}
                            min)<br>
                            ({{date('h:i:s a',strtotime($shift->min_lunch_out)-$shift->before_lunch_threshold*60)}}
                            -{{date('h:i:s a',strtotime($shift->max_lunch_in)+$shift->after_lunch_threshold*60)}})
                        </td>
                        <td>{{$shift->personal_in_out_duration}} min ({{$shift->personal_in_out_threshold}} min)</td>
                        <td>{{($shift->active)?'Active':'Inactive'}}</td>
                        <td class="actions-col">
                            <div class="actions">
                                <a class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $shift->id }}"
                                   href="javascript:void(0)"><i class="fa fa-remove"></i></a>
                            </div>
                        </td>
                    </tr>

                @endforeach

                </tbody>

            </table>
            <div class="pagination-links">{{ $shifts->appends(
	  			$_GET
	  			)->links()
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
                                url: '{{ route('shift-destroy-selected') }}',
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
                            url: '{{ route('shift-destroy') }}',
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

        $('#show_all').change(function () {
            $('.search-form').submit();
        });

        $('#branch_id').change(function () {
            $('.search-form').submit();
        });


    </script>
@endsection
