
<li id="menuItem_{{$permission->id}}">
    <div class="row custom-row menuDiv">
        <div data-id="{{$permission->id}}" class="itemTitle col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <span title="Click to show/hide children" class="disclose ui-icon ui-icon-minusthick"></span>
                    <span>{{$permission->name}}</span>
                </div>
                <div class="col-md-4">
                    <input type="text" class="" value="{{$permission->display_name}}"
                           name="permission[{{$permission->name}}][display_name]">
                </div>
                <div class="col-md-4">
                    {{--<select id="{{$permission->name}}-icon" class="select" name="{{$permission->name}}-icon">
                        <option value="{{$permission->icon}}"
                                selected="selected">{{$permission->icon}}</option>
                    </select>--}}
                    <input type="text" name="permission[{{$permission->name}}][icon]" value="{{$permission->icon}}">
                </div>
            </div>
        </div>
    </div>
    {{--@each('admin.entrust.permission.project', $levelTwos, 'permission', 'admin.entrust.permission.projects-none')--}}

        <ol>
            @foreach($permission->childPs as $permission)
                @include('entrust.permission.permission-recursive', $permission)
            @endforeach
        </ol>

</li>
