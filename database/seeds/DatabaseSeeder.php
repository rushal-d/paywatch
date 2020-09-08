<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DistrictTableSeeder::class);
        $this->call(GradeTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(ProvincesTableSeeder::class);
        $this->call(FiscalYearTableSeeder::class);
        $this->call(SystemJobtypeMastTableSeeder::class);
        $this->call(SystemPostMastTableSeeder::class);
        $this->call(SystemLeaveMastTableSeeder::class);
        $this->call(SystemTdsdetailsMastTableSeeder::class);
        $this->call(StaffTypesTableSeeder::class);
    }
}
