<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();

        return view('user', compact('users', 'roles'));
    }

    public function updateRoles(Request $request, User $user)
    {
        $roles = Role::whereIn('name', $request->roles)->pluck('id');
        $user->roles()->sync($roles);

        return response()->json(['success' => true]);
    }
}
