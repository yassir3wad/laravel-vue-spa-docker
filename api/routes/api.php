<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\UploadController;

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

	Route::get('/user', [ProfileController::class, 'me']);
	Route::get('users', function () {
		return \App\Http\Resources\Dashboard\UserProfileResource::collection(
			\App\Models\User::orderBy(\request('sort'), \request('sort_dir'))->paginate(\request('per_page'))
		);
	});
});

