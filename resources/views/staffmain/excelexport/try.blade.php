{{--
<table class="table table-bordered table-responsive" width="100%" cellspacing="0">
    <tr>
        <td>S.No.</td>
        <td>Branch</td>
        <td>Branch</td>
        <td>Job Type</td>
        <td>Working Position</td>
        <td>Staff Central ID</td>
        <td>Name</td>
        <td>Main ID</td>
        <td>Is Extra Allowance</td>
        <td>Extra in Dashain</td>
        <td>Temporary Date</td>
        <td>Permanent Date</td>
        <td>Acc Number</td>
        <td>PF Account Number</td>
        <td>Basic Salary</td>
        <td>Total Grade Amount</td>
        <td>Dearness Allowance Amount</td>
        <td>Special Allowance Amount</td>
        <td>Special Allowance Amount 2</td>
        <td>Risk Allowance Amount</td>
        <td>Total Salary</td>
        <td>Marital Status</td>
    </tr>
    @foreach($staffmains as $staffmain)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$staffmain->branch->office_name ?? null}}</td>
            <td>{{$staffmain->jobtype->jobtype_name ?? null}}</td>
            <td>{{$staffmain->jobposition->post_title ?? null}}</td>
            <td>{{$staffmain->staff_central_id}}</td>
            <td>{{$staffmain->name_eng}}</td>
            <td>{{$staffmain->main_id}}</td>
            <td>
                {{$staffmain->extra_allow ? 'Yes' : 'No'}}
            </td>
            <td>{{$staffmain->dashain_allow ? 'Yes' : 'No'}}</td>
            <td>{{$staffmain->temporary_con_date}}</td>
            <td>{{$staffmain->permanent_date_np}}</td>
            <td>{{$staffmain->acc_no}}</td>
            <td>{{$staffmain->profund_acc_no}}</td>
            @php
                $basicSalary = $staffmain->jobposition->basic_salary ?? 0;
            $totalGrade = $staffmain->total_grade_amount ?? 0;
            $dearnessAmount = $staffmain->dearness_allowance_amount ?? 0;
            $specialAllowance = $staffmain->special_allowance_amount ?? 0;
            $specialAllowance2 = $staffmain->special_allowance_2_amount ?? 0;
            $riskAllowance = $staffmain->risk_allowance_amount ?? 0;
            $totalSalary = $basicSalary + $totalGrade + $dearnessAmount + $specialAllowance + $specialAllowance2 + $riskAllowance;
            @endphp
            <td>{{$basicSalary}}</td>
            <td>{{$totalGrade}}</td>
            <td>{{$dearnessAmount}}</td>
            <td>{{$specialAllowance}}</td>
            <td>{{$specialAllowance2}}</td>
            <td>{{$riskAllowance}}</td>

            <td>{{$totalSalary}}</td>
            <td>
                @if($staffmain->marrid_stat == 1)
                    Single
                @elseif($staffmain->marrid_stat == 2)
                    Married
                @else
                @endif
            </td>
        </tr>
    @endforeach
</table>
--}}

<table class="table table-bordered table-responsive" width="100%" cellspacing="0">
    <tr>
        <td>S.No.</td>
        <td>Staff Central ID</td>
        <td>Name</td>
        <td>Branch ID</td>
        <td>Gender</td>
        <td>Is Bank</td>
        <td>Bank Name</td>
        <td>Bank Account Number</td>
        <td>Provident Fund Number</td>
        <td>Social Security Fund Number</td>
    </tr>
    @foreach($staffmains as $staffmain)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$staffmain->staff_central_id}}</td>
            <td>{{$staffmain->name_eng}}</td>
            <td>{{$staffmain->main_id}}</td>
            <td>{{$staffmain->Gender ? 'M' : 'F'}}</td>
            <td>{{empty($staffmain->bank_id) || empty($staffmain->acc_no) ? 'true' : 'false'}}</td>
            <td>{{$staffmain->bankInformation->bank_name ?? null}}</td>
            <td>{{$staffmain->acc_no}}</td>
            <td>{{$staffmain->profund_acc_no}}</td>
            <td>{{$staffmain->social_security_fund_acc_no}}</td>
        </tr>
    @endforeach
</table>
