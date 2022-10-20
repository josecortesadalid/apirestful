<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Authentication Routes...
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Password Reset Routes... Modificarlas
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');


// Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset']);

Route::get('/home/my-tokens', [HomeController::class, 'getTokens'])->name('personal-tokens');
Route::get('/home/my-clients', [HomeController::class, 'getClients'])->name('personal-clients');
Route::get('/home/authorized-clients', [HomeController::class, 'getAuthorizedClients'])->name('authorized-clients');
Route::get('/home', [HomeController::class, 'index']);


Route::get('/', function () {
    return view('welcome');
})->middleware('guest');