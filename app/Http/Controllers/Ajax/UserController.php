<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getCustomersByName(Request $request)
    {
        $users = User::authCurrentBranch()->with('roles', 'branch')->select('id', 'name', 'email');
        $limit = 100;

        if (!empty($request->limit)) {
            $limit = 100;
        }


        if (!empty($request->name)) {
            $users = $users->where('name', 'LIKE', '%' . $request->name . '%')
                ->orWhere('email', 'LIKE', '%' . $request->name . '%');
        }
        $users = $users->take($limit)->get();

        return response()->json([
            'status' => true,
            'data' => $users,
            'message' => 'Retrieved users successfully!'
        ]);
    }
}
