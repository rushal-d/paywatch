<?php

// Dashboard
Breadcrumbs::register('dashboard', function ($breadcrumbs) {
    $breadcrumbs->push('Dashboard', route('dashboard'));
});

// Dashboard -> Settings
Breadcrumbs::register('settings', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Settings', route('settings'));
});

//Shift Module
// Dashboard -> Shift
Breadcrumbs::register('shift', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Shift',route('shift-index'));
});

// Dashboard -> Shift -> Create
Breadcrumbs::register('shiftcreate', function ($breadcrumbs) {
    $breadcrumbs->parent('shift');
    $breadcrumbs->push('Create');
});

// Dashboard -> Shift -> Edit
Breadcrumbs::register('shiftedit', function ($breadcrumbs) {
    $breadcrumbs->parent('shift');
    $breadcrumbs->push('Edit');
});

// Dashboard -> Shift -> Visual Report
Breadcrumbs::register('shiftvisual', function ($breadcrumbs) {
    $breadcrumbs->parent('shift');
    $breadcrumbs->push('Shift Visual');
});

// Dashboard -> Shift -> Excel Import
Breadcrumbs::register('shiftexcelimport', function ($breadcrumbs) {
    $breadcrumbs->parent('shift');
    $breadcrumbs->push('Excel Import');
});

//Organization Shift Module
// Dashboard > Settings -> Shift
Breadcrumbs::register('organization-shift', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Organization Shift', route('organization-shift-index'));
});

// Dashboard > Organization Shift -> Create
Breadcrumbs::register('organization-shiftcreate', function ($breadcrumbs) {
    $breadcrumbs->parent('organization-shift');
    $breadcrumbs->push('Create');
});

// Dashboard > Organization Shift -> Edit
Breadcrumbs::register('orgnaization-shiftedit', function ($breadcrumbs) {
    $breadcrumbs->parent('organization-shift');
    $breadcrumbs->push('Edit');
});


//Leave Request Module
// Dashboard -> Leave Request
Breadcrumbs::register('leave-request', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Leave Request',route('leaverequest-index'));
});

// Dashboard -> Leave Request -> Create
Breadcrumbs::register('leave-request-create', function ($breadcrumbs) {
    $breadcrumbs->parent('leave-request');
    $breadcrumbs->push('Create');
});

// Dashboard -> Leave Request -> Detail
Breadcrumbs::register('leave-request-show', function ($breadcrumbs) {
    $breadcrumbs->parent('leave-request');
    $breadcrumbs->push('Details');
});


//Education Module
// Dashboard -> Education
Breadcrumbs::register('education', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Education',route('education'));
});

// Dashboard -> Education -> Create
Breadcrumbs::register('educationcreate', function ($breadcrumbs) {
    $breadcrumbs->parent('education');
    $breadcrumbs->push('Create');
});

// Dashboard -> Education -> Edit
Breadcrumbs::register('educationedit', function ($breadcrumbs) {
    $breadcrumbs->parent('education');
    $breadcrumbs->push('Edit');
});

//Section Module
// Dashboard -> Section
Breadcrumbs::register('section', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Section',route('section'));
});

// Dashboard -> Section -> Create
Breadcrumbs::register('sectioncreate', function ($breadcrumbs) {
    $breadcrumbs->parent('section');
    $breadcrumbs->push('Create');
});

// Dashboard -> Section -> Edit
Breadcrumbs::register('sectionedit', function ($breadcrumbs) {
    $breadcrumbs->parent('section');
    $breadcrumbs->push('Edit');
});

//File Type Module
// Dashboard -> File Type
Breadcrumbs::register('filetype', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('File Type',route('file-type'));
});

// Dashboard -> File Type -> Create
Breadcrumbs::register('filetypecreate', function ($breadcrumbs) {
    $breadcrumbs->parent('filetype');
    $breadcrumbs->push('Create');
});

// Dashboard -> File Type -> Edit
Breadcrumbs::register('filetypeedit', function ($breadcrumbs) {
    $breadcrumbs->parent('filetype');
    $breadcrumbs->push('Edit');
});

//App Version Module
// Dashboard -> App Version
Breadcrumbs::register('appversion', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('App Version', route('appversion'));
});

// Dashboard -> App Version -> Create
Breadcrumbs::register('appversioncreate', function ($breadcrumbs) {
    $breadcrumbs->parent('appversion');
    $breadcrumbs->push('Create');
});

// Dashboard -> App Version -> Edit
Breadcrumbs::register('appversionedit', function ($breadcrumbs) {
    $breadcrumbs->parent('appversion');
    $breadcrumbs->push('Edit');
});

