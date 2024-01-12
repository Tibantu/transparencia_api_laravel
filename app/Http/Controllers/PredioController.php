<?php

namespace App\Http\Controllers;

use App\Models\Bloco;
use App\Models\Caixa;
use App\Models\Centralidade;
use App\Models\Predio;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use \Illuminate\Database\QueryException;

class PredioController extends Controller
{

/**
    * @OA\Get(
        *     tags={"/predios"},
        *     path="/api/predios",
        *     summary="listar predios",
        *     security={{"bearerAuth": {} }},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
*/
    public function getAll()
    {
        try {
            return response() ->json(['message' => Predio::all()], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getAllByBloco($idBloco)
    {
        try {
            Predio::findOrFail($idBloco);

            return Predio::where('n_codibloco', '=', $idBloco)->get();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
        /**
    * @OA\Post(
        *     tags={"/predios"},
        *     path="/api/predios/bloco/{bloco}",
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
        *          @OA\Property(property="n_codicoord",type="int",description="id do Coordenador do prédio")
        *       )
        *     ),
        *     
        *     @OA\Response(response="201", description="bloco cadastrado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */

    public function create(Request $req, $idBloco)
    {

        $isValidData = Validator::make($req->all(), [
            "c_descpredi" => 'required|string|max:5',
            "c_entrpredi" => 'required|string|max:2',
            "n_napapredi" => 'integer',
            "n_napopredi" => 'integer',
            "d_dacrpredi" => 'string',
            "n_codicaixa" => 'integer',
            'n_codicoord' => 'integer'
        ]);
        try {
            $bloco = Bloco::find($idBloco);
            if (!$bloco)
                return response()->json(['message' => "Bloco não encontrada!"], 404);

            if ($isValidData->fails())
                return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);


            /*Criar um caixa para o bloco*/
            $dataCaixa = [
                'c_nomeentid'=>'trapredi'
            ];
            $caixa = Caixa::create($dataCaixa);

            $data = $req->all();

            $data['n_codibloco'] = (int) $idBloco;
            $data['n_codicaixa'] = (int) $caixa->n_codicaixa;


            $predio = Predio::create($data);
            $dataCaixa['n_codientid'] = (int) $predio->n_codipredi;
            $caixa->update($dataCaixa);
            return response()->json(['message' => "Predio criada com sucesso!"], 201);;
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
            /**
    * @OA\Delete(
        *     tags={"/predios"},
        *     path="/api/predios/{predio}",
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
        try {
            $predio = Predio::find($id);
            if (!$predio)
                return response()->json(['message' => "Predio não encontrado"], 404);
            $predio->delete();
            return response()->json(['message' => "Predio deletado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Erro no servidor" . $e->getMessage()], 500);
        }
    }
            /**
    * @OA\Put(
        *     tags={"/predios"},
        *     path="/api/predios/{predio}",
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
        *     tags={"/predios"},
        *     path="/api/predios/{predio}",
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
        try {
            $predio = Predio::find($id);
            if (!$predio) {
                return response()->json(['message' => "Predio não encontrado"], 404);
            }
            return response()->json($predio, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
