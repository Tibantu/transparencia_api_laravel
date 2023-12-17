<?php

use App\Http\Controllers\ApartamentoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\BlocoController;
use App\Http\Controllers\CentralidadeController;
use App\Http\Controllers\CoordenadorController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\MoradorController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\PredioController;

//ENDERECO
Route::prefix('/enderecos')->group(function () {
    Route::get('/', [EnderecoController::class, 'getAll']);
    Route::post('/', [EnderecoController::class, 'create']);
    Route::get('/{id}', [EnderecoController::class, 'getOne']);
    Route::put('/{id}', [EnderecoController::class, 'update']);
    Route::delete('/{id}', [EnderecoController::class, 'delete']);
});

// CENTRALIDADES
Route::prefix('centralidades')->group(function () {
    Route::get('/', [CentralidadeController::class, 'getAll']);
    Route::post('/', [CentralidadeController::class, 'create']);
    Route::delete('/{id}', [CentralidadeController::class, 'delete']);
    Route::put('/{id}', [CentralidadeController::class, 'update']);
    Route::get('/{id}', [CentralidadeController::class, 'getOne']);
});

// BLOCOS
Route::prefix('blocos')->group(function () {
    Route::get('/', [BlocoController::class, 'getAll']);
    /**[cria] um bloco dentro de uma centralidade - id da centralidade e fornecida na url */
    Route::post('/centr/{idCentralidade}', [BlocoController::class, 'create']);
    /**[pega] todos os blocos de uma centralidade  - id da centralidade e fornecida na url */
    Route::get('/centr/{idCentralidade}', [BlocoController::class, 'getAllByCentr']);
    Route::get('/{id}', [BlocoController::class, 'getOne']);
    Route::put('/{id}', [BlocoController::class, 'update']);
    Route::delete('/{id}', [BlocoController::class, 'delete']);
});

// PREDIOS
Route::prefix('predios')->group(function () {
    Route::get('/', [PredioController::class, 'getAll']);
    /**[cria] um bloco dentro de uma centralidade - id da centralidade e fornecida na url */
    Route::post('/bloco/{idBloco}', [PredioController::class, 'create']);
    /**[pega] todos os blocos de uma centralidade  - id da centralidade e fornecida na url */
    Route::get('/bloco/{idBloco}', [PredioController::class, 'getAllByBloco']);
    Route::get('/{id}', [PredioController::class, 'getOne']);
    Route::put('/{id}', [PredioController::class, 'update']);
    Route::delete('/{id}', [PredioController::class, 'delete']);
});

// APARTAMENTOS
Route::prefix('apartamentos')->group(function () {
    Route::get('/', [ApartamentoController::class, 'getAll']);
    /**[cria] um bloco dentro de uma centralidade - id da centralidade e fornecida na url */
    Route::post('/predio/{idPredio}', [ApartamentoController::class, 'create']);
    /**[pega] todos os blocos de uma centralidade  - id da centralidade e fornecida na url */
    Route::get('/predio/{idPredio}', [ApartamentoController::class, 'getAllByPredio']);
    Route::get('/{id}', [ApartamentoController::class, 'getOne']);
    Route::put('/{id}', [ApartamentoController::class, 'update']);
    Route::delete('/{id}', [ApartamentoController::class, 'delete']);
});
    /*Manuel Alfredo*/

//MORADOR
Route::prefix('moradores')->group(function () {
    Route::get('/', [MoradorController::class, 'getAll']);
    /**[cria] um morador */
    Route::post('/', [MoradorController::class, 'create']);
    /**[pega] todos os blocos de uma centralidade  - id da centralidade e fornecida na url */
    //Route::get('/predio/{idPredio}', [MoradorController::class, 'getAllByPredio']);
    Route::get('/{id}', [MoradorController::class, 'getOne']);
    Route::put('/{id}', [MoradorController::class, 'update']);
    Route::delete('/{id}', [MoradorController::class, 'delete']);
});


//COORDENADOR
Route::prefix('coordenadores')->group(function () {
    Route::get('/', [CoordenadorController::class, 'getAll']);
    /**[cria] um morador */
    Route::post('/', [CoordenadorController::class, 'create']);
    /**[pega] todos os blocos de uma centralidade  - id da centralidade e fornecida na url */
    //Route::get('/predio/{idPredio}', [CoordenadorController::class, 'getAllByPredio']);
    Route::get('/{id}', [CoordenadorController::class, 'getOne']);
    Route::put('/{id}', [CoordenadorController::class, 'update']);
    Route::delete('/{id}', [CoordenadorController::class, 'delete']);
});

//PAGAMENTO
Route::prefix('pagamentos')->group(function () {
    Route::get('/', [PagamentoController::class, 'getAll']);
    /**[cria] um morador */
    Route::post('/', [PagamentoController::class, 'create']);
    /**[pega] todos os blocos de uma centralidade  - id da centralidade e fornecida na url */
    //Route::get('/predio/{idPredio}', [PagamentoController::class, 'getAllByPredio']);
    Route::get('/{id}', [PagamentoController::class, 'getOne']);
    Route::put('/{id}', [PagamentoController::class, 'update']);
    Route::delete('/{id}', [PagamentoController::class, 'delete']);
});