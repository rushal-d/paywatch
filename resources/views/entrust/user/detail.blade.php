@extends('admin.layouts.app',['crumbroute' => 'vehicle-create'])
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" data-background-color="custom-color">
                        <h5 class="title"><i class="far fa-plus-square"></i> View Users</h5>
                        <p class="category">
                            View Users
                        </p>

                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-offset-2">
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Username</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{ $user->name }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Email Address</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Roles</label>
                                <div class="col-sm-10">
                                    @foreach($roles as $role)
                                        <p class="form-control-static">{{in_array($role->id, $userRoles) ? $role->display_name : null}}</p>
                                    @endforeach
                                </div>
                            </div>
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
