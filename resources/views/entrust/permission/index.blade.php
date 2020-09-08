@extends('layouts.default', ['crumbroute' => 'permission-index'])
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="card">
        <div class="quick-actions">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('permission-create') }}"><i class="fa fa-plus"></i> Add
                        New</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-success" href="javascript:void(0);" id="send-permission-to-kb"><i
                            class="fa fa-upload"></i>
                        Send Permission to KB
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="javascript:void(0);" id="get-permission-from-kb"><i
                            class="fa fa-download"></i>
                        Download Permission From KB
                    </a>
                </li>

            </ul>
        </div>
        <div class="search-box">
            {{ Form::open(array('route' => 'permission-index', 'method'=> 'GET' ,'class' => 'search-form')) }}
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
                    <a class="btn btn-outline-success btn-reset" href="{{ route('education')}}"><i
                            class="icon-refresh"></i> Reset</a>
                </div>
            </div>
            {{ Form::close()  }}
        </div>
    </div>
    <div class="card">
        <div class="card-header" data-background-color="custom-color">
            <i class="fa fa-align-justify"></i>
            Permission
        </div>
        <div class="card-content table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>Name</th>
                    <th>URI</th>
                    <th>Parent ID</th>
                    <th>Order</th>
                    <th>Icon</th>
                    <th id="action-th">Action</th>
                </tr>
                </thead>
                <tbody>
                @if ($permissions->count() > 0)
                    @foreach($permissions as $permission )
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $permission->name ?? 'no data present' }}</td>
                            <td>{{ $permission->uri ?? 'no data present' }}</td>
                            <td>{{ $permission->parents->name ?? '-' }}</td>
                            <td>{{ $permission->order ?? 'no data present' }}</td>
                            <td>{{ $permission->icon ?? 'no data present' }}</td>
                            <td class="td-actions">
                                <a class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $permission->id }}"
                                   href="javascript:void(0)"><i class="fa fa-remove"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No Data Found</td>
                    </tr>
                @endif
                </tbody>
            </table>
            <div class="pagination-links">{{ $permissions->appends($_GET)->links()
	  		}}
            </div>

        </div>
    </div>
@endsection
@section('script')
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
                            url: '{{ route('permission-destroy') }}',
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

        $('#send-permission-to-kb').click(function () {
            $.ajax({
                url: '{{route('send-permission-to-kb')}}',
                success: function (response) {
                    if (response) {
                        vex.dialog.alert(response)
                    } else {
                        vex.dialog.alert(response)
                    }
                }
            })
        });

        $('#get-permission-from-kb').click(function () {
            $.ajax({
                url: '{{route('get-permission-from-kb')}}',
                success: function (response) {
                    if (response) {
                        vex.dialog.alert(response)
                    } else {
                        vex.dialog.alert(response)
                    }
                }
            })
        });
    </script>

    <script>
        $('#records_per_page').change(function () {
            $('.search-form').submit();
        });
    </script>
@endsection
