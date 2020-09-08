<?php

namespace Tests\Functional\StaffMainMast;

use App\Department;
use App\Helpers\BSDateHelper;
use App\OrganizationSetup;
use App\Section;
use App\Shift;
use App\StaffSalaryModel;
use App\StafMainMastModel;
use App\SystemJobTypeMastModel;
use App\SystemOfficeMastModel;
use App\SystemPostMastModel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StaffCreateTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    /**
     * @test
     */
    public function staff_name_cannot_be_null()
    {
        $staffData = factory(StafMainMastModel::class)->make([
            'name_eng' => null,
        ])->toArray();
        $this->post(route('staff-main-save'), $staffData)
            ->assertSessionHasErrors('name_eng');
    }

    /**
     * @test
     */
    public function staff_shift_cannot_be_null()
    {
        $staffData = factory(StafMainMastModel::class)->make([
            'shift_id' => null,
        ])->toArray();
        $this->post(route('staff-main-save'), $staffData)
            ->assertSessionHasErrors('shift_id');
    }

    /**
     * @test
     */
    public function staff_main_id_cannot_be_null()
    {
        $staffData = factory(StafMainMastModel::class)->make([
            'main_id' => null,
        ])->toArray();
        $this->post(route('staff-main-save'), $staffData)
            ->assertSessionHasErrors('main_id');
    }

    /**
     * @test
     */
    public function staff_staff_central_id_cannot_be_existed()
    {
        $existingStaff = factory(StafMainMastModel::class)->create();

        $staffData = factory(StafMainMastModel::class)->make([
            'staff_central_id' => $existingStaff->staff_central_id,
        ])->toArray();

        $this->post(route('staff-main-save'), $staffData)
            ->assertSessionHasErrors('staff_central_id');
    }

    /**
     * @test
     */
    public function staff_appo_date_np_cannot_be_null()
    {
        $staffData = factory(StafMainMastModel::class)->make([
            'appo_date_np' => null,
        ])->toArray();
        $this->post(route('staff-main-save'), $staffData)
            ->assertSessionHasErrors('appo_date_np');
    }

    /**
     * @test
     */
    public function staff_post_id_cannot_be_null()
    {
        $staffData = factory(StafMainMastModel::class)->make([
            'post_id' => null,
        ])->toArray();
        $this->post(route('staff-main-save'), $staffData)
            ->assertSessionHasErrors('post_id');
    }

    /**
     * @test
     */
    public function staff_main_id_should_not_exist_same_in_current_branch()
    {
        $branchId = factory(SystemOfficeMastModel::class)->create()->office_id;
        $mainId = 1;

        $previousStaff = factory(StafMainMastModel::class)->create([
            'branch_id' => $branchId,
            'main_id' => $mainId
        ]);

        $staffData = factory(StafMainMastModel::class)->make([
            'branch_id' => $branchId,
            'main_id' => $mainId
        ])->toArray();

        $this->post(route('staff-main-save'), $staffData)
            ->assertSessionHasErrors('main_id');
    }

    /**
     * @test
     */
    public function staff_create_should_store_data_properly()
    {
        $testingDate = \Carbon\Carbon::now()->subYear();
        $testingDateNp = \App\Helpers\BSDateHelper::AdToBs('-', $testingDate->toDateString());

        $staffDob = Carbon::now()->subYears(2)->toDateString();
        $staffDobNp = BSDateHelper::AdToBs('-', Carbon::now()->subYears(2)->toDateString());

        $appoDateNp = BSDateHelper::AdToBs('-', Carbon::now()->subYears(3)->toDateString());

        $name_eng = $this->faker->name;
        $FName_Eng = $this->faker->name;
        $gfname_eng = $this->faker->name;

        $spname_eng = $this->faker->name;
        $district_id = 25;
        $ward_no = $this->faker->sentence;
        $tole_basti = $this->faker->sentence;
        $marrid_stat = $this->faker->numberBetween(1, 2);
        $Gender = $this->faker->numberBetween(1, 3);
        $edu_id = factory(\App\Education::class)->create()->id;
        $branch_id = factory(SystemOfficeMastModel::class)->create()->office_id;
        $caste_id = factory(\App\Caste::class)->create()->id;
        $religion_id = factory(\App\Religion::class)->create()->id;
        $shift_id = factory(\App\Shift::class)->create()->id;
        $staffType = StafMainMastModel::STAFF_TYPE_OPTION_FOR_GUARD_BBSM;
        $main_id = $this->faker->numberBetween(10, 100);
        $staffCentralId = $this->faker->numberBetween(10000, 1000000);
        $sectionId = factory(Section::class)->create()->id;
        $departmentId = factory(Department::class)->create()->id;
        $citizenNumber = $this->faker->numberBetween(10000, 1000000);
        $phoneNumber = $this->faker->phoneNumber;
        $emergencyPhoneNumber = $this->faker->phoneNumber;
        $postId = factory(\App\SystemPostMastModel::class)->create()->post_id;
        $staffCitizenOffice = $this->faker->sentence;
        $staffData = factory(StafMainMastModel::class)->make([
            'name_eng' => $name_eng,
            'FName_Eng' => $FName_Eng,
            'gfname_eng' => $gfname_eng,
            'spname_eng' => $spname_eng,
            'show_vdc' => $district_id,
            'tole_basti' => $tole_basti,
            'marrid_stat' => $marrid_stat,
            'Gender' => $Gender,
            'ward_no' => $ward_no,
            'edu_id' => $edu_id,
            'date_birth' => $testingDate->toDateString(),
            'branch_id' => $branch_id,
            'shift_id' => $shift_id,
            'staff_type' => $staffType,
            'caste_id' => $caste_id,
            'religion_id' => $religion_id,
            'staff_status' => 1,
            'main_id' => $main_id,
            'staff_central_id' => $staffCentralId,
            'section' => $sectionId,
            'department' => $departmentId,
            'staff_dob' => $staffDobNp,
            'staff_citizen_no' => $citizenNumber,
            'staff_citizen_issue_office' => $staffCitizenOffice,
            'staff_citizen_issue_date_np' => $testingDateNp,
            'phone_number' => $phoneNumber,
            'emergency_phone_number' => $emergencyPhoneNumber,
            'post_id' => $postId,
            'appo_date_np' => $appoDateNp
        ]);
//        dd($staffData->branch_id);
        $this->post(route('staff-main-save'), $staffData->toArray());

        $this->assertDatabaseHas('staff_main_mast', [
            'name_eng' => $name_eng,
            'FName_Eng' => $FName_Eng,
            'gfname_eng' => $gfname_eng,
            'spname_eng' => $spname_eng,
            'district_id' => $district_id,
            'tole_basti' => $tole_basti,
            'marrid_stat' => $marrid_stat,
            'Gender' => $Gender,
            'ward_no' => $ward_no,
            'edu_id' => $edu_id,
            'date_birth' => $testingDate->toDateString(),
            'branch_id' => $branch_id,
            'shift_id' => $shift_id,
            'staff_type' => $staffType,
            'caste_id' => $caste_id,
            'religion_id' => $religion_id,
            'staff_status' => 1,
            'main_id' => $main_id,
            'staff_central_id' => $staffCentralId,
//            'section' => $sectionId,
//            'department' => $departmentId,
            'staff_dob' => $staffDob,
            'staff_citizen_no' => $citizenNumber,
            'staff_citizen_issue_office' => $staffCitizenOffice,
            'staff_citizen_issue_date_np' => $testingDateNp,
            'phone_number' => $phoneNumber,
            'emergency_phone_number' => $emergencyPhoneNumber,
            'post_id' => $postId,
            'appo_date_np' => $appoDateNp
        ]);
    }

    /**
     * @test
     */
    public function staff_create_should_create_shift_history()
    {
        $appoDate = Carbon::yesterday()->toDateString();
        $appointment_date_np = BSDateHelper::AdToBs('-', $appoDate);
        $shiftId = factory(Shift::class)->create()->id;
        $staff = factory(StafMainMastModel::class)->make([
            'shift_id' => $shiftId,
            'appo_date_np' => $appointment_date_np
        ]);

        $this->post(route('staff-main-save'), $staff->toArray());

        $createdStaff = StafMainMastModel::latest()->first();

        $this->assertDatabaseHas('staff_shift_histories', [
            'staff_central_id' => $createdStaff->id,
            'shift_id' => $shiftId,
            'effective_from' => $appoDate
        ]);
    }

    /**
     * @test
     */
    public function staff_for_bbsm_create_should_create_staff_transfer()
    {
        $appoDate = Carbon::yesterday()->toDateString();
        $appointment_date_np = BSDateHelper::AdToBs('-', $appoDate);
        $branchId = factory(SystemOfficeMastModel::class)->create()->office_id;

        $staffGuardBBSM = factory(StafMainMastModel::class)->make([
            'branch_id' => $branchId,
            'appo_date_np' => $appointment_date_np,
            'staff_type' => StafMainMastModel::STAFF_TYPE_OPTION_FOR_BBSM
        ]);

        $this->post(route('staff-main-save'), $staffGuardBBSM->toArray());

        $createdStaff = StafMainMastModel::latest()->first();

        $this->assertDatabaseHas('staff_transefer_mast', [
            'staff_central_id' => $createdStaff->id,
            'from_date' => $appoDate,
            'from_date_np' => $appointment_date_np,
            'transfer_date' => null,
            'transfer_date_np' => null,
            'office_from' => $branchId
        ]);
    }

    /**
     * @test
     */
    public function staff_for_bbsm_create_salary_if_post_is_provided()
    {
        $appoDate = Carbon::yesterday()->toDateString();
        $appointment_date_np = BSDateHelper::AdToBs('-', $appoDate);

        $post = factory(SystemPostMastModel::class)->create();

        $staffBBSM = factory(StafMainMastModel::class)->make([
            'appo_date_np' => $appointment_date_np,
            'staff_type' => StafMainMastModel::STAFF_TYPE_OPTION_FOR_BBSM,
            'post_id' => $post->post_id,
        ]);
        $this->post(route('staff-main-save'), $staffBBSM->toArray());

        $createdStaff = StafMainMastModel::latest()->first();
        $this->assertDatabaseHas('staff_salary_mast', [
            'staff_central_id' => $createdStaff->id,
            'post_id' => $post->post_id,
            'add_salary_amount' => 0,
            'total_grade_amount' => 0,
            'salary_effected_date' => $appoDate,
            'salary_effected_date_np' => $appointment_date_np,
        ]);
    }

    /**
     * @test
     */
    public function staff_for_bbsm_create_default_post_basic_salary_if_provided_post_is_contract()
    {
        $appoDate = Carbon::yesterday()->toDateString();
        $appointment_date_np = BSDateHelper::AdToBs('-', $appoDate);

        OrganizationSetup::truncate();

        $organizationSetup = factory(OrganizationSetup::class)->create([
            'organization_code' => 'bbsm'
        ]);

        $postBasicSalary = 1250;
        $requestBasicSalary = 5900;
        $post = factory(SystemPostMastModel::class)->create([
            'basic_salary' => $postBasicSalary
        ]);

        $jobType = factory(SystemJobTypeMastModel::class)->create([
            'jobtype_code' => 'Con'
        ]);

        $staffBBSM = factory(StafMainMastModel::class)->make([
            'appo_date_np' => $appointment_date_np,
            'staff_type' => StafMainMastModel::STAFF_TYPE_OPTION_FOR_BBSM,
            'post_id' => $post->post_id,
            'basic_salary' => $requestBasicSalary,
            'jobtype_id' => $jobType->jobtype_id
        ]);
        $this->post(route('staff-main-save'), $staffBBSM->toArray());

        $createdStaff = StafMainMastModel::latest()->first();
        $this->assertDatabaseHas('staff_salary_mast', [
            'staff_central_id' => $createdStaff->id,
            'post_id' => $post->post_id,
            'add_salary_amount' => 0,
            'total_grade_amount' => 0,
            'salary_effected_date' => $appoDate,
            'salary_effected_date_np' => $appointment_date_np,
            'basic_salary' => $requestBasicSalary
        ]);
    }

    /**
     * @test
     */
    public function staff_for_bbsm_create_default_post_basic_salary_if_provided_post_is_contract1()
    {
        $appoDate = Carbon::yesterday()->toDateString();
        $appointment_date_np = BSDateHelper::AdToBs('-', $appoDate);

        OrganizationSetup::truncate();

        $organizationSetup = factory(OrganizationSetup::class)->create([
            'organization_code' => 'bbsm'
        ]);

        $postBasicSalary = 1250;
        $requestBasicSalary = 5900;
        $post = factory(SystemPostMastModel::class)->create([
            'basic_salary' => $postBasicSalary
        ]);

        $jobType = factory(SystemJobTypeMastModel::class)->create([
            'jobtype_code' => 'Con1'
        ]);

        $staffBBSM = factory(StafMainMastModel::class)->make([
            'appo_date_np' => $appointment_date_np,
            'staff_type' => StafMainMastModel::STAFF_TYPE_OPTION_FOR_BBSM,
            'post_id' => $post->post_id,
            'basic_salary' => $requestBasicSalary,
            'jobtype_id' => $jobType->jobtype_id
        ]);
        $this->post(route('staff-main-save'), $staffBBSM->toArray());

        $createdStaff = StafMainMastModel::latest()->first();
        $this->assertDatabaseHas('staff_salary_mast', [
            'staff_central_id' => $createdStaff->id,
            'post_id' => $post->post_id,
            'add_salary_amount' => 0,
            'total_grade_amount' => 0,
            'salary_effected_date' => $appoDate,
            'salary_effected_date_np' => $appointment_date_np,
            'basic_salary' => $requestBasicSalary
        ]);
    }

    /**
     * @test
     */
    public function staff_for_NepalRE_create_default_post_basic_salary_if_provided_post_is_contract1()
    {
        $appoDate = Carbon::yesterday()->toDateString();
        $appointment_date_np = BSDateHelper::AdToBs('-', $appoDate);

        OrganizationSetup::truncate();

        $organizationSetup = factory(OrganizationSetup::class)->create([
            'organization_code' => 'NEPALRE'
        ]);

        $postBasicSalary = 1250;
        $requestBasicSalary = 5900;
        $post = factory(SystemPostMastModel::class)->create([
            'basic_salary' => $postBasicSalary
        ]);

        $jobType = factory(SystemJobTypeMastModel::class)->create([
            'jobtype_code' => 'Con1'
        ]);

        $staffBBSM = factory(StafMainMastModel::class)->make([
            'appo_date_np' => $appointment_date_np,
            'staff_type' => StafMainMastModel::STAFF_TYPE_OPTION_FOR_BBSM,
            'post_id' => $post->post_id,
            'basic_salary' => $requestBasicSalary,
            'jobtype_id' => $jobType->jobtype_id
        ]);
        $this->post(route('staff-main-save'), $staffBBSM->toArray());

        $createdStaff = StafMainMastModel::latest()->first();
        $this->assertDatabaseHas('staff_salary_mast', [
            'staff_central_id' => $createdStaff->id,
            'post_id' => $post->post_id,
            'add_salary_amount' => 0,
            'total_grade_amount' => 0,
            'salary_effected_date' => $appoDate,
            'salary_effected_date_np' => $appointment_date_np,
            'basic_salary' => $post->basic_salary
        ]);
    }
