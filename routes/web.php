<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\ConferenceController;

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
                return redirect()->route('users.index');
        }
    }

    Auth::logout();
    return redirect()->route('login')->withErrors(['role' => 'Akun belum memiliki role.']);
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/conference-manager', [ConferenceController::class, 'index'])->name('conference_manager.index');
    Route::get('/editor', [EditorController::class, 'index'])->name('editor.index');
    Route::get('/editor/detail/{id}', [EditorController::class, 'detail'])->name('editor.detail');
    Route::post('/editor/paper/{paper}/assign-reviewers', [EditorController::class, 'assignReviewers'])->name('editor.assignReviewers');
    Route::post('/editor/{paperId}/send-reminder', [EditorController::class, 'sendReminder'])->name('editor.sendReminder');
    Route::post('/editor/{paper}/unassign-reviewer', [EditorController::class, 'unassignReviewer'])->name('editor.unassignReviewer');
    Route::post('/editor/{paper}/assign-section-editor', [EditorController::class, 'assignSectionEditor'])->name('editor.assignSectionEditor');
    Route::post('/editor/{paper}/unassign-section-editor', [EditorController::class, 'unassignSectionEditor'])->name('editor.unassignSectionEditor');


    
    Route::get('/reviewer', [ReviewerController::class, 'index'])->name('reviewer.index');
    Route::get('/author/kirim-artikel', [AuthorController::class, 'paper'])->name('author.kirim');

    
    // User Management Routes
    Route::post('/users', [UserController::class, 'store'])->name('users.add');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [UserController::class, 'delete'])->name('users.delete');
    Route::post('/users/{user}/update-roles', [UserController::class, 'updateRoles']);


    Route::get('/author/regis', function () {return view('author.regis');});
    Route::get('/author', [AuthorController::class, 'index'])->name('author.index');
    Route::post('/author/sendArticle', [AuthorController::class, 'store'])->name('author.store');


    Route::get('/sectionEditor', function () {return view('sectionEditor.index');});


    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});