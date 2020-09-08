<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use App\Role;
use App\RoleUser;
use App\StafMainMastModel;
use App\SystemOfficeMastModel;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['records_per_page'] = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $data['records_per_page_options'] = Config::get('constants.records_per_page_options');
        $users = User::authCurrentBranch()->with('roles', 'branch')->search($search_term);
        $data['i'] = 1;
        $data['title'] = 'Users';
        $userOptions = User::limit(10)->get();
        $data['roleOptions'] = Role::limit(10)->get();
        $mergedUsers = collect();

        if (!empty($request->user_id)) {
            $mergedUsers = User::select('id', 'name', 'email')->where('id', $request->user_id)->get();
            $users = $users->where('id', $request->user_id);
        }
        $roleIds = null;
        if (!empty($request->roles)&& isset($request->roles[0]) && !empty($request->roles[0])) {
            $roleIds = explode(',', $request->roles[0]);
            $users = $users->whereHas('roles', function ($query) use ($roleIds) {
                $query->whereIn('id', $roleIds);
            });
        }
        $userOptions = $userOptions->merge($mergedUsers);

        $data['users'] = $users->paginate($data['records_per_page']);
        $data['userOptions'] = $userOptions;
        $data['roleIds'] = collect($roleIds);
        return view('entrust.user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('user-create')) {
            return redirect()->route('user-index')->withErrors([
                'You do not have sufficient permissions'
            ]);
        }

//        $staffs = StafMainMastModel::with(['branch' => function($query){
        $staffs = StafMainMastModel::with(['branch' => function ($query) {
            $query->select('office_id', 'office_name');
        }])->select('id', 'name_eng', 'FName_Eng', 'main_id', 'branch_id', 'staff_central_id')->get();
        if (AuthRepository::isAdministrator()) {
            $roles = Role::all();
        } else {
            $roles = Role::where('name', 'Supervisor')->orWhere('name', 'Attendance Manager')->orWhere('name', 'User Create')->get();
        }
        $offices = SystemOfficeMastModel::all();
        return view('entrust.user.create', compact('roles', 'offices', 'staffs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'roles' => 'required'
        ]);
        $input = $request->only('name', 'email', 'password', 'branch_id', 'staff_central_id');
        $input['password'] = Hash::make($input['password']); //Hash password
        $user = User::create($input); //Create User table entry
        //Attach the selected Roles

        foreach ($request->input('roles') as $key => $value) {
            $user->attachRole($value);
        }
        return redirect()->route('user-index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /*if (!AuthRepository::isAdministrator()) {
            return redirect()->route('user-index');
        }*/
        $user = User::findOrFail($id);
        $staffs = StafMainMastModel::with(['branch' => function ($query) {
            $query->select('office_id', 'office_name');
        }])
            ->select('id', 'name_eng', 'FName_Eng', 'main_id', 'branch_id', 'staff_central_id')
            ->get();
        if (AuthRepository::isAdministrator()) {
            $roles = Role::all();
        } else {
            $roles = Role::where('name', 'Supervisor')->orWhere('name', 'Attendance Manager')->orWhere('name', 'User Create')->get();
        }
        $userRoles = $user->roles->pluck('id')->toArray();
        $offices = SystemOfficeMastModel::all();
        return view('entrust.user.edit', compact('user', 'roles', 'userRoles', 'offices', 'staffs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,id,' . $id,
            'roles' => 'required'
        ]);

        $input = $request->only('name', 'email', 'branch_id', 'staff_central_id');
        User::findOrFail($id)->update($input); //Create User table entry
        $user = User::findOrFail($id);
        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password); //Hash password
            User::findOrFail($id)->update($input); //Create User table entry
        }
        //Attach the selected Roles
        $user->roles()->sync($request->input('roles'));
//        foreach ($request->input('roles') as $key => $value) {
//            $user->attachRole($value);
//        }
        return redirect()->route('user-index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $user = User::find($request->id);
            $user->deleted_at = Carbon::now();
            RoleUser::where('user_id', $user->id)->delete();
            $user->deleted_by = Auth::id();
            if ($user->save()) {
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

    public function changepwd()
    {
        //dd($request);
        $user = User::find(Auth::id());
        if (!empty($user)) {
            $roles = Role::get(); //get all roles
            $userRoles = $user->roles->pluck('id')->toArray();
            return view('entrust.user.change-password', compact('user', 'roles', 'userRoles'));
        }


    }

    public function changepwdUpdate(Request $request)
    {
        $this->validate($request, [

            'password' => 'required|confirmed|min:6',
//            'roles' => 'required'
        ]);

        $password = Hash::make($request->password); //Hash password
        $user = User::find(Auth::id());
        $user->password = $password;
        $user->update(); //Create User table entry
        /*DB::table('role_user')->where('user_id', Auth::id())->delete();*/
        //Attach the selected Roles
        /*foreach ($request->input('roles') as $key => $value) {
            $user->attachRole($value);
        }*/
        return redirect()->route('user-index')
            ->with('success', 'User Updated successfully');
    }
}
