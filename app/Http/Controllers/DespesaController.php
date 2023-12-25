<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use App\Models\Divida;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DespesaController extends Controller
{
    public function getAll()
    {
        try {
            return Despesa::all();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getAllByPredio($idCoordPredio)
    {
        try {
            Despesa::findOrFail($idCoordPredio);

            return Despesa::where('n_codicoord', '=', $idCoordPredio)->get();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function create(Request $req)
    {
        $isValidData = Validator::make($req->all(), [
        'c_objedespe'=> 'required',
        'n_codicoord'=> 'required',
        'n_valodespe'=> 'required',
        'create_at',
        'updated_at',
        'c_objedespe',
        'c_fontdespe'=> 'required',
        'd_dacrdespe',
        'd_dasadespe'=> 'required',            
        ]);
        if ($isValidData->fails())
        return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

    try {
        Despesa::create($req->all());
        // dd($data);
        return response()->json(['message' => "Despesa criada com sucesso!"], 201);;
    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

    public function delete($id)
    {
        try {
            $Despesa = Despesa::find($id);
            if (!$Despesa)
                return response()->json(['message' => "Despesa não encontrado"], 404);
            $Despesa->delete();
            return response()->json(['message' => "Despesa deletado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $Divida = Divida::find($id);
            if (!$Divida) {
                return response()->json(['message' => "Divida não encontrado."], 404);
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
            $Despesa = Despesa::find($id);
            if (!$Despesa) {
                return response()->json(['message' => "Despesa não encontrado!"], 404);
            }
            return response()->json($Despesa, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}