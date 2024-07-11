<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // dd(now()->year . random_int(1000, 9999));
    return view('welcome');
});
