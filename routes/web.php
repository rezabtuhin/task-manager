<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\TaskController;
use App\Http\Controllers\Pages\TaskListController;
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

Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/', [LoginController::class, 'login'])->name('login');
});

Route::group(['middleware' => ['auth']], function (){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/tasks', [TaskListController::class, 'index']);
    Route::get('/task/{task_id}', [TaskController::class, 'index']);
    Route::get('/task/{task_id}/edit', [TaskController::class, 'edit']);
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
