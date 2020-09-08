@extends('layouts.default', ['crumbroute' => 'user-create'])
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
@endsection
@section('content')
    <form method="post" action="{{ route('user-store') }}" enctype="multipart/form-data"
          id="maindiv">
        <div class="row">
            <div class="col-md-7 col-sm-12">
                <div class="basic-info card">
                    <h5 class="card-header">Add Users</h5>

                    <div class="card-block">
                        <div class="card-text">
                            {{csrf_field()}}
                            <div class="form-group row">
                                <label for="name" class="col-md-2">User Name</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="user_name"
                                           name="name" placeholder="Enter User Name"
                                           data-validation="required" value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="display_name" class="col-md-2">Email Address</label>
                                <div class="col-md-10">
                                    <input type="email" class="form-control" id="email"
                                           name="email" placeholder="Enter Email"
                                           value="{{ old('email') }}"
                                           data-validation="required email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="branch_id" class="col-2 col-form-label">
                                    Branch
                                </label>
                                <div class="col-md-10 col-sm-10">
                                    <select id="branch_id" name="branch_id" class="input-sm" required>
                                        <option value="">Select Branch</option>
                                        @foreach($offices as $office)
                                            <option value="{{$office->office_id}}">{{$office->office_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row">
                                {!! Form::label('staff_central_id', 'Staff Name', ['class' => 'col-2 col-form-label']) !!}
                                <div class="col-md-10">
                                    {!! Form::text('staff_central_id', null, ['class' => 'input-sm', 'id' => 'staff_central_id']) !!}
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="display_name" class="col-md-2">Password</label>
                                <div class="col-md-10">
                                    <input type="password" class="form-control" name="password"
                                           placeholder="Enter Password" data-validation="required">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="display_name" class="col-md-2">Confirm Password</label>
                                <div class="col-md-10">
                                    <input type="password" class="form-control" name="password_confirmation"
                                           placeholder="Enter Password" data-validation="required">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="role" class="col-md-2">Roles</label>
                                <div class="col-md-10">
                                    @foreach($roles as $role)
                                        <input type="checkbox" value="{{$role->id}}" name="roles[]">{{ $role->name }}
                                    @endforeach
                                </div>
                            </div>
                            <div style="text-align: center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-outline-secondary" value="Reset">Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script>
        var input_staff_central_id = $('#staff_central_id');
        // input_staff_central_id.on('change', onChangeStaffCentralId());
        var staffs = <?php echo $staffs ?>;
        input_staff_central_id.selectize({
            valueField: 'id',
            labelField: 'name_eng',
            searchField: ['name_eng', 'main_id'],
            options: staffs,
            preload: true,
            maxItems: 1,
            create: false,
            render: {
                option: function (item, escape) {
                    var branch_name = null;
                    if (item.branch != null) {
                        branch_name = item.branch.office_name;
                    }
                    return '<div class="suggestions"><div> Name: ' + item.name_eng + '</div>' +
                        '<div> Branch ID: ' + item.main_id + '</div>' +
                        '<div> Branch Name: ' + branch_name + '</div>' +
                        '<div> Staff Central ID: ' + item.staff_central_id + '</div></div>';
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
@endsection
