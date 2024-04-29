<?php

use App\Http\Controllers\Api\CreateTaskController;
use App\Http\Controllers\Api\TaskDetailsController;
use App\Http\Controllers\Pages\TaskController;
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
Route::group(['middleware' => 'rate.limit.per.day'], function (){
    Route::middleware('rate.limit.create.api')->post('/create-task', [CreateTaskController::class, 'store']);

    Route::get('/task/{task_id}', [TaskDetailsController::class, 'index']);
    Route::delete('/task/delete/{task_id}', [TaskDetailsController::class, 'delete']);
});
