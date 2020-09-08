<table class="table table-bordered table-responsive" cellspacing="0">
    <tr>
        <td><b>Branch: </b></td>
        <td>{{ $payroll_details->branch->office_name }}</td>
        <td><b>Fiscal Year: </b></td>
        <td>{{ $payroll_details->fiscalyear->fiscal_code }}</td>
        <td><b>Salary Month: </b></td>
        <td>{{ \App\Helpers\BSDateHelper::_get_nepali_month($payroll_details->salary_month) }}</td>
        <td><b>Date From: </b></td>
        <td>{{  $payroll_details->from_date_np }}</td>
        <td><b>Date To: </b></td>
        <td>{{  $payroll_details->to_date_np }}</td>
        <td><b>Total Days: </b></td>
        <td>{{ $payroll_details->total_days }}</td>
        <td><b>Public Holidays: </b></td>
        <td>{{ $payroll_details->total_public_holidays }}</td>
    </tr>
    <tr>
        <td>S.No.</td>
        <td>Staff Central ID</td>
        <td>Staff Name</td>
        <td>Branch ID</td>
        <td>Working Position</td>
        <td>Min Work Hour</td>
        <td>Tax Code</td>
        <td>Present Days</td>
        <td>Total Worked Hours</td>
        <td>साप्ताहिक/सार्वजनिक बिदामा अनुपस्थिती (Days)</td>
        <td>WeekEnd Holiday (Hours)</td>
        <td>Public Holiday (Hours)</td>
        <td>घर बिदा</td>
        <td>बिरामी बिदा</td>
        <td>प्रसुती बिदा</td>
        <td>प्रसुती स्याहार बिदा</td>
        <td>किरिया बिदा</td>
        <td>सट्टा बिदा</td>
        <td>बेतलबी बिदा</td>
        <td>गयल / निलम्बन</td>

        <td>घर बिदा सट्टा तलब भुक्तानी</td>
        <td>बिरामी बिदा सट्टा तलब भुक्तानी</td>
        <td>संचित घर बिदा</td>
        <td>संचित बिरामी बिदा</td>
        <td>संचित सट्टा बिदा</td>

        <td>Salary Hours Payable</td>
        <td>OT Hours Payable</td>
        <td>Salary</td>
        <td>Dearness Allowance</td>
        <td>Other  Allowances</td>
        <td>Extra Allowance</td>
        <td>Pro. Fund</td>
        <td>Gratituity Amount</td>
        <td>Social Security Fund Amount</td>
        <td>Home / Sick Leaves</td>
        <td>Incentive</td>
        <td>OT</td>
        <td>Outstation Facility</td>

        <td>Gross Payment</td>
        <td>लेवि रकम</td>
        <td>कर्जाको मासिक असुली रकम ( - )</td>
        <td>असुली गर्नुपर्ने अग्रिम भुक्तानी / विविध रकम ( - )</td>
        <td>भुक्तानी गर्नुपर्ने विविध रकम ( + )</td>
        <td>गत महिनाको फरक तलब (+/-)</td>
        <td>Tax Amount</td>
        <td>Net Payment</td>
        <td>यसै महिनाको भुक्तानी भईसकेको तलब रकम</td>
        <td>Diff. Amount</td>
        <td>बैंक खाताको सट्टा नगदीमा भुक्तानी गर्नु परेमा Cash लेख्ने</td>
        <td>Remarks</td>
    </tr>

    @foreach($details as $data)
        <tr>
            <td>{{$i++}}</td>
            <td>{{$data->staff->staff_central_id}}</td>
            <td>{{$data->staff->name_eng}}</td>
            <td>{{$data->staff->main_id}}</td>
            <td>{{$data->staff->jobtype->jobtype_name?? ''}}</td>
            <td>{{$data->min_work_hour}}</td>
            <td>{{($data->staff->marrid_stat==1) ?'Couple': 'Single'}}</td>
            <td>{{$data->present_days}}</td>
            <td>{{$data->total_worked_hours}}</td>
            <td>{{$data->days_absent_on_holiday}}</td>
            <td>{{$data->weekend_work_hours}}</td>
            <td>{{$data->public_holiday_work_hours}}</td>
            <td>{{$data->home_leave_taken}}</td>
            <td>{{$data->sick_leave_taken}}</td>
            <td>{{$data->maternity_leave_taken}}</td>
            <td>{{$data->maternity_care_leave_taken}}</td>
            <td>{{$data->funeral_leave_taken}}</td>
            <td>{{$data->substitute_leave_taken}}</td>
            <td>{{$data->unpaid_leave_taken}}</td>
            <td>{{$data->suspended_days}}</td>
            <td>{{$data->redeem_home_leave}}</td>
            <td>{{$data->redeem_sick_leave}}</td>
            <td>{{$data->useable_home_leave}}</td>
            <td>{{$data->useable_sick_leave}}</td>
            <td>{{$data->useable_substitute_leave}}</td>
            <td>{{$data->salary_hour_payable}}</td>
            <td>{{$data->ot_hour_payable}}</td>
            <td>{{$data->basic_salary}}</td>
            <td>{{$data->dearness_allowance}}</td>
            <td>{{$data->special_allowance}}</td>
            <td>{{$data->extra_allowance}}</td>
            <td>{{$data->pro_fund}}</td>
            <td>{{$data->gratuity_amount}}</td>
            <td>{{$data->social_security_fund_amount}}</td>
            <td>{{$data->home_sick_redeem_amount}}</td>
            <td>{{$data->incentive}}</td>
            <td>{{$data->ot_amount}}</td>
            <td>{{$data->outstation_facility_amount ?? '-'}}</td>
            <td>{{$data->gross_payable}}</td>
            <td>{{$data->levy_amount}}</td>

            <td>{{$data->loan_payment}}</td>
            <td>{{$data->sundry_dr}}</td>
            <td>{{$data->sundry_cr}}</td>
            <td></td>
            <td>{{$data->tax}}</td>
            <td>{{$data->net_payable}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$data->remarks}}</td>
        </tr>

    @endforeach
</table>
