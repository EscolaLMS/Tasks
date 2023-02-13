<?php

use EscolaLms\Tasks\Http\Controllers\AdminTaskController;
use EscolaLms\Tasks\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')
    ->middleware(['auth:api'])
    ->group(function () {
        Route::prefix('admin/tasks')->group(function () {
            Route::post(null, [AdminTaskController::class, 'create']);
            Route::patch('{id}', [AdminTaskController::class, 'update']);
            Route::delete('{id}', [AdminTaskController::class, 'delete']);
            Route::post('complete/{id}', [AdminTaskController::class, 'complete']);
            Route::post('incomplete/{id}', [AdminTaskController::class, 'incomplete']);
            Route::get('', [AdminTaskController::class, 'findAll']);
        });

        Route::prefix('tasks')->group(function () {
            Route::post(null, [TaskController::class, 'create']);
            Route::patch('{id}', [TaskController::class, 'update']);
            Route::delete('{id}', [TaskController::class, 'delete']);
            Route::post('complete/{id}', [TaskController::class, 'complete']);
            Route::post('incomplete/{id}', [TaskController::class, 'incomplete']);
            Route::get('', [TaskController::class, 'findAll']);
        });

    });
