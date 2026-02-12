<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/quiz/start', [QuizController::class, 'start']);
Route::post('/quiz/submit', [QuizController::class, 'submit']);