//Department Module
// Dashboard -> Department
Breadcrumbs::register('department', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Department', route('department'));
});

// Dashboard -> Department -> Create
Breadcrumbs::register('departmentcreate', function ($breadcrumbs) {
    $breadcrumbs->parent('department');
    $breadcrumbs->push('Create');
});

// Dashboard -> Department -> Edit
Breadcrumbs::register('departmentedit', function ($breadcrumbs) {
    $breadcrumbs->parent('department');
    $breadcrumbs->push('Edit');
});


//Leave Module
// Dashboard -> Leave
Breadcrumbs::register('leave', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Leave',route('systemleave'));
});

// Dashboard -> leave -> Create
Breadcrumbs::register('leavecreate', function ($breadcrumbs) {
    $breadcrumbs->parent('leave');
    $breadcrumbs->push('Create');
});
// Dashboard -> Leave -> Edit
Breadcrumbs::register('leaveedit', function ($breadcrumbs) {
    $breadcrumbs->parent('leave');
    $breadcrumbs->push('Edit');
});

//leave end

//Leave Module
// Dashboard -> Office
Breadcrumbs::register('office', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Office',route('systemoffice'));
});

// Dashboard -> office -> Create
Breadcrumbs::register('officecreate', function ($breadcrumbs) {
    $breadcrumbs->parent('office');
    $breadcrumbs->push('Create');
});
// Dashboard -> Office -> Edit
Breadcrumbs::register('officeedit', function ($breadcrumbs) {
    $breadcrumbs->parent('office');
    $breadcrumbs->push('Edit');
});


//Post Module
// Dashboard -> Post
Breadcrumbs::register('post', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Post',route('system-post'));
});

// Dashboard -> post -> Create
Breadcrumbs::register('postcreate', function ($breadcrumbs) {
    $breadcrumbs->parent('post');
    $breadcrumbs->push('Create');
});
// Dashboard  -> post -> Edit
Breadcrumbs::register('postedit', function ($breadcrumbs) {
    $breadcrumbs->parent('post');
    $breadcrumbs->push('Edit');
});


//Staff Module
// Dashboard -> staff
Breadcrumbs::register('staff', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Staff', route('staff-main'));
});

Breadcrumbs::register('staff-warning', function ($breadcrumbs) {
    $breadcrumbs->parent('staff');
    $breadcrumbs->push('Staff Warning');
});

Breadcrumbs::register('staff-get-promotion-alert', function ($breadcrumbs) {
    $breadcrumbs->parent('staff');
    $breadcrumbs->push('Staff Promotion Alert');
});

// Dashboard -> staff -> Create
Breadcrumbs::register('staffcreate', function ($breadcrumbs) {
    $breadcrumbs->parent('staff');
    $breadcrumbs->push('Create');
});
// Dashboard -> staff -> Edit
Breadcrumbs::register('staffedit', function ($breadcrumbs) {
    $breadcrumbs->parent('staff');
    $breadcrumbs->push('Edit');
});
// Dashboard -> staff -> view
Breadcrumbs::register('staffview', function ($breadcrumbs) {
    $breadcrumbs->parent('staff');
    $breadcrumbs->push('Detail');
});
// Dashboard -> staff -> Excel Import
Breadcrumbs::register('staff-excel-import', function ($breadcrumbs) {
    $breadcrumbs->parent('staff');
    $breadcrumbs->push('Excel Import',route('excel-index'));
});

Breadcrumbs::register('staff-excel-tally', function ($breadcrumbs) {
    $breadcrumbs->parent('staff-excel-import');
    $breadcrumbs->push('Data Tally');
});


//Holiday Module
// Dashboard -> holiday
Breadcrumbs::register('holiday', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Holiday', route('system-holiday'));
});

// Dashboard -> holiday -> Create
Breadcrumbs::register('holidaycreate', function ($breadcrumbs) {
    $breadcrumbs->parent('holiday');
    $breadcrumbs->push('Create');
});
// Dashboard -> holiday -> Edit
Breadcrumbs::register('holidayedit', function ($breadcrumbs) {
    $breadcrumbs->parent('holiday');
    $breadcrumbs->push('Edit');
});

//JobType Module
// Dashboard -> jobtype
Breadcrumbs::register('jobtype', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('JobType', route('system-jobtype'));
});

// Dashboard -> jobtype -> Create
Breadcrumbs::register('jobtypecreate', function ($breadcrumbs) {
    $breadcrumbs->parent('jobtype');
    $breadcrumbs->push('Create');
});
// Dashboard -> jobtype -> Edit
Breadcrumbs::register('jobtypeedit', function ($breadcrumbs) {
    $breadcrumbs->parent('jobtype');
    $breadcrumbs->push('Edit');
});


