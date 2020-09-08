<h2 align="center">{{$organization->organization_name ?? 'Organization Name'}}</h2>
<h5 align="center">Staff Attendance Detail</h5>
<table class="attendance-information">
    <tr>
        <td><b>Branch ID: </b>{{$staff->main_id}}</td>
    <tr>
    <tr>
        <td><b>Staff Name: </b>{{$staff->name_eng}}</td>
        <td><b>Branch:</b> {{$staff->branch->office_name}}</td>
    </tr>
    <tr>
        <td><b>Work Hour: </b>{{$staff->workschedule->last()->work_hour ?? ''}}</td>
        <td><b>Weekend Day:</b> {{$weekend_name[$staff->workschedule->last()->weekend_day ?? ''] ?? ''}}</td>
    </tr>
    <tr>
        <td><b>Attendance From:</b>{{$_GET['from_date_np'] ?? ''}}</td>
        <td><b>Attendance To:</b>{{$_GET['to_date_np'] ?? ''}}</td>
    </tr>
    <tr>
        <td><b>Shift</b>: {{$staff->shift->shift_name ?? ''}}
            @if(!empty($staff->shift))
                <i>
                    ( {{date('h:i a',strtotime('-'.$staff->shift->before_punch_in_threshold.'minutes',strtotime($staff->shift->punch_in)))}}
                    - {{date('h:i a',strtotime('+'.$staff->shift->after_punch_out_threshold.'minutes',strtotime($staff->shift->punch_out)))}}
                    )
                </i>
            @endif
        </td>
    </tr>
</table>
<table class="payroll_table">
    @permission('localattendance-destroy')

    @php
        $allowDelete = true;
    @endphp
    @else
        @php
            $allowDelete = false;
        @endphp
        @endpermission
        <thead>
        <tr class="custom-theader" align="center">
            @if($forExcel)
                <th style="text-align: center;">Date (AD)</th>
            @endif
            <th style="text-align: center;">Date</th>
            <th style="text-align: center;">Punch In</th>
            <th style="text-align: center;">Lunch Out</th>
            <th style="text-align: center;">Lunch In</th>
            <th style="text-align: center;">Tiffin Out</th>
            <th style="text-align: center;">Tiffin In</th>
            <th style="text-align: center;">Personal Out</th>
            <th style="text-align: center;">Personal In</th>
            <th style="text-align: center;">Punch Out</th>
            {{--            <th style="text-align: center;">Total Work Hour</th>--}}
            <th style="text-align: center;">Total Work Time</th>
            <th style="text-align: center;">Created By</th>
            <th style="text-align: center;">Updated By</th>
            <th style="text-align: center;">Remarks</th>
            @if($allowDelete)
                <th>Action</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($localattendances as $localattendance)
            <tr class="@foreach ($localattendance['add_classes'] as $class) {{$class}} @endforeach"
                data-local-attendance-id="{{$localattendance['id']}}">
                @if($forExcel)
                    <td>{{\App\Helpers\BSDateHelper::BsToAd('-',$localattendance['date'])}}</td>
                @endif
                <td>
                    @if(!$forExcel)
                        <?php
                        if ($localattendance['id']) {
                            $route = route('localattendance-edit', ['id' => $localattendance['id']]);
                        } else {
                            $route = route('localattendance-create', [
                                'staff_central_id' => request('staff_central_id'),
                                'branch_id' => request('branch_id'),
                                'attendance_date_np' => $localattendance['date']]);
                        }
                        ?>
                    @endif


                    @if(!\Session::get('isEmployee') && !$forExcel)
                        <a href="{{$route}}" target="_blank">{{$localattendance['date']}}
                            ( {{date('D',strtotime(\App\Helpers\BSDateHelper::BsToAd('-',$localattendance['date'])))}}
                            )
                        </a>
                    @else
                        {{$localattendance['date']}}
                        ( {{date('D',strtotime(\App\Helpers\BSDateHelper::BsToAd('-',$localattendance['date'])))}})
                    @endif
                </td>
                @if($localattendance['status']=='Present')
                    <td>{{$localattendance['punch_in']}}</td>
                    <td>{{$localattendance['lunch_out']}}</td>
                    <td>{{$localattendance['lunch_in']}}</td>
                    <td>{{$localattendance['tiffin_out']}}</td>
                    <td>{{$localattendance['tiffin_in']}}</td>
                    <td>{{$localattendance['personal_out']}}</td>
                    <td>{{$localattendance['personal_in']}}</td>
                    <td>{{$localattendance['punch_out']}}</td>
                    {{--                    <td>{{$localattendance['total_work_hour']}} Hours</td>--}}
                    <td>{{$localattendance['total_work_time_format']}}</td>
                    <td>{{$localattendance['created_by']}}</td>
                    <td>{{$localattendance['updated_by']}}</td>
                    <td>{!!  $localattendance['remarks']!!}</td>
                    @if($allowDelete)
                        <td class="text-center">
                            @if($localattendance['is_force']==1)
                                <button class="btn btn-danger delete-local-attendance-button">
                                    <i class="fa fa-trash"></i>
                                </button>
                            @endif
                        </td>
                    @endif
                @else
                    <td colspan="12" class="{{$localattendance['status']}}">{{$localattendance['status']}}</td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td colspan="9">Total Work Hour</td>
            <td colspan="3">{{App\Helpers\DateHelper::convertHourToHourAndMinutesFormat($overall_total_working_hour ?? 0)}}</td>
        </tr>
        </tbody>

</table>
<table class="table-bottom" align="left" border="1px" style="margin:0% 3%;">
    <tr>
        <td>Total Days: {{$total_days}} days</td>
        <td>Total Working Days: {{$total_working_days}} days</td>
    </tr>

    <tr>
        <td>Present Days: {{$present_days}} days</td>
        <td>Absent Days: {{$absent_days}} days</td>
    </tr>

    <tr>
        <td>Weekend Days: {{$weekend_holidays}} days</td>
        <td>Public Holiday: {{$public_holidays}} days</td>
    </tr>
    <tr>
        <td colspan="2">Public Holiday on Weekend: {{$public_holiday_on_weekend}} days</td>
    </tr>

    <tr>
        <td>Paid Leave: {{$paid_leave}} days</td>
        <td>UnPaid Leave: {{$unpaid_leave}} days</td>
    </tr>

    <tr>
        <td>Public Holiday Work Hour: {{$public_holiday_work_hour}} Hrs</td>
        <td>Weekend Work Hour: {{$weekend_work_hour}} Hrs</td>
    </tr>

    <tr>
        <td>Absent on Public Holiday: {{$absent_on_public_holiday}} days</td>
        <td>Present on Public Holiday: {{$present_on_public_holiday}} days</td>
    </tr>

    <tr>
        <td>Absent on Weekend: {{$absent_on_weekend}} days</td>
        <td>Present on Weekend: {{$present_on_weekend}} days</td>
    </tr>

    {{--    @if($suspension_days > 0)--}}
    <tr>
        <td>Suspension Days: {{$suspension_days}} days</td>
    </tr>
    {{--    @endif--}}
</table>
