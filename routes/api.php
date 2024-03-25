<?php

use App\Http\Controllers\ApartamentoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BancoController;
use App\Http\Controllers\BlocoController;
use App\Http\Controllers\CaixaController;
use App\Http\Controllers\CentralidadeController;
use App\Http\Controllers\CoordenadorController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\DividaController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\MoradorController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\PredioController;
use App\Http\Controllers\TaxaController;
use App\Http\Controllers\UserController;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Password;

Route::middleware(['auth:sanctum'])->group(function () {
  Route::apiResource('/centralidades', CentralidadeController::class);
});

/* recuperar 1senha */
Route::get('/login-form', function () {
  return view('testes.login');
})->middleware('guest')->name('login.form');

Route::get('/forgot-password', function () {
  return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
  $request->validate(['email' => 'required|email']);

  $status = Password::sendResetLink(
      $request->only('email')
  );
dd($status);
  return $status === Password::RESET_LINK_SENT
              ? back()->with(['status' => __($status)])
              : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');


Route::get('/getAll', function () {
  return response()->json(['message' => 123]);
});
//Usuario
Route::prefix('auth')->group(function () {
  Route::post('/login', [AuthController::class, 'login'])->name('login');
  Route::get('/login_view', [AuthController::class, 'login_view'])->name('login_view');
  Route::get('/login_view_reset', [AuthController::class, 'login_view_reset'])->name('login_view_reset');
  Route::post('/login_view_reset', [AuthController::class, 'postlogin_view_reset'])->name('postlogin_view_reset');

  // Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
  // Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
   Route::post('/', [UserController::class, 'create']);
});
//ENDERECO
Route::prefix('enderecos')->group(function () {
  Route::get('/', [EnderecoController::class, 'getAll']);
  Route::post('/', [EnderecoController::class, 'create']);
  Route::get('/{id}', [EnderecoController::class, 'getOne']);
  Route::put('/{id}', [EnderecoController::class, 'update']);
  Route::delete('/{id}', [EnderecoController::class, 'delete']);
});

// CENTRALIDADES
Route::prefix('centralidades')->group(function () {
  Route::get('/', [CentralidadeController::class, 'getAll']);
  Route::post('/', [CentralidadeController::class, 'create'])->middleware('auth');
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
  Route::get('/predio/coord/{idCoordenador}', [TaxaController::class, 'getAllByPredio']);
  Route::get('/{id}', [TaxaController::class, 'getOne']);
  Route::put('/{id}', [TaxaController::class, 'update']);
  Route::delete('/{id}', [TaxaController::class, 'delete']);
});

//PAGAMENTO
Route::prefix('pagamentos')->group(function () {
  Route::get('/', [PagamentoController::class, 'getAll']);
  /**[cria] um morador */
  Route::get('/p/{campoDaConsulta}', [PagamentoController::class, 'getBetweenDate']);
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
  Route::get('/apartamento/{idapartamento}', [DividaController::class, 'getAllByApartamento']);
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
//CAIXA
Route::prefix('caixas')->group(function () {
  Route::get('/', [CaixaController::class, 'getAll']);
  /**[pega] todas as dividas de uma conta de apartamento  - id da conta e fornecida na url */
  Route::get('/coord/{idCoordPredio}', [CaixaController::class, 'getAllByPredio']);
  Route::get('/{id}', [CaixaController::class, 'getOne']);
  Route::put('/{id}', [CaixaController::class, 'update']);
  Route::delete('/{id}', [CaixaController::class, 'delete']);
});
//BANCO
Route::prefix('bancos')->group(function () {
  Route::get('/', [BancoController::class, 'getAll']);
  Route::post('/', [BancoController::class, 'create']);
  /**[pega] todas as dividas de uma conta de apartamento  - id da conta e fornecida na url */
  Route::get('/predio/{idPredio}', [BancoController::class, 'getAllByCoordenador']);
  Route::get('/{id}', [BancoController::class, 'getOne']);
  Route::put('/{id}', [BancoController::class, 'update']);
  Route::delete('/{id}', [BancoController::class, 'delete']);
});
