<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        return view('author.listpaper');
    }

     public function paper()
    {
        return view('author.KirimArtikel');
    }
}


