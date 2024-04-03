<?php

namespace App\Http\Controllers;

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
     *     tags={"/usuarios"},
     *     path="/auth",
     *     summary="Cadastrar usuarios",
     *     security={{"bearerAuth": {} }},
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="login",type="string",description="nome ou número de usuário"),
     *          @OA\Property(property="email",type="string",description="email válido"),
     *          @OA\Property(property="password",type="string",description="senha pessoal"),
     *          @OA\Property(property="tipo",type="string",description="tipo de usuario"),
     *       )
     *     ),
     *
     *     @OA\Response(response="201", description="usuario cadastrada successfully"),
     *     @OA\Response(response="412", description="Erro ao validar os dados"),
     *     @OA\Response(response="500", description="Validation errors")
     * )
     */


    public function create(Request $req)
    {
        $isValidData = Validator::make($req->all(), [
            "login" => 'required|string',
            "email" => 'required|string',
            "password" => 'required|string',
            "tipo" => 'required|string', // recebe o tipo de usuario(coordenador, morador, ...)
            "remember_token" => 'string'
        ]);

        if ($isValidData->fails())
            return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 412);

        try {
            //criar um morador if tipo == tramord
            $dadosEntidade = [];
            $idEntidade = 0;
            switch ($req->tipo) {
              case 'c_nomemorad':
                  array_merge(['c_nomemorad' => $req->login], $dadosEntidade);
                  $idEntidade = Morador::insertGetId($dadosEntidade);
                  break;
              // Adicionar mais casos conforme necessário
              default:
                   array_merge(['outro' => $req->login], $dadosEntidade);
                  break;
          }


            //criar usuario
            $user = new User();
            $user->c_logiusuar = $req->login;
            $user->c_emaiusuar = $req->email;
            $user->c_senhusuar = Hash::make($req->password);
            $user->c_nomeentid = $req->tipo;
            $user->n_codientid = $idEntidade;
            // dd($user);
            $user->save();

            return response()->json(['message' => "usuario criado com sucesso!"], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