//Tds Module
// Dashboard -> tds
Breadcrumbs::register('tds', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Tds', route('system-tds'));
});

// Dashboard -> tds -> Create
Breadcrumbs::register('tdscreate', function ($breadcrumbs) {
    $breadcrumbs->parent('tds');
    $breadcrumbs->push('Create');
});
// Dashboard -> tds -> Edit
Breadcrumbs::register('tdsedit', function ($breadcrumbs) {
    $breadcrumbs->parent('tds');
    $breadcrumbs->push('Edit');
});
// Dashboard -> Dashain Tihar Setup
Breadcrumbs::register('dashaintiharsetup', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Dashain Tihar Setup');
});
// Dashboard -> Tihar Setup
Breadcrumbs::register('tiharsetup', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Tihar Setup');
});

//Fiscal Year Module
// Dashboard -> Fiscal year
Breadcrumbs::register('fiscalyear', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Fiscal Year', route('fiscal-year'));
});

// Dashboard -> fiscalyear -> Create
Breadcrumbs::register('fiscalyearcreate', function ($breadcrumbs) {
    $breadcrumbs->parent('fiscalyear');
    $breadcrumbs->push('Create');
});
// Dashboard -> fiscalyear -> Edit
Breadcrumbs::register('fiscalyearedit', function ($breadcrumbs) {
    $breadcrumbs->parent('fiscalyear');
    $breadcrumbs->push('Edit');
});


//Calender Holiday Year Module
// Dashboard  -> tds
Breadcrumbs::register('calenderholiday', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Grant Holiday',route('calender-holiday'));
});

// Dashboard -> calenderholiday -> Create
Breadcrumbs::register('calenderholidaycreate', function ($breadcrumbs) {
    $breadcrumbs->parent('calenderholiday');
    $breadcrumbs->push('Create');
});
// Dashboard -> calenderholiday -> Edit
Breadcrumbs::register('calenderholidayedit', function ($breadcrumbs) {
    $breadcrumbs->parent('calenderholiday');
    $breadcrumbs->push('Edit');
});


//Bank Module
// Dashboard -> bank
Breadcrumbs::register('bank', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Bank',route('bank-index'));
});

// Dashboard -> bank -> Create
Breadcrumbs::register('bankcreate', function ($breadcrumbs) {
    $breadcrumbs->parent('bank');
    $breadcrumbs->push('Create');
});
// Dashboard -> bank -> Edit
Breadcrumbs::register('bankedit', function ($breadcrumbs) {
    $breadcrumbs->parent('bank');
    $breadcrumbs->push('Edit');
});


//payroll Module
// Dashboard -> payroll
Breadcrumbs::register('payroll', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Payroll', route('attendance-detail'));
});

// Dashboard > Settings -> payroll -> Create
Breadcrumbs::register('payrollcreate', function ($breadcrumbs) {
    $breadcrumbs->parent('payroll');
    $breadcrumbs->push('Create');
});


// Dashboard > Dashain Payment
Breadcrumbs::register('dashain-payment', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Dashain Payment', route('dashain-payment-show'));
});
// Dashboard ->  Staff Wise Payroll Details-> show
Breadcrumbs::register('dashain-payment-show', function ($breadcrumbs) {
    $breadcrumbs->parent('dashain-payment');
    $breadcrumbs->push('Dashain Payment Show');
});

// Dashboard ->  Staff Wise Payroll Details
Breadcrumbs::register('staff-payroll-detail', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Staff Payroll Details', route('staff-payroll-detail'));
});
// Dashboard ->  Staff Wise Payroll Details-> show
Breadcrumbs::register('staff-payroll-detail-show', function ($breadcrumbs) {
    $breadcrumbs->parent('staff-payroll-detail');
    $breadcrumbs->push('Staff Payroll Details Show');
});

//HouseLoan Module
// Dashboard -> houseloan
Breadcrumbs::register('houseloan', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Houseloan', route('houseloan-index'));
});

// Dashboard -> houseloan -> Create
Breadcrumbs::register('houseloancreate', function ($breadcrumbs) {
    $breadcrumbs->parent('houseloan');
    $breadcrumbs->push('Create');
});
// Dashboard -> houseloan -> Edit
Breadcrumbs::register('houseloanedit', function ($breadcrumbs) {
    $breadcrumbs->parent('houseloan');
    $breadcrumbs->push('Edit');
});

//Sundry Loan Module
// Dashboard -> sundryloan
Breadcrumbs::register('sundryloan', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Sundry', route('sundryloan-index'));
});

