<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
    * @OA\Post(
        *     tags={"/usuarios"},
        *     path="/usuarios",
        *     summary="Cadastrar usuarios",
        *     security={{"bearerAuth": {} }},
        *     @OA\RequestBody(
        *       required=true,
        *       @OA\JsonContent(
        *          type="object",
        *          @OA\Property(property="name",type="string",default="nome de adesão",description="denominação da centralidade"),
        *          @OA\Property(property="email",type="string",description="email válido"),
        *          @OA\Property(property="password",type="string",default="senha pessoal",description="denominação da centralidade"),
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
             "name" => 'required|string',
             "email" => 'required|string',
             "password" => 'required|string',
             "remember_token" => 'string'
         ]);
         if ($isValidData->fails())
             return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 412);

         try {
            $user = new User();
            $user->password = bcrypt($req->password);
            $user->email = $req->email;
            $user->name = $req->name;
            $user->save();
           // User::create($req->all());
             return response()->json(['message' => "usuario criado com sucesso!"], 201);
         } catch (\Illuminate\Database\QueryException $e) {
             return response()->json(['message' => $e->getMessage()], 500);
         }
     }
}
