<table class="table table-borderless table">
    <tr>
        <td>Staff Name : {{$staff->name_eng}}</td>
        <td>Staff Central Id : {{$staff->staff_central_id}}</td>
    </tr>
    <tr>
        <td>Date From : {{$input['from_date_np'] ?? ''}}</td>
        <td>Date To : {{$input['to_date_np'] ?? ''}}</td>
    </tr>

</table>
<table class="table table-bordered">
    <tr>
    <td><strong>SN</strong></td>
    <td><strong>Leave</strong></td>
    <td><strong>Fiscal Year</strong></td>
    <td><strong>Description</strong></td>
    <td><strong>Consumption</strong></td>
    <td><strong>Earned</strong></td>
    <td><strong>Balance</strong></td>
    </tr>
    <tbody>
    @foreach($leave_balances as $leave_balance)

        <tr>
            <td>{{$i++}}</td>
            <td>{{$leave_balance->leave->leave_name}}</td>
            <td>{{$leave_balance->fiscal->fiscal_code}}</td>
            <td>{{$leave_balance->description ?? ''}}</td>
            <td>{{$leave_balance->consumption ?? ''}}</td>
            <td>{{$leave_balance->earned ?? ''}}</td>
            <td>{{$leave_balance->balance ?? ''}}</td>
        </tr>

    @endforeach
    </tbody>

</table>
