<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/


Route::view('/', 'landingpage')->name('landingpage');
Route::view('/login', 'users.login')->name('login');
Route::view('/register', 'users.register')->name('register');


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::prefix('user')->group(function () {

    Route::view('/dashboard', 'users.dashboard')->name('user.dashboard');
    Route::view('/profile', 'users.profiles')->name('user.profile');
    Route::view('/announcement', 'users.announcements')->name('user.announcements');
    Route::view('/payments', 'users.payments')->name('user.payments');
    Route::view('/settings', 'users.settings')->name('user.settings');
      Route::view('/myroom', 'users.myroom')->name('user.myroom');
    

});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::view('/payments', 'admin.payments')->name('admin.payments');
    Route::view('/occupants', 'admin.occupants')->name('admin.occupants');
    Route::view('/register', 'admin.register')->name('admin.register');
    Route::view('/owners', 'admin.owners')->name('admin.owners');
    Route::view('/reservation', 'admin.reservation')->name('admin.resevations');
    Route::view('/rooms', 'admin.rooms')->name('admin.rooms');
    Route::view('/boarding-houses', 'admin.boardinghouses')->name('admin.boarding-houses');
    Route::view('/rentals', 'admin.rentals')->name('admin.rentals');
    Route::view('/prole', 'admin.profile')->name('admin.profile');
    Route::view('user&roles', 'admin.user&roles')->name('admin.user&roles');
    

});