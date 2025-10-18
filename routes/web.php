<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/reviewer', function () {
    return view('reviewer');
});

Route::get('/author/kirim-artikel', function () {
    return view('author.KirimArtikel');
});

Route::get('/regis', function () {
    return view('author.regis');
});


Route::get('/login', function () {
    return view('login');
});

Route::get('/editor', function () {
    return view('editor');
});