<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\Coordenador;
use App\Models\Predio;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BancoController extends Controller
{
    public function getAll()
    {
        try {
            return Banco::all();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getAllByCoordenador($idPredi)
    {
        try {
            //$coordenador = Coordenador::find($idCoordenador);
            $predio = Predio::find($idPredi);
            if (!$predio)
                return response()->json(['message' => "Predio não encontrado!"], 404);
            
            return Banco::where('n_codicoord', '=', $predio->n_codicoord)->get();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function create(Request $req)
    {
        $isValidData = Validator::make($req->all(), 
        [
            'c_entibanco' => 'required|string',
            'c_descbanco',
            'n_saldbanco',
            'd_dacrbanco',
            'n_codicoord',
            'n_codientid',
            'c_nomeentid',
            'create_at',
            'updated_at'
        ]);
        if ($isValidData->fails())
        return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

    try {
        $predio = Predio::find($req->input('n_codipredi'));
        $data = $req->all();
        $data['n_codientid'] = (int) $predio->n_codipredi;
        $data['n_codicoord'] = (int) $predio->n_codicoord;
        $data['c_nomeentid'] = 'trapredi';

        Banco::create($data);
        // dd($data);
        return response()->json(['message' => "Banco criado com sucesso!"], 201);;
    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

    public function delete($id)
    {
        try {
            $Banco = Banco::find($id);
            if (!$Banco)
                return response()->json(['message' => "Banco não encontrada"], 404);
            $Banco->delete();
            return response()->json(['message' => "Banco deletado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $Banco = Banco::find($id);
            if (!$Banco) {
                return response()->json(['message' => "Banco não encontrado."], 404);
            }
            $Banco->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function getOne($id)
    {
        try {
            $Banco = Banco::find($id);
            if (!$Banco) {
                return response()->json(['message' => "Banco não encontrado!"], 404);
            }
            return response()->json($Banco, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}