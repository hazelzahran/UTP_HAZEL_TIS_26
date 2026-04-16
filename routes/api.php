<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContainerController;

Route::get('/containers', [ContainerController::class, 'index']);
Route::post('/containers', [ContainerController::class, 'store']);