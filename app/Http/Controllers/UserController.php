<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = User::with('roles');

        // --- Filter by role ---
        if ($request->role) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // --- Filter by status ---
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // --- Search by name, email, affiliation ---
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('affiliation', 'like', "%$search%");
            });
        }

        // --- Pagination (5 data per page seperti kode awal) ---
        $users = $query->paginate(5);
        $users->appends($request->all()); // penting: agar filter tetap saat next page

        // Roles untuk filter dropdown, admin dikeluarkan
        $roles = Role::where('name', '!=', 'admin')->get();

        $totalUsers = User::count();

        $conferenceManagers = User::whereHas('roles', function ($q) {
            $q->where('name', 'conference_manager');
        })->count();

        $editors = User::whereHas('roles', function ($q) {
            $q->where('name', 'editor');
        })->count();

        $reviewers = User::whereHas('roles', function ($q) {
            $q->where('name', 'reviewer');
        })->count();

        return view('user', compact('users', 'roles', 'totalUsers', 'conferenceManagers', 'editors', 'reviewers'));
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

        // Buat user
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

        // Ambil roles (default: author)
        $roles = $request->input('roles', ['author']);

        // Ambil ID dari tabel roles
        $roleIds = Role::whereIn('name', $roles)->pluck('id')->toArray();

        // Set roles (multiple diperbolehkan)
        $user->roles()->sync($roleIds);


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
