<div class="sidebar">
    <nav id="sidebarmenu" class="sidebar-nav">
        <ul class="nav">
            <?php
            $helper_returns = \App\Helpers\MenuHelper::allMenu();
            $allpermissions = $helper_returns->get();
            $permissions = $allpermissions->where('parent_id', 0);
            $user = Auth::user();
            config(['role' => $user->hasRole('Administrator')]);
            ?>
            @foreach($permissions as $permission)
                <li class="nav-item @if(count($permission->childPs) > 0 and substr($permission->name, 0, 1) == "#") nav-dropdown @endif">
                    <a class="nav-link @if(count($permission->childPs) > 0 and substr($permission->name, 0, 1) == "#") nav-dropdown-toggle @endif"
                       href="@if(count($permission->childPs) > 0 and substr($permission->name, 0, 1) == "#") {{$permission->name}}
                       @else
                       {{route($permission->name)}}
                       @endif">
                        <i class="{{$permission->icon}}"></i>
                        &nbsp; {{$permission->display_name}}
                    </a>
                    @if(count($permission->childPs)>0 and substr($permission->name, 0, 1) == "#")
                        <?php
                        if ($permission->id > 0) {
                            $childs = $allpermissions->where('parent_id', $permission->id)
                                ->sortBy('order');
                        }
                        ?>
                        <ul class="nav-dropdown-items">
                            @foreach ($childs as $child)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route($child->name)}}">
                                        <i class="{{$child->icon}}"></i>
                                        &nbsp;
                                        {{ $child->display_name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>