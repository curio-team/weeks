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
Route::get('/login', fn() => redirect('/amoclient/redirect'))->name('login');
Route::get('/amoclient/ready', fn() => redirect('/admin'));
