<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\AuthRequest;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

use Stringable;

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

    public function login_view(Request $request)
    {
        return view("auth.login");
    }
    public function login_view_reset(Request $request)
    {
        return view("auth.forgot-password");
    }
      /**
   * @OA\Post(
   *     tags={"/login"},
   *     path="/auth/reset-senha",
   *     summary="Envia o link para o reset da senha, passando o email cadastrado no sistema",
   *     security={{"bearerAuth": {} }},
   *     @OA\RequestBody(
   *       required=true,
   *       @OA\JsonContent(
   *          type="object",
   *          @OA\Property(property="email",type="string",description="email")
   *       )
   *     ),
   *
   *     @OA\Response(response="200", description="ink enviado no seu email"),
   *     @OA\Response(response="401", description="email nao registrado")
   * )
   */
    public function postlogin_view_reset(Request $request)
    {
      $user = User::getEmailSingle($request->email);
        if(!empty($user))
        {
          $user->remember_token = Str::random(30);
          Mail::to($user->c_emaiusuar)->send(new ForgotPasswordMail($user));
          $user->save();
          return response()->json(['message' => 'link enviado no seu email'], 401);
        }
        else
        {
          return response()->json(['message' => 'email nao registrado'], 401);
        }
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
      if(!Hash::check($credenciais['senha'], $user->c_senhusuar))
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
        ], 200);
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
