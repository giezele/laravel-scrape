<?php

use App\Http\Controllers\JobController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::post('/jobs', [JobController::class, 'create']);
Route::get('/jobs/{id}', [JobController::class, 'show']);
Route::delete('/jobs/{id}', [JobController::class, 'destroy']);