// Dashboard -> sundryloan -> Create
Breadcrumbs::register('sundryloancreate', function ($breadcrumbs) {
    $breadcrumbs->parent('sundryloan');
    $breadcrumbs->push('Create');
});
// Dashboard -> sundryloan -> Edit
Breadcrumbs::register('sundryloanedit', function ($breadcrumbs) {
    $breadcrumbs->parent('sundryloan');
    $breadcrumbs->push('Edit');
});
// Dashboard -> sundryloan -> Detail
Breadcrumbs::register('sundryloandetail', function ($breadcrumbs) {
    $breadcrumbs->parent('sundryloan');
    $breadcrumbs->push('Detail');
});


//Vehical Loan Module
// Dashboard -> vehicalloan
Breadcrumbs::register('vehicalloan', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Vehicle Loan', route('vehicalloan-index'));
});

// Dashboard -> vehicalloan -> Create
Breadcrumbs::register('vehicalloancreate', function ($breadcrumbs) {
    $breadcrumbs->parent('vehicalloan');
    $breadcrumbs->push('Create');
});
// Dashboard -> vehicalloan -> Edit
Breadcrumbs::register('vehicalloanedit', function ($breadcrumbs) {
    $breadcrumbs->parent('vehicalloan');
    $breadcrumbs->push('Edit');
});


//Vehicle Loan Diff Income Module
// Dashboard -> vehicle-loan-diff-income
Breadcrumbs::register('vehicle-loan-diff-income', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Diff Vehicle Loan Income', route('vehicle-loan-diff-income-index'));
});

// Dashboard -> vehicle-loan-diff-income -> Create
Breadcrumbs::register('vehicle-loan-diff-income-create', function ($breadcrumbs) {
    $breadcrumbs->parent('vehicle-loan-diff-income');
    $breadcrumbs->push('Create');
});
// Dashboard -> vehicle-loan-diff-income -> Edit
Breadcrumbs::register('vehicle-loan-diff-income-edit', function ($breadcrumbs) {
    $breadcrumbs->parent('vehicle-loan-diff-income');
    $breadcrumbs->push('Edit');
});

//Loan Deduct Module
// Dashboard -> loan-deduct
Breadcrumbs::register('loan-deduct', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Loan Deduct',route('loan-deduct-index'));
});

// Dashboard -> loan -deduct-> Create
Breadcrumbs::register('loan-deduct-create', function ($breadcrumbs) {
    $breadcrumbs->parent('loan-deduct');
    $breadcrumbs->push('Create');
});
// Dashboard -> loan -deduct-> Edit
Breadcrumbs::register('loan-deduct-edit', function ($breadcrumbs) {
    $breadcrumbs->parent('loan-deduct');
    $breadcrumbs->push('Edit');
});
// Dashboard -> loan -deduct-> Show
Breadcrumbs::register('loan-deduct-show', function ($breadcrumbs) {
    $breadcrumbs->parent('loan-deduct');
    $breadcrumbs->push('Show');
});


//Allowance Module
// Dashboard -> allowance
Breadcrumbs::register('allowance', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Allowance', route('allowance-index'));
});

// Dashboard -> allowance -> Create
Breadcrumbs::register('allowancecreate', function ($breadcrumbs) {
    $breadcrumbs->parent('allowance');
    $breadcrumbs->push('Create');
});
// Dashboard -> allowance -> Edit
Breadcrumbs::register('allowanceedit', function ($breadcrumbs) {
    $breadcrumbs->parent('allowance');
    $breadcrumbs->push('Edit');
});


//Staff Transfer Module
// Dashboard -> allowance
Breadcrumbs::register('transfer', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Staff Transfer', route('staff-transfer'));
});

// Dashboard -> transfer -> Create
Breadcrumbs::register('transfercreate', function ($breadcrumbs) {
    $breadcrumbs->parent('transfer');
    $breadcrumbs->push('Create');
});
// Dashboard -> transfer -> Edit
Breadcrumbs::register('transferedit', function ($breadcrumbs) {
    $breadcrumbs->parent('transfer');
    $breadcrumbs->push('Edit');
});


//Leavebalance Module
// Dashboard -> leavebalance
Breadcrumbs::register('leavebalance', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Leavebalance', route('leavebalance-index'));
});

// Dashboard -> leavebalance -> Create
Breadcrumbs::register('leavebalancecreate', function ($breadcrumbs) {
    $breadcrumbs->parent('leavebalance');
    $breadcrumbs->push('Create');
});
// Dashboard -> leavebalance -> Edit
Breadcrumbs::register('leavebalanceedit', function ($breadcrumbs) {
    $breadcrumbs->parent('leavebalance');
    $breadcrumbs->push('Edit');
});


