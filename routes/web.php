<?php

use App\Http\Middleware\EnsureMailLiteApiKeyExist;
use Illuminate\Support\Facades\Route;

Route::get('/api-key', [App\Http\Controllers\AppController::class, 'initApiKey']);
Route::post('/api-key', [App\Http\Controllers\AppController::class, 'saveApiKey']);

Route::middleware([EnsureMailLiteApiKeyExist::class])->group(function () {
    Route::get('/', [App\Http\Controllers\SubscriberController::class, 'index']);
    Route::get('/subscribers', [App\Http\Controllers\SubscriberController::class, 'all']);
    Route::post('/subscribers', [App\Http\Controllers\SubscriberController::class, 'create']);
    Route::delete('/subscribers/{id}', [App\Http\Controllers\SubscriberController::class, 'delete']);
    Route::get('/subscribers/{id}', [App\Http\Controllers\SubscriberController::class, 'single']);
});
