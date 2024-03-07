<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\AuthRequest;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{


  /**
   * @OA\Post(
   *     tags={"/login"},
   *     path="/login",
   *     summary="fazero login no sistema",
   *     security={{"bearerAuth": {} }},
   *     @OA\RequestBody(
   *       required=true,
   *       @OA\JsonContent(
   *          type="object",
   *          @OA\Property(property="login",type="string",description="nome de adesao"),
   *          @OA\Property(property="senha",type="int",description="senha do pessoal")
   *       )
   *     ),
   *
   *     @OA\Response(response="201", description="login successfully"),
   *     @OA\Response(response="412", description="Erro ao logar"),
   *     @OA\Response(response="500", description="Validation errors")
   * )
   */

    public function login(Request $request)
    {

        $credenciais = $request->only(['login', 'senha']);

        if (count($credenciais) != 2) {
            return response()->json(['message' => 'credencias enviadas está incorreta'], 401);
        }
        $user = User::where('c_logiusuar', $credenciais['login'])->first();

        if (!$user)
            return response()->json(['message' => 'credencias enviadas está incorreta'], 401);

        if (Hash::check($credenciais['senha'], $user->c_senhusuar)) {
            return response()->json(['message' => 'credencias enviadas está incorreta'], 401);
        }
        $token = $user->createToken('token_access');

        return response()->json((['message' => "User token ", 'token' => $token->plainTextToken]));
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'mensage' => 'sucesso',
        ]);
    }
    public function me(Request $request)
    {

        $user = $request->user();
        dd($user);
        return response()->json([
            'me' => $user,
        ]);
    }
}
