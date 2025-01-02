<?php

namespace App\Http\Controllers;

use App\Models\Bloco;
use App\Models\Caixa;
use App\Models\Centralidade;
use App\Models\Coordenador;
use App\Models\Predio;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use \Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redis;

class PredioController extends Controller
{

/**
    * @OA\Get(
        *     tags={"predios"},
        *     path="/predios/bloco/{idBloco}",
        *     summary="listar predios de um bloco",
        *     security={{"bearerAuth": {} }},
        *     @OA\Parameter(
        *         name="idBloco",
        *         in="path",
        *         description="id do Bloco",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
         *     @OA\Response(response="404", description="Bloco não encontrado."),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
*/
  public function getAllByBloco($idBloco)
    {
        try {
            $bloco = Bloco::find($idBloco);
            if (!$bloco) {
            return response()->json(['message' => "Bloco não encontrado."], 404);
      }
            return response()->json(['predios' => Predio::where('n_codibloco', '=', $idBloco)->get()], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
        /**
    * @OA\Post(
        *     tags={"predios"},
        *     path="/predios/bloco/{bloco}",
        *     summary="Cadastrar um predios numa bloco",
        *     security={{"bearerAuth": {} }},
         *     @OA\Parameter(
        *         name="bloco",
        *         in="path",
        *         description="id da bloco",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\RequestBody(
        *       required=true,
        *       @OA\JsonContent(
        *          type="object",
        *          @OA\Property(property="c_descpredi",type="string",description="denominação do Prédio"),
        *          @OA\Property(property="c_entrpredi",type="string",description="Entrada do prédio"),
        *          @OA\Property(property="c_nomecoord",type="string",description="nome do coordenador do prédio"),
        *          @OA\Property(property="c_apelcoord",type="string",description="ultimo nome do coordenador do prédio")
        *       )
        *     ),
        *
        *     @OA\Response(response="200", description="bloco cadastrado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados || Erro ao criar predio"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */

    public function create(Request $req, $idBloco)
    {

    //APENAS O SISTEMA PODE USAR ESTA ROTA
    return response()->json(['message' => 'APENAS O SISTEMA PODE USAR ESTA ROTA'], 404);
      /*
        $isValidData = Validator::make($req->all(), [
           //dados do predio
            "c_descpredi" => 'required|string|max:10',
            "c_entrpredi" => 'required|string|max:2',
            //dados do coordenador do predio
            "c_nomecoord" => 'required|string|max:20',
            'c_apelcoord' => 'required|string|max:20'
        ]);

        try {
            $bloco = Bloco::find($idBloco);
            if (!$bloco)
                return response()->json(['message' => "Bloco não encontrado!"], 404);

            if ($isValidData->fails())
                return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 412);


            //Criar um caixa para o predio
            $dataCaixa = [
                'c_nomeentid'=>'trapredi'
            ];
            $caixa = Caixa::create($dataCaixa);

            //Criar o coordenador do predio
            $dataCoord = [
                'c_nomeentid'=>'trapredi',
                'c_nomecoord'=>$req->c_nomecoord,
                'c_apelcoord'=>$req->c_apelcoord,
            ];
            $coord = Coordenador::create($dataCoord);

            if(!$caixa || !$coord){
              return response()->json(['message' => "Erro ao criar predio"], 412);
            }
            $data = $req->all();

            $data['n_codibloco'] = (int) $idBloco;
            $data['n_codicaixa'] = (int) $caixa->n_codicaixa;
            $data['n_codicoord'] = (int) $coord->n_codicoord;


            $predio = Predio::create($data);
            $dataCaixa['n_codientid'] = (int) $predio->n_codipredi;
            $caixa->update($dataCaixa);

            $dataCoord['n_codientid'] = (int) $predio->n_codipredi;
            $coord->update($dataCoord);

            return response()->json(['message' => "Predio criado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }*/
    }
            /**
    * @OA\Delete(
        *     tags={"predios"},
        *     path="/predios/{predio}",
        *     summary="apagar um predio",
        *       security={{"bearerAuth": {} }},
        *       @OA\Parameter(
        *         name="predio",
        *         in="path",
        *         description="id do predio",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="predio deletado com sucesso!"),
        *     @OA\Response(response="404", description="predio não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function delete($id)
    {
      //APENAS O SISTEMA PODE USAR ESTA ROTA
      return response()->json(['message' => 'APENAS O SISTEMA PODE USAR ESTA ROTA'], 404);
/*        try {
            $predio = Predio::find($id);
            if (!$predio)
                return response()->json(['message' => "Predio não encontrado"], 404);
            $predio->delete();
            return response()->json(['message' => "Predio deletado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Erro no servidor" . $e->getMessage()], 500);
        }*/
    }
            /**
    * @OA\Put(
        *     tags={"predios"},
        *     path="/predios/{predio}",
        *     summary="atualizar os dados de um predios",
        *     security={{"bearerAuth": {} }},
         *     @OA\Parameter(
        *         name="predio",
        *         in="path",
        *         description="id da bloco",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\RequestBody(
        *       required=true,
        *       @OA\JsonContent(
        *          type="object",
        *          @OA\Property(property="c_descpredi",type="string",description="denominação do Prédio"),
        *          @OA\Property(property="c_entrpredi",type="string",description="Entrada do prédio"),
        *       )
        *     ),
        *
        *     @OA\Response(response="201", description="predio Atualizado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function update(Request $req, $id)
    {
        try {
            $predio = Predio::find($id);
            if (!$predio) {
                return response()->json(['message' => "predio não encontrada."], 404);
            }
            $predio->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
/**
    * @OA\Get(
        *     tags={"predios"},
        *     path="/predios/{predio}",
        *     summary="mostrar um predio",
        *     security={{ "bearerAuth": {}}},
        *     @OA\Parameter(
        *         name="predio",
        *         in="path",
        *         description="id do predio",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="predio não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getOne($id)
    {
        $cachedPredio = null;
        try {

          $cachedPredio = Redis::get('predio_' . $id);
          if($cachedPredio) {

            $predio = json_decode($cachedPredio, FALSE);
            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from redis',
                'predio' => $predio,
            ]);
          }else {
            $predio = Predio::find($id);
            if (!$predio)
                return response()->json(['message' => "Predio não encontrado"], 404);
            else{
                $predioJson = json_encode($predio);
                Redis::set('predio_' . $id, $predioJson);
              }
          }

        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }catch(\Exception $e){

        }finally{
          if(!$cachedPredio) {
              $predio = Predio::find($id);
              if (!$predio)
                return response()->json(['message' => "Predio não encontrado"], 404);
              return response()->json([
                  'status_code' => 201,
                  'message' => 'Fetched from database',
                  'predio' => $predio,
            ]);
          }
        }
    }
}
