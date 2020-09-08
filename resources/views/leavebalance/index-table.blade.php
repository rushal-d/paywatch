<table class="table table-responsive table-striped table-hover table-all" width="100%" cellspacing="0">
    <tbody>
    <tr align="left">
        {{--  <th><input type="checkbox" class="check-all"></th>--}}
        <td><strong>Branch ID</strong></td>
        <td><strong>Staff Name</strong></td>
        @foreach($leaves as $leave)
            <td><strong>{{$leave->leave_name ?? ''}}</strong></td>
        @endforeach
        @if(!isset($_GET['export']))
            <td><strong>View</strong></td>
        @endif
    </tr>
    @foreach($staffs as $staff)
        <tr>
            <td>{{$staff->main_id ?? 'N/A'}}</td>
            <td>{{$staff->name_eng ?? 'N/A'}}</td>

            @foreach($leaves as $leave)
                <td>
                    @php $balance=$staff->leaveBalance->where('leave_id','=',$leave->leave_id)->last()@endphp
                    @if(!empty($balance))
                        <a target="_blank"
                           href="{{ route('leavebalancestatementshow',['leave_type' => $balance->leave_id, 'staff_central_id' => $balance->staff_central_id]) }}">
                            {{round(($balance->balance ?? 0),3) ?? ''}}
                        </a>
                    @else
                        N/A
                    @endif
                </td>
            @endforeach
            @if(!isset($_GET['export']))
                <td>
                    <a target="_blank"
                       href="{{ route('leavebalancestatementshow',['staff_central_id' => $staff->id]) }}">
                        <i class="fa fa-eye"></i>
                    </a>
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