// Reports
Breadcrumbs::register('reports', function ($breadcrumbs) {
    $breadcrumbs->push('Reports', route('reports'));
});

// Leave balance statement
Breadcrumbs::register('leavebalancestatement', function ($breadcrumbs) {
    $breadcrumbs->push('Leave Balance Statement', route('leavebalancestatement'));
});

// Staff wise payroll summary
Breadcrumbs::register('leavebalancestatementshow', function ($breadcrumbs) {
    $breadcrumbs->parent('leavebalancestatement');
    $breadcrumbs->push('Show');
});

// Staff wise payroll summary
Breadcrumbs::register('salary-dynamic-report', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Salary Dynamic Report Index', route('dynamicReportIndex'));
    $breadcrumbs->push('Salary Dynamic Report');
});

// Staff wise payroll summary
Breadcrumbs::register('salary-dynamic-report-index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Salary Dynamic Report');
});

// Staff wise payroll summary
Breadcrumbs::register('staff_payroll_summary', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Staff Wise Payroll Summary', route('staff_payroll_summary'));
});

// Staff wise payroll summary
Breadcrumbs::register('staff_payroll_summary-show', function ($breadcrumbs) {
    $breadcrumbs->parent('staff_payroll_summary');
    $breadcrumbs->push('Show');
});

// Bank Statement
Breadcrumbs::register('bankstatement', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Bank Statement', route('bankstatement'));
});

// Bank Statement
Breadcrumbs::register('cashstatement', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Cash Statement', route('cashstatement'));
});

// Sundry Report
Breadcrumbs::register('sundry-report', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Sundry Report', route('sundry-report'));
});

// Tax Statement
Breadcrumbs::register('taxstatement', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Tax Statement', route('taxstatement'));
});



Breadcrumbs::register('taxstatement-personal', function ($breadcrumbs) {
    $breadcrumbs->parent('taxstatement');
    $breadcrumbs->push('Tax Report Personal');
});

// Social Security Tax Statement
Breadcrumbs::register('socialsecuritytaxstatement', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Social Security Tax Statement', route('social-security-tax-statement'));
});

Breadcrumbs::register('socialsecuritytaxstatement-personal', function ($breadcrumbs) {
    $breadcrumbs->parent('socialsecuritytaxstatement');
    $breadcrumbs->push('Social Security Tax Report Personal');
});

// Pro Fund Ledger
Breadcrumbs::register('pfledger', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Provident Fund Report', route('pfledger'));
});

Breadcrumbs::register('pfledger-personal', function ($breadcrumbs) {
    $breadcrumbs->parent('pfledger');
    $breadcrumbs->push('Provident Fund Personal Ledger');
});

// CIT Ledger
Breadcrumbs::register('citledger', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('CIT Report', route('pfledger'));
});

Breadcrumbs::register('citledger-personal', function ($breadcrumbs) {
    $breadcrumbs->parent('citledger');
    $breadcrumbs->push('CIT Personal Ledger');
});

// House Loan Statement
Breadcrumbs::register('houseloanstatement', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Home Loan Statement', route('houseloanstatement'));
});

// Vehicle Loan Statement
Breadcrumbs::register('vehicleloanstatement', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Vehcile Loan Statement', route('vehicleloanstatement'));
});

// Summary
Breadcrumbs::register('summary', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Summary', route('summary'));
});


//Sundry Type Module
// Dashboard -> sundry type
Breadcrumbs::register('sundryType', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Sundry Type', route('sundry-type-index'));
});

// Dashboard -> Sundry Type -> Create
Breadcrumbs::register('sundryTypeCreate', function ($breadcrumbs) {
    $breadcrumbs->parent('sundryType');
    $breadcrumbs->push('Create');
});

// Dashboard -> Sundry Type -> Edit
Breadcrumbs::register('sundryTypeEdit', function ($breadcrumbs) {
    $breadcrumbs->parent('sundryType');
    $breadcrumbs->push('Edit');
});

//Sundry Type Module
// Dashboard -> Staff Status
Breadcrumbs::register('staff-status', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Staff Status', route('staff-status'));
});

// Dashboard -> Staff Status -> Create
Breadcrumbs::register('staff-status-create', function ($breadcrumbs) {
    $breadcrumbs->parent('staff-status');
    $breadcrumbs->push('Create');
});

// Dashboard -> Staff Status -> Edit
Breadcrumbs::register('staff-status-edit', function ($breadcrumbs) {
    $breadcrumbs->parent('staff-status');
    $breadcrumbs->push('Edit');
});


