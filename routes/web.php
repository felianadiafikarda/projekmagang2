<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\AdminController;


Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();
    $highestRole = $user->roles()->orderByDesc('level')->first();

    if ($highestRole) {
        switch ($highestRole->name) {
            case 'conference_manager':
                return redirect()->route('conference_manager.index');
            case 'editor':
                return redirect()->route('editor.index');
            case 'reviewer':
                return redirect()->route('reviewer.index');
            case 'author':
                return redirect()->route('author.index');
            case 'admin':
                return redirect()->route('admin.index');
        }
    }

    Auth::logout();
    return redirect()->route('login')->withErrors(['role' => 'Akun belum memiliki role.']);
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/conference-manager', [ConferenceController::class, 'index'])->name('conference_manager.index');
    Route::get('/editor', [EditorController::class, 'index'])->name('editor.index');
    Route::get('/reviewer', [ReviewerController::class, 'index'])->name('reviewer.index');
    Route::get('/author/kirim-artikel', [AuthorController::class, 'index'])->name('author.index');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/author/regis', function () {
    return view('author.regis');
});
});
