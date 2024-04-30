<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

<<<<<<< Updated upstream
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
=======
    Route::get('/task/{task_id}', [TaskDetailsController::class, 'index']);
    Route::put('/task/{task_id}', [TaskDetailsController::class, 'update']);
    Route::delete('/task/delete/{task_id}', [TaskDetailsController::class, 'delete']);
>>>>>>> Stashed changes
});
