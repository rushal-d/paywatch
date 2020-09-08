<?php
return [
    'kb_url' => 'http://3dprintnepal.com/kbbackend/public/index.php',
    'social_security_fund' => 1,
    'gratuity_default' => 10,
    'profund_default' => 10,
    'contribution_default' => 10,
    'records_per_page' => 50,
    'working_hour' => 8,
    'max_working_hour' => 8,
    'max_working_hour_for_holiday' => 8,
    'records_per_page_options' => array(
        '20' => 20,
        '30' => 30,
        '50' => 50,
        '80' => 80,
        '100' => 100,
        '150' => 150,
        '200' => 200,
        '500' => 500
    ),
    'gender' => [
        1 => 'Male',
        2 => 'Female',
        3 => 'Other',
    ],
    'status_options' => array(
        '0' => 'Deactivate',
        '1' => 'Activate'
    ),
    'tds_options' => array(
        '0' => 'Single',
        '1' => 'Couple'
    ),
    'tds_slabs' => array(
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5'
    ),

    // this is the last slab government has provided
    'tds_last_slab_number' => '5',

    'allowance_options' => array(
        '0' => 'Dearness Allowance',
        '1' => 'Extra Allowance',
        '2' => 'Special Allowance (क)',
        '7' => 'Special Allowance (ख)',
        '8' => 'Outstation Facility',
        '3' => 'Gratuity Allowance',
        '4' => 'Risk Allowance',
        '5' => 'Dashain Allowance',
        '6' => 'Other Allowance'
    ),
    'jobtype_options' => array(
        \App\StafMainMastModel::STAFF_TYPE_OPTION_FOR_BBSM => 'BBSM',
        \App\StafMainMastModel::STAFF_TYPE_OPTION_FOR_GUARD_BBSM => 'Guard BBSM',
        \App\StafMainMastModel::STAFF_TYPE_OPTION_FOR_COMPANY => 'Company',
        \App\StafMainMastModel::STAFF_TYPE_OPTION_FOR_COMPANY_GUARD => 'Company Guard',
        \App\StafMainMastModel::STAFF_TYPE_OPTION_FOR_BBSM_NOT_IN_PAYROLL => 'BBSM NOT IN Payroll',
    ),
    'weekend_days' => array(
        '1' => 'Monday',
        '2' => 'Tuesday',
        '3' => 'Wednesday',
        '4' => 'Thursday',
        '5' => 'Friday',
        '6' => 'Saturday',
        '7' => 'Sunday',
    ),
    'balance_description' => array(
        '1' => 'Opening Leave Balance',
        '2' => 'Approved Leave',
    ),
    'payroll_upload_dir' => '/uploads/payroll_files/',
    'sections' => array(
        '0' => "Main",
    ),
    'departments' => array(
        '0' => "Finance",
        '1' => "HR",
        '2' => "Administration",
        '3' => "Management",
        '4' => "Marketing",
        '5' => "Sales",
        '6' => "R&D",
    ),
    /*'sections' => array(
        '0' => "Grocery",
        '1' => "Electronics",
    ),
    'departments' => array(
        '0' => "Sales",
        '1' => "Management",
    ),*/
    'loan_account_status' => array(
        '0', //first sanctioned loan
        '1', //installment amount
        '2', //completed installment
    ),
    'levy_amount' => 30,
    'sundry_types' => [
        1 => 'CR',
        2 => 'DR'
    ],
    'employee_status' => [
        'Working',
        'Resign',
        'Dismiss',
        'Fire',
        'Suspense'
    ],
    'month_name' => [
        '4' => 'Shrawan',
        '5' => 'Bhadra',
        '6' => 'Ashwin',
        '7' => 'Kartik',
        '8' => 'Mangsir',
        '9' => 'Paush',
        '10' => 'Magh',
        '11' => 'Falgun',
        '12' => 'Chaitra',
        '1' => 'Baishakh',
        '2' => 'Jesth',
        '3' => 'Asadh',
    ],
    'month_name_with_extra' => [
        '4' => 'Shrawan',
        '5' => 'Bhadra',
        '13' => 'Dashain',
        '6' => 'Ashwin',
        '14' => 'Tihar',
        '7' => 'Kartik',
        '8' => 'Mangsir',
        '9' => 'Paush',
        '10' => 'Magh',
        '11' => 'Falgun',
        '12' => 'Chaitra',
        '1' => 'Baishakh',
        '2' => 'Jesth',
        '3' => 'Asadh',
    ],
    'month_name_with_dashain_and_tihar' => [
        '4' => 'Shrawan',
        '5' => 'Bhadra',
        '6' => 'Ashwin',
        '7' => 'Kartik',
        '8' => 'Mangsir',
        '9' => 'Paush',
        '10' => 'Magh',
        '11' => 'Falgun',
        '12' => 'Chaitra',
        '13' => 'Dashain',
        '14' => 'Tihar',
        '1' => 'Baishakh',
        '2' => 'Jesth',
        '3' => 'Asadh',
    ],
    'bonus' => [
        '1' => 'dashain',
        '2' => 'tihar'
    ],
    'calculate_payroll_type' => [
        '1' => 'Hourly Basis',
        '2' => 'Day Basis without Overtime',
        '3' => 'Day Basis with Overtime',
    ],
    'payroll_type_implemented' => 1,
    'attendance_background_color_code' => [
        'present_on_weekend' => '#00CC66',
        'present_on_public_holiday' => '#99FF99',
        'is_force' => '#a28e8e',
        'Weekend' => '#008CBA',
        'Absent' => '#f58373',
        'Approved' => '#BA756B',
    ],
    'tax_payable_months_number' => 12,
    'leave_code' => [
        'weekend_holiday_leave' => 1,
        'public_holiday_leave' => 2,
        'home_leave' => 3,
        'sick_leave' => 4,
        'maternity_leave' => 5,
        'maternity_care_leave' => 6,
        'funeral_leave' => 7,
        'substitute_leave' => 8,
        'without_pay_leave' => 9,
        'without_pay_maternity_leave' => 10,
    ],
    'leave_request_status' => [
        'not_approved' => 0,
        'approved' => 1,
        'rejected' => 2
    ],
    'leave_request_color_hex_code' => [
        1 => '#008000',
        2 => '#FFA500',
    ],
    'roles' => [
        'names' => [
            'administrator' => 'Administrator',
            'editor' => 'Editor',
            'employee' => 'Employee'
        ]
    ],
    'manual_attendance' => [
        'status' => ['Present', 'Absent']
    ],
    'job_alert_types' => [
        1 => 'Non Permanent to Permanent Promotion',
        2 => 'Age Limit Promotion',
        3 => 'Trainee To Non Permanent'
    ],
    'staff_permanent_promotion' => [
        'minimum_work_days' => 240
    ],
    'staff_trainee_to_non_permanent_promotion' => [
        'minimum_work_days' => 180
    ],
    'staff_above_age_promotion' => [
        'maximum_age_in_years' => 58
    ],
    'app_version_uploaded_dir' => 'app-version',
    'loan_types' => [
        \App\LoanDeduct::HOUSE_LOAN_TYPE_ID => 'House Loan',
        \App\LoanDeduct::VEHICLE_LOAN_TYPE_ID => 'Vehicle Loan'
    ],
    'file_sections' => [
        'citizenship' => 'Citizenship Docs',
        'nominee' => 'Nominee Files',
        'appointment' => 'Appointment Letter',
        'pan' => 'Pan Card Image',
        'leave_request_documents' => 'Leave Request Documents',
        'training_documents' => 'Training',
        'education' => 'Education',
        'award' => 'Award',
    ],

    'leave_types' => [
        1 => 'Collapsible',
        2 => 'Non-Collapsible',
    ],
    'leave_earnable_periods' => [
        \App\SystemLeaveMastModel::LEAVE_EARNABLE_PERIOD_FOR_MONTHLY => 'Monthly',
        \App\SystemLeaveMastModel::LEAVE_EARNABLE_PERIOD_FOR_YEARLY => 'Every Fiscal Year',
    ],
    'leave_earnable_types' => [
        \App\SystemLeaveMastModel::EARN_ABLE_TYPE_FOR_FLAT => 'Flat',
        \App\SystemLeaveMastModel::EARN_ABLE_TYPE_FOR_PRESENT_DAYS_RATIO => 'Present Days Ratio',
//        \App\SystemLeaveMastModel::EARN_ABLE_TYPE_FOR_MIN_PRESENT_THRESHOLD => 'Min Present Threshold',
        \App\SystemLeaveMastModel::EARN_ABLE_TYPE_FOR_EVERY_SPECIFIC_NUMBER_OF_DAYS_PRESENT => 'Every Specific Number of Days Present in Month',
        \App\SystemLeaveMastModel::EARN_ABLE_TYPE_FOR_DAYS_FROM_APPOINTMENT => 'Days From Appointment',
        \App\SystemLeaveMastModel::EARN_ABLE_TYPE_FOR_YEAR_FROM_APPOINTMENT => 'Year From Appointment',
    ],
    'useability_count_units' => [
        1 => 'Days in Fiscal Year',
        2 => 'Time(s)',
        3 => 'Day(s) in Month',
        4 => 'Time(s) in Month',
        5 => 'Time(s) in Service Period',
    ],
    'organization_type' => [
        1 => 'Work Hour Calculation',
        2 => 'Present Day Calculation',
    ], 'organization_structure' => [
        1 => 'Supermarket',
        2 => 'Insurance Company',
    ]
    , 'allowance_types' => [
        1 => 'Monthly Payable',
        2 => 'Yearly Payable',
    ],
    'overtime_calculation_types' => [
        1 => 'Staff Assigned Work Hour',
        2 => 'Organization Shift'
    ]
];
