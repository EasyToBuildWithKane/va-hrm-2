<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Notification\Controllers\NotificationController;

Route::get('/', [NotificationController::class, 'index']);
Route::post('/{notification}/read', [NotificationController::class, 'read']);
Route::post('/read-all', [NotificationController::class, 'readAll']);
