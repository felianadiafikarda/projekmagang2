<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paper;
use App\Models\User;
use App\Models\Role;

class EditorController extends Controller
{
    public function index(Request $request)
    {
        // Ambil apakah page=list atau page=assign
        $page = $request->get('page', 'list');

        // ========= LIST PAGE ==========
        if ($page === 'list') {

            // Ambil semua paper + authors
            $papers = Paper::with(['authors'])
                        ->orderBy('created_at', 'desc')
                        ->get();

            // Ambil semua editor dari role_user
            $editors = User::whereHas('roles', function($q) {
                $q->where('name', 'editor'); // atau 'section_editor' sesuai kebutuhan
            })->get();

            return view('editor', compact('page', 'papers', 'editors'));
        }

        // ========= ASSIGN REVIEWER PAGE ==========
        if ($page === 'assign') {

            $id = $request->get('id');
            $paper = Paper::with(['authors'])->findOrFail($id);

            // Reviewer list
            $all_reviewers = User::whereHas('roles', function($q) {
                $q->where('name', 'reviewer');
            })->get();
            
            $all_section_editors = User::whereHas('roles', function($q) {
                $q->where('name', 'section_editor');
            })->get();
            

            // Assigned reviewers dan section editors
            $assignedReviewers = $paper->reviewers()->get(); 
            $assignedSectionEditors = $paper->sectionEditors()->get();



            return view('editor', [
                'page'                  => 'assign',
                'paper'                 => $paper,
                'all_reviewers'         => $all_reviewers,
                'all_section_editors'   => $all_section_editors,
                'assignedReviewers'     => $assignedReviewers,
                'assignedSectionEditors'=> $assignedSectionEditors,
                'editors'               => $all_section_editors // untuk editorName di JS
            ]);
        }

        // default
        return redirect()->back();
    }
}