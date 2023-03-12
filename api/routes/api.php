<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\UploadController;
use App\Http\Controllers\Dashboard\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
	Route::post('/files', [UploadController::class, 'store']);

	Route::get('/me', [ProfileController::class, 'me']);

	Route::prefix('users')->as('users.')->controller(UserController::class)->group(function () {
		Route::get('/', 'index')->name('index');
		Route::get('{user}', 'show')->name('show');
		Route::post('/', 'store')->name('store');
		Route::put('{user}', 'update')->name('update');
		Route::delete('{user}', 'destroy')->name('delete');
		Route::patch('{user}/reset-password', 'resetPassword')->name('reset-password');
		Route::patch('{user}/change-status', 'changeStatus')->name('change-status');
	});
});