//
//    /**
//     * @test
//     */
//    public function staff_for_bbsm_and_guard_bbsm_create_should_create_staff_transfer()
//    {
//        $appoDate = Carbon::yesterday()->toDateString();
//        $appointment_date_np = BSDateHelper::AdToBs('-', $appoDate);
//        $branchId = factory(SystemOfficeMastModel::class)->create()->office_id;
//
//        $staffGuardBBSM = factory(StafMainMastModel::class)->make([
//            'branch_id' => $branchId,
//            'appo_date_np' => $appointment_date_np,
//            'staff_type' => StafMainMastModel::STAFF_TYPE_OPTION_FOR_GUARD_BBSM
//        ]);
//
//        $this->post(route('staff-main-save'), $staffGuardBBSM->toArray());
//
//        $createdStaff = StafMainMastModel::latest()->first();
//
//        $this->assertDatabaseHas('staff_transefer_mast', [
//            'staff_central_id' => $createdStaff->id,
//            'from_date' => $appoDate,
//            'from_date_np' => $appointment_date_np,
//            'transfer_date' => null,
//            'transfer_date_np' => null,
//            'office_from' => $branchId
//        ]);
//
//        $staffBBSM = factory(StafMainMastModel::class)->make([
//            'branch_id' => $branchId,
//            'appo_date_np' => $appointment_date_np,
//            'staff_type' => StafMainMastModel::STAFF_TYPE_OPTION_FOR_BBSM
//        ]);
//
//        $this->post(route('staff-main-save'), $staffBBSM->toArray());
//
//        $createdStaff = StafMainMastModel::latest()->first();
//
//        $this->assertDatabaseHas('staff_transefer_mast', [
//            'staff_central_id' => $createdStaff->id,
//            'from_date' => $appoDate,
//            'from_date_np' => $appointment_date_np,
//            'transfer_date' => null,
//            'transfer_date_np' => null,
//            'office_from' => $branchId
//        ]);
//    }

    /**
     * @test
     */
    public function staff_for_company_and_company_guard_and_bbsm_not_in_payroll_create_should_not_create_staff_transfer()
    {
        $appoDate = Carbon::yesterday()->toDateString();
        $appointment_date_np = BSDateHelper::AdToBs('-', $appoDate);
        $branchId = factory(SystemOfficeMastModel::class)->create()->office_id;

        $staffCompany = factory(StafMainMastModel::class)->make([
            'branch_id' => $branchId,
            'appo_date_np' => $appointment_date_np,
            'staff_type' => StafMainMastModel::STAFF_TYPE_OPTION_FOR_COMPANY
        ]);

        $this->post(route('staff-main-save'), $staffCompany->toArray());

        $createdStaff = StafMainMastModel::latest()->first();

        $this->assertDatabaseMissing('staff_transefer_mast', [
            'staff_central_id' => $createdStaff->id,
            'from_date' => $appoDate,
            'from_date_np' => $appointment_date_np,
            'transfer_date' => null,
            'transfer_date_np' => null,
            'office_from' => $branchId
        ]);

        $staffCompanyGuard = factory(StafMainMastModel::class)->make([
            'branch_id' => $branchId,
            'appo_date_np' => $appointment_date_np,
            'staff_type' => StafMainMastModel::STAFF_TYPE_OPTION_FOR_COMPANY_GUARD
        ]);

        $this->post(route('staff-main-save'), $staffCompanyGuard->toArray());

        $createdStaff = StafMainMastModel::latest()->first();

        $this->assertDatabaseMissing('staff_transefer_mast', [
            'staff_central_id' => $createdStaff->id,
            'from_date' => $appoDate,
            'from_date_np' => $appointment_date_np,
            'transfer_date' => null,
            'transfer_date_np' => null,
            'office_from' => $branchId
        ]);

        $staffNotInBBSMPayroll = factory(StafMainMastModel::class)->make([
            'branch_id' => $branchId,
            'appo_date_np' => $appointment_date_np,
            'staff_type' => StafMainMastModel::STAFF_TYPE_OPTION_FOR_BBSM_NOT_IN_PAYROLL
        ]);

        $this->post(route('staff-main-save'), $staffNotInBBSMPayroll->toArray());

        $createdStaff = StafMainMastModel::latest()->first();

        $this->assertDatabaseMissing('staff_transefer_mast', [
            'staff_central_id' => $createdStaff->id,
            'from_date' => $appoDate,
            'from_date_np' => $appointment_date_np,
            'transfer_date' => null,
            'transfer_date_np' => null,
            'office_from' => $branchId
        ]);
    }
}
