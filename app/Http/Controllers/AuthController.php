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
    
    public function login(AuthRequest $request){
       /*
        $credenciais = $request->only([
            'email',
            'password',
            'device_name'
        ]);
*/

        $user = User::where('email', $request->email)->first();
        //  dd($user);
        if(!$user || !Hash::check($request->password,$user->password)){
            throw ValidationException::withMessages([
                'email'=> ['credencias enviadas está incorreta']
            ]);
        } 
        //Login inico apagar tokens noutros dispositivos
        //deslogar noutros dispositivo
      // if($request->has('logout_others_devices'))
        $user->tokens()->delete();
        
        $token = $user->createToken($request->device_name)->plainTextToken;
        return response()->json([
            'token'=> $token,
        ]);
    }
    public function logout(Request $request){
        
        $request->user()->tokens()->delete();
        
        return response()->json([
            'mensage'=> 'sucesso',
        ]);

    }
    public function me(Request $request){
        
       $user = $request->user();
        dd($user);
        return response()->json([
            'me'=> $user,
        ]);

    }
}


