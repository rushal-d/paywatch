<h2 align="center">{{$organization->organization_name ?? 'Organization Name'}}</h2>
<h5 align="center">Staff Overtime Detail</h5>

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
        <td><b>Overtime From:</b>{{$_GET['from_date_np'] ?? ''}}</td>
        <td><b>Overtime To:</b>{{$_GET['to_date_np'] ?? ''}}</td>
    </tr>
</table>

<table class="payroll_table">
    <tr>
        <td><b>SN</b></td>
        <td><b>Date (AD)</b></td>
        <td><b>Date (BS)</b></td>
        <td><b>Late In</b></td>
        <td><b>Punch In Time</b></td>
        <td><b>Punch Out Time</b></td>
        <td><b>Early Out</b></td>
        <td><b>Overtime Work Hour</b></td>
        <td><b>Status</b></td>
    </tr>

    @foreach($overtime_datas as $overtime_data)
        <tr class="@foreach ($overtime_data['add_classes'] as $class) {{$class}} @endforeach">
            <td>{{$loop->iteration}}</td>
            <td>{{\App\Helpers\BSDateHelper::BsToAd('-',$overtime_data['date'])}}</td>
            <td>{{$overtime_data['date']}}</td>
            <td>{{$overtime_data['lateIn'] ?? ''}}</td>
            <td>{{$overtime_data['punchin'] ?? ''}}</td>
            <td>{{$overtime_data['punch_out'] ?? ''}}</td>
            <td>{{$overtime_data['earlyOut'] ?? ''}}</td>
            <td>{{App\Helpers\DateHelper::convertHourToHourAndMinutesFormat($overtime_data['overtime_hour'] ?? 0 )}} </td>
            <td>{{$overtime_data['status'] ?? null}}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="2">Total Work Hour</td>
        <td>{{App\Helpers\DateHelper::convertHourToHourAndMinutesFormat($total_overtime_work_hour ?? 0 )}}
            / {{round($total_overtime_work_hour,2)}} hrs
        </td>
        <td></td>
    </tr>

</table>
