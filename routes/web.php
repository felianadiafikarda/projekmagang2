<?php

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\PreparedEmailController;
use App\Http\Controllers\SectionEditorController;

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
            case 'section_editor':
                return redirect()->route('section_editor.index');
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

Route::get('/registrasi', function () {
        return view('registrasi');
    });

// Reviewer Invitation Routes (public - accessed via email link)
Route::get('/review-invitation/{token}', [ReviewerController::class, 'showInvitation'])->name('reviewer.invitation');
Route::post('/review-invitation/{token}/accept', [ReviewerController::class, 'acceptInvitation'])->name('reviewer.invitation.accept');
Route::post('/review-invitation/{token}/decline', [ReviewerController::class, 'declineInvitation'])->name('reviewer.invitation.decline');

// Test route untuk preview halaman invitation (hapus di production)
Route::get('/review-invitation-preview', function () {
    $paper = App\Models\Paper::with('authors')->first();
    if (!$paper) {
        return 'Tidak ada paper di database. Silakan tambahkan paper terlebih dahulu.';
    }
    return view('reviewer.invitation', [
        'paper' => $paper,
        'reviewer' => App\Models\User::first(),
        'deadline' => now()->addDays(10)->format('Y-m-d'),
        'status' => 'assigned',
        'token' => 'test-preview-token',
    ]);
})->name('reviewer.invitation.preview');

Route::middleware('auth')->group(function () {
    Route::get('/conference-manager', [ConferenceController::class, 'index'])->name('conference_manager.index');
    Route::get('/editor', [EditorController::class, 'index'])->name('editor.index');
    Route::get('/editor/detail/{id}', [EditorController::class, 'detail'])->name('editor.detail');
    Route::post('/editor/paper/{paper}/assign-reviewers', [EditorController::class, 'assignReviewers'])->name('editor.assignReviewers');
    Route::post('/editor/{paperId}/send-reminder', [EditorController::class, 'sendReminder'])->name('editor.sendReminder');
    Route::post('/editor/{paper}/unassign-reviewer', [EditorController::class, 'unassignReviewer'])->name('editor.unassignReviewer');
    Route::post( '/editor/{id}/assign-section-editor',[EditorController::class, 'assignSectionEditorWithEmail'])->name('editor.assignSectionEditor');
    
    Route::post('/editor/{paper}/unassign-section-editor', [EditorController::class, 'unassignSectionEditor'])->name('editor.unassignSectionEditor');
    Route::patch('/editor/paper/{id}/status', [EditorController::class, 'updateStatus'])->name('editor.updateStatus');
    
    

    
    Route::get('/reviewer', [ReviewerController::class, 'index'])->name('reviewer.index');
    Route::post('/reviewer/{paper}/accept', [ReviewerController::class, 'acceptReview'])->name('reviewer.accept');
    Route::post('/reviewer/{paper}/decline', [ReviewerController::class, 'declineReview'])->name('reviewer.decline');
    Route::get('/reviewer/review/{paper}', [ReviewerController::class, 'showReviewForm'])->name('reviewer.review');
    Route::post('/reviewer/review/{paper}/submit', [ReviewerController::class, 'submitReview'])->name('reviewer.submitReview');
    Route::post('/reviewer/review/{paper}/save-draft', [ReviewerController::class, 'saveDraft'])->name('reviewer.saveDraft');
    Route::get('/author/kirim-artikel', [AuthorController::class, 'paper'])->name('author.kirim');
    Route::get('/author/revision/{id}', [AuthorController::class, 'revision'])->name('author.revision');

    // User Management Routes
    Route::post('/users/add', [UserController::class, 'store'])->name('users.add');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [UserController::class, 'delete'])->name('users.delete');
    Route::post('/users/{user}/update-roles', [UserController::class, 'updateRoles']);


    Route::get('/author/regis', function () {
        return view('author.regis');
    });

    Route::get('/review', function () {
        return view('review');
    });

    Route::get('/author', [AuthorController::class, 'index'])->name('author.index');
    Route::post('/author/sendArticle', [AuthorController::class, 'store'])->name('author.store');


    // Section Editor Routes
    Route::get('/section-editor', [SectionEditorController::class, 'index'])->name('section_editor.index');
    Route::get('/section-editor/detail/{id}', [SectionEditorController::class, 'detail'])->name('section_editor.detail');
    Route::post('/section-editor/paper/{paper}/assign-reviewers', [SectionEditorController::class, 'assignReviewers'])->name('section_editor.assignReviewers');
    Route::post('/section-editor/{paperId}/send-reminder', [SectionEditorController::class, 'sendReminder'])->name('section_editor.sendReminder');
    Route::post('/section-editor/{paper}/unassign-reviewer', [SectionEditorController::class, 'unassignReviewer'])->name('section_editor.unassignReviewer');

    // Prepared Email Routes
    Route::get('/prepared-emails', [PreparedEmailController::class, 'index'])->name('prepared_email.index');
    Route::get('/prepared-emails/create', [PreparedEmailController::class, 'create'])->name('prepared_email.create');
    Route::post('/prepared-emails', [PreparedEmailController::class, 'store'])->name('prepared_email.store');
    Route::get('/prepared-emails/{emailTemplate}/edit', [PreparedEmailController::class, 'edit'])->name('prepared_email.edit');
    Route::put('/prepared-emails/{emailTemplate}', [PreparedEmailController::class, 'update'])->name('prepared_email.update');
    Route::delete('/prepared-emails/{emailTemplate }', [PreparedEmailController::class, 'destroy'])->name('prepared_email.destroy');
    Route::get('/prepared-email/{code}', [PreparedEmailController::class, 'getTemplate']);

    
    Route::post('/notifications/read-all', function () {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    })->name('notifications.markRead');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});