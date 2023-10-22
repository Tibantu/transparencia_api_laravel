<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\BlocoController;
use App\Http\Controllers\CentralidadeController;

Route::get('/todos', [TodoController::class, 'getAll']);
Route::post('/todos', [TodoController::class, 'save']);
Route::get('/blocos', [BlocoController::class,'getAll']);

// CENTRALIDADES
Route::get('/centralidades', [CentralidadeController::class,'index']);
Route::post('/centralidades', [CentralidadeController::class,'create']);
