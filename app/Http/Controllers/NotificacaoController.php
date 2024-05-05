<?php

namespace App\Http\Controllers;

use App\Models\Coordenador;
use App\Models\Morador;
use App\Models\Notificacao;
use App\Models\Predio;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificacaoController extends Controller
{
  /**METODOTOS PRIVADO*/

  private function validaIDs($partamentosIds, $apartamentos) {
    // Itera sobre os IDs dos apartamentos
    foreach ($partamentosIds as $apartamentoId) {
        // Verifica se há pelo menos um apartamento com o ID correspondente e que esteja oucupado
        foreach ($apartamentos as $apartamento) {
            if ($apartamento->n_codiapart == $apartamentoId && $apartamento->n_codimorad != null) {
                return true; // Retorna true se encontrar uma correspondência
            }
        }
    }

    // Retorna false se nenhum apartamento correspondente for encontrado
    return false;
}

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

    /**
    * @OA\Get(
        *     tags={"notificacoes"},
        *     path="/notificacoes",
        *     summary="listar notificacoes de um apartamento, apenas para morador",
        *     security={{ "bearerAuth": {}}},
        *     @OA\Response(response="200", description=""),
        *     @OA\Response(response="404", description="predio ou funcionarios não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getAllByNotificacoes()
      {
          try {

            $notificacoes = null;
            $morador = $this->getMorador();
                if(!$morador){
                  return response()->json(['message' => 'morador nao encontrado'], 404);
                }
                $apartamento = $morador->apartamento;
                if(!$apartamento){
                  return response()->json(['message' => 'apartamento nao encontrado'], 404);
                }
                $notificacoes =  $apartamento->notificacoes;
               // dd($notificacoes);


            if(!$notificacoes){
              return response()->json(['message' => 'notificacoes nao encontradas'], 404);
            }
            return response()->json(['funcionarios' => $notificacoes], 200);
          } catch (QueryException $e) {
              return response()->json(['message' => $e->getMessage()], 500);
          }
      }


            /**
    * @OA\Post(
        *     tags={"notificacoes"},
        *     path="/notificacoes/{apartamento_ids}",
        *     summary="Registrar notificacoes apanas para coordenador",
        *     security={{"bearerAuth": {} }},
        *     @OA\Parameter(
        *         name="apartamento_ids",
        *         in="path",
        *         description="lista dos IDs dos apartamentos pra serem notificado. Separados por virgula",
        *         required=true,
        *         @OA\Schema(
        *             type="string"
        *         )
        *     ),
        *     @OA\RequestBody(
        *       required=true,
        *         description="Cria notificação para apartamentos oucupados",
        *         @OA\MediaType(
        *             mediaType="multipart/form-data",
        *             @OA\Schema(ref="#/components/schemas/Notificacao")
        *         )
        *     ),
        *
        *     @OA\Response(response="201", description="morador cadastrado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="404", description="apartamento não encontrado"),
        *     @OA\Response(response="405", description="apartamento oucupado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function create(Request $req, $apartamento_ids)
    {
      $isValidData = Validator::make($req->all(), [
        'mensagem' => 'required|string',
        'tipo' => 'required|string'
        ]);

        if ($isValidData->fails())
            return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 412);

        try {
              $predio = $this->getPredio();
              if(!$predio){
                return response()->json(['message' => 'Não es coordenador do predio'], 404);
              }

              $apartamentosIds = explode(',',$apartamento_ids); // IDs dos apartamentos aos quais deseja associar a notificação
              //dd($apartamento_ids);
              if(!$this->validaIDs($apartamentosIds, $predio->apartamentos))
                  return response()->json(['message' => "apartamentos não identificado ou não ocupado!"], 404);
              $notificacao = Notificacao::create([
                    'c_descnotif' => $req->mensagem,
                    'c_tiponotif' => $req->tipo
                ]);
              $notificacao->apartamentos()->attach($apartamentosIds);

            return response()->json(['message' => "notificacao criada com sucesso!"], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
  }

}



/*
//Para registrar uma notificação para vários apartamentos no Laravel,
// Criar uma nova notificação
$notificacao = Notificacao::create([
    'c_descnotif' => 'Descrição da notificação',
    'c_tiponotif' => 'Tipo da notificação',
    // outras colunas necessárias
]);

// Associar a notificação aos apartamentos
$apartamentosIds = [1, 2, 3]; // IDs dos apartamentos aos quais você deseja associar a notificação

$notificacao->apartamentos()->attach($apartamentosIds);

// Ou você pode associar a notificação a um apartamento específico
// $apartamentoId = 1; // ID do apartamento
// $notificacao->apartamentos()->attach($apartamentoId);

// Ou você pode usar o método sync para substituir todas as associações existentes pelos IDs fornecidos
// $notificacao->apartamentos()->sync($apartamentosIds);
*/
