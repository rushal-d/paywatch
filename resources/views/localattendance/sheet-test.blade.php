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
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>=INDEX('Attendance Sheet'!E$2:'Attendance Sheet'!E$400,MATCH('Payroll Excel'!$C2,'Attendance Sheet'!$C$2:'Attendance Sheet'!$C$400,0))</td>
    </tr>
</table>
