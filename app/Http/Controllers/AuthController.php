<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\AuthRequest;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
   *     path="/auth/username",
   *     summary="confirmar a existencia do usuario, primeira etapa de acesso ao sistema",
   *     security={{"bearerAuth": {} }},
   *     @OA\RequestBody(
   *       required=true,
   *       @OA\JsonContent(
   *          type="object",
   *          @OA\Property(property="username",type="string",description="username")
   *       )
   *     ),
   *
   *     @OA\Response(response="200", description="username válido"),
   *     @OA\Response(response="401", description="credencias incorreta | username inválido")
   * )
   */

   public function username(Request $request)
   {
       $credenciais = $request->only(['username']);

       if (count($credenciais) != 1) {
           return response()->json(['message' => 'credencias incorreta'], 401);
       }
       $user = User::where('c_logiusuar', $credenciais['username'])->first();

       if (!$user)
           return response()->json(['message' => 'username inválido'], 401);
       return response()->json(['message' => "username válido"], 202);

   }

    public function login(Request $request)
    {
      if(Auth::attempt($request->only('email','senha'))){
        return response()->json(['message' => 'Autorizado'], 200);
      }
      return response()->json(['message' => 'Nao Autorizado'], 403);
    }
//te1ste
    public function login_view(Request $request)
    {
        return view("auth.login");
    }
//  te1ste
    public function login_view_reset(Request $request)
    {
        return view("auth.forgot-password");
    }
      /**
   * @OA\Post(
   *     tags={"/login"},
   *     path="/auth/reset-senha",
   *     summary="Envia o link, para alterar a senha, no email de ususario cadastrado",
   *     security={{"bearerAuth": {} }},
   *     @OA\RequestBody(
   *       required=true,
   *       @OA\JsonContent(
   *          type="object",
   *          @OA\Property(property="email",type="string",description="email")
   *       )
   *     ),
   *
   *     @OA\Response(response="200", description="link enviado no seu email"),
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
          return response()->json(['message' => 'link enviado no seu email'], 200);
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
   *     summary="login no sistema. Segunda etapa do login, passando a senha, o login da etapa 1 num campo oculto",
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

      $credenciais = $request->only(['username', 'senha']);

      if (count($credenciais) != 2) {
          return response()->json(['message' => 'credencias incorreta'], 401);
       }
      $user = User::where('c_logiusuar', $credenciais['username'])->first();
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
        //dd($user);
        return response()->json([
            'me' => $user,
        ]);
    }

}
