<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\UploadController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\RoleController;

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

	Route::prefix('roles')->as('roles.')->controller(RoleController::class)->group(function () {
		Route::get('/', 'index')->name('index');
		Route::get('list', 'list')->name('permissions');
		Route::get('permissions', 'permissions')->name('permissions');
		Route::get('{role}', 'show')->name('show');
		Route::post('/', 'store')->name('store');
		Route::put('{role}', 'update')->name('update');
		Route::delete('{role}', 'destroy')->name('delete');
	});
});

