<?php

use App\Http\Controllers\Admin\ViewDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ViewLoginController;

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

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('vetlink')->group(function () {
    Route::get('/login', ViewLoginController::class)->name('login');
});

Route::prefix('vetlink')->name('vetlink.admin.')->group(function () {
    Route::get('dashboard', ViewDashboardController::class)->name('dashboard');
});