

@foreach($all_attendance_data as $attendanceData)
    <h5 align="center">{{$attendanceData['name']}}</h5>

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
            <td><b>Total Work Hour</b></td>
            <td><b>Status</b></td>
        </tr>

        @foreach($attendanceData['attendance'] as $overtime_data)
            <tr class="@foreach ($overtime_data['add_classes'] as $class) {{$class}} @endforeach">
                <td>{{$loop->iteration}}</td>
                <td>{{\App\Helpers\BSDateHelper::BsToAd('-',$overtime_data['date'])}}</td>
                <td>{{$overtime_data['date']}}</td>
                <td>{{$overtime_data['lateIn'] ?? ''}}</td>
                <td>{{$overtime_data['punchin'] ?? ''}}</td>
                <td>{{$overtime_data['punch_out'] ?? ''}}</td>
                <td>{{$overtime_data['earlyOut'] ?? ''}}</td>
                <td>{{App\Helpers\DateHelper::convertHourToHourAndMinutesFormat($overtime_data['overtime_hour'] ?? 0 )}} </td>
                <td>{{App\Helpers\DateHelper::convertHourToHourAndMinutesFormat($overtime_data['total_work_hour'] ?? 0 )}} </td>
                <td>{{$overtime_data['status'] ?? null}}</td>
            </tr>
        @endforeach
       {{-- <tr>
            <td colspan="2">Total Work Hour</td>
            <td>{{App\Helpers\DateHelper::convertHourToHourAndMinutesFormat($total_overtime_work_hour ?? 0 )}}
                / {{round($total_overtime_work_hour,2)}} hrs
            </td>
            <td></td>
        </tr>--}}

    </table>

@endforeach
