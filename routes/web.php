<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/about', function () {
    return view('about-us');
});
Route::get('/register', function () {
    return view('register');
});
