<?php

namespace App\Helpers;

class DynamicReportHelper
{
    public static function getAttendanceInformationClass()
    {
        $attendanceInformationClass = [
            'present-days' => 'Present Days',
            'total-work-hour' => 'Total Work Hour',
            'absent-on-holiday' => 'Absent on Holiday',
            'weekend-work-hour' => 'Weekend Work Hour',
            'public-holiday-work-hour' => 'Public Holiday Work Hour',
            'public-holiday-weekend-work-hour' => 'Public Holiday Weekend Work Hour',
        ];

        return $attendanceInformationClass;
    }

    public static function getLeaveClass()
    {
        $leaveClass = [
            'home-leave' => 'Home Leave',
            'sick-leave' => 'Sick Leave',
            'substitute-leave' => 'Substitute Leave',
            'funeral-leave' => 'Funeral Leave',
            'pregnant-leave' => 'Maternity Leave',
            'pregnant-care-leave' => 'Maternity Care Leave',
            'leave-without-pay' => 'Leave Without Pay',
            'suspense-days' => 'Suspense Days',
        ];

        return $leaveClass;
    }

    public static function getPayableParameters()
    {
        $payableParameters = [
            "redeem_home_leave" => "Redeem Home Leave",
            "redeem_sick_leave" => "Redeem Sick Leave",
            "salary" => "Salary",
            "dearness_allowance" => "Dearness Allowance",
            "other_allowance" => "Other Allowance",
            "extra_allowance" => "Extra Allowance",
            "profund" => "Pro. Fund",
            "social_security_fund_" => "Social Security Fund ",
            "social_security_" => "Social Security 1%",
            "sick_leave_redeem_amount" => "Home/Sick Leave Redeem Amount",
            "incentive" => "Incentive",
            "ot_amount" => "OT Amount",
            "gross_payment" => "Gross Payment",
            "levy" => "levy Amount",
            "loan_deducted_amount" => "Loan Deducted Amount ",
            "sundry_loans" => "Sundry Differences",
            "net_payment" => "Net Payment",
            "salary_hour_payable" => "Salary Hour Payable",
            "ot_hour_payable" => "OT Hour Payable",
            "social_security_tax_amount" => "Social Security Tax Amount",
            "bank_paid_amount" => "Bank Paid Amount",
            "cash_paid_amount" => "Cash Paid Amount",
        ];

        return $payableParameters;
    }

    public static function getBankInformation()
    {
        $bankInformation = [
            "bank_name" => "Bank Name",
            "account_number" => "Account Number",
            "cycode" => "CYCODE",
            "brCode" => "brCode",
            "transtype" => "transtype",
        ];

        return $bankInformation;
    }

    public static function getStatementClass()
    {
        $statementClass = [
            "income-tax-amount" => "Income Tax Amount",
        ];

        return $statementClass;
    }
}
