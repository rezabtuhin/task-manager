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
<<<<<<< Updated upstream
=======


Route::group(['middleware' => ['auth']], function (){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/tasks', [TaskListController::class, 'index']);
    Route::get('/task/{task_id}', [TaskController::class, 'index']);
    Route::get('/task/{task_id}/edit', [TaskController::class, 'edit']);
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

>>>>>>> Stashed changes
