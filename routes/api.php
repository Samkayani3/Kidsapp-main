<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\addVehicleDetails;
use App\Http\Controllers\KidController;
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
    Route::get('/', function () {
    });

    /*********User**********/
    Route::post('/login', [RegisterController::class, 'login'])->name('login')->name('login');
    Route::post('/register-user', [RegisterController::class, 'register'])->name('register-user');
    Route::post('/enter-otp', [RegisterController::class, 'otpMatch'])->name('enter-otp');

    Route::post('password-reset-email', [RegisterController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password-reset-form/{id}', [RegisterController::class, 'showResetForm'])->name('password.reset');
    Route::post('password-update/{id}', [RegisterController::class, 'reset'])->name('password.update');

    Route::get('/all-users', [RegisterController::class, 'displayAllData'])->middleware('admin');
    Route::get('/user-details/{id}', [RegisterController::class, 'getUser'])->middleware('admin');


    Route::middleware('jwt.token')->group(function () {
        Route::post('/logout', [RegisterController::class, 'logout']);


        // Route For update profile
        Route::put('update-profile', [RegisterController::class, 'updateProfile'])->name('update-profile');


        Route::middleware(['check.category:Driver'])->group(function () {
            Route::post('/add-vehicles', [addVehicleDetails::class, 'store'])->name('add-vehicles');
            Route::put('/vehicles/{id}', [addVehicleDetails::class, 'update']);
            Route::delete('/delete-vehicle/{id}', [addVehicleDetails::class, 'destroy'])->name('delete-vehicle');
            Route::get('/all-vehicles', [addVehicleDetails::class, 'index'])->name('all-vehicles');
            Route::get('/vehicles', [addVehicleDetails::class, 'show'])->name('vehicles');
        });

        // Parent Routes
        Route::middleware(['check.category:Parent'])->group(function () {
            Route::post('/add-kids', [KidController::class, 'store'])->name('add-kids');
            Route::get('/kids', [KidController::class, 'getKidsByUserId']);
            Route::put('/kid/{kidId}', [KidController::class, 'update']);
            Route::delete('/delete-kid/{kidId}', [KidController::class, 'delete']);
        });
    });
});
