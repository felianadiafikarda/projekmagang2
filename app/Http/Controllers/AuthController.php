<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\ReviewerResponseMail;
use App\Models\PreparedEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

    public function registration(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'username'   => ['required', 'string', 'max:50', 'unique:users,username'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'username'   => $validated['username'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
        ]);

        $authorRole = Role::where('name', 'author')->first();
        $user->roles()->attach($authorRole->id);

        $template = PreparedEmail::where(
            'email_template',
            'new_notification_registration'
        )->first();

        if ($template && $user->email) {

            $body = $template->body;
            $body = str_replace('{{fullName}}', $user->full_name, $body);
            $body = str_replace('{{email}}', $user->email, $body);

            // ubah newline ke HTML <br>
            $bodyHtml = nl2br(e($body));

            Mail::to($user->email)->send(
                new ReviewerResponseMail(
                    $template->subject,
                    $bodyHtml
                )
            );
        }

        return redirect()
            ->route('login')
            ->with('success', 'Registration successful. Please login.');
    }
}
