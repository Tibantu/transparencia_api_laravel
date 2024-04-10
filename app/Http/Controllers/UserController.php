<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Coordenador;
use App\Models\Morador;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            "login" => 'required|string',
            "email" => 'required|string',
            "password" => 'required|string',
            "tipo" => 'required|string|in:tracoord,tramorad', // recebe o tipo de usuario(coordenador, morador ou ...)
            "remember_token" => 'string',
            "tipoDeEntidadeACoordenar" => 'string|in:trapredi,trabloco',
            "codiEntidade" => 'integer',
            "codiApartamento" => 'required|integer'
        ]);

        if ($isValidData->fails()) {
            return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 412);
        }

        try {
            //criar um morador if tipo == tramord
            $dadosEntidade = [];
            $idEntidade = 0;
            switch ($req->tipo) {
                case 'tramorad':
                    $dadosEntidade = ['c_nomemorad' => $req->login];
                    $idEntidade = Morador::insertGetId($dadosEntidade);
                    //*codiApartamento alterar
                    $apartamento = Apartamento::find($req->codiApartamento);
                    if ($apartamento) {
                        // Alterando o nome do usuário
                        $apartamento->n_codimorad = $idEntidade;
                        // Salvando as alterações no banco de dados
                        $idEntidade->save();
                    } else {
                      return response()->json(['message' => "Apartamento não encontrado!"], 404);
                    }
                    break;
                case 'tracoord':
                    $dadosEntidade = [
                        'c_nomecoord' => $req->login,
                        'c_nomeentid' => $req->tipoDeEntidadeACoordenar,
                        'n_codientid' => $req->codiEntidade
                    ];
                    $idEntidade = Coordenador::insertGetId($dadosEntidade);
                    //*codiApartamento
                    break;
            }

            //criar usuario
            $user = new User();
            $user->c_logiusuar = $req->login;
            $user->c_emaiusuar = $req->email;
            $user->c_senhusuar = Hash::make($req->password);
            $user->c_nomeentid = $req->tipo;
            $user->n_codientid = $idEntidade;
            $user->save();

            return response()->json(['message' => "usuario criado com sucesso!"], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
