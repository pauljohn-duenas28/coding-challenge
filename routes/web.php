<?php

use App\Http\Controllers\JobListController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home');

Route::get('/jobs', [JobListController::class, 'index']);
Route::get('/jobs/create', [JobListController::class, 'create']);
Route::get('/jobs/{job}', [JobListController::class, 'show']);
Route::post('/jobs', [JobListController::class, 'store']);
Route::put('/jobs/{job}', [JobListController::class, 'publish']);
Route::get('/post-jobs', [JobListController::class, 'post_jobs']);


//Auth
Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);

//login
Route::get('/login', [SessionController::class, 'create']);
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);
