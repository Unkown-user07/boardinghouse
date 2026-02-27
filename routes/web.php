<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('users.dashboard');
})->name('dashboard');

Route::get('/profile', function () {
    return view('users.profiles');
})->name('profile');

Route::get('/announcement', function () {
    return view('users.announcements');
})->name('announcements');

Route::get('/payments', function () {
    return view('users.payments');
})->name('payments');

Route::get('/settings', function () {
    return view('users.settings');
})->name('settings');

Route::get('/landingpage', function(){
    return view('landingpage');
})->name('landingpage');

Route::get('/login', function(){
    return view('users.login');
})->name('login');

Route::get('/register', function(){
    return view('users.register');
})->name('register');

Route::get('/admin-dashboard', function(){
    return view('admin.dashboard');
})->name('dasboard');