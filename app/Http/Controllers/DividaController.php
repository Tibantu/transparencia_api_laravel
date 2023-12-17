<?php

namespace App\Http\Controllers;

use App\Models\Divida;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DividaController extends Controller
{
    public function getAll()
    {
        try {
            return Divida::all();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getAllByConta($idConta)
    {
        try {
            Divida::findOrFail($idConta);

            return Divida::where('n_codiconta', '=', $idConta)->get();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function create(Request $req)
    {
        $isValidData = Validator::make($req->all(), 
        [
            'c_estadivid',
            'c_descdivid' => 'required|string',
            'n_muaddivid',
            'n_valtdivid',
            'n_valodivid' => 'required|string',
            'n_vapedivid',
            'n_vapadivid',
            'n_prazdivid',
            'd_dcomdivid',
            'd_dapadivid',
            'd_dacodivid',
            'd_dappdivid',
            'n_vmuldivid',
            'n_cododivid',
            'n_codicoord' => 'required|string',
            'n_codiconta' => 'required|string',
            'create_at',
            'updated_at',
            'd_dacrdivid',
        ]);
        if ($isValidData->fails())
        return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

    try {
        Divida::create($req->all());
        // dd($data);
        return response()->json(['message' => "Divida criada com sucesso!"], 201);;
    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

    public function delete($id)
    {
        try {
            $Divida = Divida::find($id);
            if (!$Divida)
                return response()->json(['message' => "Divida não encontrada"], 404);
            $Divida->delete();
            return response()->json(['message' => "Divida deletada com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $Divida = Divida::find($id);
            if (!$Divida) {
                return response()->json(['message' => "Divida não encontrada."], 404);
            }
            $Divida->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function getOne($id)
    {
        try {
            $Divida = Divida::find($id);
            if (!$Divida) {
                return response()->json(['message' => "Divida não encontrada!"], 404);
            }
            return response()->json($Divida, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}

