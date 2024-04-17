<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Morador;
use App\Models\Pagamento;
use App\Utils\Util;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Throw_;

class PagamentoController extends Controller
{
  public array $camposAceitaveisNaConsulta = ['d_datapagam', 'd_dacrpagam', 'd_dacopagam'];
  private array $listaDeActionsPorCampo;

  public function __construct()
  {
    // Código no construtor
    $this->listaDeActionsPorCampo  = [
      'd_dacrpagam' =>array($this, 'getConsultaDePagamentoEntreAsData'),
      'd_datapagam' =>array($this, 'getConsultaDePagamentoEntreAsData'),
      'd_dacopagam' =>array($this, 'getConsultaDePagamentoEntreAsData'),
    ];
  }


  private function getConsultaDePagamentoEntreAsData(Request $req, ...$args){
    // [$dataInicial, $dataFinal] = $req->only(['di', 'df']);
    $query = Pagamento::query();

    if (!$req->has('di') && !$req->has('df')) {
      throw new Exception('Erro ao validar os dados para consultas.');
    }
    if ($req->has('di') && !$req->has('df')) {
      // faz a consulta com [di]
      if (!Util::validarData($req->input('di')))
        throw new Exception('Erro ao validar os dados. Data invalida.');
      $query->where($args['campo'], '=', $req->input('di'));
    }
    if (!$req->has('di') && $req->has('df')) {
      // faz a consulta com [df]
      if (!Util::validarData($req->input('df')))
        throw new Exception('Erro ao validar os dados. Data invalida.');
      $query->where($args['campo'], '=', $req->input('df'));
    }
    if ($req->has('di') && $req->has('df')) {
      // faz a consulta com [di] e [df]
      if (!(Util::validarData($req->input('di')) && Util::validarData($req->input('df'))))
        throw new Exception('Erro ao validar os dados. Data invilida.');

      // $dx = new \DateTime($req->input('di'));
      // $dy = new \DateTime($req->input('df'));
      $query->whereBetween($args['campo'], [$req->input('di'), $req->input('df')]);
    }
    return $query->get();
  }
  /**
   * @OA\Get(
   *     tags={"/pagamentos"},
   *     path="/pagamentos",
   *     summary="listar pagamentos",
   *     security={{"bearerAuth": {} }},
   *     @OA\Response(response="200", description="sucesso"),
   *     @OA\Response(response="500", description="Erro no servidor")
   * )
   */
  public function getAll()
  {
    try {
      //$data = response()->json(['pagamentos' => Pagamento::all()], 200);
      $user = auth()->user();
      $data = response()->json(['pagamentos' => []], 200);

      if ($user->c_nomeentid == 'tramorad' && $user->n_codientid != null) {
          $apartamento = Apartamento::where('n_codimorad', $user->n_codientid)->first();
          if ($apartamento) {
            //$apartamento->pagamentos;
              $data = response()->json(['pagamentos' => Pagamento::where('n_codiapart', $apartamento->n_codiapart)->get()], 200);
          }else{
            $data = response()->json(['message' => 'nemhum pagamento encontrado'], 200);
          }
      }

      return $data;

    } catch (QueryException $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }


    /**
   * @OA\Get(
   *     tags={"/pagamentos"},
   *     path="/pagamentos/morador/{idMorador}",
   *     summary="mostrar pagamento",
   *     security={{ "bearerAuth": {}}},
   *     @OA\Parameter(
   *         name="idMorador",
   *         in="path",
   *         description="id do pagamento",
   *         required=false,
   *         @OA\Schema(type="int")
   *     ),
   *     @OA\Response(response="200", description="sucesso"),
   *     @OA\Response(response="404", description="Morador não encontrado"),
   *     @OA\Response(response="500", description="Erro no servidor")
   * )
   */
    public function getAllByPagamentos($idMorador)
    {
        try {

          $morador = Morador::with('pagamentos')->find($idMorador);
          if(!$morador){
            return response()->json(['message' => 'Morador não encontrado'], 404);
          }
          $pagamentos = $morador->pagamentos;
          return response()->json(['pagamentos' => $pagamentos], 200);

        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

  /**
   * @OA\Post(
   *     tags={"/pagamentos"},
   *     path="/pagamentos",
   *     summary="registrar pagamento",
   *     security={{"bearerAuth": {} }},
   *     @OA\RequestBody(
   *       required=true,
   *       @OA\JsonContent(
   *          required={"n_valopagam","c_descpagam","c_formpagam","d_datapagam","n_codiapart","n_codidivid"},
   *          type="object",
   *          @OA\Property(property="n_valopagam",type="string",description="valor do pagamento"),
   *          @OA\Property(property="c_descpagam",type="float",description="descrição do pagamento"),
   *          @OA\Property(property="c_formpagam",type="int",description="forma de pagamento"),
   *          @OA\Property(property="d_datapagam",type="float",description="data de pagamento"),
   *          @OA\Property(property="n_codidivid",type="date",description="id divida"),
   *          @OA\Property(property="n_codiapart",type="string",description="id apartamento"),
   *       )
   *     ),
   *
   *     @OA\Response(response="201", description="pagamento registrado com sucesso"),
   *     @OA\Response(response="412", description="Erro ao validar os dados"),
   *     @OA\Response(response="404", description="Morador não encontrado"),
   *     @OA\Response(response="500", description="Erro no servidor")
   * )
   */
  public function create(Request $req)
  {
    $isValidData = Validator::make($req->all(), [
      'n_valopagam'  => 'required',
      'n_vadipagam',
      'c_descpagam'  => 'required|string',
      'c_formpagam'  => 'required|string',
      'd_datapagam'  => 'required',
      'd_dacrpagam',
      'create_at',
      'updated_at',
      'd_dacopagam',
      'c_bancpagam',
      'n_codibanco',
      'n_estapagam',
      'n_codicoord',
      'n_codidivid'  => 'required',
      'n_codiapart'  => 'required'
    ]);
    if ($isValidData->fails())
      return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

    try {
      Pagamento::create($req->all());
      // dd($data);
      return response()->json(['message' => "Pagamento criado com sucesso!"], 201);;
    } catch (QueryException $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }


  /**
   * @OA\Delete(
   *     tags={"/pagamentos"},
   *     path="/pagamentos/{pagamento}",
   *     summary="deletar pagamento",
   *       security={{"bearerAuth": {} }},
   *       @OA\Parameter(
   *         name="pagamento",
   *         in="path",
   *         description="id da pagamento",
   *         required=false,
   *         @OA\Schema(type="int")
   *     ),
   *     @OA\Response(response="200", description="pagamento deletado com sucesso!"),
   *     @OA\Response(response="404", description="pagamento não encontrado"),
   *     @OA\Response(response="500", description="Erro no servidor")
   * )
   */
  public function delete($id)
  {
    try {
      $Pagamento = Pagamento::find($id);
      if (!$Pagamento)
        return response()->json(['message' => "Pagamento não encontrado"], 404);
      $Pagamento->delete();
      return response()->json(['message' => "Pagamento deletado com sucesso!"], 200);
    } catch (QueryException $e) {
      return response()->json(['message' => "Hello " . $e->getMessage()], 500);
    }
  }
  public function update(Request $req, $id)
  {
    try {
      $Pagamento = Pagamento::find($id);
      if (!$Pagamento) {
        return response()->json(['message' => "Pagamento não encontrado."], 404);
      }
      $Pagamento->update($req->all());

      return response()->json($req->all());
    } catch (QueryException $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }
  /**
   * @OA\Get(
   *     tags={"/pagamentos"},
   *     path="/pagamentos/{pagamento}",
   *     summary="mostrar pagamento",
   *     security={{ "bearerAuth": {}}},
   *     @OA\Parameter(
   *         name="pagamento",
   *         in="path",
   *         description="id do pagamento",
   *         required=false,
   *         @OA\Schema(type="int")
   *     ),
   *     @OA\Response(response="200", description="sucesso"),
   *     @OA\Response(response="404", description="pagamento não encontrado"),
   *     @OA\Response(response="500", description="Erro no servidor")
   * )
   */
  public function getOne($id)
  {
    try {
      $Pagamento = Pagamento::find($id);
      if (!$Pagamento) {
        return response()->json(['message' => "Pagamento não encontrado!"], 404);
      }
      return response()->json($Pagamento, 200);
    } catch (QueryException $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }
  private function validarCampoDePesquisa(string $campoAVerificar, array $camposDoModelo, array $camposAceitavies): bool
  {
    // $fillableFields = (new Pagamento())->getFillable();
    $camposDoModeloFiltrado = array_filter($camposDoModelo, function ($campo) use ($camposAceitavies) {
      return in_array($campo, $camposAceitavies);
    });
    return in_array($campoAVerificar, $camposDoModeloFiltrado);
  }

  private function executarPesquisaDoCampo(string $campo, Request $req, ...$args)
  {
    if (!isset($this->listaDeActionsPorCampo[$campo])) {
      throw  new Exception("Não foi definido uma ação para o campo $campo");
    }
    return call_user_func_array($this->listaDeActionsPorCampo[$campo], [$req, ...$args, "campo"=> $campo]);
  }

  /**
   * @OA\GET(
   *     tags={"/pagamentos"},
   *     path="/pagamentos/p/{campoDaConsulta}",
   *     summary="consultar pagamentos no intervalo das datas inicial e final, de alguma propriedade do tipo date do pagamento",
   *       security={{"bearerAuth": {} }},
   *       @OA\Parameter(
   *         name="campoDaConsulta",
   *         in="path",
   *         description="pesquisa por propriedades do pagamento, jeralmente do tipo datas",
   *         required=false,
   *         @OA\Schema(
   *             type="string",
   *             enum={"d_dacrpagam", "d_datapagam", "d_dacopagam"},
   *             description="propriedade [permitida]: d_dacrpagam, d_datapagam, d_dacopagam")
   *     ),
   *       @OA\Parameter(
   *         name="di",
   *         in="query",
   *         description="data [inícial] X do pagamento no fotmato: Ano-mes-dia",
   *         required=false,
   *         @OA\Schema(type="date")
   *     ),
   *       @OA\Parameter(
   *         name="df",
   *         in="query",
   *         description="data [final] X do pagamento no fotmato: Ano-mes-dia",
   *         required=false,
   *         @OA\Schema(type="date")
   *     ),
   *     @OA\Response(response="200", description=""),
   *     @OA\Response(response="412", description="O campo  X Não é permitido."),
   *     @OA\Response(response="500", description="Erro no servidor")
   * )
   */

  public function getBetweenDate(Request $req, $campoDaConsulta)
  {
    $camposDoModelo = (new Pagamento())->getFillable();

    if (!$this->validarCampoDePesquisa($campoDaConsulta, $camposDoModelo, $this->camposAceitaveisNaConsulta))
      return response()->json(['message' => "O campo " . $campoDaConsulta . " Não é permitido."], 412);

    try {
      $resultado = $this->executarPesquisaDoCampo($campoDaConsulta, $req);
      return response()->json(['pagamentos ' => $resultado], 200);
    } catch (QueryException | Exception $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }
}