// Dashboard  -> Fiscal Year Attendance
Breadcrumbs::register('fiscal-year-attendance', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Fiscal Year Attendance', route('fiscalyearattendancesum'));
});

// Dashboard -> Fiscal Year Attendance -> Create
Breadcrumbs::register('fiscal-year-attendance-create', function ($breadcrumbs) {
    $breadcrumbs->parent('fiscal-year-attendance');
    $breadcrumbs->push('Create');
});

// Dashboard  -> Fiscal Year Attendance -> Edit
Breadcrumbs::register('fiscal-year-attendance-edit', function ($breadcrumbs) {
    $breadcrumbs->parent('fiscal-year-attendance');
    $breadcrumbs->push('Edit');
});


Breadcrumbs::register('fiscal-year-attendance-import', function ($breadcrumbs) {
    $breadcrumbs->parent('fiscal-year-attendance');
    $breadcrumbs->push('Import');
});


// Dashboard -> Alternative Shift
Breadcrumbs::register('alternative-shift', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Alternative Shift', route('alternative-shift-index'));
});

// Dashboard > Settings -> Alternative Shift -> Create
Breadcrumbs::register('alternative-shift-create', function ($breadcrumbs) {
    $breadcrumbs->parent('alternative-shift');
    $breadcrumbs->push('Create');
});

// Dashboard > Change Bulk Weekend
Breadcrumbs::register('change-bulk-weekend', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Change Weekend Bulk',route('change-weekend'));
});

Breadcrumbs::register('change-bulk-weekend-staff-list', function ($breadcrumbs) {
    $breadcrumbs->parent('change-bulk-weekend');
    $breadcrumbs->push('Staff List');
});


// Dashboard -> Organization Setup
Breadcrumbs::register('organization', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Organization Setup');
});

//Role
// Dashboard -> Role
Breadcrumbs::register('role-index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Roles', route('role-index'));
});

// Dashboard -> Role -> Create
Breadcrumbs::register('role-create', function ($breadcrumbs) {
    $breadcrumbs->parent('role-index');
    $breadcrumbs->push('Create');
});

// Dashboard -> Role-> Edit
Breadcrumbs::register('role-edit', function ($breadcrumbs) {
    $breadcrumbs->parent('role-index');
    $breadcrumbs->push('Edit');
});

//Permission
// Dashboard -> Permission
Breadcrumbs::register('permission-index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Permissions', route('permission-index'));
});

// Dashboard  -> Permission -> Create
Breadcrumbs::register('permission-create', function ($breadcrumbs) {
    $breadcrumbs->parent('permission-index');
    $breadcrumbs->push('Create');
});

// Dashboard -> Permission-> Edit
Breadcrumbs::register('permission-edit', function ($breadcrumbs) {
    $breadcrumbs->parent('permission-index');
    $breadcrumbs->push('Edit');
});

//User
// Dashboard  -> User
Breadcrumbs::register('user-index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Users', route('user-index'));
});

// Dashboard  -> User -> Create
Breadcrumbs::register('user-create', function ($breadcrumbs) {
    $breadcrumbs->parent('user-index');
    $breadcrumbs->push('Create');
});

// Dashboard  -> User-> Edit
Breadcrumbs::register('user-edit', function ($breadcrumbs) {
    $breadcrumbs->parent('user-index');
    $breadcrumbs->push('Edit');
});

// Dashboard -> User-> Edit
Breadcrumbs::register('user-change-password', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Change Password');
});

//AssignRole
// Dashboard  -> AssignRole
Breadcrumbs::register('assignrole-index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Permission Roles', route('assignrole-index'));
});


// Dashboard -> AssignRole-> Edit
Breadcrumbs::register('assignrole-edit', function ($breadcrumbs) {
    $breadcrumbs->parent('assignrole-index');
    $breadcrumbs->push('Edit');
});

//local attendance
// Dashboard -> Local Attendance
Breadcrumbs::register('localattendance-index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Staff Attendance', route('localattendance'));
});
// Dashboard  -> Local Attendance -> Show
Breadcrumbs::register('localattendance-show', function ($breadcrumbs) {
    $breadcrumbs->parent('localattendance-index');
    $breadcrumbs->push('Show');
});


//punchOutWarning Module
// Dashboard -> Punch Out Warning
Breadcrumbs::register('punchout-warning-index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Punch Out Warning');
});


//Staff Attendance Module
// Dashboard -> Staff Attendance
Breadcrumbs::register('staff-attendance-index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Staff Attendance');
});


//Daywise Attendance Module
// Dashboard -> Daywise Attendance
Breadcrumbs::register('daywise-attendance-index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Daywise Attendance');
});


