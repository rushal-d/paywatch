@extends('layouts.default', ['crumbroute' => 'role-index'])
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="card">
        <div class="quick-actions">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('role-create') }}"><i class="fa fa-plus"></i> Add
                        New</a>
                </li>

            </ul>
        </div>
        <div class="search-box">
            {{ Form::open(array('route' => 'role-index', 'method'=> 'GET' ,'class' => 'search-form')) }}
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
                    <a class="btn btn-outline-success btn-reset" href="{{ route('role-index')}}"><i
                                class="icon-refresh"></i> Reset</a>
                </div>
            </div>
            {{ Form::close()  }}
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i>
            Role
        </div>
        <div class="card-content table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>Name</th>
                    <th>Display Name</th>
                    <th>Description</th>
                    <th id="action-th">Action</th>
                </tr>
                </thead>
                <tbody>
                @if ($roles->count() > 0)
                    @foreach($roles as $role )
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $role->name ?? 'no data present' }}</td>
                            <td>{{ $role->display_name ?? 'no data present' }}</td>
                            <td>{{ $role->description ?? '-' }}</td>
                            <td class="td-actions">
                                <a type="button" rel="tooltip" title="Edit"
                                   href="{{ route('role-edit',$role->id) }}"
                                   class="btn btn-success btn-simple btn-sm ">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $role->id }}"
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
            <div class="pagination-links">{{ $roles->appends($_GET)->links()
	  		}}
            </div>
        </div>
    </div>

    @include('layouts.deleteModal')
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
                            url: '{{ route('role-destroy') }}',
                            data: {_token: '{{ csrf_token() }}', id: $this.data('id')},
                            // send Blob objects via XHR requests:
                            success: function (response) {
                                if (response == 'Successfully Deleted') {
                                    toastr.success('Successfully Deleted')
                                    $this.parent().parent().parent().remove();
                                }
                                else {
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
    </script>
@endsection
