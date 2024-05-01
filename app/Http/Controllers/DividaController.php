<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Divida;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DividaController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"dividas"},
     *     path="/dividas",
     *     summary="listar dividas do morador logado",
     *     security={{"bearerAuth": {} }},
     *     @OA\Response(response="200", description="sucesso"),
     *     @OA\Response(response="500", description="Erro no servidor")
     * )
     */
    public function getAll()
    {
//PARA O MORADOR
        try {
              $user = auth()->user();
              $data = response()->json(['message' => 'nao autorizado'], 200);

              if ($user->c_nomeentid == 'tramorad' && $user->n_codientid != null) {
                  $apartamento = Apartamento::where('n_codimorad', $user->n_codientid)->first();
                  if (!$apartamento)
                      return response()->json(['message' => "apartamento não encontrada!"], 404);
                  $dividas = $apartamento->dividas()->paginate(5);
                  if (!$dividas)
                    return response()->json(['message' => "dividas não encontrada!"], 404);

                  $data = response()->json(['dividas' => $dividas ], 200);
              }

              return $data;

         } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

   /**
    * @OA\Get(
        *     tags={"dividas"},
        *     path="/dividas/apartamento/{idapartamento}",
        *     summary="LISTAR DIVIDAS PARA O COORDENADOR",
        *     security={{ "bearerAuth": {}}},
        *     @OA\Parameter(
        *         name="idapartamento",
        *         in="path",
        *         description="id do apartamento",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="caixa não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getAllByApartamento($idApartamento)
    {
//PARA O COORDENADOR
      try {
            $apartamento = Apartamento::find($idApartamento);

            if (!$apartamento)
                return response()->json(['message' => "Apartamento não encontrado!"], 404);
            $dividas = $apartamento->dividas()->paginate(5);
                //echo "a3p3art: ".$apartamento;
            if (!$dividas)
                return response()->json(['message' => "dividas não encontrada!"], 404);

            return response()->json(['dividas' => $dividas], 200);
      } catch (QueryException $e) {
          return response()->json(['message' => $e->getMessage()], 500);
      }
  }

    public function delete($id)
    {
        try {
            $Divida = Divida::find($id);
            if (!$Divida)
                return response()->json(['message' => "Divida não encontrada"], 404);
            $Divida->delete();
            return response()->json(['message' => "Divida deletada com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $Divida = Divida::find($id);
            if (!$Divida) {
                return response()->json(['message' => "Divida não encontrada."], 404);
            }
            $Divida->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    /**
     * @OA\Get(
     *     tags={"dividas"},
     *     path="/dividas/{divida}",
     *     summary="mostrar divida",
     *     security={{ "bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="divida",
     *         in="path",
     *         description="id do divida",
     *         required=false,
     *         @OA\Schema(type="int")
     *     ),
     *     @OA\Response(response="200", description="sucesso"),
     *     @OA\Response(response="404", description="divida não encontrada"),
     *     @OA\Response(response="500", description="Erro no servidor"),
     * )
     */
    public function getOne($id)
    {
        try {
            $Divida = Divida::find($id);
            if (!$Divida) {
                return response()->json(['message' => "Divida não encontrada!"], 404);
            }
            return response()->json($Divida, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