// Bulk Force Attendance Module
// Dashboard -> Bulk Force Attendance
Breadcrumbs::register('bulk-force-attendance-index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Bulk Force Attendance');
});

// Dashboard  -> Bulk Force Attendance -> Bulk Force Attendance Filter
Breadcrumbs::register('bulk-force-attendance-filter', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Bulk Force Attendance', route('bulk-force-show'));
    $breadcrumbs->push('Bulk Force Attendance Filter');
});

// Manual Attendance Module
// Dashboard -> Manual Attendance
Breadcrumbs::register('manual-attendance-index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Manual Attendance', route('manual-attendance'));
});

// Dashboard -> Manual Attendance -> Manual Attendance Filter
Breadcrumbs::register('manual-attendance-filter', function ($breadcrumbs) {
    $breadcrumbs->parent('manual-attendance-index');
    $breadcrumbs->push('Manual Attendance Filter');
});

// Force Entry Attendance Module
// Dashboard -> Add Force Entry Attendance
Breadcrumbs::register('force-entry-attendance-create', function ($breadcrumbs) {
    $breadcrumbs->parent('localattendance-index');
    $breadcrumbs->push('Add Force Entry Attendance');
});

// Force Entry Attendance Module
// Dashboard -> Edit Force Entry Attendance
Breadcrumbs::register('force-entry-attendance-edit', function ($breadcrumbs) {
    $breadcrumbs->parent('localattendance-index');
    $breadcrumbs->push('Edit Force Entry Attendance');
});

Breadcrumbs::register('religion', function($breadcrumbs){
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Religion', route('religion'));
});

Breadcrumbs::register('religionedit', function($breadcrumbs){
    $breadcrumbs->parent('religion');
    $breadcrumbs->push('Edit Religion');
});

Breadcrumbs::register('religioncreate', function($breadcrumbs){
    $breadcrumbs->parent('religion');
    $breadcrumbs->push('Create Religion');
});

Breadcrumbs::register('caste', function($breadcrumbs){
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Caste', route('caste'));
});

Breadcrumbs::register('casteedit', function($breadcrumbs){
    $breadcrumbs->parent('caste');
    $breadcrumbs->push('Edit Caste');
});

Breadcrumbs::register('castecreate', function($breadcrumbs){
    $breadcrumbs->parent('caste');
    $breadcrumbs->push('Create Caste');
});


Breadcrumbs::register('publicholiday', function($breadcrumbs){
    $breadcrumbs->parent('settings');
    $breadcrumbs->push('Public Holiday', route('public-holiday'));
});

Breadcrumbs::register('publicholidayedit', function($breadcrumbs){
    $breadcrumbs->parent('settings');
    $breadcrumbs->push('Public Holiday', route('public-holiday'));
    $breadcrumbs->push('Edit Public Holiday');
});

Breadcrumbs::register('publicholidaycreate', function($breadcrumbs){
    $breadcrumbs->parent('settings');
    $breadcrumbs->push('Public Holiday', route('public-holiday'));
    $breadcrumbs->push('Create Public Holiday');
});


//Staff Job Type Alert Module
// Dashboard -> Staff Job Type Alert
Breadcrumbs::register('staff-jobtype-alert-index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Staff Job Type Alert', route('staff-job-type-alert'));
});


//Staff Job Type Alert Age Module
// Dashboard -> Staff Job Type Alert -> Age Alert
Breadcrumbs::register('staff-jobtype-alert-warning-age', function ($breadcrumbs) {
    $breadcrumbs->parent('staff-jobtype-alert-index');
    $breadcrumbs->push(' Age Alert');
});


//Staff Job Type Alert Age Module
// Dashboard -> Staff Job Type Alert -> Non Permanent to Permanent Alert
Breadcrumbs::register('staff-jobtype-alert-warning-non-permanent-to-permanent', function ($breadcrumbs) {
    $breadcrumbs->parent('staff-jobtype-alert-index');
    $breadcrumbs->push('Non Permanent to Permanent Alert');
});

// Staff Job Type Alert Age Module
// Dashboard -> Staff Job Type Alert -> Trainee To Non Permanent
Breadcrumbs::register('staff-jobtype-alert-warning-trainee-to-non-permanent', function ($breadcrumbs) {
    $breadcrumbs->parent('staff-jobtype-alert-index');
    $breadcrumbs->push('Trainee To Non Permanent');
});



//Staff CIT Deduction Module
// Dashboard -> staff-cit-deduction
Breadcrumbs::register('staff-cit-deduction-index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Staff CIT Deduction', route('staff-cit-deduction-index'));
});

