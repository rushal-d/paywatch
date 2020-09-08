<table class="attendance-information">
    <tr>
        <td><b>Total Staffs: </b>
            {{$totalStaffsCount}}
        </td>
        <td><b>Branch:</b>
            {{$branchName}}
        </td>
    </tr>
    <tr>
        <td><b>Total Presents: </b>
            {{$presentStaffsCount}}
        </td>
        <td><b>Department:</b>
            {{$departmentName}}
        </td>
    </tr>
    <tr>
        <td><b>Total Absents:</b>
            {{$absentStaffsCount}}
        </td>
        <td><b>Shift Name:</b>
            {{$shiftName}}
        </td>
    </tr>
    <tr>
        {{--        <td width="80%"><b>Total Hours Worked:</b>--}}
        {{--            {{$totalHoursWorked . ' ' . str_plural('hour', $totalHoursWorked)}}--}}
        {{--        </td>--}}
        <td><b>Date:</b>
            {{$_GET['date'] ?? ''}}
        </td>
    </tr>
</table>

@php
    $staff_edit_permission=false;
@endphp
@permission('localattendance-edit')
@php
    $staff_edit_permission=true;
@endphp
@endpermission
<table class="payroll_table" border="1px">

    <tr align="center">
        {{--            <th>Local Att. ID</th>--}}
        <td><b>Staff ID</b></td>
        <td><b>Staff Name</b></td>
        @if(isset($_GET['show_phone_number']) && $_GET['show_phone_number']==1)
            <td><b>Phone Number</b></td>
        @endif
        <td><b>Branch</b></td>
        <td><b>Shift</b></td>
        <td><b>Punch In</b></td>
        <td><b>Punch Out</b></td>
        <td><b>Lunch Out</b></td>
        <td><b>Lunch In</b></td>
        <td><b>Tiffin Out</b></td>
        <td><b>Tiffin In</b></td>
        <td><b>Personal Out</b></td>
        <td><b>Personal In</b></td>
        {{--            <td><b>Total Work Hour</b></td>--}}
        <td><b>Total Work Time</b></td>
        <td><b>Modified By</b></td>
    </tr>
    <tbody>
    @foreach($localattendances as $localattendance)

        <?php
        $route = route('localattendance-edit', ['id' => $localattendance->id]);
        ?>

        <tr class="{{$localattendance['add_classes']}}">
            {{--                <td>{{$localattendance->id ?? ''}}</td>--}}
            <td>{{$localattendance->staff->main_id ?? ''}}</td>
            @if($staff_edit_permission && !isset($_GET['export']))
                <td>
                    <a href="{{$route}}" target="_blank">{{$localattendance->staff->name_eng ?? ''}}</a>
                </td>
            @else
                <td>{{$localattendance->staff->name_eng ?? ''}}</td>
            @endif
            @if(isset($_GET['show_phone_number']) && $_GET['show_phone_number']==1)
                <td>{{$localattendance->staff->phone_number ?? ''}}</td>
            @endif

            <td>{{$localattendance->branch->office_name ?? ''}}</td>
            <td>{{$localattendance->staff->shift->shift_name ?? ''}}</td>
            <td>{{!(empty($localattendance->punchin_datetime)) ? date('h:i a',strtotime($localattendance->punchin_datetime)) : ''}}</td>
            <td>{{!(empty($localattendance->punchout_datetime)) ? date('h:i a',strtotime($localattendance->punchout_datetime)) : ''}}</td>
            <td>{{ !(empty($localattendance->lunchout_datetime)) ? date('h:i a',strtotime($localattendance->lunchout_datetime)) : '' }}</td>
            <td>{{ !(empty($localattendance->lunchin_datetime)) ? date('h:i a',strtotime($localattendance->lunchin_datetime)) : '' }}</td>
            <td>{{ !(empty($localattendance->tiffinout_datetime)) ? date('h:i a',strtotime($localattendance->tiffinout_datetime)) : '' }}</td>
            <td>{{ !(empty($localattendance->tiffinin_datetime)) ? date('h:i a',strtotime($localattendance->tiffinin_datetime)) : '' }}</td>
            <td>{{ !(empty($localattendance->personalout_datetime)) ? date('h:i a',strtotime($localattendance->personalout_datetime)) : '' }}</td>
            <td>{{ !(empty($localattendance->personalin_datetime)) ? date('h:i a',strtotime($localattendance->personalin_datetime)) : '' }}</td>
            <td>{{\App\Helpers\DateHelper::convertHourToHourAndMinutesFormat($localattendance->total_work_hour)}}</td>
            <td>{{$localattendance->last_modified_by ?? ''}}</td>
        </tr>
    @endforeach

    @foreach($absentStaffs as $absentStaff)
        <?php
        $route = route('localattendance-create', [
            'staff_central_id' => $absentStaff->id,
            'branch_id' => $absentStaff->branch->office_id,
            'attendance_date_np' => request('date')
        ]);
        ?>
        <tr>

            <td>{{$absentStaff->main_id ?? ''}}</td>
            @if($staff_edit_permission && !isset($_GET['export']))
                <td><a href="{{$route}}" target="_blank">{{$absentStaff->name_eng ?? ''}}</a></td>
            @else
                <td>{{$absentStaff->name_eng ?? ''}}</td>
            @endif
            @if(isset($_GET['show_phone_number']) && $_GET['show_phone_number']==1)
                <td>{{$localattendance->staff->phone_number ?? ''}}</td>
            @endif
            <td>{{$absentStaff->branch->office_name ?? ''}}</td>
            <td>{{$absentStaff->shift->shift_name ?? ''}}</td>
            <td colspan="10" class="{{$absentStaff->status}}">{{$absentStaff->status}}</td>
        </tr>
    @endforeach
    </tbody>
</table>


