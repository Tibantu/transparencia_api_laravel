<?php

namespace App\Http\Controllers;

use App\Models\Coordenador;
use App\Models\Despesa;
use App\Models\Divida;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DespesaController extends Controller
{
        /**
    * @OA\Get(
        *     tags={"/despesas"},
        *     path="/despesas",
        *     summary="listar despesas",
        *     security={{"bearerAuth": {} }},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
*/
    public function getAll()
    {
        try {
            return Despesa::all();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

            /**
    * @OA\Get(
        *     tags={"/despesas"},
        *     path="/despesas/coord/{idCoordPredio}",
        *     summary="mostrar um despesa",
        *     security={{ "bearerAuth": {}}},
        *     @OA\Parameter(
        *         name="idCoordPredio",
        *         in="path",
        *         description="id do coordenador do predio",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="despesas não encontrada"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getAllByPredio($idCoordPredio)
    {
        try {
            $coord = Coordenador::find($idCoordPredio);
            if(!$coord)
                return response()->json(['message' => 'coordenador não encontrado'], 404);


            return response()->json([Despesa::where('n_codicoord', '=', $idCoordPredio)->get()],200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

            /**
    * @OA\Post(
        *     tags={"/despesas"},
        *     path="/despesas",
        *     summary="Registrar uma despesa",
        *     security={{"bearerAuth": {} }},
        *     @OA\RequestBody(
        *       required=true,
        *       @OA\JsonContent(
        *          type="object",
        *          @OA\Property(property="c_objedespe",type="string",description="objectivo da despesa"),
        *          @OA\Property(property="n_codicoord",type="int",description="id do coordenador que criou a despesa"),
        *          @OA\Property(property="n_valodespe",type="float",description="valores da deespesa"),
        *          @OA\Property(property="c_fontdespe",type="int",default="caixa" ,description="fonte dos valores"),
        *          @OA\Property(property="d_dasadespe",type="date",description="data do saque dos valores")
        *       )
        *     ),
        *
        *     @OA\Response(response="201", description="despesa cadastrado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function create(Request $req)
    {
        $isValidData = Validator::make($req->all(), [
        'c_objedespe'=> 'required',
        'n_codicoord'=> 'required',
        'n_valodespe'=> 'required',
        'create_at',
        'updated_at',
        'c_objedespe',
        'c_fontdespe'=> 'required',
        'd_dacrdespe',
        'd_dasadespe'=> 'required',
        ]);
        if ($isValidData->fails())
        return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

    try {
        Despesa::create($req->all());
        return response()->json(['message' => "Despesa criada com sucesso!"], 201);;
    } catch (QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}
   /**
    * @OA\Delete(
        *     tags={"/despesas"},
        *     path="/despesas/{despesa}",
        *     summary="apagar uma despesas",
        *       security={{"bearerAuth": {} }},
        *       @OA\Parameter(
        *         name="despesa",
        *         in="path",
        *         description="id do despesa",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="despesa deletada com sucesso!"),
        *     @OA\Response(response="404", description="despesas não encontrada"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function delete($id)
    {
        try {
            $Despesa = Despesa::find($id);
            if (!$Despesa)
                return response()->json(['message' => "Despesa não encontrado"], 404);
            $Despesa->delete();
            return response()->json(['message' => "Despesa deletado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $Despesa = Despesa::find($id);
            if (!$Despesa) {
                return response()->json(['message' => "Despesa não encontrado."], 404);
            }
            $Despesa->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
        /**
    * @OA\Get(
        *     tags={"/despesas"},
        *     path="/despesas/{despesa}",
        *     summary="mostrar um despesa",
        *     security={{ "bearerAuth": {}}},
        *     @OA\Parameter(
        *         name="despesa",
        *         in="path",
        *         description="id do despesa",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="despesa não encontrada"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getOne($id)
    {
        try {
            $Despesa = Despesa::find($id);
            if (!$Despesa) {
                return response()->json(['message' => "Despesa não encontrado!"], 404);
            }
            return response()->json($Despesa, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
