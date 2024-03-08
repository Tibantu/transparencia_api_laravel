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
   *     path="/auth/login",
   *     summary="confirmar a existencia do usuario, primeira etapa de acesso ao sistema",
   *     security={{"bearerAuth": {} }},
   *     @OA\RequestBody(
   *       required=true,
   *       @OA\JsonContent(
   *          type="object",
   *          @OA\Property(property="login",type="string",description="login")
   *       )
   *     ),
   *
   *     @OA\Response(response="200", description="login válido"),
   *     @OA\Response(response="401", description="credencias incorreta | login inválido")
   * )
   */

    public function login(Request $request)
    {
        $credenciais = $request->only(['login']);

        if (count($credenciais) != 1) {
            return response()->json(['message' => 'credencias incorreta'], 401);
        }
        $user = User::where('c_logiusuar', $credenciais['login'])->first();

        if (!$user)
            return response()->json(['message' => 'login inválido'], 401);
        return response()->json(['message' => "login válido"], 200);

    }

      /**
   * @OA\Post(
   *     tags={"/login"},
   *     path="/auth/senha",
   *     summary="login no sistema. Segunda etapa do logim, passando a senha, o login da etapa 1 num campo oculto",
   *     security={{"bearerAuth": {} }},
   *     @OA\RequestBody(
   *       required=true,
   *       @OA\JsonContent(
   *          type="object",
   *          @OA\Property(property="login",type="string",description="login"),
   *          @OA\Property(property="senha",type="string",description="senha do pessoal")
   *       )
   *     ),
   *
   *     @OA\Response(response="200", description="retorna um objecto {'message': 'User token', 'token': '18|A5wFrzvyI435BuH7NqT0sgTkARCpEDWTUaoW0faTca383882'}  "),
   *     @OA\Response(response="401", description="credencias incorreta | senha incorreta")
   * )
   */
    public function senha(Request $request){

      $credenciais = $request->only(['login', 'senha']);

      if (count($credenciais) != 2) {
          return response()->json(['message' => 'credencias incorreta'], 401);
       }
      $user = User::where('c_logiusuar', $credenciais['login'])->first();
      if (!$user){
          return response()->json(['message' => 'credencias incorreta'], 401);
      }
      if($user->c_senhusuar != $credenciais['senha'])
          return response()->json(['message' => 'senha incorreta'], 401);

      $user->tokens()->delete();
      $token = $user->createToken('token_access');

      return response()->json(['message' => "User token", 'token' => $token->plainTextToken], 200);

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
