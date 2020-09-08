<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/try/removeTransferMistakeRecords', 'TryController@removeTransferMistakeRecords');
Route::get('/try/staffWithoutInitialRecords', 'TryController@staffWithoutInitialRecords');
Route::get('/try/staffWithMultipleInitialRecords', 'TryController@staffWithMultipleInitialRecords');
Route::get('/try', 'TryController@try');
Route::get('/try/calculateTotalAttendance', 'TryController@calculateTotalAttendance');
Route::get('try-fetchAttendanceToAttendanceDetail', 'AttendanceDetailController@fetchAttendanceToAttendanceDetail');
Route::get('try-payroll_by_branch', 'StaffMainMastController@payroll_by_branch');
Route::get('alternativeTest', 'TryController@alternativeTest');
Route::get('gradeSplitTest', 'TryController@gradeSplitTest');

Route::get('try/calculate-leave', 'TryController@calculateLeave');
Route::get('try/calculate-leave-pdr', 'TryController@forPDR');
Route::get('/try/calculate-leave-check', 'TryController@checkHere');


Route::get('/dashboard', 'DashboardController@index')->name('dashboard')->middleware('auth');
Route::group(['middleware' => ['auth', 'permissionmiddleware', 'web']], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard')->middleware('auth');
    Route::get('get-attendance-detail-number-of-staffs', 'DashboardController@getAttendanceDetailNumberOfStaffs')->name('get-attendance-detail-number-of-staffs');
//    Route::view('/', function () {
//        return redirect('login');
//    });

    Route::get('/home', 'DashboardController@index')->name('dashboard');
    Route::post('/home/payroll_details', 'DashboardController@payroll_details')->name('payroll-details');
    Route::get('/settings', 'DashboardController@settings')->name('settings');

    /* Education Module */
    Route::get('/education', 'EducationController@index')->name('education');
    Route::get('/education/create', 'EducationController@create')->name('educationcreate');
    Route::post('/education/store', 'EducationController@store')->name('educationsave');
    Route::get('/education/edit/{id}', 'EducationController@edit')->name('educationedit');
    Route::post('/education/update/{id}', 'EducationController@update')->name('educationupdate');
    Route::post('/education/destroy', 'EducationController@destroy')->name('educationdestroy');
    Route::post('/education/destroyselected', 'EducationController@destroySelected')->name('educationdestroyselected');

    /* Section Module */
    Route::get('/section', 'SectionController@index')->name('section');
    Route::get('/section/create', 'SectionController@create')->name('sectioncreate');
    Route::post('/section/store', 'SectionController@store')->name('sectionsave');
    Route::get('/section/edit/{id}', 'SectionController@edit')->name('sectionedit');
    Route::post('/section/update/{id}', 'SectionController@update')->name('sectionupdate');
    Route::post('/section/destroy', 'SectionController@destroy')->name('sectiondestroy');
    Route::post('/section/destroyselected', 'SectionController@destroySelected')->name('sectiondestroyselected');

    /* Caste Module */
    Route::get('/caste', 'CasteController@index')->name('caste');
    Route::get('/caste/create', 'CasteController@create')->name('castecreate');
    Route::post('/caste/store', 'CasteController@store')->name('castesave');
    Route::get('/caste/edit/{id}', 'CasteController@edit')->name('casteedit');
    Route::post('/caste/update/{id}', 'CasteController@update')->name('casteupdate');
    Route::post('/caste/destroy', 'CasteController@destroy')->name('castedestroy');
    Route::post('/caste/destroyselected', 'CasteController@destroySelected')->name('castedestroyselected');

    /* Religion Module */
    Route::get('/religion', 'ReligionController@index')->name('religion');
    Route::get('/religion/create', 'ReligionController@create')->name('religioncreate');
    Route::post('/religion/store', 'ReligionController@store')->name('religionsave');
    Route::get('/religion/edit/{id}', 'ReligionController@edit')->name('religionedit');
    Route::post('/religion/update/{id}', 'ReligionController@update')->name('religionupdate');
    Route::post('/religion/destroy', 'ReligionController@destroy')->name('religiondestroy');
    Route::post('/religion/destroyselected', 'ReligionController@destroySelected')->name('religiondestroyselected');

    /* Province Module */
    Route::get('/province', 'ProvinceController@index')->name('province');
    Route::get('/province/create', 'ProvinceController@create')->name('provincecreate');
    Route::post('/province/store', 'ProvinceController@store')->name('provincesave');
    Route::get('/province/edit/{id}', 'ProvinceController@edit')->name('provinceedit');
    Route::post('/province/update/{id}', 'ProvinceController@update')->name('provinceupdate');
    Route::post('/province/destroy', 'ProvinceController@destroy')->name('provincedestroy');
    Route::post('/province/destroyselected', 'ProvinceController@destroySelected')->name('provincedestroyselected');

    /* App Version Module */
    Route::get('/app-version', 'AppVersionController@index')->name('appversion');
    Route::get('/app-version/download-zip/{id}', 'AppVersionController@downloadZip')->name('appversion.downloadZip');
    Route::get('/app-version/create', 'AppVersionController@create')->name('appversion.create');
    Route::post('/app-version/store', 'AppVersionController@store')->name('appversion.save');
    Route::get('/app-version/edit/{id}', 'AppVersionController@edit')->name('appversion.edit');
    Route::post('/app-version/update/{id}', 'AppVersionController@update')->name('appversion.update');
    Route::post('/app-version/destroy', 'AppVersionController@destroy')->name('appversion.destroy');
    Route::post('/app-version/destroyselected', 'AppVersionController@destroySelected')->name('appversion.destroyselected');

    //Dashian Tihar Rate Setup
    Route::get('/dashain_tihar_setup', 'DashainTiharSetupController@create')->name('dashaintiharsetup');
    Route::post('/dashain_tihar_setup/store', 'DashainTiharSetupController@store')->name('dashaintiharsetupstore');

    //organization setup
    Route::get('/organization-setup', 'OrganizationSetupController@create')->name('organizationsetup');
    Route::post('/organization-setup/store', 'OrganizationSetupController@store')->name('organizationsetupstore');

    /* File Type Module */
    Route::get('/file-type', 'FileTypeController@index')->name('file-type');
    Route::get('/file-type/create', 'FileTypeController@create')->name('file-typecreate');
    Route::post('/file-type/store', 'FileTypeController@store')->name('file-typesave');
    Route::get('/file-type/edit/{id}', 'FileTypeController@edit')->name('file-typeedit');
    Route::post('/file-type/update/{id}', 'FileTypeController@update')->name('file-typeupdate');
    Route::post('/file-type/destroy', 'FileTypeController@destroy')->name('file-typedestroy');
    Route::post('/file-type/destroyselected', 'FileTypeController@destroySelected')->name('file-typedestroyselected');

    Route::get('/fiscal-year-attendance', 'FiscalYearAttendanceSumController@index')->name('fiscalyearattendancesum');
    Route::get('/fiscal-year-attendance/create', 'FiscalYearAttendanceSumController@create')->name('fiscalyearattendancesum-create');
    Route::post('/fiscal-year-attendance/store', 'FiscalYearAttendanceSumController@store')->name('fiscalyearattendancesum-store');
    Route::get('/fiscal-year-attendance/edit/{id}', 'FiscalYearAttendanceSumController@edit')->name('fiscalyearattendancesum-edit');
    Route::post('/fiscal-year-attendance/update/{id}', 'FiscalYearAttendanceSumController@update')->name('fiscalyearattendancesum-update');
    Route::post('/fiscal-year-attendance/destroy', 'FiscalYearAttendanceSumController@destroy')->name('fiscalyearattendancesum-destroy');
    Route::post('/fiscal-year-attendance/destroyselected', 'FiscalYearAttendanceSumController@destroySelected')->name('fiscalyearattendancesum-destroy-selected');
    Route::get('/fiscal-year-attendance-import', 'FiscalYearAttendanceSumController@import')->name('fiscalyearattendancesum-import');
    Route::post('/fiscal-year-attendance-import-save', 'FiscalYearAttendanceSumController@importStore')->name('fiscalyearattendancesum-import-store');


    /*Local Device Attendance*/
    Route::get('/localattendance', 'FetchAttendanceController@index')->name('localattendance');
    Route::get('/localattendance/create', 'FetchAttendanceController@create')->name('localattendance-create');
    Route::get('/localattendance/edit/{id}', 'FetchAttendanceController@edit')->name('localattendance-edit');
    Route::post('/localattendance/update/{id}', 'FetchAttendanceController@update')->name('localattendance-store');
    Route::post('/localattendance/store', 'FetchAttendanceController@store')->name('localattendance-store');
    Route::get('/localattendance/show/{id}', 'FetchAttendanceController@show')->name('localattendance-show');
    Route::get('/localattendance/print', 'FetchAttendanceController@print')->name('localattendance-print');
    Route::get('/localattendance/excel', 'FetchAttendanceController@excelExport')->name('localattendance-excel-export');
    Route::delete('/localattendance', 'FetchAttendanceController@destroy')->name('localattendance-destroy');

    Route::get('/localattendace/summary', 'FetchAttendanceController@summary')->name('localattendance-summary');
    Route::get('/localattendace/summary/show', 'FetchAttendanceController@summary_show')->name('localattendance-summary-show');
    Route::get('/localattendace/summary/export', 'FetchAttendanceController@summary_export')->name('localattendance-summary-export');

    Route::get('localattendace-daywise', 'FetchAttendanceController@daywiseindex')->name('localattendance-daywise');
    Route::get('localattendace-daywise/show', 'FetchAttendanceController@daywise_show')->name('localattendance-daywise-show');

    Route::get('localattendance/punch-out-warning', 'FetchAttendanceController@punchOutWarning')->name('localattendance-punch-out-warning');

    Route::post('/getmonthdatefromto', 'FetchAttendanceController@getMonthDateFromTo')->name('getmonthdatefromto');
    Route::get('overtime-work', 'FetchAttendanceController@overtimeworkIndex')->name('overtime-work-index');
    Route::get('overtime-work/show', 'FetchAttendanceController@overtimeworkShow')->name('overtime-work-show');
    Route::get('overtime-work/excel', 'FetchAttendanceController@overtimeworkExcelExport')->name('overtime-work-excel-export');
    /** Bulk force Attendance */
    Route::get('bulk-force-attendance', 'BulkForceAttendanceController@show')->name('bulk-force-show');
    Route::get('bulk-force-attendance/filter', 'BulkForceAttendanceController@filter')->name('bulk-force-show-filter');
    Route::post('bulk-force-attendance/filter-view', 'BulkForceAttendanceController@filterView')->name('bulk-force-show-filter-view');
    Route::post('bulk-force-attendance', 'BulkForceAttendanceController@store')->name('bulk-force-store');
    /** End Bulk force attendance */

    /*ALl Staff Monthly Attendance Nepal Re*/
    Route::get('all-staff-attendance-monthly', 'FetchAttendanceController@allStaffMontlyAttendanceIndex')->name('all-staff-attendance-monthly');
    Route::get('all-staff-attendance-monthly-show', 'FetchAttendanceController@allStaffMontlyAttendanceShow')->name('all-staff-attendance-monthly-show');
    /*ALl Staff Monthly Attendance Nepal Re End*/

    /*Manual Attendance*/
    Route::get('manual-attendance', 'ManualAttendanceController@index')->name('manual-attendance');
    Route::get('manual-attendance-filter', 'ManualAttendanceController@filter')->name('manual-attendance-filter');
    Route::post('manual-attendance-filter-view', 'ManualAttendanceController@filterView')->name('manual-attendance-filter-view');
    Route::post('manual-attendance', 'ManualAttendanceController@store')->name('manual-attendance-store');
    /*Manual Attendance END*/

    /*grade increment*/
    Route::get('grade-revision', 'GradeRevisionController@index')->name('grade-revision');
    Route::get('grade-revision/store', 'GradeRevisionController@reviseGrade')->name('grade-revision-store');
    Route::get('grade-revision/multiple', 'GradeRevisionController@reviseGradeMultiple')->name('grade-revision-multiple');

    /*shift setting*/
    Route::get('shift', 'ShiftController@index')->name('shift-index');
    Route::get('shift/create', 'ShiftController@create')->name('shift-create');
    Route::post('shift/store', 'ShiftController@store')->name('shift-store');
    Route::get('shift/edit/{id}', 'ShiftController@edit')->name('shift-edit');
    Route::patch('shift/update/{id}', 'ShiftController@update')->name('shift-update');
    Route::post('shift/destroy', 'ShiftController@destroy')->name('shift-destroy');
    Route::post('shift/destroy/selected', 'ShiftController@destroySelected')->name('shift-destroy-selected');
    Route::get('shift/visual', 'ShiftController@shiftVisual')->name('shift-visual');
    Route::post('get-shift-by-branch', 'ShiftController@by_branch')->name('get-shift-by-branch');
    Route::get('shift-import-index', 'ShiftController@shiftImportIndex')->name('shift-import-index');
    Route::post('shift-import', 'ShiftController@shiftImport')->name('shift-import');

    /*organization shift*/
    Route::get('organization-shift', 'OrganizationMastShiftController@index')->name('organization-shift-index');
    Route::get('organization-shift/create', 'OrganizationMastShiftController@create')->name('organization-shift-create');
    Route::post('organization-shift/store', 'OrganizationMastShiftController@store')->name('organization-shift-store');
    Route::get('organization-shift/edit/{id}', 'OrganizationMastShiftController@edit')->name('organization-shift-edit');
    Route::patch('organization-shift/update/{id}', 'OrganizationMastShiftController@update')->name('organization-shift-update');
    Route::post('organization-shift/destroy', 'OrganizationMastShiftController@destroy')->name('organization-shift-destroy');
    Route::post('organization-shift/destroy/selected', 'OrganizationMastShiftController@destroySelected')->name('organization-shift-destroy-selected');

    Route::get('change-staff-shift', 'ShiftController@changeShift')->name('change-shift');
    Route::get('change-staff-shift/filter', 'ShiftController@changeShiftFilter')->name('change-shift-filter');
    Route::post('change-staff-shift/store', 'ShiftController@changeShiftStore')->name('change-shift-store');

    Route::get('change-staff-weekend', 'ChangeWeekendController@index')->name('change-weekend');
    Route::get('change-staff-weekend/filter', 'ChangeWeekendController@staffList')->name('weekend-staff-list');
    Route::post('change-staff-weekend/store', 'ChangeWeekendController@store')->name('change-weekend-store');

    Route::get('staff-shift-import', 'ShiftController@staffShiftImportIndex')->name('staff-shift-import-index');
    Route::post('staff-shift-import', 'ShiftController@staffShiftImport')->name('staff-shift-import');

    Route::get('alternative-shift', 'AlternativeDayShiftController@index')->name('alternative-shift-index');
    Route::get('alternative-shift/create', 'AlternativeDayShiftController@create')->name('alternative-shift-create');
    Route::post('alternative-shift/store', 'AlternativeDayShiftController@store')->name('alternative-shift-store');
    Route::post('alternative-shift/destroy', 'AlternativeDayShiftController@destroy')->name('alternative-shift-destroy');
    Route::post('alternative-shift/destroy/selected', 'AlternativeDayShiftController@destroySelected')->name('alternative-shift-destroy-selected');


    /* System Leave Module Start*/
    Route::get('/systemleave', 'SystemLeaveMastController@index')->name('systemleave');
    Route::get('/systemleave/create', 'SystemLeaveMastController@create')->name('systemleavecreate');
    Route::post('/systemleave/store', 'SystemLeaveMastController@store')->name('systemleavesave');
    Route::get('/systemleave/edit/{id}', 'SystemLeaveMastController@edit')->name('systemleaveedit');
    Route::post('/systemleave/update/{id}', 'SystemLeaveMastController@update')->name('systemleaveupdate');
    Route::post('/systemleave/destroy', 'SystemLeaveMastController@destroy')->name('systemleavedestroy');
    Route::post('/systemleave/destroyselected', 'SystemLeaveMastController@destroySelected')->name('systemleavedestroyselected');


    /* System office Module Start*/
    Route::get('/systemoffice', 'SystemOfficeMastController@index')->name('systemoffice');
    Route::get('/systemoffice/create', 'SystemOfficeMastController@create')->name('systemofficecreate');
    Route::post('/systemoffice/store', 'SystemOfficeMastController@store')->name('systemofficesave');
    Route::get('/systemoffice/edit/{id}', 'SystemOfficeMastController@edit')->name('systemofficeedit');
    Route::post('/systemoffice/update/{id}', 'SystemOfficeMastController@update')->name('systemofficeupdate');
    Route::post('/systemoffice/destroy', 'SystemOfficeMastController@destroy')->name('systemofficedestroy');
    Route::post('/systemoffice/destroyselected', 'SystemOfficeMastController@destroySelected')->name('systemofficedestroyselected');
    Route::post('/systemoffice/toogle-alternative-shift', 'SystemOfficeMastController@toogleAlternativeShift')->name('toogle-office-alternative-shift');


    /* Staff Transfer Module Start*/
    Route::get('/stafftransfer', 'StaffTransferMastController@index')->name('staff-transfer');
    Route::get('/stafftransfer/create', 'StaffTransferMastController@create')->name('staff-transfer-create');
    Route::post('/stafftransfer/store', 'StaffTransferMastController@store')->name('staff-transfer-save');
    Route::get('/stafftransfer/edit/{id}', 'StaffTransferMastController@edit')->name('staff-transfer-edit');
    Route::post('/stafftransfer/update/{id}', 'StaffTransferMastController@update')->name('staff-transfer-update');
    Route::post('/stafftransfer/destroy', 'StaffTransferMastController@destroy')->name('staff-transfer-destroy');
    Route::post('/stafftransfer/destroyselected', 'StaffTransferMastController@destroySelected')->name('staff-transfer-destroy-selected');

    /* Staff Status Module Start*/
    Route::get('/staff-status', 'EmployeeStatusController@index')->name('staff-status');
    Route::get('/staff-status/create', 'EmployeeStatusController@create')->name('staff-status-create');
    Route::post('/staff-status/store', 'EmployeeStatusController@store')->name('staff-status-save');
    Route::get('/staff-status/edit/{id}', 'EmployeeStatusController@edit')->name('staff-status-edit');
    Route::post('/staff-status/update/{id}', 'EmployeeStatusController@update')->name('staff-status-update');
    Route::post('/staff-status/destroy', 'EmployeeStatusController@destroy')->name('staff-status-destroy');
    Route::post('/staff-status/destroyselected', 'EmployeeStatusController@destroySelected')->name('staff-status-destroy-selected');


    /* System Post Mast Transfer Module Start*/
    Route::get('/systempost', 'SystemPostMastController@index')->name('system-post');
    Route::get('/systempost/create', 'SystemPostMastController@create')->name('system-post-create');
    Route::post('/systempost/store', 'SystemPostMastController@store')->name('system-post-save');
    Route::get('/systempost/edit/{id}', 'SystemPostMastController@edit')->name('system-post-edit');
    Route::post('/systempost/update/{id}', 'SystemPostMastController@update')->name('system-post-update');
    Route::post('/systempost/destroy', 'SystemPostMastController@destroy')->name('system-post-destroy');
    Route::post('/systempost/destroyselected', 'SystemPostMastController@destroySelected')->name('system-post-destroy-selected');
    Route::get('/systempost/order', 'SystemPostMastController@orderPost')->name('system-post-order');
    Route::post('/systempost/order-save', 'SystemPostMastController@orderPostSave')->name('system-post-order-save');
    /* System Post Mast Transfer Module Start*/
    Route::get('/stafftype', 'StaffTypeController@index')->name('staff-type');
    Route::get('/stafftype/create', 'StaffTypeController@create')->name('staff-type-create');
    Route::post('/stafftype/store', 'StaffTypeController@store')->name('staff-type-save');
    Route::get('/stafftype/edit/{id}', 'StaffTypeController@edit')->name('staff-type-edit');
    Route::post('/stafftype/update/{id}', 'StaffTypeController@update')->name('staff-type-update');
    Route::post('/stafftype/destroy', 'StaffTypeController@destroy')->name('staff-type-destroy');
    Route::post('/stafftype/destroyselected', 'StaffTypeController@destroySelected')->name('staff-type-destroy-selected');


    /* Staff Main Mast Transfer Module Start*/
    Route::get('/staffmain', 'StaffMainMastController@index')->name('staff-main');
    Route::get('/staffmain/create', 'StaffMainMastController@create')->name('staff-main-create');
    Route::post('/staffmain/store', 'StaffMainMastController@store')->name('staff-main-save');
    Route::get('/staffmain/edit/{id}', 'StaffMainMastController@edit')->name('staff-main-edit');
    Route::get('/staffmain/viewdetail/{id}', 'StaffMainMastController@viewdetail')->name('staff-main-viewdetail');
    Route::post('/staffmain/update/{id}', 'StaffMainMastController@update')->name('staff-main-update');
    Route::post('/staffmain/uploadfile', 'StaffMainMastController@uploadFile')->name('staff-file-upload');
    Route::post('/staffmain/fileRemove', 'StaffMainMastController@fileRemove')->name('staff-file-remove');
    Route::get('/staffmain/fileDownload/{filename}', 'StaffMainMastController@fileDownload')->name('staff-file-download');
    Route::post('/staffmain/destroy', 'StaffMainMastController@destroy')->name('staff-main-destroy');
    Route::post('/staffmain/destroyselected', 'StaffMainMastController@destroySelected')->name('staff-main-destroy-selected');
    Route::post('/staffmain/showvdc', 'StaffMainMastController@showvdc')->name('staff-main-vdc-show');
    Route::post('/staffmain/showsalary', 'StaffMainMastController@showsalary')->name('staff-main-salary-show');
    Route::post('/staffmain/excelimport', 'StaffMainMastController@excelimport')->name('staff-main-excel-import');
    Route::get('/staffmain/excelexport', 'StaffMainMastController@excelExport')->name('staff-main-excel-export');
    Route::get('/staffmain/getstaff', 'StaffMainMastController@getStaff')->name('get-staff');
    Route::get('/staffmain/warning', 'StaffMainMastController@getWarningStaff')->name('staff-main-warning');
    Route::post('/staffmain/getonestaff', 'StaffMainMastController@getoneStaff')->name('get-one-staff');
    Route::post('/staffmain/bybranch', 'StaffMainMastController@by_branch')->name('get-staff-by-branch');
    Route::post('/staffmain/payroll/bybranch', 'StaffMainMastController@payroll_by_branch')->name('get-payroll-staff-by-branch');
    Route::post('/staffmain/warning/bybranch', 'StaffMainMastController@warning_by_branch')->name('get-warning-staff-by-branch');
    Route::post('/staffmain/checkmain-id-unique', 'StaffMainMastController@checkMainIdUnique')->name('check-main-id-unique');


    /** Job Type Alert */
    Route::get('/staffmain/job-type-alert', 'StaffJobTypeAlertController@index')->name('staff-job-type-alert');
    Route::get('/staffmain/job-type-alert/show', 'StaffJobTypeAlertController@show')->name('staff-job-type-alert.show');
    Route::get('/staffmain/job-type-alert/non-permanent-to-permanent', 'StaffJobTypeAlertController@nonPermanentToPermanent')->name('staff-job-type-alert.non-permanent-to-permanent');
    Route::get('/staffmain/job-type-alert/trainee-to-non-permanent', 'StaffJobTypeAlertController@traineeToNonPermanent')->name('staff-job-type-alert.trainee-to-non-permanent');
    Route::get('/staffmain/job-type-alert/age-limit', 'StaffJobTypeAlertController@ageLimit')->name('staff-job-type-alert.age-limit');
    Route::post('/staffmain/change-non-permanent-to-permanent', 'StaffJobTypeAlertController@changeNonPermanentToPermanent')->name('staff-job-type-alert.change-non-permanent-to-permanent');
    Route::post('/staffmain/change-above-age-contract', 'StaffJobTypeAlertController@changeAboveAgeContract')->name('staff-job-type-alert.change-above-age-contract');

    Route::post('/staffmain/change-trainee-to-non-permanent', 'StaffJobTypeAlertController@changeTraineeToNonPermanent')->name('staff-job-type-alert.change-trainee-to-non-permanent');
    //    Route::get('/staffmain/get-promotion-alert', 'StaffMainMastController@getStaffPromotionAlertIndex')->name('staff-get-promotion-alert');


    //excel import of staff
    Route::get('staffmain/excel', 'StaffMainMastController@excelIndex')->name('excel-index');
    Route::post('staffmain/excel/store', 'StaffMainMastController@excelStore')->name('excel-store');
    Route::post('staffmain/excel/company-store', 'StaffMainMastController@companyExcelStore')->name('company-excel-store');
    Route::post('staffmain/excel/update', 'StaffMainMastController@accountNumberImport')->name('excel-update');
    Route::post('staffleaveimport', 'StaffMainMastController@staffLeaveImport')->name('staff-leave-import');
    Route::post('staffsundryimport', 'StaffMainMastController@staffSundryImport')->name('staff-sundry-import');
    Route::post('graderevisionimport', 'StaffMainMastController@staffGradeRevisonImport')->name('staff-grade-revision-import');
    Route::post('staff-tally-import', 'StaffMainMastController@checkStaffDataWithExcel')->name('staff-tally-import');
    Route::post('last-main-id-of-branch', 'StaffMainMastController@lastMainIdofBranch')->name('last-main-id-of-branch');
    Route::post('staff-allowance-import', 'StaffMainMastController@importStaffAllowance')->name('staff-allowance-import');

    /*Staff Nominee*/
    Route::get('staffmain/staff-nominee/{id}', 'StaffMainMastController@staffNominee')->name('staff-nominee');
    Route::post('staffmain/staff-nominee/{id}/store', 'StaffMainMastController@staffNomineeStore')->name('staff-nominee-store');

    /*Staff Payment*/
    Route::get('staffmain/staff-payment/{id}', 'StaffMainMastController@staffPayment')->name('staff-payment');
    Route::post('staffmain/staff-payment/{id}/store', 'StaffMainMastController@staffPaymentStore')->name('staff-payment-store');
    Route::delete('staffmain/staff-payment/delete', 'StaffMainMastController@staffPaymentDelete')->name('staff-payment-delete');

    /*Staff Grade*/
    Route::get('staffmain/grade/{id}', 'StaffGradeController@index')->name('staff-grade');
    Route::post('staffmain/grade/{id}/store', 'StaffGradeController@store')->name('staff-grade-store');
    Route::delete('staffmain/grade/delete', 'StaffGradeController@destroy')->name('staff-grade-delete');

    /*Staff Position*/
    Route::get('staffmain/post/{id}', 'StaffJobPositionController@index')->name('staff-position');
    Route::post('staffmain/post/{id}/store', 'StaffJobPositionController@store')->name('staff-position-store');
    Route::delete('staffmain/post/delete', 'StaffJobPositionController@destroy')->name('staff-position-delete');
    Route::get('staffmain/post-transferMainRecordToStaffJobPosition', 'StaffJobPositionController@transferMainRecordToStaffJobPosition')->name('transfer-post-record');


    /*Staff Job Information*/
    Route::get('staffmain/staff-job-information/{id}', 'StaffMainMastController@staffJobInformation')->name('staff-job-information');
    Route::post('staffmain/staff-job-information/{id}/store', 'StaffMainMastController@staffJobInformationStore')->name('staff-job-information-store');

    /*Staff Work Schedule*/
    Route::get('staffmain/staff-work-schedule/{id}', 'StaffMainMastController@staffWorkschedule')->name('staff-work-schedule');
    Route::post('staffmain/staff-work-schedule/{id}/store', 'StaffMainMastController@staffWorkscheduleStore')->name('staff-work-schedule-store');

    /*Staff Leave Balance*/
    Route::get('staffmain/staff-leave-balance/{id}', 'StaffMainMastController@staffLeaveBalance')->name('staff-leave-balance');
    Route::post('staffmain/staff-leave-balance/{id}/store', 'StaffMainMastController@staffLeaveBalanceStore')->name('staff-leave-balance-store');

    /*Staff Leave Balance*/
    Route::get('staffmain/staff-salary/{id}', 'StaffMainMastController@staffSalary')->name('staff-salary');
    Route::post('staffmain/staff-salary/{id}/store', 'StaffMainMastController@staffSalaryStore')->name('staff-salary-store');
    Route::delete('staffmain/staff-salary', 'StaffMainMastController@deleteStaffSalary')->name('staff-salary-delete');

    /*staff training*/
    Route::get('/staffmain/training-detail/{staff_central_id}', 'TrainingDetailController@index')->name('training-detail-index');
    Route::get('/staffmain/training-detail/create/{staff_central_id}', 'TrainingDetailController@create')->name('training-detail-create');
    Route::post('/staffmain/training-detail/store/{staff_central_id}', 'TrainingDetailController@store')->name('training-detail-save');
    Route::get('/staffmain/training-detail/edit/{id}', 'TrainingDetailController@edit')->name('training-detail-edit');
    Route::post('/staffmain/training-detail/update/{id}', 'TrainingDetailController@update')->name('training-detail-update');
    Route::post('/staffmain/training-detail/destroy', 'TrainingDetailController@destroy')->name('training-detail-destroy');
    Route::post('/staffmain/training-detail/destroyselected', 'TrainingDetailController@destroySelected')->name('training-detail-destroy-selected');

    /* System Job Type Module Start*/
    Route::get('/systemjobtype', 'SystemJobTypeMastController@index')->name('system-jobtype');
    Route::get('/systemjobtype/create', 'SystemJobTypeMastController@create')->name('system-jobtype-create');
    Route::post('/systemjobtype/store', 'SystemJobTypeMastController@store')->name('system-jobtype-save');
    Route::get('/systemjobtype/edit/{id}', 'SystemJobTypeMastController@edit')->name('system-jobtype-edit');
    Route::post('/systemjobtype/update/{id}', 'SystemJobTypeMastController@update')->name('system-jobtype-update');
    Route::post('/systemjobtype/destroy', 'SystemJobTypeMastController@destroy')->name('system-jobtype-destroy');
    Route::post('/systemjobtype/destroyselected', 'SystemJobTypeMastController@destroySelected')->name('system-jobtype-destroy-selected');

    /* System Job Type Module Start*/
    Route::get('/department', 'DepartmentController@index')->name('department');
    Route::get('/department/create', 'DepartmentController@create')->name('department-create');
    Route::post('/department/store', 'DepartmentController@store')->name('department-save');
    Route::get('/department/edit/{id}', 'DepartmentController@edit')->name('department-edit');
    Route::post('/department/update/{id}', 'DepartmentController@update')->name('department-update');
    Route::post('/department/destroy', 'DepartmentController@destroy')->name('department-destroy');
    Route::post('/department/destroyselected', 'DepartmentController@destroySelected')->name('department-destroy-selected');

    /* Holiday Type Module Start*/
    Route::get('/holiday', 'SystemHolidayMastController@index')->name('system-holiday');
    Route::get('/holiday/create', 'SystemHolidayMastController@create')->name('system-holiday-create');
    Route::post('/holiday/store', 'SystemHolidayMastController@store')->name('system-holiday-save');
    Route::get('/holiday/edit/{id}', 'SystemHolidayMastController@edit')->name('system-holiday-edit');
    Route::post('/holiday/update/{id}', 'SystemHolidayMastController@update')->name('system-holiday-update');
    Route::post('/holiday/destroy', 'SystemHolidayMastController@destroy')->name('system-holiday-destroy');
    Route::post('/holiday/destroyselected', 'SystemHolidayMastController@destroySelected')->name('system-holiday-destroy-selected');

    Route::get('/public-holiday', 'PublicHolidayController@index')->name('public-holiday');
    Route::get('/public-holiday/create', 'PublicHolidayController@create')->name('public-holiday-create');
    Route::post('/public-holiday/store', 'PublicHolidayController@store')->name('public-holiday-save');
    Route::get('/public-holiday/edit/{id}', 'PublicHolidayController@edit')->name('public-holiday-edit');
    Route::post('/public-holiday/update/{id}', 'PublicHolidayController@update')->name('public-holiday-update');
    Route::post('/public-holiday/destroy', 'PublicHolidayController@destroy')->name('public-holiday-destroy');
    Route::post('/public-holiday/destroyselected', 'PublicHolidayController@destroySelected')->name('public-holiday-destroy-selected');

    /* Holiday Type Module Start*/
    Route::get('/systemtds', 'SystemTdsMastController@index')->name('system-tds');
    Route::get('/systemtds/create', 'SystemTdsMastController@create')->name('system-tds-create');
    Route::post('/systemtds/store', 'SystemTdsMastController@store')->name('system-tds-save');
    Route::get('/systemtds/edit/{id}', 'SystemTdsMastController@edit')->name('system-tds-edit');
    Route::post('/systemtds/update/{id}', 'SystemTdsMastController@update')->name('system-tds-update');
    Route::post('/systemtds/destroy', 'SystemTdsMastController@destroy')->name('system-tds-destroy');
    Route::post('/systemtds/destroyselected', 'SystemTdsMastController@destroySelected')->name('system-tds-destroy-selected');


    Route::resource('pictures', 'PictureController', ['only' => ['index', 'store', 'destroy']]);

//fiscal year
    Route::get('/fiscalyear', 'FiscalYearController@index')->name('fiscal-year');
    Route::get('/fiscalyear/create', 'FiscalYearController@create')->name('fiscal-year-create');
    Route::post('/fiscalyear/store', 'FiscalYearController@store')->name('fiscal-year-save');
    Route::get('/fiscalyear/edit/{id}', 'FiscalYearController@edit')->name('fiscal-year-edit');
    Route::post('/fiscalyear/update/{id}', 'FiscalYearController@update')->name('fiscal-year-update');
    Route::post('/fiscalyear/destroy', 'FiscalYearController@destroy')->name('fiscal-year-destroy');
    Route::post('/fiscalyear/destroyselected', 'FiscalYearController@destroySelected')->name('fiscal-year-destroy-selected');


//calender holiday year
    Route::get('/calenderholiday', 'CalenderHolidayController@index')->name('calender-holiday');
    Route::get('/calenderholiday/create', 'CalenderHolidayController@create')->name('calender-holiday-create');
    Route::post('/calenderholiday/store', 'CalenderHolidayController@store')->name('calender-holiday-save');
    Route::get('/calenderholiday/search', 'CalenderHolidayController@search')->name('calender-detail-search');
    Route::get('/calenderholiday/edit/{id}', 'CalenderHolidayController@edit')->name('calender-holiday-edit');
    Route::post('/calenderholiday/update/{id}', 'CalenderHolidayController@update')->name('calender-holiday-update');
    Route::post('/calenderholiday/destroy', 'CalenderHolidayController@destroy')->name('calender-holiday-destroy');
    Route::post('/calenderholiday/destroyselected', 'CalenderHolidayController@destroySelected')->name('calender-holiday-destroy-selected');
    Route::post('/calenderholiday/check-public-holiday', 'CalenderHolidayController@check_public_holiday')->name('check-public-holiday');
    Route::post('/calenderholiday/check-conditions', 'CalenderHolidayController@calenderHolidayCondition')->name('check-calender-holiday-conditions');

//Attendance Detail holiday year
    Route::get('/attendancedetail/show/', 'AttendanceDetailController@index')->name('attendance-detail');
    Route::get('/attendancedetail/show/{id}', 'AttendanceDetailController@show')->name('attendance-detail-show');
    Route::get('/attendancedetail/download/{id}', 'AttendanceDetailController@download')->name('attendance-detail-download');
    Route::get('/attendancedetail/create', 'AttendanceDetailController@create')->name('attendance-detail-create');
    Route::get('/attendancedetail/edit/{id}', 'AttendanceDetailController@edit')->name('attendance-detail-edit');
    Route::post('/attendancedetail/update/{id}', 'AttendanceDetailController@update')->name('attendance-detail-update');
    Route::post('/attendancedetail/destroy', 'AttendanceDetailController@destroy')->name('attendance-detail-destroy');
    Route::post('/attendancedetail/destroyselected', 'AttendanceDetailController@destroySelected')->name('attendance-detail-destroy-selected');
    Route::post('/attendancedetail/excelimport', 'AttendanceDetailController@excelimport')->name('attendance-detail-excel-import');
    Route::get('/attendancedetail/payroll', 'AttendanceDetailController@payroll')->name('attendance-detail-payroll');
    Route::get('/attendancedetail/search', 'AttendanceDetailController@search')->name('attendance-detail-search');
    Route::get('/attendancedetail/payroll/action/{id}', 'AttendanceDetailController@action')->name('attendance-action');
    Route::post('/attendancedetail/payroll/calculate/', 'AttendanceDetailController@calculate')->name('attendance-calculate');
    Route::post('/attendancedetail/payroll/calculate/confirm/{payroll_id}', 'AttendanceDetailController@calculate_save')->name('attendance-calculate-confirm');
    Route::post('/payroll/create', 'AttendanceDetailController@payrollCreate')->name('payroll-create');
    Route::get('/payroll/staff', 'AttendanceDetailController@listPayrollStaffofBranch')->name('listPayrollStaffofBranch');
    Route::post('/attendancedetail/fetch-to-detail', 'AttendanceDetailController@fetchAttendanceToAttendanceDetail')->name('fetch-to-detail');
    Route::post('/attendancedetail/get-netpayment', 'AttendanceDetailController@getNetpayment')->name('get-netpayment');
    Route::get('/attendancedetail/warning-before-payroll', 'AttendanceDetailController@warningBeforePayroll')->name('warning-before-payroll');
    Route::get('/attendancedetail/payroll-rollback/{id}', 'AttendanceDetailController@payrollRollback')->name('payroll-rollback');

    Route::post('/payroll/attendancedetail/deleted/{payroll_id}', 'AttendanceDetailController@destroyAttendanceDetail')->name('delete-payroll-attendance-detail');

    Route::get('payroll-difference', 'PayrollDifferenceController@index')->name('payroll-difference');
    Route::get('payroll-difference-show', 'PayrollDifferenceController@show')->name('payroll-difference-show');
    Route::get('payroll-difference-single-confirm', 'PayrollDifferenceController@payrollDifferenceSingleConfirm')->name('payroll-difference-single-confirm');
    Route::get('payroll-difference/payroll/{id}', 'PayrollDifferenceController@payrollInfo')->name('payroll-difference-confirmed');
    Route::post('payroll-difference/payroll/calculate', 'PayrollDifferenceController@totalPayrollDifference')->name('payroll-difference-calculate');

    Route::get('staff/payroll-detail', 'StaffPayrollDetailController@index')->name('staff-payroll-detail');
    Route::get('staff/payroll-detail/show', 'StaffPayrollDetailController@show')->name('staff-payroll-detail-show');


    /*Nepal Reinsurance payroll*/
    Route::get('nepalre/payroll/{payrollid}', 'AttendanceDetailController@nepalrepayroll')->name('nepal-re-payroll');
    Route::post('nepalre/payroll/confirm', 'AttendanceDetailController@nepalrepayroll_confirm')->name('nepal-re-payroll-confirm');
    Route::get('nepalre/tax-calculation/index', 'TaxCalculationController@nepalReTaxCalculationIndex')->name('nepal-re-tax-calculation-index');
    Route::get('nepalre/tax-calculation/calculate', 'TaxCalculationController@nepalReTaxCalculation')->name('nepal-re-tax-calculation');
    Route::post('nepalre/tax-calculation/calculate/save', 'TaxCalculationController@taxCalculationSave')->name('nepal-re-tax-calculation-save');
    /*Nepal Reinsurance payroll module end*/

    Route::get('report/staff/payroll/summary', 'ReportController@staff_payroll_summary')->name('staff_payroll_summary');
    Route::get('report/staff/payroll/summary/show', 'ReportController@staff_payroll_summary_show')->name('staff_payroll_summary-show');

    Route::get('get-payroll-name', 'PayrollDetailController@getPayrollName')->name('get-payroll-name');

    /*Overtime Work Payroll*/
    Route::get('overtime-payroll', 'OvertimePayrollController@index')->name('overtime-payroll-index');
    Route::get('overtime-payroll/calculate', 'OvertimePayrollController@calculate')->name('overtime-payroll-calculate');
    /*Overtime Work Payroll End*/
    /* Bank Module */
    Route::get('/bank', 'BankControllerMast@index')->name('bank-index');
    Route::get('/bank/create', 'BankControllerMast@create')->name('bank-create');
    Route::post('/bank/store', 'BankControllerMast@store')->name('bank-save');
    Route::get('/bank/edit/{id}', 'BankControllerMast@edit')->name('bank-edit');
    Route::post('/bank/update/{id}', 'BankControllerMast@update')->name('bank-update');
    Route::post('/bank/destroy', 'BankController@destroyMast')->name('bank-destroy');
    Route::post('/bank/destroyselected', 'BankControllerMast@destroySelected')->name('bank-destroy-selected');


    /* House Loan Module */
    Route::get('/houseloan', 'HouseLoanControllerMast@index')->name('houseloan-index');
    Route::get('/houseloan/create', 'HouseLoanControllerMast@create')->name('houseloan-create');
    Route::post('/houseloan/store', 'HouseLoanControllerMast@store')->name('houseloan-save');
    Route::get('/houseloan/edit/{id}', 'HouseLoanControllerMast@edit')->name('houseloan-edit');
    Route::post('/houseloan/update/{id}', 'HouseLoanControllerMast@update')->name('houseloan-update');
    Route::get('/houseloan/detail/{id}', 'HouseLoanControllerMast@show')->name('houseloan-show');
    Route::get('/houseloan/detail/export/{id}', 'HouseLoanControllerMast@detailExport')->name('houseloan-excel-export');
    Route::post('/houseloan/destroy', 'HouseLoanControllerMast@destroy')->name('houseloan-destroy');
    Route::post('/houseloan/destroyselected', 'HouseLoanControllerMast@destroySelected')->name('houseloan-destroy-selected');
    Route::post('/houseloan/check', 'HouseLoanControllerMast@check_house_loan')->name('house-loan-check');

    /* House Loan Diff Income Module */
    Route::get('/house-loan-diff-income', 'HouseLoanDiffIncomeController@index')->name('house-loan-diff-income-index');
    Route::get('/house-loan-diff-income/modify', 'HouseLoanDiffIncomeController@modify')->name('house-loan-diff-income-modify');
    Route::post('/house-loan-diff-income/store', 'HouseLoanDiffIncomeController@store')->name('house-loan-diff-income-save');
    Route::post('/house-loan-diff-income/destroy', 'HouseLoanDiffIncomeController@destroy')->name('house-loan-diff-income-destroy');
    Route::post('/house-loan-diff-income/destroyselected', 'HouseLoanDiffIncomeController@destroySelected')->name('house-loan-diff-income-destroy-selected');

    Route::post('get-previous-house-loan-diff-income-by-filter', 'Ajax\HouseLoanDiffIncomeController@getPreviousByFilter')->name('get-previous-house-loan-diff-income-by-filter');

    /* Sundry Loan Module */
    Route::get('/sundry', 'SundryLoanControllerTrans@index')->name('sundryloan-index');
    Route::get('/sundry/create', 'SundryLoanControllerTrans@create')->name('sundryloan-create');
    Route::post('/sundry/store', 'SundryLoanControllerTrans@store')->name('sundryloan-save');
    Route::get('/sundry/edit/{id}', 'SundryLoanControllerTrans@edit')->name('sundryloan-edit');
    Route::post('/sundry/update/{id}', 'SundryLoanControllerTrans@update')->name('sundryloan-update');
    Route::post('/sundry/update/inprogress/{id}', 'SundryLoanControllerTrans@update_inprogress')->name('sundryloan-update-inprogress');
    Route::get('/sundry/detail/{id}', 'SundryLoanControllerTrans@show')->name('sundryloan-detail');
    Route::post('/sundry/destroy', 'SundryLoanControllerTrans@destroy')->name('sundryloan-destroy');
    Route::post('/sundry/destroyselected', 'SundryLoanControllerTrans@destroySelected')->name('sundry-destroy-selected');

    Route::get('/sundry-type', 'SundryTypeController@index')->name('sundry-type-index');
    Route::get('/sundry-type/create', 'SundryTypeController@create')->name('sundry-type-create');
    Route::post('/sundry-type/store', 'SundryTypeController@store')->name('sundry-type-save');
    Route::get('/sundry-type/edit/{id}', 'SundryTypeController@edit')->name('sundry-type-edit');
    Route::post('/sundry-type/update/{id}', 'SundryTypeController@update')->name('sundry-type-update');
    Route::post('/sundry-type/destroy', 'SundryTypeController@destroy')->name('sundry-type-destroy');
    Route::post('/sundry-type/destroyselected', 'SundryTypeController@destroySelected')->name('sundry-type-destroy-selected');

    /* vehical Loan Module */
    Route::get('/vehicalloan', 'VehicalLoanControllerTrans@index')->name('vehicalloan-index');
    Route::get('/vehicalloan/create', 'VehicalLoanControllerTrans@create')->name('vehicalloan-create');
    Route::post('/vehicalloan/store', 'VehicalLoanControllerTrans@store')->name('vehicalloan-save');
    Route::get('/vehicalloan/edit/{id}', 'VehicalLoanControllerTrans@edit')->name('vehicalloan-edit');
    Route::post('/vehicalloan/update/{id}', 'VehicalLoanControllerTrans@update')->name('vehicalloan-update');
    Route::post('/vehicalloan/destroy', 'VehicalLoanControllerTrans@destroy')->name('vehicalloan-destroy');
    Route::post('/vehicalloan/destroyselected', 'VehicalLoanControllerTrans@destroySelected')->name('vehicalloan-destroy-selected');
    Route::post('/vehicalloan/check', 'VehicalLoanControllerTrans@check_vehicle_loan')->name('vehicle-loan-check');

    Route::get('/vehicle-loan-diff-income', 'VehicleLoanDiffIncomeController@index')->name('vehicle-loan-diff-income-index');
    Route::get('/vehicle-loan-diff-income/modify', 'VehicleLoanDiffIncomeController@modify')->name('vehicle-loan-diff-income-modify');
    Route::post('/vehicle-loan-diff-income/store', 'VehicleLoanDiffIncomeController@store')->name('vehicle-loan-diff-income-save');
    Route::post('/vehicle-loan-diff-income/destroy', 'VehicleLoanDiffIncomeController@destroy')->name('vehicle-loan-diff-income-destroy');
    Route::post('/vehicle-loan-diff-income/destroyselected', 'VehicleLoanDiffIncomeController@destroySelected')->name('vehicle-loan-diff-income-destroy-selected');

    Route::post('get-previous-vehicle-loan-diff-income-by-filter', 'Ajax\VehicleLoanDiffIncomeController@getPreviousByFilter')->name('get-previous-vehicle-loan-diff-income-by-filter');


    /* vehical Loan Module */
    Route::get('/loan-deduct', 'LoanDeductController@index')->name('loan-deduct-index');
    Route::get('/loan-deduct/create', 'LoanDeductController@create')->name('loan-deduct-create');
    Route::post('/loan-deduct/store', 'LoanDeductController@store')->name('loan-deduct-save');
    Route::get('/loan-deduct/edit/{id}', 'LoanDeductController@edit')->name('loan-deduct-edit');
    Route::post('/loan-deduct/update/{id}', 'LoanDeductController@update')->name('loan-deduct-update');
    Route::post('/loan-deduct/destroy', 'LoanDeductController@destroy')->name('loan-deduct-destroy');
    Route::post('/loan-deduct/destroyselected', 'LoanDeductController@destroySelected')->name('loan-deduct-destroy-selected');
    Route::post('/loan-deduct/check', 'LoanDeductController@check_vehicle_loan')->name('vehicle-loan-check');
    Route::get('/loan-deduct/show', 'LoanDeductController@show')->name('loan-deduct-show');

    Route::post('loan-deduct-attendance/filter-view', 'Ajax\LoanDeductController@filterView')->name('loan-deduct-show-filter-view');

    /* allowance  Module */
    Route::get('/allowance', 'AllowanceControllerMast@index')->name('allowance-index');
    Route::get('/allowance/create', 'AllowanceControllerMast@create')->name('allowance-create');
    Route::post('/allowance/store', 'AllowanceControllerMast@store')->name('allowance-save');
    Route::get('/allowance/edit/{id}', 'AllowanceControllerMast@edit')->name('allowance-edit');
    Route::post('/allowance/update/{id}', 'AllowanceControllerMast@update')->name('allowance-update');
    Route::post('/allowance/destroy', 'AllowanceControllerMast@destroy')->name('allowance-destroy');
    Route::post('/allowance/destroyselected', 'AllowanceControllerMast@destroySelected')->name('allowance-destroy-selected');

    /*Bonuses*/
    Route::get('/bonuses', 'BonusesController@index')->name('bonuses.index');
    Route::get('/bonuses/create', 'BonusesController@create')->name('bonuses.create');
    Route::post('/bonuses/store', 'BonusesController@store')->name('bonuses.store');
    Route::get('/bonuses/edit/{id}', 'BonusesController@edit')->name('bonuses.edit');
    Route::patch('/bonuses/update/{id}', 'BonusesController@update')->name('bonuses.update');
    Route::post('/bonuses/destroy', 'BonusesController@destroy')->name('bonuses.destroy');
    Route::post('/bonuses/list-all', 'BonusesController@listAll')->name('bonuses.listall');
    Route::post('/bonuses/bulk', 'BonusesController@bulkInsert')->name('bonuses.bulkinsert');
    Route::get('bonuses/filter-view', 'BonusesController@filterView')->name('bonuses.filterview');
    Route::post('bonuses/delete-selected', 'BonusesController@deleteSelected')->name('staff-bonuses-destroy-selected');

    /*Staff Insurance Premium*/
    Route::get('/staff-insurance-premium', 'StaffInsurancePremiumController@index')->name('staff-insurance-premium-index');
    Route::get('/staff-insurance-premium/create', 'StaffInsurancePremiumController@create')->name('staff-insurance-premium-create');
    Route::post('/staff-insurance-premium/store', 'StaffInsurancePremiumController@store')->name('staff-insurance-premium-store');
    Route::get('/staff-insurance-premium/edit/{id}', 'StaffInsurancePremiumController@edit')->name('staff-insurance-premium-edit');
    Route::patch('/staff-insurance-premium/update/{id}', 'StaffInsurancePremiumController@update')->name('staff-insurance-premium-update');
    Route::post('/staff-insurance-premium/destroy', 'StaffInsurancePremiumController@destroy')->name('staff-insurance-premium-destroy');
    Route::post('/staff-insurance-premium/destroy-selected', 'StaffInsurancePremiumController@deleteSelected')->name('staff-insurance-destroy-selected');

    /*Calculate Leave Balance Module*/
    Route::get('/calculate-leave-balance/filter-view', 'CalculateLeaveBalanceController@filterView')->name('calculate-leave-balance-filter');
    Route::get('/calculate-leave-balance/index', 'CalculateLeaveBalanceController@index')->name('calculate-leave-balance-index');
    Route::post('/calculate-leave-balance/', 'CalculateLeaveBalanceController@store')->name('calculate-leave-balance-store');

    /* leavebalance  Module */
    Route::get('/leavebalance', 'LeaveBalanceController@index')->name('leavebalance-index');
    Route::get('/leavebalance/create', 'LeaveBalanceController@create')->name('leavebalance-create');
    Route::post('/leavebalance/store', 'LeaveBalanceController@store')->name('leavebalance-save');
    Route::get('/leavebalance/edit/{id}', 'LeaveBalanceController@edit')->name('leavebalance-edit');
    Route::post('/leavebalance/update/{id}', 'LeaveBalanceController@update')->name('leavebalance-update');
    Route::post('/leavebalance/destroy', 'LeaveBalanceController@destroy')->name('leavebalance-destroy');
    Route::post('/leavebalance/destroyselected', 'LeaveBalanceController@destroySelected')->name('leavebalance-destroy-selected');
    Route::get('/leavebalance/search', 'LeaveBalanceController@search')->name('leavebalance-search');
    Route::get('/leavebalance/details', 'LeaveBalanceController@getDetails')->name('get-leave-balance-details');
    Route::post('/leavebalance/import-leave-balance', 'LeaveBalanceController@importLeaveBalance')->name('import-leave-balance');

    /* leaverequest module */
    Route::get('/leaverequest', 'LeaveRequestController@index')->name('leaverequest-index');
    Route::get('/leaverequest/create', 'LeaveRequestController@create')->name('leaverequest-create');
    Route::post('/leaverequest/store', 'LeaveRequestController@store')->name('leaverequest-save');
    Route::get('/leaverequest/search', 'LeaveRequestController@search')->name('leave-request-search');
    Route::get('/leaverequest/edit/{id}', 'LeaveRequestController@edit')->name('leaverequest-edit');
    Route::post('/leaverequest/update/{id}', 'LeaveRequestController@update')->name('leaverequest-update');
    Route::post('/leaverequest/approve', 'LeaveRequestController@approve')->name('leaverequest-approve');
    Route::post('/leaverequest/reject', 'LeaveRequestController@reject')->name('leaverequest-reject');
    Route::post('/leaverequest/destroy', 'LeaveRequestController@destroy')->name('leaverequest-destroy');
    Route::post('/leaverequest/destroyselected', 'LeaveRequestController@destroySelected')->name('leaverequest-destroy-selected');
    Route::get('/leaverequest/show/{id}', 'LeaveRequestController@show')->name('leaverequest-show');
    /* end of leaverequest module */


    /*Dashain Payment*/
    Route::get('/dashain-payment', 'TransDashainPaymentController@index')->name('dashain-payment');
    Route::post('/dashain-payment/show', 'TransDashainPaymentController@show')->name('dashain-payment-show');
    Route::post('/dashain-payment/confirm', 'TransDashainPaymentController@confirm')->name('dashain-payment-confirm');

    /*Tihar Payment*/
    Route::get('/tihar-payment', 'TransTiharPaymentController@index')->name('tihar-payment');
    Route::post('/tihar-payment/show', 'TransTiharPaymentController@show')->name('tihar-payment-show');
    Route::post('/tihar-payment/confirm', 'TransTiharPaymentController@confirm')->name('tihar-payment-confirm');

    /* Reports  */
    Route::get('/reports/taxstatement', 'ReportController@taxStatement')->name('taxstatement');
    Route::get('/reports/taxstatement/personal', 'ReportController@taxStatement_personal')->name('taxstatement-personal');
    Route::get('/reports/social-security-tax-statement', 'ReportController@socialSecurityTaxStatement')->name('social-security-tax-statement');
    Route::get('/reports/social-security-tax-statement/personal', 'ReportController@socialSecurityTaxStatementPersonal')->name('social-security-tax-statement-personal');
    Route::get('/reports/bankstatement', 'ReportController@bankStatement')->name('bankstatement');
    Route::get('/reports/bankstatement/export', 'ReportController@bankStatementExport')->name('bankstatement-export');
    Route::get('/reports/cashstatement', 'ReportController@cashStatement')->name('cashstatement');
    Route::get('/reports/cashstatement/export', 'ReportController@cashStatementExport')->name('cashstatement-export');
    Route::get('/reports/sundry', 'ReportController@sundry')->name('sundry-report');
    Route::get('/reports/pfledger', 'ReportController@pfLedger')->name('pfledger');
    Route::get('/reports/pfledger/personal', 'ReportController@pfLedger_personal')->name('pfledger-personal');
    Route::get('/reports/citledger', 'ReportController@citLedger')->name('citledger');
    Route::get('/reports/citledger/personal', 'ReportController@citLedger_personal')->name('citledger-personal');
    Route::get('/reports/houseloanstatement', 'ReportController@homeloanStatement')->name('houseloanstatement');
    Route::get('/reports/vehicleloanstatement', 'ReportController@vehicleloanStatement')->name('vehicleloanstatement');
    Route::get('/reports/summary', 'ReportController@summary')->name('summary');
    Route::get('/reports/leavebalance', 'ReportController@leavebalance')->name('leavebalancestatement');
    Route::get('/reports/leavebalance/show', 'ReportController@leavebalanceshow')->name('leavebalancestatementshow');
    Route::get('/reports/dynamic-report-index', 'ReportController@dynamicReportIndex')->name('dynamicReportIndex');
    Route::get('/reports/dynamic-report', 'ReportController@dynamicReport')->name('dynamicReport');
    Route::get('/reports/dynamic-report/download', 'ReportController@dynamicReportDownload')->name('dynamicReport.download');

    /*User management roles------ENTRUST*/
    /*role*/
    Route::get('role', 'RoleController@index')->name('role-index');
    Route::get('role/create', 'RoleController@create')->name('role-create');
    Route::post('role/store', 'RoleController@store')->name('role-store');
    Route::get('role/{id}/edit', 'RoleController@edit')->name('role-edit');
    Route::patch('role/{id}/update', 'RoleController@update')->name('role-update');
    Route::post('role/destroy', 'RoleController@destroy')->name('role-destroy');


    Route::get('permission', 'PermissionController@index')->name('permission-index');
    Route::get('permission/create', 'PermissionController@create')->name('permission-create');
    Route::post('permission/store', 'PermissionController@store')->name('permission-store');
    Route::post('/permission/add', 'PermissionController@add')->name('permission-add');
    Route::post('/permission/addmenu', 'PermissionController@displayNameStore')->name('permission-addmenu');
    Route::post('permission/destroy', 'PermissionController@destroy')->name('permission-destroy');
    Route::get('send-permission-to-kb', 'PermissionController@sendPermissionToKB')->name('send-permission-to-kb');
    Route::get('get-permission-from-kb', 'PermissionController@getPermission')->name('get-permission-from-kb');

    Route::get('user', 'UserController@index')->name('user-index');
    Route::get('user/create', 'UserController@create')->name('user-create');
    Route::post('user/store', 'UserController@store')->name('user-store');
    Route::get('user/{id}/edit', 'UserController@edit')->name('user-edit');
    Route::patch('user/{id}/update', 'UserController@update')->name('user-update');
    Route::post('user/destroy', 'UserController@destroy')->name('user-destroy');
    Route::get('user/change-password', 'UserController@changepwd')->name('user-change-password');
    Route::patch('user/change-password-update', 'UserController@changepwdUpdate')->name('user-password-update');


    Route::get('assignrole', 'AssignRoleController@index')->name('assignrole-index');
    Route::get('assignrole/edit/{id}', 'AssignRoleController@edit')->name('assignrole-edit');
    Route::patch('assignrole/update/{id}', 'AssignRoleController@update')->name('assignrole-update');


    /* Staff CIT Deduction Module */
    Route::get('/staff-cit-deduction', 'StaffCitDeductionController@index')->name('staff-cit-deduction-index');
    Route::get('/staff-cit-deduction/create', 'StaffCitDeductionController@create')->name('staff-cit-deduction-create');
    Route::post('/staff-cit-deduction/store', 'StaffCitDeductionController@store')->name('staff-cit-deduction-save');
    Route::get('/staff-cit-deduction/edit/{id}', 'StaffCitDeductionController@edit')->name('staff-cit-deduction-edit');
    Route::post('/staff-cit-deduction/update/{id}', 'StaffCitDeductionController@update')->name('staff-cit-deduction-update');
    Route::post('/staff-cit-deduction/destroy', 'StaffCitDeductionController@destroy')->name('staff-cit-deduction-destroy');
    Route::post('/staff-cit-deduction/destroyselected', 'StaffCitDeductionController@destroySelected')->name('staff-cit-deduction-destroy-selected');
    Route::post('/staff-cit-deduction/check', 'StaffCitDeductionController@check_vehicle_loan')->name('vehicle-loan-check');

    Route::post('staff-cit-deduction/filter-view', 'Ajax\StaffCitDeductionController@filterView')->name('staff-cit-deduction-show-filter-view');


    Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function () {
        Route::get('/', function () {
            return '';
        })->name('ajax');

        Route::get('get-user-attendance-by-attendance-date-and-staff-central-id', 'FetchAttendanceController@getUserAttendanceByAttendanceDateAndStaffCentralId')->name('ajax-get-user-attendance-by-attendance-date-and-staff-central-id');
        Route::post('get-loan-details-based-on-loan-type-id', 'LoanDeductController@getLoanDetailsBasedOnLoanTypeId')->name('ajax-get-loan-details-based-on-loan-type-id');
        Route::get('get-user-data-from-search', 'UserController@getCustomersByName')->name('ajax.selectize.get-users-by-name');
    });

    Route::get('ajax-get-staff-by-id', 'StaffMainMastController@getStaffById')->name('ajax-get-staff-by-id');

});



