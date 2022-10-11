<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/', function (Request $request, Response $response){
    return response()->json([
        'status' => 'success',
        'data'   => ['message' => 'Welcome to Coalition Technologies Task Management Assessment Api']
    ]);
})->name('index');

Route::group(['middleware' => 'guest'], function (){

    Route::post('register', RegisterController::class)->name('register');

    Route::post('login', [AuthenticationController::class, 'store'])->name('login');
});

Route::prefix('project')->name('project.')->group(function(){
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::post('/', [ProjectController::class, 'store'])->name('store');
    Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
    Route::patch('/{project}', [ProjectController::class, 'update'])->name('update');
    Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('delete');
});

Route::prefix('task')->name('task.')->group(function(){
    Route::get('/', [TasksController::class, 'index'])->name('index');
    Route::post('/', [TasksController::class, 'store'])->name('store');
    Route::get('/{task}', [TasksController::class, 'show'])->name('show');
    Route::patch('/{task}', [TasksController::class, 'update'])->name('update');
    Route::delete('/{task}', [TasksController::class, 'destroy'])->name('delete');
    Route::post('/priority', [TasksController::class, 'setPriority'])->name('setPriority');

});

Route::middleware('auth')->group(function(){

});