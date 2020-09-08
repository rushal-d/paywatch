<?php

namespace App\Console\Commands;

use App\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class PullPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull Permission From KB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $baseUrl = Config::get('constants.kb_url');
        $url = $baseUrl . '/api/get-permission/paywatch';
        $ch = curl_init();
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $response = json_decode($response, true);
        $status = false;
        try {
            DB::beginTransaction();
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('permissions')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $permissionsBulk = $response['permissions'];
            foreach ($permissionsBulk as $key => $v) {
                $permissionsBulk[$key] ['id'] = $permissionsBulk[$key] ['permission_id'];
                unset($permissionsBulk[$key]['permission_id']);
                unset($permissionsBulk[$key]['project_id']);
            }
            Permission::insert($permissionsBulk);
            $status = true;
        } catch (\Exception $e) {
            DB::rollBack();
            $status = false;
        }
        if ($status) {
            DB::commit();
        }
        if ($status) {
            echo 'Data Downloaded Successfully!';
            die();
        } else {
            echo 'Something Went Wrong!';
            die();
        }

    }
}
