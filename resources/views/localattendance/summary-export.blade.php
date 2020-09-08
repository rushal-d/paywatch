<table>
    <tr>
        <td>SN</td>
        <td>Staff Name</td>
        <td>Staff Central ID</td>
        <td>Branch ID</td>
        <td>Present Days</td>
        <td>Total Work Hour</td>
        <td>Absent on Holidays</td>
        <td>Weekend Work Hour</td>
        <td>Public Holiday Work Hour</td>
        <td>Suspense Days</td>
    </tr>

    @foreach($localattendances as $localattendance)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$localattendance['name']}}</td>
            <td>{{$localattendance['staff_central_id']}}</td>
            <td>{{$localattendance['main_id']}}</td>
            <td>{{$localattendance['present_days']}}</td>
            <td>{{round($localattendance['total_work_hour'])}}</td>
            <td>{{$localattendance['absent_on_weekend']+$localattendance['absent_on_public_holiday']-$localattendance['absent_on_pubic_holiday_on_weekend']}}</td>
            <td>{{round($localattendance['weekend_holiday_work_hour_for_payroll'])}}</td>
            <td>{{round($localattendance['public_holiday_work_hour_for_payroll'])}}</td>
            <td>{{round($localattendance['suspense_days'])}}</td>
        </tr>
    @endforeach
</table>
