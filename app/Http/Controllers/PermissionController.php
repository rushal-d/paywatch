<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $data['records_per_page'] = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $data['records_per_page_options'] = Config::get('constants.records_per_page_options');
        $data['i'] = 1;
        $data['permissions'] = Permission::search($search_term)->where('isURL', 0)->paginate($data['records_per_page']);
        return view('entrust.permission.index', $data);
    }

    public function create()
    {
        $not_found = [];

        $allPermissions = Permission::get();
        $url_checks = $allPermissions->where('isURL', '1');

        if (!empty($url_checks)) {
            foreach ($url_checks as $permission) {
                $status = false;
                foreach (Route::getRoutes()->getRoutes() as $route) {
                    $action = $route->getAction();
                    if (!array_search('permissionmiddleware', $action['middleware'])) {
                        continue;
                    }
                    if (array_key_exists('as', $action)) {
                        if ($permission->name == $action['as']) {
                            $status = true;
                        }
                    }
                }
                if (!$status) {
                    $permission->delete();
                }
            }
        }

        $route_name = [];
        $i = 1;
        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();
            if (!array_search('permissionmiddleware', $action['middleware'])) {
                continue;
            }
            if (array_key_exists('as', $action)) {
                $route_name[] = $action['as'];
                $name = $action['as'];
                $permission_route = $allPermissions->where('name', $name)->first();
                if (empty($permission_route)) {
                    $new_permission_route = new Permission();
                    $new_permission_route->name = $name;
                    $new_permission_route->parent_id = 0;
                    $new_permission_route->save();
                } else {
                    if ($permission_route->parent_id != 0) {
                        if (empty($allPermissions->where('id', $permission_route->parent_id)->first())) {
                            $permission_route->parent_id = 0;
                            $permission_route->save();
                        }
                    }
                }
                //Permission::updateOrCreate(['name' => $name], ['id' => $i++,], ['isURL' => '1']);
            }
        }
        $permissions = Permission::with(['childPs' => function ($query) {
            $query->with(['childPs' => function ($query) {
                $query->with(['childPs' => function ($query) {
                    $query->with('childPs');
                }]);
            }]);
        }])->rootPermission()->get();


        return view('entrust.permission.create', compact('permissions', 'not_found'));
    }

    public function check()
    {
        $permissions = Permission::rootPermission()->get();
        return view('entrust.permission.check', compact('permissions'));
    }

    public function store(Request $request)
    {
        $p_order = 1;
        $i = 1;
        foreach ($request->menu as $menu) {
            $i++;
            if ($menu['parent_id'] == null) {
                $i = 1;
                $p_order++;
                $parent_id = 0;
            } else {
                $parent_id = $menu['parent_id'];
            }
            /*if($menu['item_id'] == 248){
                dd($menu['parent_id']);
            }*/
//            echo $parent_id;
            if (!empty($menu['item_id'])) {

                $permission = Permission::find($menu['item_id']);
                if ($menu['parent_id'] == null) {
                    $permission->order = $p_order;

                } else {
                    $permission->order = $i;
                }

                $permission->parent_id = $parent_id;
                $permission->update();
            }
        }
        $data = array();
        $data['success'] = 1;
        return response()->json($data);
    }

    public function add(Request $request)
    {
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->isURL = 0;
        $permission->parent_id = 0;
        $permission->save();
        return redirect()->back();
    }

    public function displayNameStore(Request $request)
    {
        $values = $request->permission;
        foreach ($values as $key => $value) {
            $permission = Permission::where('name', $key)->first();
            if (!empty($permission)) {
                $permission->display_name = $value['display_name'];
                $permission->icon = $value['icon'];
                $permission->update();
            }
        }
        return redirect()->back();

    }

    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $user = Permission::find($request->id);
            if ($user->delete()) {
                $success = true;
            }
            if ($success) {
                echo 'Successfully Deleted';
            } else {
                echo "Error deleting!";
            }
        } else {
            echo "Error deleting!";
        }
    }

    public function sendPermissionToKB()
    {
        $baseUrl = Config::get('constants.kb_url');
        $permissions = Permission::get();
        $data['permissions'] = $permissions->toArray();
        //set POST variables
        $url = $baseUrl . '/api/update-permission/paywatch';

//        $data = ['abc' => 1];
//url-ify the data for the POST
        $fields_string = json_encode($data);
//open connection
        $ch = curl_init();

//set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute post
        $result = curl_exec($ch);

//close connection
        curl_close($ch);
        if ($result) {
            echo 'Data Send Successfully!';
            die();
        } else {
            echo 'Something Went Wrong!';
            die();
        }
    }

    public function getPermission()
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
