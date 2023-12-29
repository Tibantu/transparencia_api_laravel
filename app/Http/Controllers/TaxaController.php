<?php

namespace App\Http\Controllers;

use App\Models\Taxa;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxaController extends Controller
{
    public function getAll()
    {
        try {
            return Taxa::all();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getAllByPredio($idCoordenador)
    {
        try {
            $taxa = taxa::find($idCoordenador);
            if (!$taxa)
                return response()->json(['message' => "Taxa não encontrada"], 404);

            return Taxa::where('n_codicoord', '=', $idCoordenador)->get();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function create(Request $req)
    {
        $isValidData = Validator::make($req->all(), 
        [
            'c_desctaxa' => 'required|string',
            'n_valotaxa' => 'required|string',
            'n_vmultaxa',
            'n_permtaxa',
            'n_diaetaxa',
            'create_at',
            'updated_at',
            'd_dacrtaxa',
            'd_denvtaxa',
            'c_freqtaxa' => 'required',
            'n_praztaxa',
            'c_constaxa',
            'n_codicoord'=> 'required|integer',
        ]);
        if ($isValidData->fails())
        return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

    try {
        Taxa::create($req->all());
        // dd($data);
        return response()->json(['message' => "Taxa criado com sucesso!"], 201);;
    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

    public function delete($id)
    {
        try {
            $Taxa = Taxa::find($id);
            if (!$Taxa)
                return response()->json(['message' => "Taxa não encontrada"], 404);
            $Taxa->delete();
            return response()->json(['message' => "Taxa deletada com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $Taxa = Taxa::find($id);
            if (!$Taxa) {
                return response()->json(['message' => "Taxa não encontrada."], 404);
            }
            $Taxa->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function getOne($id)
    {
        try {
            $Taxa = Taxa::find($id);
            if (!$Taxa) {
                return response()->json(['message' => "Taxa não encontrada!"], 404);
            }
            return response()->json($Taxa, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
