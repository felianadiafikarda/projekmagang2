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
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // cek user berdasarkan email
        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            // redirect berdasarkan role
            switch ($user->role) {
                case 'conference_manager':
                    return redirect('/conference-manager');
                case 'editor':
                    return redirect('/editor');
                case 'reviewer':
                    return redirect('/reviewer');
                default:
                    return redirect('/author');
            }
        }

        return back()->withErrors([
            'email' => 'email atau password salah!',
        ])->onlyInput('email');
    }

    // logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
