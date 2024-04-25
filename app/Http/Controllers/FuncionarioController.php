<?php

namespace App\Http\Controllers;

use App\Models\Coordenador;
use App\Models\Funcionario;
use App\Models\Morador;
use App\Models\Predio;
use App\Models\Telefone;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class FuncionarioController extends Controller
{
  /**METODOTOS PRIVADO*/

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
        *     tags={"/funcionarios"},
        *     path="/funcionarios",
        *     summary="listar funcionarios de um predio, morador ou cordenadores do predio tenhem acesso a esta rota",
        *     security={{ "bearerAuth": {}}},
        *     @OA\Response(response="200", description=""),
        *     @OA\Response(response="404", description="predio ou funcionarios não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getAllByFuncionarios()
      {
          try {
            $predio = $this->getPredio();
            $funcionarios = null;
            if($predio)
            {
              $funcionarios = $predio->funcionarios;
            }else{
                $morador = $this->getMorador();
                if(!$morador){
                  return response()->json(['message' => 'morador nao encontrado'], 404);
                }
                $predio = $morador->apartamento->predio;
                if(!$predio){
                  return response()->json(['message' => 'predio nao encontrado'], 404);
                }
                $funcionarios =  $predio->funcionarios;
            }

            if(!$funcionarios){
              return response()->json(['message' => 'funcionarios nao encontrados'], 404);
            }
            return response()->json(['funcionarios' => $funcionarios], 200);
          } catch (QueryException $e) {
              return response()->json(['message' => $e->getMessage()], 500);
          }
      }

      /**
    * @OA\Post(
        *     tags={"/funcionarios"},
        *     path="/funcionarios",
        *     summary="Registrar funcionarios",
        *     security={{"bearerAuth": {} }},
        *     @OA\RequestBody(
        *       required=true,
        *       @OA\JsonContent(
        *          type="object",
        *          required={"login", "funcao", "salario", "apelido", "nome"},
        *          @OA\Property(property="nome",type="string",description="nome do funcionario"),
        *          @OA\Property(property="apelido",type="string",description="ultimo nome do funcionario"),
        *          @OA\Property(property="salario",type="string",description="valor do salario mensal em AKZ do funcionario"),
        *            @OA\Property(property="funcao", type="string", example="example_user", description="funcao do funcionario"),
        *            @OA\Property(property="email", type="string", format="email", example="example@example.com", description="Email do funcionario"),
        *            @OA\Property(property="telefone", type="string", example="92222956", description="telefone do funcionario"),
        *            @OA\Property(property="telefone_alternativo", type="string", example="92222956", description="telefone alternativo do funcionario")
        *       )
        *     ),
        *
        *     @OA\Response(response="201", description="morador cadastrado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="404", description="apartamento não encontrado"),
        *     @OA\Response(response="405", description="apartamento oucupado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function create(Request $req)
    {
      $isValidData = Validator::make($req->all(), [
          //dados do funcionario
          "nome" => 'required|string',
            "apelido" => 'required|string',
            "salario" => 'required|integer',
            "funcao" => 'required|string',
           //dados de contacto
            "telefone" => 'required|string',
            "telefone_alternativo" => 'string',
            "email" => 'string'
        ]);

        if ($isValidData->fails())
            return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 412);

            try {
              $predio = $this->getPredio();
              if(!$predio){
                return response()->json(['message' => 'Não es coordenador do predio'], 404);
              }
              $dadosFuncionario = [
                  'c_nomefunci' => $req->nome,
                  'c_apelfunci' => $req->apelido,
                  'n_salafunci' => $req->salario,
                  'c_actifunci' => $req->funcao,
                  'c_nomeentid' => 'trapredi',
                  'n_codientid' => $predio->n_codipredi
              ];

              $funcionario = Funcionario::create($dadosFuncionario);
              if(!$funcionario){
                return response()->json(['message' => 'erro ao criar funcionario'], 404);
              }
              $dadosContacto = [
                'c_numetelef' => $req->telefone,
                'c_numatelef' => $req->telefone_alternativo,
                'c_emaitelef' => $req->email,
                'c_nomeentid' => 'trafunci',
                'n_codientid' => $funcionario->n_codifunci
            ];
            $telefone = Telefone::create($dadosContacto);

            if(!$telefone)
                return response()->json(['message' => "Contacto do funcionario nao registrado"], 404);

            return response()->json(['message' => "funcionario criado com sucesso!"], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
  }

}
