<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api',    'prefix' => 'v1'], function ($router) {
    Route::get('/',function() { });

    /*********User**********/
    Route::post('/login', [RegisterController::class, 'login'])->name('login')->name('login');
    Route::post('/register-user', [RegisterController::class, 'register'])->name('register-user');



});