// Dashboard -> staff-cit-deduction-> Create
Breadcrumbs::register('staff-cit-deduction-create', function ($breadcrumbs) {
    $breadcrumbs->parent('staff-cit-deduction-index');
    $breadcrumbs->push('Create');
});
// Dashboard -> staff-cit-deduction-> Edit
Breadcrumbs::register('staff-cit-deduction-edit', function ($breadcrumbs) {
    $breadcrumbs->parent('staff-cit-deduction-index');
    $breadcrumbs->push('Edit');
});
// Dashboard -> staff-cit-deduction-> Show
Breadcrumbs::register('staff-cit-deduction-show', function ($breadcrumbs) {
    $breadcrumbs->parent('staff-cit-deduction-index');
    $breadcrumbs->push('Show');
});

//Staff Bonuses Module
//Dashboard -> staff-bonuses
Breadcrumbs::register('bonuses.index', function ($breadcrumbs){
   $breadcrumbs->parent('dashboard');
   $breadcrumbs->push('Staff Bonuses', route('bonuses.index'));
});

//Dashboard  -> staff-bonuses-> Create
Breadcrumbs::register('bonuses.create', function ($breadcrumbs){
   $breadcrumbs->parent('bonuses.index');
   $breadcrumbs->push('Create');
});

//Dashboard -> staff-bonuses-> Edit
Breadcrumbs::register('bonuses.edit', function ($breadcrumbs){
   $breadcrumbs->parent('bonuses.index');
   $breadcrumbs->push('Edit');
});

//Staff Overtime Module
//Dashboard -> staff-bonuses
Breadcrumbs::register('overtime-work-index', function ($breadcrumbs){
   $breadcrumbs->parent('dashboard');
   $breadcrumbs->push('Overtime Report', route('overtime-work-index'));
});

//House Loan Diff Income Module
// Dashboard -> house-loan-diff-income
Breadcrumbs::register('house-loan-diff-income', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Diff House Loan Income', route('house-loan-diff-income-index'));
});

// Dashboard -> house-loan-diff-income -> Create
Breadcrumbs::register('house-loan-diff-income-create', function ($breadcrumbs) {
    $breadcrumbs->parent('house-loan-diff-income');
    $breadcrumbs->push('Create');
});
// Dashboard -> house-loan-diff-income -> Edit
Breadcrumbs::register('house-loan-diff-income-edit', function ($breadcrumbs) {
    $breadcrumbs->parent('house-loan-diff-income');
    $breadcrumbs->push('Edit');
});

//Staff Insurance Premium Module
//Dashboard -> staff-insurance-premium
Breadcrumbs::register('staff-insurance-premium-index', function ($breadcrumbs){
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Staff Insurance Premium', route('staff-insurance-premium-index'));
});

//Dashboard->staff-insurance-premium->create
Breadcrumbs::register('staff-insurance-premium-create', function ($breadcrumbs){
    $breadcrumbs->parent('staff-insurance-premium-index');
    $breadcrumbs->push('Create');
});

//Dashboard->staff-insurance-premium->edit
Breadcrumbs::register('staff-insurance-premium-edit', function ($breadcrumbs){
    $breadcrumbs->parent('staff-insurance-premium-index');
    $breadcrumbs->push('Edit');
});

//Calculate Leave Balance Controller
//Dashboard -> calculate-leave-balance
Breadcrumbs::register('calculate-leave-balance-filter', function ($breadcrumbs){
   $breadcrumbs->parent('dashboard');
   $breadcrumbs->push('Calculate Leave Balance', route('calculate-leave-balance-filter'));
});

//Dashboard -> calculate-leave-balance -> index
Breadcrumbs::register('calculate-leave-balance-index', function ($breadcrumbs){
   $breadcrumbs->parent('calculate-leave-balance-filter');
   $breadcrumbs->push('Index');
});

//Staff Type Module
// Dashboard -> Staff Type
Breadcrumbs::register('stafftype', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Staff Type',route('staff-type'));
});

// Dashboard -> stafftype -> Create
Breadcrumbs::register('stafftypecreate', function ($breadcrumbs) {
    $breadcrumbs->parent('stafftype');
    $breadcrumbs->push('Create');
});
// Dashboard  -> stafftype -> Edit
Breadcrumbs::register('stafftypeedit', function ($breadcrumbs) {
    $breadcrumbs->parent('stafftype');
    $breadcrumbs->push('Edit');
});

// Dashboard  -> tax calculation
Breadcrumbs::register('nepal-re-tax-calculation', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Nepal Re Tax Calculation',route('nepal-re-tax-calculation-index'));
});
