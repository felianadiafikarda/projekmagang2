<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::with('roles')->paginate(3);
        $roles = Role::all();

        return view('user', compact('users', 'roles'));
    }

    public function updateRoles(Request $request, User $user)
    {
        $roles = Role::whereIn('name', $request->roles)->pluck('id');
        $user->roles()->sync($roles);

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'username'   => 'required|string|max:100|unique:users,username',
            'password'   => 'required|string|min:6',
            'affiliation' => 'nullable|string|max:255',
            'address'    => 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'country'    => 'nullable|string|max:100',

            'roles'      => 'array',
            'roles.*'    => 'string'
        ]);

        // 1. Buat User
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'username'   => $request->username,
            'password'   => Hash::make($request->password),
            'affiliation' => $request->affiliation,
            'address'    => $request->address,
            'phone'      => $request->phone,
            'country'    => $request->country,
        ]);

        // 2. Ambil roles dari request (default: author)
        $roles = $request->roles ?? ['author'];

        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $user->roles()->attach($role->id);
            }
        }

        return response()->json([
            'message' => 'User created successfully',
            'user'    => $user
        ]);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        // kalau pakai tabel pivot user_roles
        $user->roles()->detach();

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}
