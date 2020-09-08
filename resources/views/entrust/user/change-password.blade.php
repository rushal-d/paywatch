@extends('layouts.default', ['crumbroute' => 'user-change-password'])
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" data-background-color="custom-color">
                        <h5 class="title"><i class="far fa-plus-square"></i> Edit Users</h5>

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            {{--form start--}}
                            <form method="post" action="{{ route('user-password-update') }}"
                                  enctype="multipart/form-data"
                                  id="user-form">
                                {{method_field('PATCH')}}

                                {{csrf_field()}}
                                <div class="form-group col-md-12 row">
                                    <label for="name" class="col-md-2">User Name </label>
                                    <div class="col-md-10">

                                        <p>
                                            Name :{{ $user->name }}
                                        </p>


                                    </div>
                                </div>
                                <div class="form-group col-md-12 row">

                                    <label for="display_name" class="col-md-2">Email Address</label>
                                    <div class="col-md-10">
                                        <p>{{ $user->email }}</p>

                                    </div>

                                </div>

                                <div class="form-group col-md-12 row">
                                    <label for="display_name" class="col-md-2">Password</label>
                                    <div class="col-md-10">
                                        <input type="password" class="form-control" name="password"
                                               placeholder="Enter Password" data-validation="required"
                                               data-validation-error-msg="Please enter the password">
                                        <small class="form-text text-muted"><span class="label label-warning">* Required Field</span>
                                        </small>


                                    </div>
                                </div>

                                <div class="form-group col-md-12 row">
                                    <label for="display_name" class="col-md-2">Confirm Password</label>
                                    <div class="col-md-10">
                                        <input type="password" class="form-control" name="password_confirmation"
                                               placeholder="Enter Password" data-validation="required"
                                               data-validation-error-msg="Please enter the password">
                                        <small class="form-text text-muted"><span class="label label-warning">* Required Field</span>
                                        </small>


                                    </div>
                                </div>
                                @permission('user-edit')
                                <div class="form-group col-md-12 row">
                                    <label for="role" class="col-md-2">Roles</label>
                                    <div class="col-md-10">
                                        @foreach($roles as $role)
                                            <input type="checkbox" value="{{$role->id}}"
                                                   name="roles[]" {{in_array($role->id, $userRoles) ? "checked" : null}}>{{ $role->name }}
                                        @endforeach
                                    </div>
                                </div>
                                @endpermission


                                <div style="text-align: center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary" value="Reset">Cancel
                                    </button>
                                </div>
                            </form>
                            {{--form end--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        span.help-block.form-error {
            display: block;
            position: initial;
        }
    </style>
@endsection

@section('script')

@endsection
