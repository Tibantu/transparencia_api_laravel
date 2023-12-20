<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use App\Models\Divida;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

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
            'n_valopagam'  => 'required',
            'n_vadipagam',
            'c_descpagam'  => 'required|string',
            'c_formpagam'  => 'required|string',
            'd_datapagam'  => 'required',
            'd_dacrpagam',
            'create_at',
            'updated_at',
            'd_dacopagam',
            'c_bancpagam',
            'n_codibanco',
            'n_estapagam',
            'n_codicoord',
            'n_codidivid'  => 'required',
            'n_codiapart'  => 'required'
        ]);
        if ($isValidData->fails())
        return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

    try {
        Pagamento::create($req->all());
        // dd($data);
        return response()->json(['message' => "Pagamento criado com sucesso!"], 201);;
    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

    public function delete($id)
    {
        try {
            $Pagamento = Pagamento::find($id);
            if (!$Pagamento)
                return response()->json(['message' => "Pagamento não encontrado"], 404);
            $Pagamento->delete();
            return response()->json(['message' => "Pagamento deletado com sucesso!"], 200);
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