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
        <td>Is Extra Allowance</td>
        <td>Extra in Dashain</td>
        <td>Basic Salary</td>
        <td>Previous Grades</td>
        <td>Current Grade Amount</td>
        <td>Dearness Allowance Amount</td>
        <td>Risk Allowance Amount</td>
        <td>Special Allowance Amount</td>
        <td>Misc Allowance Amount 2</td>
        <td>Home Leave</td>
        <td>Sick Leave</td>
        <td>Maternity Leave</td>
        <td>Maternity Care Leave</td>
        <td>Funeral Leave</td>
        <td>Account Number</td>
        <td>Profund Account NUmber</td>
        <td>Social Security Account NUmber</td>
    </tr>
    @foreach($staffmains as $staffmain)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$staffmain->staff_central_id}}</td>
            <td>{{$staffmain->name_eng}}</td>
            <td>{{$staffmain->main_id}}</td>
            @if($staffmain->Gender == 1)
                <td>M</td>
            @elseif($staffmain->Gender == 2)
                <td>F</td>
            @else
                <td>O</td>
            @endif
            <td>
                {{$staffmain->extra_allow ? 'Yes' : 'No'}}
            </td>
            <td>{{$staffmain->dashain_allow ? 'Yes' : 'No'}}</td>
            @php
                $basicSalary = $staffmain->jobposition->basic_salary ?? 0;
            $totalGrade = $staffmain->total_grade_amount ?? 0;
            $dearnessAmount = $staffmain->dearness_allowance_amount ?? 0;
            $specialAllowance = $staffmain->special_allowance_amount ?? 0;
            $specialAllowance2 = $staffmain->special_allowance_2_amount ?? 0;
            $riskAllowance = $staffmain->risk_allowance_amount ?? 0;
            $totalSalary = $basicSalary + $totalGrade + $dearnessAmount + $specialAllowance + $specialAllowance2 + $riskAllowance;
            @endphp
            <td>{{$staffmain->latestsalary->basic_salary ?? 0}}</td>
            <td>{{$staffmain->latestsalary->total_grade_amount ?? 0}}</td>
            <td>{{$staffmain->latestsalary->add_grade_this_fiscal_year ?? 0}}</td>
            <td>{{$dearnessAmount}}</td>
            <td>{{$riskAllowance}}</td>
            <td>{{$specialAllowance}}</td>
            <td>{{$specialAllowance2}}</td>

            <td>{{$staffmain->homeLeaveBalanceLast->balance ?? 0}}</td>
            <td>{{$staffmain->sickLeaveBalanceLast->balance ?? 0}}</td>
            <td>{{$staffmain->maternityLeaveBalanceLast->balance ?? 0}}</td>
            <td>{{$staffmain->maternityCareLeaveBalanceLast->balance ?? 0}}</td>
            <td>{{$staffmain->funeralLeaveBalanceLast->balance ?? 0}}</td>
            <td>'{{$staffmain->acc_no ?? null}}</td>
            <td>{{$staffmain->profund_acc_no ?? null}}</td>
            <td>{{$staffmain->social_security_fund_acc_no ?? null}}</td>

        </tr>
    @endforeach
</table>
