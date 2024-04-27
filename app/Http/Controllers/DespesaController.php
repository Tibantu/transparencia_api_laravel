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
        *     tags={"despesas"},
        *     path="/despesas",
        *     summary="listar despesas",
        *     security={{"bearerAuth": {} }},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="Coordenador nao encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
*/
    public function getAll()
    {

      try {
        $user = auth()->user();
        $data = response()->json(['message' => "nao autorizado"], 404);

        if ($user->c_nomeentid == 'tracoord' && $user->n_codientid != null) {
            $coordenador = Coordenador::find($user->n_codientid);
            if (!$coordenador) {
                return $data = response()->json(['message' => 'Coordenador nao encontrado'], 404);
            }

            $data = response()->json(['despesas' => $coordenador->despesas], 200);
        }

        return $data;

      } catch (QueryException $e) {
          return response()->json(['message' => $e->getMessage()], 500);
      }
    }

            /**
    * @OA\Post(
        *     tags={"despesas"},
        *     path="/despesas",
        *     summary="Registrar uma despesa",
        *     security={{"bearerAuth": {} }},
        *     @OA\RequestBody(
        *       required=true,
        *         description="Cria despesa para justificar algum gasto",
        *         @OA\MediaType(
        *             mediaType="multipart/form-data",
        *             @OA\Schema(ref="#/components/schemas/Despesa")
        *         )
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
        'objectivo'=> 'required|string',
        'valor'=> 'required|numeric',
        'fonte'=> 'required',
        'data'=> 'required|date',
        ]);
        if ($isValidData->fails())
        return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

    try {
        $user = auth()->user();

        if ($user->c_nomeentid == 'tracoord' && $user->n_codientid != null) {
            $coordenador = Coordenador::find($user->n_codientid);
            if (!$coordenador) {
                return $data = response()->json(['message' => 'Coordenador nao encontrado'], 404);
            }

            Despesa::create([
              'c_objedespe'=> $req->objectivo,
              'n_codicoord'=> $coordenador->n_codicoord,
              'n_valodespe'=> $req->valor,
              'c_fontdespe'=> $req->fonte,
              'd_dasadespe'=> $req->data,
              ]);
        }

        return response()->json(['message' => "Despesa criada com sucesso!"], 201);;
    } catch (QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}
   /**
    * @OA\Delete(
        *     tags={"despesas"},
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
        *     tags={"despesas"},
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
