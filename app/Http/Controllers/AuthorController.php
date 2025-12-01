<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $papers = \App\Models\Paper::with(['authors'])
                ->where('user_id', auth()->id())
                ->get();

         return view('author.listpaper', compact('papers'));

        
    }

     public function paper()
    {
        $user = auth()->user();
        return view('author.KirimArtikel', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // ========== PAPER ==========
            'judul'     => 'required|string|max:255',
            'abstrak'   => 'required|string',
        
            'keywords' => [
                'required',
                'string',
                'regex:/^[^,\s]+(\s*,\s*[^,\s]+)+$/',
            ],
        
            // FILE UPLOAD
            'file_artikel' => 'required|file|mimes:pdf,doc,docx|max:10000',
        
            // AUTHORS
            'authors'                       => 'required|array|min:1',
            'authors.*.email'               => 'required|email',
            'authors.*.first_name'          => 'required|string|max:255',
            'authors.*.last_name'           => 'required|string|max:255',
            'authors.*.organization'        => 'required|string|max:255',
            'authors.*.country'             => 'required',
        
            // Primary
            'primary' => 'required|integer|min:0',
        
        ], [
            // PAPER
            'judul.required' => 'The article title is required.',
            'abstrak.required' => 'The abstract is required.',
            'keywords.required' => 'Keywords are required.',
            'keywords.regex' => 'Invalid keyword format. Use commas as separators, e.g., computer, network, AI.',
        
            // FILE UPLOAD
            'file_artikel.required' => 'The article file is required.',
            'file_artikel.mimes' => 'The file must be in PDF, DOC, or DOCX format.',
            'file_artikel.max' => 'The file size must not exceed 10MB.',
        
            // AUTHORS
            'authors.required' => 'At least one author is required.',
            'authors.*.email.required' => 'The author email is required.',
            'authors.*.email.email' => 'The author email format is invalid.',
            'authors.*.first_name.required' => 'The author\'s first name is required.',
            'authors.*.last_name.required' => 'The author\'s last name is required.',
            'authors.*.organization.required' => 'The author\'s organization is required.',
            'authors.*.country.required' => 'The author\'s country is required.',
        
            // PRIMARY AUTHOR
            'primary.required' => 'Please select the primary author.',
        ]);
        
        
        

        // SIMPAN FILE
        $filePath = $request->file('file_artikel')->store('papers', 'public');


        // SIMPAN PAPER
        $paper = \App\Models\Paper::create([
            'user_id' => auth()->id(),
            'judul' => $request->judul,
            'abstrak' => $request->abstrak,
            'keywords' => $request->keywords,
            'file_path' => $filePath,
        ]);

        // SIMPAN AUTHORS
        foreach ($request->authors as $i => $author) {
            \App\Models\PaperAuthor::create([
                'paper_id' => $paper->id,
                'is_primary' => ($request->primary == $i),
                'email' => $author['email'],
                'first_name' => $author['first_name'],
                'last_name' => $author['last_name'],
                'organization' => $author['organization'],
                'country' => $author['country'],
            ]);
        }

        return redirect()->route('author.index')->with('success', 'Artikel berhasil dikirim!');
    }

}