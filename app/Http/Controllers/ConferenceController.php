<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PaperAuthor;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    public function index()
    {
        $authorActivities = PaperAuthor::with(['user.roles', 'paper'])
            ->orderBy('created_at', 'desc')
            ->get();

        $authors = User::withCount('paper') // hitung jumlah paper dari table papers
        ->whereHas('roles', function ($q) {
            $q->where('name', 'author');
        })
        ->get();

        $totalAuthors = User::whereHas('roles', function ($q) {
            $q->where('name', 'author');
        })->count();

        $totalReviewers = User::whereHas('roles', function ($q) {
            $q->where('name', 'reviewer');
        })->count();

        $totalEditors = User::whereHas('roles', function ($q) {
            $q->where('name', 'editor');
        })->count();

        return view('conference_manager', compact('authorActivities', 'authors', 'totalAuthors', 'totalReviewers', 'totalEditors'));
    }
}
