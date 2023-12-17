<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    public function getAll()
    {
        try {
            return Coordenador::all();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
/*
    public function getAllByMoradores($idPredio)
    {
        try {
            Apartamento::findOrFail($idBloco);

            return Apartamento::where('n_codibloco', '=', $idBloco)->get();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
*/
    public function create(Request $req)
    {
        $isValidData = Validator::make($req->all(), [
            'c_nomeentid' => 'required|string',
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
