<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\AuthRequest;
use App\Models\User;
use App\Models\Usuario;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        $credenciais = $request->only(['email', 'senha']);

        if (count($credenciais) != 2) {
            return response()->json(['message' => 'credencias enviadas está incorreta'], 401);
        }
        $user = Usuario::where('c_logiusuar', $credenciais['email'])->first();

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
