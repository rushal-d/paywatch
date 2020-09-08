<?php

use App\Province;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('provinces')->delete();

        $nowDateTime = Carbon::now()->toDateTimeString();

        $provincesData = [
            [
                'id' => 1,
                'province_name' => 'Province 1',
                'province_name_np' => "प्रदेश १",
                'created_at' => $nowDateTime,
                'deleted_at' => $nowDateTime
            ],
            [
                'id' => 2,
                'province_name' => 'Province 2',
                'province_name_np' => "प्रदेश २",
                'created_at' => $nowDateTime,
                'deleted_at' => $nowDateTime
            ],
            [
                'id' => 3,
                'province_name' => 'Province 3',
                'province_name_np' => "प्रदेश ३",
                'created_at' => $nowDateTime,
                'deleted_at' => $nowDateTime
            ],
            [
                'id' => 4,
                'province_name' => 'Province 4',
                'province_name_np' => "प्रदेश ४",
                'created_at' => $nowDateTime,
                'deleted_at' => $nowDateTime
            ],
            [
                'id' => 5,
                'province_name' => 'Province 5',
                'province_name_np' => "प्रदेश ५",
                'created_at' => $nowDateTime,
                'deleted_at' => $nowDateTime
            ],
            [
                'id' => 6,
                'province_name' => 'Province 6',
                'province_name_np' => "प्रदेश ६",
                'created_at' => $nowDateTime,
                'deleted_at' => $nowDateTime
            ],
            [
                'id' => 7,
                'province_name' => 'Province 7',
                'province_name_np' => "प्रदेश ७",
                'created_at' => $nowDateTime,
                'deleted_at' => $nowDateTime
            ],
        ];

        Province::insert($provincesData);
    }
}
