<?php

namespace App\Http\Controllers;

use App\Models\Coordenador;
use App\Utils\MyUtils;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoordenadorController extends Controller
{
    /**
    * @OA\Get(
        *     tags={"/coordenadores"},
        *     path="/coordenadores",
        *     summary="listar coordenadores",
        *     security={{"bearerAuth": {} }},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
*/
    public function getAll()
    {
        try {
            return Coordenador::all();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getAllByBloco($idBloco)
    {//trazer todos os coordenadores de predios do bloco
      //acesso apenas para coord bloco
        try {
          $user = auth()->user();
          $iscoordBloco = MyUtils::isCoordBloco($user);
          if ($iscoordBloco) {
            $coordenadores = Coordenador::where('n_codibloco', '=', $idBloco)->get();
          }else
          dd("NAO é coord bloco");

            return response()->json(['message' => $coordenadores], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function create(Request $req)
    {
        $isValidData = Validator::make($req->all(), [
            'c_nomeentid' => 'required|string|in:trapredi,trabloco',
            'n_codientid' => 'required|integer',
            'c_nomecoord' => 'required|string',
            'c_apelcoord' => 'required|string',
            'create_at',
            'updated_at',
            'd_dacrcoord',
            'd_daimcoord',
            'n_codimorad' => 'required|integer'
        ]);
        if ($isValidData->fails())
        return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

    try {
        Coordenador::create($req->all());
        // dd($data);
        return response()->json(['message' => "Coordenador criado com sucesso!"], 201);;
    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

    public function delete($id)
    {
        try {
            $Coordenador = Coordenador::find($id);
            if (!$Coordenador)
                return response()->json(['message' => "Coordenador não encontrado"], 404);
            $Coordenador->delete();
            return response()->json(['message' => "Coordenador deletado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $Coordenador = Coordenador::find($id);
            if (!$Coordenador) {
                return response()->json(['message' => "Coordenador não encontrado."], 404);
            }
            $Coordenador->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function getOne($id)
    {
        try {
            $Coordenador = Coordenador::find($id);
            if (!$Coordenador) {
                return response()->json(['message' => "Coordenador não encontrado!"], 404);
            }
            return response()->json($Coordenador, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
