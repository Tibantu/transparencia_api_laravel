<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Morador;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ContaController extends Controller
{

/**
    * @OA\Get(
        *     tags={"/contas"},
        *     path="/contas/morador",
        *     summary="listar conta do apartamento do morador logado",
        *     security={{"bearerAuth": {} }},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="Coordenador ou predio nao encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
*/
  public function getConta()
  {
//    return response()->json(['message' => "nao autorizado"], 404);
    //dd("ola");
    try {
      $user = auth()->user();

      $data = response()->json(['message' => "nao autorizado"], 404);
      if ($user->c_nomeentid == 'tramorad' && $user->n_codientid != null) {
          $morador = Morador::find($user->n_codientid);
          if (!$morador) {
              return $data = response()->json(['message' => 'morador nao encontrado'], 404);
          }
      }
      $conta = $morador->apartamento->conta;

          if (!$conta) {
            return $data = response()->json(['message' => 'conta nao encontrado'], 404);
          }
          $data = response()->json(['conta' => $conta], 200);


      return $data;

    } catch (QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
  }
 /**
    * @OA\Get(
        *     tags={"/contas"},
        *     path="/contas/{conta}",
        *     summary="mostrar uma conta",
        *     security={{ "bearerAuth": {}}},
        *     @OA\Parameter(
        *         name="conta",
        *         in="path",
        *         description="id da conta",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="conta não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getOne($id)
    {
        try {
            $Caixa = Conta::find($id);
            if (!$Caixa) {
                return response()->json(['message' => "Conta não encontrada!"], 404);
            }
            return response()->json($Caixa, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
