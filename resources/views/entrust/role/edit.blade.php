@extends('layouts.default', ['crumbroute' => 'role-edit'])
@section('content')

    <div class="row">
        <div class="col-md-7 col-sm-12">
            <div class="basic-info card">
                <h5 class="card-header">Edit Role</h5>
                <div class="card-block">
                    <div class="card-text">
                        <form method="post" action="{{ route('role-update',$role->id) }}" enctype="multipart/form-data"
                              id="role-form">
                            {{method_field('PATCH')}}
                            {{csrf_field()}}

                            <div class="form-group col-md-12 row">
                                <label for="name" class="col-md-2">Role Name </label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="role_name"
                                           name="role_name" placeholder="Enter Role"
                                           data-validation="required" value="{{$role->name}}">
                                </div>
                            </div>
                            <div class="form-group col-md-12 row">
                                <label for="display_name" class="col-md-2">Display Name</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="display_name"
                                           name="display_name" placeholder="Enter Display Name"
                                           data-validation="required" value="{{$role->display_name}}"
                                           data-validation-error-msg="Please enter the Display Number">
                                    <small class="form-text text-muted"><span class="label label-warning">* Required Field</span>
                                    </small>

                                </div>
                            </div>
                            <div class="form-group col-md-12 row">
                                <label for="display_name" class="col-md-2">Description</label>
                                <div class="col-md-10">
                                    <textarea name="description" id="" cols="30" rows="10"
                                              class="form-control">{{$role->description}}</textarea>
                                </div>
                            </div>
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

@endsection
