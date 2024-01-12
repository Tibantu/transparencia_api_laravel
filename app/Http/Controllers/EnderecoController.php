<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EnderecoController extends Controller
{
    public function getAll()
    {
        try {
            return response() ->json(['message' => Endereco::all()], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
    public function create(Request $req)
    {
        $isValidData =  Validator::make($req->all(), [
            'c_paisender' => 'string|min:2',
            'c_descender' => 'string|max:100',
            'c_provender' => 'string|max:14',
            'c_muniender' => 'string|max:15',
            'c_bairender' => 'string|max:15'
        ]);
        if ($isValidData->fails())
            return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

        try {
            $data = Endereco::create($req->all());
            // dd($data);
            return response()->json(['message' => "Endereço criada com sucesso!"], 201);;
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
        /**
    * @OA\Get(
        *     tags={"/enderecos"},
        *     path="/api/enderecos/{endereco}",
        *     summary="mostrar um endereco",
        *     security={{ "bearerAuth": {}}},   
        *     @OA\Parameter(
        *         name="endereco",
        *         in="path",
        *         description="id do endereco",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="endereco não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getOne($id)
    {
        // dd($id);
        try {
            $endereco = Endereco::find($id);
            if(!$endereco)
                return response()->json(['message' => "Endereço não encontrado"], 404);
            
            return response()->json($endereco, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $endereco = Endereco::find($id);
            if(!$endereco)
                return response()->json(['message'=> 'endereço não encontrado'], 400);
            
            $endereco->update($req->all());

            return response()->json($endereco);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message'=> $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $centralidade = Endereco::findOrFail($id);
            $centralidade->delete();
            return response()->json(['message' => "Endereço deletada com sucesso!"], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
