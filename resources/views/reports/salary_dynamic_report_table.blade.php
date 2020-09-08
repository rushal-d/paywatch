<div class="table-scrollable dragscroll">

    <table class="table table-bordered table-responsive" width="100%" cellspacing="0">
        <tr>
            <td>S.No.</td>
            <td>Working Position</td>
            <td>Staff Central ID</td>
            <td>Staff Name</td>
            @if(in_array("present-days", $selectedClassesArray))
                <td class="present-days">Present Days</td>
            @endif
            @if(in_array("total-work-hour", $selectedClassesArray))
                <td class="total-work-hour">Total Work Hour</td>
            @endif
            @if(in_array("absent-on-holiday", $selectedClassesArray))
                <td class="absent-on-holiday">Absent On Holiday</td>
            @endif
            @if(in_array("weekend-work-hour", $selectedClassesArray))
                <td class="weekend-work-hour">Weekend Work Hour</td>
            @endif
            @if(in_array("public-holiday-work-hour", $selectedClassesArray))
                <td class="public-holiday-work-hour">Public Holiday Work</td>
            @endif
            @if(in_array("home-leave", $selectedClassesArray))
                <td class="home-leave">Home Leave</td>
            @endif
            @if(in_array("sick-leave", $selectedClassesArray))
                <td class="sick-leave">Sick Leave</td>
            @endif
            @if(in_array("pregnant-leave", $selectedClassesArray))
                <td class="pregnant-leave">Pregnant Leave</td>
            @endif
            @if(in_array("pregnant-care-leave", $selectedClassesArray))
                <td class="pregnant-care-leave">Pregnant Care Leave</td>
            @endif
            @if(in_array("funeral-leave", $selectedClassesArray))
                <td class="funeral-leave">Funeral Leave</td>
            @endif
            @if(in_array("substitute-leave", $selectedClassesArray))
                <td class="substitute-leave">Substitute Leave</td>
            @endif
            @if(in_array("leave-without-pay", $selectedClassesArray))
                <td class="leave-without-pay">Leave Without Pay</td>
            @endif
            @if(in_array("suspense-days", $selectedClassesArray))
                <td class="suspense-days">Suspense Days</td>
            @endif
            @if(in_array("redeem_home_leave", $selectedClassesArray))
                <td class="redeem_home_leave">Redeem Home Leave</td>
            @endif
            @if(in_array("redeem_sick_leave", $selectedClassesArray))
                <td class="redeem_sick_leave">Redeem Sick Leave</td>
            @endif
            @if(in_array('salary', $selectedClassesArray))
                <td class="salary">Salary</td>
            @endif
            @if(in_array("salary_hour_payable", $selectedClassesArray))
                <td class="salary_hour_payable">Salary Hour Payable</td>
            @endif
            @if(in_array("ot_hour_payable", $selectedClassesArray))
                <td class="ot_hour_payable">OT Hour Payable</td>
            @endif
            @if(in_array("social_security_tax_amount", $selectedClassesArray))
                <td class="social_security_tax_amount">Social Security Tax Amount</td>
            @endif
            @if(in_array("bank_paid_amount", $selectedClassesArray))
                <td class="bank_paid_amount">Bank Paid Amount</td>
            @endif
            @if(in_array("cash_paid_amount", $selectedClassesArray))
                <td class="cash_paid_amount">Cash Paid Amount</td>
            @endif
            @if(in_array("dearness_allowance", $selectedClassesArray))
                <td class="dearness_allowance">Dearness Allowance</td>
            @endif
            @if(in_array("other_allowance", $selectedClassesArray))
                <td class="other_allowance">Other Allowance</td>
            @endif
            @if(in_array("extra_allowance", $selectedClassesArray))
                <td class="extra_allowance">Extra Allowance</td>
            @endif

            @if(in_array("profund", $selectedClassesArray))
                <td class="profund">Profund</td>
            @endif

            @if(in_array("social_security_fund", $selectedClassesArray))
                <td class="social_security_fund ">Social Security Fund</td>
            @endif
            @if(in_array("sick_leave_redeem_amount", $selectedClassesArray))
                <td class="sick_leave_redeem_amount">Home/sick</td>
            @endif
            @if(in_array("incentive", $selectedClassesArray))
                <td class="incentive">Incentive</td>
            @endif
            @if(in_array("ot_amount", $selectedClassesArray))
                <td class="ot_amount">OT Amount</td>
            @endif
            @if(in_array("gross_payment", $selectedClassesArray))
                <td class="gross_payment">Gross Payment</td>
            @endif
            @if(in_array("levy", $selectedClassesArray))
                <td class="levy">Levy</td>
            @endif
            @if(in_array("loan_deducted_amount", $selectedClassesArray))
                <td class="loan_deducted_amount ">Loan Deducted
                    Amount
                </td>
            @endif
            @if(in_array("sundry_loans", $selectedClassesArray))
                <td class="sundry_loans">Sundry Loans</td>
            @endif
            @if(in_array("bank_name", $selectedClassesArray))
                <td class="bank_name">Bank Name</td>
            @endif
            @if(in_array("account_number", $selectedClassesArray))
                <td class="account_number">Account Number</td>
            @endif
            @if(in_array("cycode", $selectedClassesArray))
                <td class="cycode">Cycode</td>
            @endif
            @if(in_array("brCode", $selectedClassesArray))
                <td class="brCode">BrCode</td>
            @endif
            @if(in_array("transtype", $selectedClassesArray))
                <td class="transtype">Transtype</td>
            @endif
            @if(in_array("income-tax-amount", $selectedClassesArray))
                <td class="income-tax-amount">Income Tax Amount</td>
            @endif
            @if(in_array("net_payment", $selectedClassesArray))
                <td class="net_payment">Net Payment</td>
            @endif
        </tr>
        <?php
        $totalBankPaidAmount=0;
        $totalCashPaidAmount=0;
        ?>
        @foreach($payrollConfirms as $payrollConfirm)
            <?php
            $transBankStatement = $transBankStatements->where('staff_central_id', $payrollConfirm->staff_central_id)->first();
            ?>

            <tr>
                <td>{{$loop->iteration}}</td>

                <td>{{ $payrollConfirm->staff->jobtype->jobtype_name ?? ''}}</td>
                <td>{{$payrollConfirm->staff->staff_central_id ?? ''}}</td>
                <td>{{$payrollConfirm->staff->name_eng ?? ''}}</td>
                @if(in_array('present-days', $selectedClassesArray))
                    <td class="present-days">{{$payrollConfirm->present_days}}</td>
                @endif

                @if(in_array('total-work-hour', $selectedClassesArray))
                    <td class="total-work-hour">{{$payrollConfirm->total_worked_hours}}</td>
                @endif

                @if(in_array('absent-on-holiday', $selectedClassesArray))
                    <td class="absent-on-holiday">{{$payrollConfirm->days_absent_on_holiday}}</td>
                @endif

                @if(in_array('weekend-work-hour', $selectedClassesArray))
                    <td class="weekend-work-hour">{{$payrollConfirm->weekend_work_hours}}</td>
                @endif

                @if(in_array('public-holiday-work-hour', $selectedClassesArray))
                    <td class="public-holiday-work-hour">{{$payrollConfirm->public_holiday_work_hours}}</td>
                @endif

                @if(in_array('home-leave', $selectedClassesArray))
                    <td class="home-leave">{{$payrollConfirm->home_leave_taken}}</td>
                @endif

                @if(in_array('sick-leave', $selectedClassesArray))
                    <td class="sick-leave">{{$payrollConfirm->sick_leave_taken}}</td>
                @endif

                @if(in_array('pregnant-leave', $selectedClassesArray))
                    <td class="pregnant-leave">{{$payrollConfirm->maternity_leave_taken}}</td>
                @endif

                @if(in_array('pregnant-care-leave', $selectedClassesArray))
                    <td class="pregnant-care-leave">{{$payrollConfirm->maternity_care_leave_taken}}</td>
                @endif


                @if(in_array('funeral-leave', $selectedClassesArray))
                    <td class="funeral-leave">{{$payrollConfirm->funeral_leave_taken}}</td>
                @endif

                @if(in_array('substitute-leave', $selectedClassesArray))
                    <td class="substitute-leave">{{$payrollConfirm->substitute_leave_taken}}</td>
                @endif

                @if(in_array('leave-without-pay', $selectedClassesArray))
                    <td class="leave-without-pay">{{$payrollConfirm->unpaid_leave_taken}}</td>
                @endif

                @if(in_array('suspense-days', $selectedClassesArray))
                    <td class="suspense-days">{{$payrollConfirm->suspended_days}}</td>
                @endif

                @if(in_array('redeem_home_leave', $selectedClassesArray))
                    <td class="redeem_home_leave">{{$payrollConfirm->redeem_home_leave}}</td>
                @endif

                @if(in_array('redeem_sick_leave', $selectedClassesArray))
                    <td class="redeem_sick_leave">{{$payrollConfirm->redeem_sick_leave}}</td>
                @endif

                @if(in_array('salary', $selectedClassesArray))
                    <td class="salary">{{$payrollConfirm->basic_salary}}</td>
                @endif

                @if(in_array('salary_hour_payable', $selectedClassesArray))

                    <td class="salary_hour_payable">{{$payrollConfirm->salary_hour_payable}}</td>
                @endif

                @if(in_array("ot_hour_payable", $selectedClassesArray))
                    <td class="ot_hour_payable">{{$payrollConfirm->ot_hour_payable}}</td>
                @endif

                @if(in_array("social_security_tax_amount", $selectedClassesArray))
                    <?php
                    $socialSecurityTaxStatement = $socialSecurityTaxStatements->where('staff_central_id', $payrollConfirm->staff_central_id)->first();
                    ?>
                    <td class="social_security_tax_amount">{{$socialSecurityTaxStatement->tax_amount}}</td>
                @endif

                @if(in_array("bank_paid_amount", $selectedClassesArray))
                    @if(!empty($transBankStatement))
                        <?php
                        $totalBankPaidAmount += $payrollConfirm->net_payable;
                        ?>
                        <td class="bank_paid_amount">{{$payrollConfirm->net_payable}}</td>
                    @else
                        <td></td>
                    @endif
                @endif

                @if(in_array("cash_paid_amount", $selectedClassesArray))
                    @if(empty($transBankStatement))
                        <?php
                        $totalCashPaidAmount += $payrollConfirm->net_payable;
                        ?>
                        <td class="cash_paid_amount">{{$payrollConfirm->net_payable}}</td>
                    @else
                        <td class="cash_paid_amount"></td>
                    @endif
                @endif

                @if(in_array('dearness_allowance', $selectedClassesArray))
                    <td class="dearness_allowance">{{$payrollConfirm->dearness_allowance}}</td>
                @endif

                @if(in_array('other_allowance', $selectedClassesArray))
                    <td class="other_allowance">{{$payrollConfirm->special_allowance}}</td>
                @endif

                @if(in_array('extra_allowance', $selectedClassesArray))
                    <td class="extra_allowance">{{$payrollConfirm->extra_allowance}}</td>
                @endif

                @if(in_array('profund', $selectedClassesArray))
                    <td class="profund">{{$payrollConfirm->pro_fund}}</td>
                @endif


                @if(in_array('social_security_fund', $selectedClassesArray))

                    <td class="social_security_fund">{{$payrollConfirm->social_security_fund_amount}}</td>
                @endif

                @if(in_array('sick_leave_redeem_amount', $selectedClassesArray))
                    <td class="sick_leave_redeem_amount">{{$payrollConfirm->home_sick_redeem_amount}}</td>
                @endif
                @if(in_array('incentive', $selectedClassesArray))
                    <td class="incentive">{{$payrollConfirm->incentive}}</td>
                @endif
                @if(in_array('ot_amount', $selectedClassesArray))
                    <td class="ot_amount">{{$payrollConfirm->ot_amount}}</td>
                @endif
                @if(in_array('gross_payment', $selectedClassesArray))
                    <td class="gross_payment">{{$payrollConfirm->gross_payable}}</td>
                @endif
                @if(in_array('levy', $selectedClassesArray))
                    <td class="levy">{{$payrollConfirm->levy_amount}}</td>
                @endif
                @if(in_array('loan_deducted_amount', $selectedClassesArray))
                    <td class="loan_deducted_amount">{{$payrollConfirm->loan_payment}}</td>
                @endif
                @if(in_array('sundry_loans', $selectedClassesArray))
                    <td class="sundry_loans">{{$payrollConfirm->sundry_cr - $payrollConfirm->sundry_dr}}</td>
                @endif

                @if(in_array('bank_name', $selectedClassesArray))
                    @if(!empty($transBankStatement))
                        <td class="bank_name">{{$transBankStatement->bank->bank_name ?? ''}}</td>
                    @else
                        <td class="bank_name"></td>
                    @endif
                @endif

                @if(in_array('account_number', $selectedClassesArray))
                    @if(!empty($transBankStatement))
                        <td class="account_number">{{$transBankStatement->acc_no ?? ''}}</td>
                    @else
                        <td class="account_number"></td>
                    @endif
                @endif

                @if(in_array('cycode', $selectedClassesArray))
                    <td class="cycode">NPR</td>
                @endif


                @if(in_array('brCode', $selectedClassesArray))
                    @if(!empty($transBankStatement))
                        <td class="brCode">{{\App\Helpers\FunctionHelper::getBrCodeFromAccountNumber($transBankStatement->acc_no ?? null)}}</td>
                    @else
                        <td class="brCode"></td>
                    @endif
                @endif
                @if(in_array('transtype', $selectedClassesArray))
                    <td class="transtype">C</td>
                @endif
                @if(in_array('income-tax-amount', $selectedClassesArray))
                    <td class="income-tax-amount">{{$payrollConfirm->tax}}</td>
                @endif
                @if(in_array('net_payment', $selectedClassesArray))
                    @if(!empty($transBankStatement))
                        <td class="net_payment">{{$transBankStatement->total_payment}}</td>
                    @else
                        <td class="net_payment">{{$payrollConfirm->net_payable}}</td>
                    @endif
                @endif
            </tr>
        @endforeach
        <tr>
            <td colspan="4">Total</td>
            @if(in_array('present-days', $selectedClassesArray))
                <td class="present-days">-</td>
            @endif

            @if(in_array('total-work-hour', $selectedClassesArray))
                <td class="total-work-hour">-</td>
            @endif

            @if(in_array('absent-on-holiday', $selectedClassesArray))
                <td class="absent-on-holiday">-</td>
            @endif

            @if(in_array('weekend-work-hour', $selectedClassesArray))
                <td class="weekend-work-hour">-</td>
            @endif

            @if(in_array('public-holiday-work-hour', $selectedClassesArray))
                <td class="public-holiday-work-hour">-</td>
            @endif

            @if(in_array('home-leave', $selectedClassesArray))
                <td class="home-leave">-</td>
            @endif

            @if(in_array('sick-leave', $selectedClassesArray))
                <td class="sick-leave">-</td>
            @endif

            @if(in_array('pregnant-leave', $selectedClassesArray))
                <td class="pregnant-leave">-</td>
            @endif

            @if(in_array('pregnant-care-leave', $selectedClassesArray))
                <td class="pregnant-care-leave">-</td>
            @endif


            @if(in_array('funeral-leave', $selectedClassesArray))
                <td class="funeral-leave">-</td>
            @endif

            @if(in_array('substitute-leave', $selectedClassesArray))
                <td class="substitute-leave">-</td>
            @endif

            @if(in_array('leave-without-pay', $selectedClassesArray))
                <td class="leave-without-pay">-</td>
            @endif

            @if(in_array('suspense-days', $selectedClassesArray))
                <td class="suspense-days">-</td>
            @endif

            @if(in_array('redeem_home_leave', $selectedClassesArray))
                <td class="redeem_home_leave">-</td>
            @endif

            @if(in_array('redeem_sick_leave', $selectedClassesArray))
                <td class="redeem_sick_leave">-</td>
            @endif

            @if(in_array('salary', $selectedClassesArray))
                <td class="salary">{{$payrollConfirms->sum('basic_salary')}}</td>
            @endif

            @if(in_array('salary_hour_payable', $selectedClassesArray))
                <td class="salary_hour_payable">{{$payrollConfirms->sum('salary_hour_payable')}}</td>
            @endif

            @if(in_array("ot_hour_payable", $selectedClassesArray))
                <td class="ot_hour_payable">{{$payrollConfirms->sum('ot_hour_payable')}}</td>
            @endif

            @if(in_array("social_security_tax_amount", $selectedClassesArray))
                <td class="social_security_tax_amount">{{$socialSecurityTaxStatements->sum('tax_amount')}}</td>
            @endif

            @if(in_array("bank_paid_amount", $selectedClassesArray))
                <td class="bank_paid_amount">{{$totalBankPaidAmount}}</td>
            @endif

            @if(in_array("cash_paid_amount", $selectedClassesArray))
                <td class="cash_paid_amount">{{$totalCashPaidAmount}}</td>
            @endif

            @if(in_array('dearness_allowance', $selectedClassesArray))
                <td class="dearness_allowance">{{$payrollConfirms->sum('dearness_allowance')}}</td>
            @endif

            @if(in_array('other_allowance', $selectedClassesArray))
                <td class="other_allowance">{{$payrollConfirms->sum('special_allowance')}}</td>
            @endif

            @if(in_array('extra_allowance', $selectedClassesArray))
                <td class="extra_allowance">{{$payrollConfirms->sum('extra_allowance')}}</td>
            @endif

            @if(in_array('profund', $selectedClassesArray))
                <td class="profund">{{$payrollConfirms->sum('pro_fund')}}</td>
            @endif


            @if(in_array('social_security_fund', $selectedClassesArray))
                <td class="social_security_fund">{{$payrollConfirms->sum('social_security_fund_amount')}}</td>
            @endif

            @if(in_array('sick_leave_redeem_amount', $selectedClassesArray))
                <td class="sick_leave_redeem_amount">{{$payrollConfirms->sum('home_sick_redeem_amount')}}</td>
            @endif
            @if(in_array('incentive', $selectedClassesArray))
                <td class="incentive">{{$payrollConfirms->sum('incentive')}}</td>
            @endif
            @if(in_array('ot_amount', $selectedClassesArray))
                <td class="ot_amount">{{$payrollConfirms->sum('ot_amount')}}</td>
            @endif
            @if(in_array('gross_payment', $selectedClassesArray))
                <td class="gross_payment">{{$payrollConfirms->sum('gross_payable')}}</td>
            @endif
            @if(in_array('levy', $selectedClassesArray))
                <td class="levy">{{$payrollConfirms->sum('levy_amount')}}</td>
            @endif
            @if(in_array('loan_deducted_amount', $selectedClassesArray))
                <td class="loan_deducted_amount">{{$payrollConfirms->sum('loan_payment')}}</td>
            @endif
            @if(in_array('sundry_loans', $selectedClassesArray))
                <td class="sundry_loans">{{$payrollConfirms->sum('sundry_cr ')- $payrollConfirms->sum('sundry_dr')}}</td>
            @endif

            @if(in_array('bank_name', $selectedClassesArray))
                    <td class="bank_name">-</td>
            @endif

            @if(in_array('account_number', $selectedClassesArray))
                    <td class="account_number">-</td>
            @endif

            @if(in_array('cycode', $selectedClassesArray))
                <td class="cycode">-</td>
            @endif


            @if(in_array('brCode', $selectedClassesArray))
                    <td class="brCode">-</td>
            @endif
            @if(in_array('transtype', $selectedClassesArray))
                <td class="transtype">-</td>
            @endif
            @if(in_array('income-tax-amount', $selectedClassesArray))
                <td class="income-tax-amount">{{$payrollConfirms->sum('tax')}}</td>
            @endif
            @if(in_array('net_payment', $selectedClassesArray))
                    <td class="net_payment">{{$payrollConfirms->sum('net_payable')}}</td>
            @endif
        </tr>
    </table>
</div>
