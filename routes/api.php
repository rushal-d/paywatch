<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('fetch-attendance', 'APIController@fetchAttendance');
Route::get('getstaffs/{id}', 'APIController@getStaffsByBranchID');
Route::get('getshift/{id}', 'APIController@getShiftsByBranchID');

Route::post('get-attendance', 'APIController@getAttendance');
Route::post('update-get-attendance', 'APIController@updateGetAttendance');

/** Fingerprint */
Route::post('save-staff-fingerprint', 'APIController@saveStaffFingerprint');
Route::post('get-staff-fingerprint', 'APIController@getStaffFingerprint');
Route::post('update-staff-fingerprint', 'APIController@updateStaffFingerprint');
/** End of fingerprint */

/** Fingerprint */
Route::post('get-deleted-local-attendance', 'APIController@getDeletedLocalAttendance');
Route::post('update-deleted-local-attendance', 'APIController@updatedDeletedLocalAttendance');
/** End of fingerprint */


Route::get('users/{branch_id}', 'APIController@getBranchUsers');
Route::get('fiscalyear', 'APIController@getFiscalYear');

Route::post('attendance-server-get-unsynced-attendance', 'APIController@attendanceServerGetUnsyncedAttendance');
Route::post('attendance-server-update-sync-status', 'APIController@attendanceServerUpdateSyncStatus');
Route::post('attendance-server-update-attendances', 'APIController@attendanceServerUpdateAttendances');

Route::post('server-get-unsynced-shift', 'APIController@serverGetUnsyncedShift');
Route::post('server-update-sync-status-shift', 'APIController@serverUpdateSyncStatusShift');


Route::post('server-get-unsynced-staff', 'APIController@serverGetUnsyncedStaff');
Route::post('server-add-unsynced-staff', 'APIController@serverAddSyncStatusStaff');
Route::post('server-update-sync-status-staff', 'APIController@serverUpdateSyncStatusStaff');


Route::post('server-get-unsynced-transfer-staff', 'APIController@serverGetUnsyncedTransferStaff');
Route::post('server-update-sync-status-transfer-staff', 'APIController@serverUpdateSyncStatusTransferStaff');


Route::post('attendance-server-update-fingerprint/{branch_id}', 'APIController@attendanceServerUpdateFingerprint');
Route::post('get-public-holidays', 'APIController@getPublicHoliday');

Route::post('check-for-update', 'APIController@checkForUpdate');
Route::post('download-update', 'APIController@downloadUpdate');

//searchengine
Route::get('actions/{userID}', 'APIController@getActions')->name('apiActions');

