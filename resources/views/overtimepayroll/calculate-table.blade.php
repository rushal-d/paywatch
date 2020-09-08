
<table class="table-bordered table">
    <tr>
        <td><b>SN</b></td>
        <td><b>Staff Name</b></td>
        <td><b>Salary</b></td>
        <td><b>Grade</b></td>
        <td><b>Total</b></td>
        <td><b>Hours</b></td>
        <td><b>Salary Per Hour</b></td>
        <td><b>Total Amount</b></td>
        <td><b>OT 1.5% Addition</b></td>
        <td><b>Gross Amount</b></td>
        <td><b>Amount Sent To Bank</b></td>
    </tr>
    @foreach($overtimeRecords as $record)
        <tr>
            <td>
                {{$loop->iteration}}
            </td>
            <td>{{$record['staff_name']}}</td>
            <td>{{$record['basic_salary']}}</td>
            <td>{{$record['grade_amount']}}</td>
            <td>{{$record['total_payable']}}</td>
            <td>{{$record['overtime_work_hour']}}</td>
            <td>{{$record['payble_per_hour']}}</td>
            <td>{{$record['total_amount']}}</td>
            <td>{{$record['ot_rate']}}</td>
            <td>{{$record['gross_amount']}}</td>
            <td></td>
        </tr>
    @endforeach
    <tr>
        <td colspan="7">Total</td>
        <td>{{$total_amount}}</td>
        <td></td>
        <td>{{$gross_amount}}</td>
        <td></td>
    </tr>
</table>
