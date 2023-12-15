<?php

namespace App\Http\Controllers;

use App\Models\Morador;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MoradorController extends Controller
{
    public function getAll()
    {
        try {
            return Morador::all();
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
            "c_nomemorad" => 'required|string',
            "c_apelmorad" => 'required|string',
            "c_bilhmorad" => 'string'
        ]);
        if ($isValidData->fails())
        return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

    try {
        Morador::create($req->all());
        // dd($data);
        return response()->json(['message' => "Morador criado com sucesso!"], 201);;
    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

    public function delete($id)
    {
        try {
            $Morador = Morador::find($id);
            if (!$Morador)
                return response()->json(['message' => "Morador não encontrado"], 404);
            $Morador->delete();
            return response()->json(['message' => "Morador deletado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $Morador = Morador::find($id);
            if (!$Morador) {
                return response()->json(['message' => "Morador não encontrado."], 404);
            }
            $Morador->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function getOne($id)
    {
        try {
            $Morador = Morador::find($id);
            if (!$Morador) {
                return response()->json(['message' => "Morador não encontrado!"], 404);
            }
            return response()->json($Morador, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}