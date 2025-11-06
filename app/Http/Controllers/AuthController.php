<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // tampilkan halaman login
    public function showLogin()
    {
        return view('login');
    }

    // proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !\Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau password salah!'
            ])->onlyInput('email');
        }

        Auth::login($user);

        // Ambil role pertama (karena user bisa punya banyak role)
        $role = $user->roles->first()->name ?? null;

        // Jika tidak punya role, tolak
        if (!$role) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun ini belum memiliki role!'
            ]);
        }

        // Redirect berdasarkan role
        switch ($role) {
            case 'conference_manager':
                return redirect()->route('conference_manager.index');
            case 'editor':
                return redirect()->route('editor.index');
            case 'reviewer':
                return redirect()->route('reviewer.index');
            case 'author':
                return redirect()->route('author.index');
            case 'admin':
                return redirect()->route('users.index');
            default:
                return redirect()->route('author.index');
        }
    }

    // logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
