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
Route::get('/', function () {
	dd(\App\Events\TestEvent::broadcast());
	return view('welcome');
});
//
//Route::get('/login', function () {
//	return redirect(config('app.client_url'));
//})->name('login');