<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Bloco;
use App\Models\Caixa;
use App\Models\Centralidade;
use App\Models\Coordenador;
use App\Models\Morador;
use App\Models\Predio;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{

/**
 * @OA\Post(
 *     path="/api/create",
 *     tags={"Users"},
 *     summary="Cria um novo usuário",
 *     description="Cria um novo usuário com base nos dados fornecidos",
 *     operationId="createUser",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Dados do usuário a serem criados",
 *         @OA\JsonContent(
 *             required={"login", "email", "password", "tipo", "codiApartamento"},
 *             @OA\Property(property="login", type="string", example="example_user", description="Login do usuário"),
 *             @OA\Property(property="email", type="string", format="email", example="example@example.com", description="Email do usuário"),
 *             @OA\Property(property="password", type="string", example="password123", description="Senha do usuário"),
 *             @OA\Property(property="tipo", type="string", enum={"tracoord", "tramorad"}, description="Tipo de usuário (coordenador ou morador)"),
 *             @OA\Property(property="remember_token", type="string", description="Token de lembrete opcional"),
 *             @OA\Property(property="tipoDeEntidadeACoordenar", type="string", enum={"trapredi", "trabloco"}, description="Tipo de entidade a ser coordenada (opcional)"),
 *             @OA\Property(property="codiEntidade", type="integer", description="ID da entidade (opcional)"),
 *             @OA\Property(property="codiApartamento", type="integer", description="ID do apartamento (obrigatório para tramorad)")
 *         )
 *     ),
 *     @OA\Response(response="201", description="Usuário criado com sucesso"),
 *     @OA\Response(response="412", description="Erro ao validar os dados"),
 *     @OA\Response(response="404", description="Apartamento não encontrado"),
 *     @OA\Response(response="500", description="Erro interno do servidor")
 * )
 */





    public function create(Request $req)
    {
        $isValidData = Validator::make($req->all(), [
            //dados do usuario
            "login" => 'required|string',
            "email" => 'required|string',
            "password" => 'required|string',
            //dados do coord
            "nome" => 'required|string',
            "apelido" => 'required|string',
            //dados do predio
            "descricao_do_bloco" => 'string',
            //"id_bloco" => 'string', // vai no ; se for vasio criar um bloco na centralidade com a descricao_do_bloco
            //dados do predio
            "descricao_do_predio" => 'required|string',
            "entrada_do_predio" => 'required|string',
            "id_centralidade" => 'required|int'
        ]);

        if ($isValidData->fails()) {
            return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 412);
        }

        try {
/* *///criar um bloco, apenas se id_bloco for vasio

          $bloco = [];
          //dd($req->id_bloco);
          if(isNull($req->id_bloco) && $req->descricao_do_bloco != "")
          {
                $centralidadde = Centralidade::find($req->id_centralidade);
                if (!$centralidadde)
                    return response()->json(['message' => "Centralidade não encontrada!"], 404);

              /*Criar um caixa para o bloco*/
                $dataCaixa = [
                  'c_nomeentid'=>'trabloco'
                ];

                $caixa = Caixa::create($dataCaixa);

                    //criar o bloco
                $dadosBloco = [
                    'c_descbloco' => $req->descricao_do_bloco,
                    'n_codicentr' => $req->id_centralidade,
                    'n_codicaixa' => $caixa->n_codicaixa
                ];

                $bloco = Bloco::create($dadosBloco);
                $dataCaixa['n_codientid'] = (int) $bloco->n_codibloco;
                $caixa->update($dataCaixa);
          }else{
            $bloco = Bloco::find($req->id_bloco);
          }

/* */

/* *///criar um predio

        $bloco = Bloco::find($bloco->n_codibloco);
        if (!$bloco)
            return response()->json(['message' => "Bloco não encontrado!"], 404);

        /*Criar um caixa para o predio*/
        $dataCaixaPredi = [
          'c_nomeentid'=>'trapredi'
        ];
        $caixaPredi = Caixa::create($dataCaixaPredi);
        if(!$caixaPredi){
        return response()->json(['message' => "Erro ao criar predio"], 412);
        }

        $dadosPredi = [
          'c_entrpredi' => $req->entrada_do_predio,
          'c_descpredi' => $req->descricao_do_predio,
          'n_codicaixa' => $caixaPredi->n_codicaixa,
          'n_codibloco' => $bloco->n_codibloco
          //d3ado1s do predi
      ];


        $predio = Predio::create($dadosPredi);
        $caixaPredi['n_codientid'] = (int) $predio->n_codipredi;
        $caixaPredi->update($dataCaixa);

/* */

/* *///criar um coord

            $dadosCoord = [
                'c_nomecoord' => $req->nome,
                'c_nomeentid' => 'trapredi',
                'n_codientid' => $predio->n_codipredi,
                'c_apelcoord' => $req->apelido
            ];
          $coord = Coordenador::create($dadosCoord);

          if(!$coord)
            return response()->json(['message' => "Erro ao criar o coordenador"], 412);

          $dadosPredi['n_codicoord'] = (int) $coord->n_codicoord;
          $predio->update($dadosPredi);

/* */

/* *///criar um usuario
            $dadosUser = [
              'c_logiusuar' => $req->login,
              'c_senhusuar' => Hash::make($req->password),
              'n_codientid' => $coord->n_codicoord,
              'c_nomeentid' => 'tracoord',
              'c_emaiusuar' => $req->email
          ];

            //criar usuario
            $user = User::create($dadosUser);

            return response()->json(['message' => "usuario criado com sucesso!"], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
