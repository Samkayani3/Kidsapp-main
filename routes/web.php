<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\addVehicleDetails;
use App\Http\Controllers\KidController;
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

Route::get('/register', function () {
    return view('register');
});


Route::get('/', [RegisterController::class, 'displayAllData']);
Route::post('/register-user', [RegisterController::class, 'register'])->name('register-user');
Route::post('/login', [RegisterController::class, 'login'])->name('login')->name('login');
Route::get('/user-id/{id}', [RegisterController::class, 'getUser']);
Route::post('/logout', [RegisterController::class, 'logout']);
Route::post('password-reset-link', [RegisterController::class, 'sendResetLinkEmail'])->name('password-reset-link');

// Route for password reset form
Route::get('password-reset-form/{token}', [RegisterController::class, 'showResetForm'])->name('password-reset-form');
Route::post('password-update', [RegisterController::class, 'reset'])->name('password-update');

// Route For update profile
Route::put('update-profile/{id}', [RegisterController::class, 'updateProfile'])->name('update-profile');


// Driver Routes
Route::middleware(['check.category:Driver'])->group(function () {
    Route::post('/add-vehicles', [addVehicleDetails::class, 'store'])->name('add-vehicles');
    Route::put('/vehicles/{id}', [addVehicleDetails::class, 'update']);
    Route::delete('/delete-vehicle/{id}', [addVehicleDetails::class, 'destroy'])->name('delete-vehicle');
    Route::get('/all-vehicles', [addVehicleDetails::class, 'index'])->name('all-vehicles');
    Route::get('/vehicles/{id}', [addVehicleDetails::class, 'show']);
});

// Parent Routes
Route::middleware(['check.category:Parent'])->group(function () {
    Route::post('/add-kids', [KidController::class, 'store'])->name('add-kids');
    Route::get('/kid/{userId}', [KidController::class, 'getKidsByUserId']);
    Route::put('/kid/{kidId}', [KidController::class, 'update']);
    Route::delete('/delete-kid/{kidId}', [KidController::class, 'delete']);
});
