<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\Coordenador;
use App\Models\Predio;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CaixaController extends Controller
{

/**
    * @OA\Get(
        *     tags={"/caixas"},
        *     path="/caixas/coord",
        *     summary="listar caixas do predio do cooord logado",
        *     security={{"bearerAuth": {} }},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="Coordenador ou predio nao encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
*/
  public function getCaixa()
  {
//    return response()->json(['message' => "nao autorizado"], 404);
    //dd("ola");
    try {
      $user = auth()->user();

      $data = response()->json(['message' => "nao autorizado"], 404);
      $predio = [];
      if ($user->c_nomeentid == 'tracoord' && $user->n_codientid != null) {
          $coordenador = Coordenador::find($user->n_codientid);
          if (!$coordenador) {
              return $data = response()->json(['message' => 'Coordenador nao encontrado'], 404);
          }else{
              if($coordenador->c_nomeentid == 'trapredi')
              {
                  $predio = Predio::find($coordenador->n_codientid);
              }else
              {
              return $data = response()->json(['message' => 'Coordenador nao encontrado'], 404);
            }
          }

          if (!$predio) {
            return $data = response()->json(['message' => 'predio nao encontrado'], 404);
          }
          $caixa = $predio->caixa;
          $data = response()->json(['caixa' => $caixa], 200);
     }

      return $data;

    } catch (QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
  }

    public function delete($id)
    {

      try {
        $user = auth()->user();
        $data = response()->json(['message' => "nao autorizado"], 404);
        $predio = [];
        if ($user->c_nomeentid == 'tracoord' && $user->n_codientid != null) {
            $coordenador = Coordenador::find($user->n_codientid);
            if (!$coordenador) {
                return $data = response()->json(['message' => 'Coordenador nao encontrado'], 404);
            }else{
              $predio = Predio::where('n_codicoord', $user->n_codientid);
            }

            if (!$predio) {
              return $data = response()->json(['message' => 'predio nao encontrado'], 404);
            }

            $caixa = $predio->caixa;
            if(!$caixa){
              return $data = response()->json(['message' => 'caixa nao encontrado'], 404);
            }
            $caixa->delete();
            $data = response()->json(['message' => "Caixa deletada com sucesso!"], 200);
        }

        return $data;

      } catch (QueryException $e) {
          return response()->json(['message' => $e->getMessage()], 500);
      }

   }
    /**
    * @OA\Get(
        *     tags={"/caixas"},
        *     path="/caixas/{caixa}",
        *     summary="mostrar um Taxa",
        *     security={{ "bearerAuth": {}}},
        *     @OA\Parameter(
        *         name="caixa",
        *         in="path",
        *         description="id do caixa",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="caixa não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getOne($id)
    {
        try {
            $Caixa = Caixa::find($id);
            if (!$Caixa) {
                return response()->json(['message' => "Caixa não encontrada!"], 404);
            }
            return response()->json($Caixa, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
