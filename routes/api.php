<?php

use App\Http\Controllers\ApartamentoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BancoController;
use App\Http\Controllers\BlocoController;
use App\Http\Controllers\CaixaController;
use App\Http\Controllers\CentralidadeController;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\CoordenadorController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\DividaController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\MoradorController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PredioController;
use App\Http\Controllers\REDISController;
use App\Http\Controllers\TaxaController;
use App\Http\Controllers\UserController;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Password;

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return response()->json(['message' => 'nao autorizado'], 500);
});*/

Route::get('/blogs/{id}', [REDISController::class, 'index']);

/* recuperar 1senha */
//Usuario
Route::prefix('auth')->group(function () {
  Route::post('/username', [AuthController::class, 'username'])->name('username');
  Route::post('/senha', [AuthController::class, 'senha']);
  Route::post('/login', [AuthController::class, 'login'])->name('login');
  Route::get('/login_view', [AuthController::class, 'login_view'])->name('login_view');// rota nao necessaria
  Route::get('/login_view_reset', [AuthController::class, 'login_view_reset'])->name('login_view_reset');// rota nao necessaria
  Route::post('/reset-senha', [AuthController::class, 'postlogin_view_reset'])->name('postlogin_view_reset');// rota necessaria

  Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
  Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
  Route::post('/coord_bloco/centr/{idCentr}/bloco/{idbloco}', [UserController::class, 'create_coord_bloco']);
  Route::post('/centr/{idCentr}/bloco/{idbloco}', [UserController::class, 'create']);
  Route::post('/morador/usuario', [UserController::class, 'create_morad'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
  // Código protegido


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
      Route::post('/', [CentralidadeController::class, 'create']);
      /*[pega] todos as centralidades de uma provoncia  - provincia, fornecida na url
      Route::get('/provincia/{denominacao}', [CentralidadeController::class, 'getAllByProvincia']); */
      Route::delete('/{id}', [CentralidadeController::class, 'delete']);
      Route::put('/{id}', [CentralidadeController::class, 'update']);
      Route::get('/{id}', [CentralidadeController::class, 'getOne']);
    });

    // BLOCOS
    Route::prefix('blocos')->group(function () {
     // Route::get('/', [BlocoController::class, 'getAll']);
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
      //Route::get('/', [PredioController::class, 'getAll']);
      /**[cria] um bloco dentro de uma centralidade - id da centralidade e fornecida na url */
      Route::post('/bloco/{idBloco}', [PredioController::class, 'create']);
      /**[pega] todos os blocos de uma centralidade  - id da centralidade e fornecida na url */
      Route::get('/bloco/{idBloco}', [PredioController::class, 'getAllByBloco']);
      Route::get('/{id}', [PredioController::class, 'getOne']);
      Route::put('/{id}', [PredioController::class, 'update']);
      //Route::delete('/{id}', [PredioController::class, 'delete']);
    });

    // APARTAMENTOS
    Route::prefix('apartamentos')->group(function () {
      //Route::get('/', [ApartamentoController::class, 'getAll']);
      Route::post('/', [ApartamentoController::class, 'create']);
      Route::get('/', [ApartamentoController::class, 'getAllByPredio']);
      Route::get('/morador', [ApartamentoController::class, 'getOneApartamento']);
      Route::get('/{id}', [ApartamentoController::class, 'getOne']);
      Route::put('/{id}', [ApartamentoController::class, 'update']);
      //Route::delete('/{id}', [ApartamentoController::class, 'delete']);
    });
    /*Manuel Alfredo*/

    //MORADOR
    Route::prefix('moradores')->group(function () {
      //Route::get('/', [MoradorController::class, 'getAll']);
      /**[cria] um morador */
      Route::post('/apartamento/{idApartamento}', [MoradorController::class, 'create']);
      Route::get('/', [MoradorController::class, 'getAllByMoradores']);
      Route::get('/me', [MoradorController::class, 'me']);

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
      Route::get('/bloco/{idbloco}', [CoordenadorController::class, 'getAllByBloco']);
      Route::get('/{id}', [CoordenadorController::class, 'getOne']);
      Route::put('/{id}', [CoordenadorController::class, 'update']);
      Route::delete('/{id}', [CoordenadorController::class, 'delete']);
    });

    //TAXA
    Route::prefix('taxas')->group(function () {
      Route::get('/', [TaxaController::class, 'getAllByPredio']);
      Route::post('/', [TaxaController::class, 'create']);
      /**[pega] todas as taxas de um predio  - id do predio e fornecida na url */
      //Route::get('/predio/coord/{idCoordenador}', [TaxaController::class, 'getAllByPredio']);
      Route::get('/{id}', [TaxaController::class, 'getOne']);
      Route::put('/{id}', [TaxaController::class, 'update']);
      Route::delete('/{id}', [TaxaController::class, 'delete']);
    });

    //PAGAMENTO
    Route::prefix('pagamentos')->group(function () {
      Route::get('/', [PagamentoController::class, 'getAll']);
      Route::get('/morador/{idMorador}', [PagamentoController::class, 'getAllByPagamentos']);
      Route::get('/p/{campoDaConsulta}', [PagamentoController::class, 'getBetweenDate']);
      Route::post('/divida/{idDivida}', [PagamentoController::class, 'create']);
      Route::get('/{id}', [PagamentoController::class, 'getOne']);
      Route::put('/confirm/{id}', [PagamentoController::class, 'update_confirm']);
      Route::put('/{id}', [PagamentoController::class, 'update']);
      Route::delete('/{id}', [PagamentoController::class, 'delete']);
    });

    //DIVIDA
    Route::prefix('dividas')->group(function () {
      Route::get('/', [DividaController::class, 'getAll']);
      /**[pega] todas as dividas de uma conta de apartamento  - id da conta e fornecida na url */
      Route::get('/apartamento/{idapartamento}', [DividaController::class, 'getAllByApartamento']);
      Route::get('/{id}', [DividaController::class, 'getOne']);
      Route::put('/{id}', [DividaController::class, 'update']);
      //Route::delete('/{id}', [DividaController::class, 'delete']);
    });
    //DESPESA
    Route::prefix('despesas')->group(function () {
      Route::get('/', [DespesaController::class, 'getAll']);
      /**[cria] um morador */
      Route::post('/', [DespesaController::class, 'create']);
      Route::get('/{id}', [DespesaController::class, 'getOne']);
      Route::put('/{id}', [DespesaController::class, 'update']);
      Route::delete('/{id}', [DespesaController::class, 'delete']);
    });
    //CAIXA
    Route::prefix('caixas')->group(function () {
      //Route::get('/', [CaixaController::class, 'getCaixa']);
      /**[pega] todas as dividas de uma conta de apartamento  - id da conta e fornecida na url */
      Route::get('/coord', [CaixaController::class, 'getCaixa']);
      Route::get('/{id}', [CaixaController::class, 'getOne']);
      Route::put('/{id}', [CaixaController::class, 'update']);
     // Route::delete('/{id}', [CaixaController::class, 'delete']);
    });
    //CONTA
    Route::prefix('contas')->group(function () {
      Route::get('/morador', [ContaController::class, 'getConta']);
      Route::get('/{id}', [ContaController::class, 'getOne']);
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
    //DOCUMENTOS
    Route::prefix('documentos')->group(function () {
      Route::get('/recibo', [PDFController::class, 'downloadPDF']);
      Route::get('/pagamento/recibo/{idPagamento}', [PDFController::class, 'getPagamentoPDF']);
      Route::get('/coord/recibo/pagamento/{idPagamento}', [PDFController::class, 'getPagamentoCoordPDF']);
    });

    //Funcionario
    Route::prefix('funcionarios')->group(function () {
      //Route::get('/', [MoradorController::class, 'getAll']);
      /**[cria] um morador */
      Route::post('/', [FuncionarioController::class, 'create']);
      Route::get('/', [FuncionarioController::class, 'getAllByFuncionarios']);
      Route::get('/{id}', [MoradorController::class, 'getOne']);//*
      Route::put('/{id}', [MoradorController::class, 'update']);//*
      Route::delete('/{id}', [MoradorController::class, 'delete']);//*
    });

    //Notificacao
    Route::prefix('notificacoes')->group(function () {
      Route::post('/{apartamento_ids}', [NotificacaoController::class, 'create']);
      Route::get('/', [NotificacaoController::class, 'getAllByNotificacoes']);
      Route::get('/{id}', [MoradorController::class, 'getOne']);//*
      Route::put('/{id}', [MoradorController::class, 'update']);//*
      Route::delete('/{id}', [MoradorController::class, 'delete']);//*
    });
});
