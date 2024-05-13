<?php

namespace App\Http\Controllers;

use App\Models\Bloco;
use App\Models\Centralidade;
use App\Models\Apartamento;
use App\Models\Conta;
use App\Models\Coordenador;
use App\Models\Morador;
use App\Models\Predio;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use \Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redis;

use function PHPUnit\Framework\isNull;

class ApartamentoController extends Controller
{
  /**METODOTOS PRIVADO*/

  private function getPredio(){

    $user = auth()->user();

      $predio = null;
      if ($user->c_nomeentid == 'tracoord' && $user->n_codientid != null) {
        $coord = Coordenador::find($user->n_codientid);
        if(!$coord){
          return response()->json(['message' => "Coordenador não encontrado"], 404);
        }
        if($coord->c_nomeentid != 'trapredi'){
          return response()->json(['message' => "Não es coordenador do predio"], 404);
        }
        $predio = Predio::find($coord->n_codientid);
        if(!$predio){
          return response()->json(['message' => "Predio não encontrado"], 404);
        }
        //$apartamentos = $predio->apartamentos;
      }
      return $predio;
  }
  private function getMorador(){
  $user = auth()->user();

    $morador = null;
    if ($user->c_nomeentid == 'tramorad' && $user->n_codientid != null) {
      $morador = Morador::find($user->n_codientid);
    }
    return $morador;
}
  /** */



    /**
    * @OA\Get(
        *     tags={"apartamentos"},
        *     path="/apartamentos",
        *     summary="mostrar apartamentos do predio ",
        *     security={{"bearerAuth": {} }},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="Predio não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getAllByPredio()
    {
        try {
          $predio = $this->getPredio();//Predio::with('apartamentos.moradores')->find($idPredio);
          if(!$predio){
            return response()->json(['message' => 'Não es coordenador do predio'], 404);
          }
                $apartamentos = $predio->apartamentos;
                $data = response()->json(['apartamentos' => $apartamentos], 200);
              //}
                return $data;
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    /**
    * @OA\Post(
        *     tags={"apartamentos"},
        *     path="/apartamentos",
        *     summary="Registrar um apartamento",
        *     security={{"bearerAuth": {} }},
        *     @OA\RequestBody(
        *       required=true,
        *         description="Cria apartamentos para o prédio",
        *         @OA\MediaType(
        *             mediaType="multipart/form-data",
        *             @OA\Schema(ref="#/components/schemas/Apartamento")
        *         )
        *     ),
        *
        *     @OA\Response(response="201", description="apartamento cadastrado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="404", description="apartamento não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function create(Request $req)
    {

        $isValidData = Validator::make($req->all(), [
            'porta'=> 'required|string|max:5',
            'tipo'=>  'required|string|max:5'
        ]);
        try {
            $predio = $this->getPredio();//Predio::find($idPredio);
            if (!$predio)
                return response()->json(['message' => "predio não encontrado"], 404);

            if ($isValidData->fails())
                return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);
                /*criar conta do apartamento*/
                $dataConta = [
                    'n_saldconta'=> 0
                ];
                $conta = Conta::create($dataConta);

            $data = [
              'c_portapart' => $req->porta,
              'c_tipoapart' => $req->tipo,
              'n_andapredi' => $req->andar
            ];

            $data['n_codipredi'] = (int) $predio->n_codipredi;
            $data['n_codiconta'] = (int) $conta->n_codiconta;


            Apartamento::create($data);
            return response()->json(['message' => "Apartamento criada com sucesso!"], 201);;
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $Apartamento = Apartamento::find($id);
            if (!$Apartamento)
                return response()->json(['message' => "Apartamento não encontrado"], 404);
            if(isNull($Apartamento->n_codimorad))
                return response()->json(['message' => "apartamento com morador, não deletado"], 405);
            $Apartamento->delete();
            return response()->json(['message' => "Apartamento deletado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
        /**
    * @OA\Put(
        *     tags={"apartamentos"},
        *     path="/apartamentos/predio/{idPredio}",
        *     summary="Registrar uma apartamento",
        *     security={{"bearerAuth": {} }},
        *     @OA\Parameter(
        *         name="idPredio",
        *         in="path",
        *         description="id do apartamento",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\RequestBody(
        *       required=true,
        *       @OA\JsonContent(
        *          type="object",
        *          @OA\Property(property="c_portapart",type="string",description="porta do apartamento"),
        *          @OA\Property(property="c_tipoapart",type="string",description="tipo do apartamento"),
        *          @OA\Property(property="n_nandapart",type="int",description="andar do apartamento"),
        *       )
        *     ),
        *
        *     @OA\Response(response="201", description="apartamento cadastrado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="404", description="apartamento não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function update(Request $req, $id)
    {
        try {
            $Apartamento = Apartamento::find($id);
            if (!$Apartamento) {
                return response()->json(['message' => "Apartamento não encontrada."], 404);
            }
            $Apartamento->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
/**
    * @OA\Get(
        *     tags={"apartamentos"},
        *     path="/apartamentos/{idApartamento}",
        *     summary="mostrar apartamento",
        *     security={{ "bearerAuth": {}}},
        *     @OA\Parameter(
        *         name="idApartamento",
        *         in="path",
        *         description="id do apartamento",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="apartamento não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getOne($id)
    {
      $cachedApartamento = null;
      try {

            $cachedApartamento = Redis::get('apartamento_' . $id);
            if($cachedApartamento) {

              $apartamento = json_decode($cachedApartamento, FALSE);
              return response()->json([
                  'status_code' => 201,
                  'message' => 'Fetched from redis',
                  'apartamento' => $apartamento,
              ]);
            }else {
              $apartamento = Apartamento::find($id);
              if (!$apartamento)
                  return response()->json(['message' => "Apartamento não encontrado"], 404);
              else{
                  $apartamentoJson = json_encode($apartamento);
                  Redis::set('apartamento_' . $id, $apartamentoJson);
                }
            }

            } catch (QueryException $e) {
              return response()->json(['message' => $e->getMessage()], 500);
          }catch(\Exception $e){

          }finally{

            if(!$cachedApartamento) {
                $apartamento = Apartamento::find($id);
                if (!$apartamento)
                  return response()->json(['message' => "Apartamento não encontrado"], 404);
                return response()->json([
                    'status_code' => 201,
                    'message' => 'Fetched from database',
                    'apartamento' => $apartamento,
              ]);
            }
          }
        }
    /**
    * @OA\Get(
        *     tags={"apartamentos"},
        *     path="/apartamentos/morador",
        *     summary="mostrar apartamento do morador logado",
        *     security={{ "bearerAuth": {}}},
        *     @OA\Response(response="200", description=""),
        *     @OA\Response(response="404", description="apartamento não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getOneApartamento()
    {
        try {
            $morador = $this->getMorador();
            if (!$morador) {
              return response()->json(['message' => "morador não encontrada!"], 404);
            }
            $apartamento = $this->getMorador()->apartamento;
            if (!$apartamento) {
                return response()->json(['message' => "Apartamento não encontrada!"], 404);
            }
            return response()->json(['apartamento' => $apartamento ], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
  }
