@extends('layouts.default', ['crumbroute' => 'assignrole-edit'])
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" data-background-color="custom-color">
                        <h5 class="title"><i class="far fa-plus-square"></i> Assign Menu to Role:
                            <span>{{$role->name}}</span></h5>
                    </div>
                    <div class="col-lg-12">
                        <div class="tab-container">
                            <form id="maindiv" method="post" action="{{ route('assignrole-update',$role->id) }}"
                                  enctype="multipart/form-data">
                                {{csrf_field()}}
                                {{method_field('PATCH')}}
                                <input type="hidden" value="{{$role->id}}" name="role">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                           role="tab" aria-controls="home" aria-selected="true">Home
                                        </a>
                                    </li>
                                    @foreach($permissions as $permission)
                                        <li class="nav-item">
                                            <a data-toggle="tab" class="nav-link"
                                               role="tab"
                                               href="#{{trim(str_replace('.','',$permission->name),'#')}}_menu">{{$permission->display_name}}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                         aria-labelledby="home-tab">...
                                    </div>
                                    @foreach($permissions as $permission)
                                        <div id="{{trim(str_replace('.','',$permission->name),'#')}}_menu"
                                             class="tab-pane"
                                             role="tabpanel">
                                            <h3>{{$permission->display_name}}</h3>
                                            <ul>
                                                <li>
                                                    <input class="main-parent parent" type="checkbox"
                                                           name="permissions[]"
                                                           value="{{ $permission->id }}" {{in_array($permission->id, $rolePermission) ? "checked" : null}} >{{ $permission->display_name }}
                                                    <ul class="level_one">
                                                        @foreach ($permission->childPs as $child)
                                                            <li><input class="level-one-parent parent child"
                                                                       type="checkbox" name="permissions[]"
                                                                       value="{{ $child->id }}" {{in_array($child->id, $rolePermission) ? "checked" : null}} >{{ $child->display_name }}
                                                                <ul class="level_two" style="list-style: none">
                                                                    @foreach($child->childPs as $c)
                                                                        <li><input class="child" type="checkbox"
                                                                                   name="permissions[]"
                                                                                   value="{{ $c->id }}" {{in_array($c->id, $rolePermission) ? "checked" : null}} >{{$c->display_name}}
                                                                        </li>
                                                                        <ul class="level_three"
                                                                            style="list-style: none">
                                                                            @foreach($c->childPs as $cc)
                                                                                <li><input class="child" type="checkbox"
                                                                                           name="permissions[]"
                                                                                           value="{{ $cc->id }}" {{in_array($cc->id, $rolePermission) ? "checked" : null}} >{{$cc->display_name}}
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>

                                    @endforeach
                                </div>
                                <div style="text-align: center; padding: 10px;">
                                    <button type="submit" class="btn btn-primary">Update</button>
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
@endsection
@section('script')
    <script>
        //handle checkboxes
        $('.parent').on('click', function () {
            //get all child checkboxes
            var checkboxes = $(this).next().find('.child');
            //check is the current item is checked or not
            var isChecked = $(this).prop('checked');
            //apply the check or unchecked by parent state
            checkboxes.prop('checked', isChecked);
        });
    </script>
@endsection
