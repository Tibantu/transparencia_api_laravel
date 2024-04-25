<?php

namespace App\Http\Controllers;

use App\Models\Morador;
use App\Models\Notificacao;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
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
        *     tags={"/notificacoes"},
        *     path="/notificacoes",
        *     summary="listar notificacoes de um apartamento",
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
                dd($notificacoes);


            if(!$notificacoes){
              return response()->json(['message' => 'notificacoes nao encontradas'], 404);
            }
            return response()->json(['funcionarios' => $notificacoes], 200);
          } catch (QueryException $e) {
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
