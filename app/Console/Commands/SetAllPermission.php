<?php

namespace App\Console\Commands;

use App\Permission;
use http\Client\Curl\User;
use Illuminate\Console\Command;

class SetAllPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setall:permissions {--role=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign All Permission';

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
        if (!empty($this->option('role'))) {
            $role = $this->option('role');
            $role = \App\Role::where('name', $role)->first();
            if (empty($role)) {
                echo 'Role Not Found!';
            } else {
                $permission_ids = Permission::pluck('id')->toArray();
                $role->permission()->sync($permission_ids);
                echo 'Completed';
            }
        }
    }
}
