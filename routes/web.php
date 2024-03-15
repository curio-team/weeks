<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn () => view('calendar'));
Route::get('/login', fn () => redirect('/sdclient/redirect'))->name('login');
Route::get('/sdclient/ready', fn () => redirect('/admin'));

Route::get('/sdclient/error', function () {
    $error = session('sdclient.error');
    $error_description = session('sdclient.error_description');
    return 'There was an error signing in: ' . $error_description . ' (' . $error . ')<br><a href="/login">Try again</a>';
});
