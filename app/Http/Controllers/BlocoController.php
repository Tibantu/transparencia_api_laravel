<?php

namespace App\Http\Controllers;

use App\Models\Bloco;
use App\Models\Caixa;
use App\Models\Centralidade;
use App\Models\Todo;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use \Illuminate\Database\QueryException;

class BlocoController extends Controller
{
/**
 * @OA\Get(
 *     tags={"/blocos"},
 *     path="/blocos/centralidade/{idCentralidade}",
 *     summary="Obter todos os blocos por centralidade",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="idCentralidade",
 *         in="path",
 *         description="ID da centralidade",
 *         required=false,
 *         @OA\Schema(type="int")
 *     ),
 *     @OA\Response(response="200", description="Sucesso"),
 *     @OA\Response(response="404", description="Centralidade não encontrada"),
 *     @OA\Response(response="500", description="Erro no servidor")
 * )
 */
public function getAllByCentr($idCentralidade)
{
    try {
        $centralidade = Centralidade::find($idCentralidade);

        if (!$centralidade) {
            return response()->json(['message' => "Centralidade não encontrada."], 404);
        }

        $blocos = Bloco::where('n_codicentr', $idCentralidade)->get();

        return response()->json(['blocos' => $blocos], 200);
    } catch (QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}



    /**
    * @OA\Post(
        *     tags={"/blocos"},
        *     path="/blocos/centralidade/{centralidade}",
        *     summary="Cadastrar um bloco numa centralidade",
        *     security={{"bearerAuth": {} }},
         *     @OA\Parameter(
        *         name="centralidade",
        *         in="path",
        *         description="id da centralidade",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\RequestBody(
        *       required=true,
        *       @OA\JsonContent(
        *          type="object",
        *          @OA\Property(property="c_descbloco",type="string",description="denominação do bloco"),
        *          @OA\Property(property="c_ruabloco",type="string",description="ruas que delimitam o bloco")
        *       )
        *     ),
        *
        *     @OA\Response(response="201", description="bloco cadastrado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function create(Request $req, $idCentralidade)
    {

        $isValidData = Validator::make($req->all(), [
            "c_descbloco" => 'required|string|max:50',
            "n_nblocentr" => 'integer',
            "n_codicoord" => 'integer',
            "c_ruabloco" => 'string',
        ]);
        $centralidadde = Centralidade::find($idCentralidade);
        if (!$centralidadde)
            return response()->json(['message' => "Centralidade não encontrada!"], 404);

        if ($isValidData->fails())
            return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

        /*Criar um caixa para o bloco*/
        $dataCaixa = [
            'c_nomeentid'=>'trabloco'
        ];
        $caixa = Caixa::create($dataCaixa);


        $data = $req->all();

        $data['n_codicentr'] = (int) $idCentralidade;
        $data['n_codicaixa'] = (int) $caixa->n_codicaixa;

        try {
            $bloco = Bloco::create($data);
            $dataCaixa['n_codientid'] = (int) $bloco->n_codibloco;
            $caixa->update($dataCaixa);
            return response()->json(['message' => "Bloco criada com sucesso!"], 201);;
        } catch (QueryException $e) {
            /*deletar a caixa porque o bloco nao foi criado */
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
        /**
    * @OA\Delete(
        *     tags={"/blocos"},
        *     path="/blocos/{bloco}",
        *     summary="apagar um bloco",
        *       security={{"bearerAuth": {} }},
        *       @OA\Parameter(
        *         name="bloco",
        *         in="path",
        *         description="id do bloco",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="bloco deletado com sucesso!"),
        *     @OA\Response(response="404", description="bloco não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function delete($id)
    {

    //APENAS O SISTEMA PODE USAR ESTA ROTA
    return response()->json(['message' => 'APENAS O SISTEMA PODE USAR ESTA ROTA'], 404);
/*        try {
            $bloco = Bloco::find($id);
            if (!$bloco)
                return response()->json(['message' => "bloco não encontrado"], 404);
            $bloco->delete();
            return response()->json(['message' => "bloco deletado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }*/
    }
        /**
    * @OA\Put(
        *     tags={"/blocos"},
        *     path="/blocos/{bloco}",
        *     summary="Atualizar um bloco de uma centralidade",
        *     security={{"bearerAuth": {} }},
         *     @OA\Parameter(
        *         name="bloco",
        *         in="path",
        *         description="id do bloco",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\RequestBody(
        *       required=true,
        *       @OA\JsonContent(
        *          type="object",
        *          @OA\Property(property="c_descbloco",type="string",description="denominação do bloco"),
        *          @OA\Property(property="c_ruabloco",type="string",description="ruas que delimitam o bloco")
        *       )
        *     ),
        *
        *     @OA\Response(response="201", description="bloco cadastrado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function update(Request $req, $id)
    {
        try {
            $Bloco = Bloco::find($id);
            if (!$Bloco) {
                return response()->json(['message' => "Bloco não encontrada."]);
            }
            $Bloco->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
     /**
    * @OA\Get(
        *     tags={"/blocos"},
        *     path="/blocos/{bloco}",
        *     summary="mostrar um bloco",
        *     security={{ "bearerAuth": {}}},
        *     @OA\Parameter(
        *         name="bloco",
        *         in="path",
        *         description="id do bloco",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="blocos não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getOne($id)
    {
        try {
            $bloco = Bloco::find($id);
            if (!$bloco) {
                return response()->json(['message' => "Bloco não encontrada!"], 404);
            }
            return response()->json($bloco, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
