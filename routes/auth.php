<?php

use Illuminate\Support\Facades\Route;

// Implemented by Laravel Breeze authentication scaffolding.
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
