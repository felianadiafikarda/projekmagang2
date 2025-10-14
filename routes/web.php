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
