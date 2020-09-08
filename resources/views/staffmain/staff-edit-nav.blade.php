<div class="card">
    <div class="quick-actions">
        <ul class="nav nav-pills">
            @php
                $route_name=Route::currentRouteName();
            @endphp
            {{--<li class="nav-item">
                <a class="nav-link" href="{{route('staff-main-create')}}">
                    Add New Staff</a>
            </li>--}}


            <li class="nav-item">
                <a class="nav-link text-success" href="{{route('staff-main-edit',$staffmain->id)}}">
                    @if($route_name==='staff-main-edit')
                        <u>Edit Personal Info</u>
                    @else
                        Edit Personal Info
                    @endif
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-success" href="{{route('staff-nominee',$staffmain->id)}}">
                    @if($route_name==='staff-nominee')
                        <u>Edit Staff Nominee</u>
                    @else
                        Edit Staff Nominee
                    @endif
                    </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-danger" href="{{route('staff-payment',$staffmain->id)}}">
                    @if($route_name==='staff-payment')
                        <u>Edit Staff Payment</u>
                    @else
                        Edit Staff Payment
                    @endif
                    </a>
            </li>

            <li class="nav-item">
                <a class="nav-link " href="{{route('staff-job-information',$staffmain->id)}}">
                    @if($route_name==='staff-job-information')
                        <u>Edit Staff Job Info.</u>
                    @else
                        Edit Staff Job Info.
                    @endif

                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-danger" href="{{route('staff-work-schedule',$staffmain->id)}}">
                    @if($route_name==='staff-work-schedule')
                        <u>Edit Staff Work Schedule</u>
                    @else
                        Edit Staff Work Schedule
                    @endif
                    </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-danger" href="{{route('training-detail-index',$staffmain->id)}}">
                    @if($route_name==='training-detail-index')
                        <u>Staff Training</u>
                    @else
                      Staff Training
                    @endif
                    </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-success" href="{{route('staff-leave-balance',$staffmain->id)}}">
                    @if($route_name==='staff-leave-balance')
                        <u>Edit Staff Leave Balance</u>
                    @else
                        Edit Staff Leave Balance
                    @endif
                    </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-danger" href="{{route('staff-salary',$staffmain->id)}}">
                    @if($route_name==='staff-salary')
                        <u>Edit Staff Salary</u>
                    @else
                        Edit Staff Salary
                    @endif
                    </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-danger" href="{{route('staff-grade',$staffmain->id)}}">
                    @if($route_name==='staff-salary')
                        <u>Edit Staff Grade</u>
                    @else
                        Edit Staff Grade
                    @endif
                    </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-success" href="{{route('staff-position',$staffmain->id)}}">
                    @if($route_name==='staff-position')
                        <u>Edit Position</u>
                    @else
                        Edit Position
                    @endif
                    </a>
            </li>

        </ul>
    </div>
</div>
