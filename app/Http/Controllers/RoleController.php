<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['records_per_page'] = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $data['records_per_page_options'] = Config::get('constants.records_per_page_options');
        $data['i'] = 1;
        $data['roles'] = Role::search($search_term)->paginate($data['records_per_page']);
        return view('entrust.role.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('entrust.role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = new Role();
        $role->name = $request->role_name;
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();
        $notification = array(
            'message' => 'Data Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('role-index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        return view('entrust.role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        $input = $request->all();
        $role->update($input);
        $notification = array(
            'message' => 'Data Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('role-index')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $role = Role::find($request->id);
            if ($role->delete()) {
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
}
