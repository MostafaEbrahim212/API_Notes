<?php
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\NotesController;




Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('profile', [AuthController::class, 'profile']);
    route::apiResource('notes', NotesController::class);
});


