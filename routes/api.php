<?php

use App\Http\Controllers\ApartamentoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\BlocoController;
use App\Http\Controllers\CentralidadeController;
use App\Http\Controllers\CoordenadorController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\DividaController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\MoradorController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\PredioController;
use App\Http\Controllers\TaxaController;

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
    /**[pega] todos as centralidades de uma provoncia  - provincia, fornecida na url */
    Route::get('/provincia/{denominacao}', [CentralidadeController::class, 'getAllByProvincia']);
    Route::delete('/{id}', [CentralidadeController::class, 'delete']);
    Route::put('/{id}', [CentralidadeController::class, 'update']);
    Route::get('/{id}', [CentralidadeController::class, 'getOne']);
});

// BLOCOS
Route::prefix('blocos')->group(function () {
    Route::get('/', [BlocoController::class, 'getAll']);
    /**[cria] um bloco dentro de uma centralidade - id da centralidade e fornecida na url */
    Route::post('/centralidade/{idCentralidade}', [BlocoController::class, 'create']);
    /**[pega] todos os blocos de uma centralidade  - id da centralidade e fornecida na url */
    Route::get('/centralidade/{idCentralidade}', [BlocoController::class, 'getAllByCentr']);
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
    //Route::get('/bloco/{idbloco}', [CoordenadorController::class, 'getAllByBloco']);
    Route::get('/{id}', [CoordenadorController::class, 'getOne']);
    Route::put('/{id}', [CoordenadorController::class, 'update']);
    Route::delete('/{id}', [CoordenadorController::class, 'delete']);
});

//TAXA
Route::prefix('taxas')->group(function () {
    Route::get('/', [TaxaController::class, 'getAll']);
    /**[cria] um morador */
    Route::post('/', [TaxaController::class, 'create']);
    /**[pega] todas as taxas de um predio  - id do predio e fornecida na url */
    Route::get('/predio/{idCoordenador}', [TaxaController::class, 'getAllByPredio']);
    Route::get('/{id}', [TaxaController::class, 'getOne']);
    Route::put('/{id}', [TaxaController::class, 'update']);
    Route::delete('/{id}', [TaxaController::class, 'delete']);
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

//DIVIDA
Route::prefix('dividas')->group(function () {
    Route::get('/', [DividaController::class, 'getAll']);
    /**[cria] um morador */
    Route::post('/', [DividaController::class, 'create']);
    /**[pega] todas as dividas de uma conta de apartamento  - id da conta e fornecida na url */
    Route::get('/conta/{idConta}', [DividaController::class, 'getAllByConta']);
    Route::get('/{id}', [DividaController::class, 'getOne']);
    Route::put('/{id}', [DividaController::class, 'update']);
    Route::delete('/{id}', [DividaController::class, 'delete']);
});
//DESPESA
Route::prefix('despesas')->group(function () {
    Route::get('/', [DespesaController::class, 'getAll']);
    /**[cria] um morador */
    Route::post('/', [DespesaController::class, 'create']);
    /**[pega] todas as dividas de uma conta de apartamento  - id da conta e fornecida na url */
    Route::get('/coord/{idCoordPredio}', [DespesaController::class, 'getAllByPredio']);
    Route::get('/{id}', [DespesaController::class, 'getOne']);
    Route::put('/{id}', [DespesaController::class, 'update']);
    Route::delete('/{id}', [DespesaController::class, 'delete']);
});