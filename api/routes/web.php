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
	return view('welcome');
});

Route::get('/server', function (\Illuminate\Http\Request $request) {
	$header = $request->cookie('X-XSRF-TOKEN');
	return $request->header();
	return $_COOKIE;
});