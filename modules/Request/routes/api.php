<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Request\Controllers\RequestController;

Route::get('/', [RequestController::class, 'index']);
Route::post('/', [RequestController::class, 'store']);
Route::get('/{request}', [RequestController::class, 'show']);
Route::delete('/{request}', [RequestController::class, 'destroy']);
