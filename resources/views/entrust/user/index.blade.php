@extends('layouts.default', ['crumbroute' => 'user-index'])
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="card">
        <div class="quick-actions">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    @if(auth()->user()->can('user-create'))
                        <a class="nav-link" href="{{ route('user-create') }}"><i class="fa fa-plus"></i> Add
                            New</a>
                    @endif
                </li>

            </ul>
        </div>
        <div class="search-box">
            {{ Form::open(array('route' => 'user-index', 'method'=> 'GET' ,'class' => 'search-form')) }}
            <div class="row">
                <div class="col-md-2 col-sm-6 col-xs-12">
                    {{ Form::text('user_id', request("user_id"), array('class' => 'form-control', 'id' => 'user_id','placeholder' => 'Please select a user'))}}
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    {{ Form::text('search', request('search'), array('class' => 'form-control', 'placeholder' => 'Search by name, email'))}}
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    {{ Form::text('roles[]', null, array('class' => 'form-control','id' => 'roles_id', 'placeholder' => 'Select multiple roles'))}}
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    {{ Form::select('rpp', $records_per_page_options, $records_per_page , array('class' => 'form-control','id' => 'records_per_page')) }}
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    <button class="btn btn-success">Search</button>
                    <a class="btn btn-outline-success btn-danger" href="{{ route('user-index')}}"><i
                            class="icon-refresh"></i> Reset</a>
                </div>
            </div>
            {{ Form::close()  }}
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i>
            Users
        </div>
        <div class="card-content table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Branch</th>
                    @role('Administrator')
                    <th id="action-th">Action</th>
                    @endrole
                </tr>
                </thead>
                <tbody>
                @php
                    $edit_user_permission=Auth::user()->can('user-edit');
                    $delete_user_permission=Auth::user()->can('user-destroy');
                @endphp
                @if ($users->count() > 0)
                    @foreach($users as $user )
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $user->name ?? 'no data present' }}</td>
                            <td>{{ $user->email ?? 'no data present' }}</td>
                            <td>@foreach($user->roles as $r) [{{$r->name}}] @endforeach </td>
                            <td>{{$user->branch->office_name ?? '-'}} </td>


                            <td class="td-actions">
                                {{--<a type="button" rel="tooltip" title="View"--}}
                                {{--class="btn btn-info btn-simple btn-lg "--}}
                                {{--href="{{ route('userdetail',$user->id) }}">--}}
                                {{--<i class="fas fa-eye"></i>--}}
                                {{--</a>--}}
                                @if($edit_user_permission)
                                    <a type="button" rel="tooltip" title="Edit"
                                       href="{{ route('user-edit',$user->id) }}"
                                       class="btn btn-success btn-simple btn-sm ">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endif
                                @if($delete_user_permission)
                                    <a class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $user->id }}"
                                       href="javascript:void(0)"><i class="fa fa-remove"></i></a>
                                @endif
                                {{--@role('administrator')--}}
                                {{--<a type="button" href="{{route('user-change-password',$user->id)}}"--}}
                                {{--rel="tooltip" title="Change Password"--}}
                                {{--class="btn btn-danger btn-simple btn-lg"> <i class="fa fa-edit"></i></a>--}}
                                {{--@endrole--}}
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
            <div class="pagination-links">{{ $users->appends($_GET)->links()
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
                            url: '{{ route('user-destroy') }}',
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

    <script>
        let roleOptions = <?php echo $roleOptions ?>;
        let roles_selectize_option = $('#roles_id').selectize({
            valueField: 'id',
            labelField: 'name',
            options: roleOptions,
            maxItems: 5,
            plugins: ['remove_button'],
            create: false,
            render: {
                option: function (item, escape) {
                    console.log(item);
                    return '<div class="suggestions"><div>' + item.name + '</div>';
                }
            },
        });

        let selectedRole = <?php echo $roleIds ?? null ?>;
        roles_selectize_option[0].selectize.setValue(selectedRole);
    </script>

    <script>
        let user_options = <?php echo $userOptions ?>;
        let user_selectize_option = $('#user_id').selectize({
            valueField: 'id',
            labelField: 'name',
            options: user_options,
            maxItems: 1,
            plugins: ['remove_button'],
            create: false,
            preload: true,
            render: {
                option: function (item, escape) {
                    return '<div class="suggestions"><div> Name: ' + item.name + '</div>' +
                        '<div> Email: ' + item.email + '</div>';
                }
            },

            load: function (query, callback) {
                if (!query.length) {
                    return callback();
                }
                $.ajax({
                    url: '{{ route('ajax.selectize.get-users-by-name') }}?name=' + encodeURIComponent(query) + '&limit={{10}}',
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (response) {
                        callback(response.data);
                    }
                });
            },
            searchField: ['name', 'email'],
        });
    </script>




@endsection
