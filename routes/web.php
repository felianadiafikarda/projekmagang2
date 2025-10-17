<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

// Halaman Revi
Route::get('/reviewer', function () {
    return view('reviewer');
});

Route::get('/author/kirim-artikel', function () {
    return view('author.KirimArtikel');
});

// Halaman utama author (form kirim artikel)
Route::get('/author/kirim-artikel', function () {
    return view('author.KirimArtikel'); // pastikan file-nya ada di resources/views/author/kirim-artikel.blade.php
});
