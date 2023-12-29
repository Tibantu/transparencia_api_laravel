<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CaixaController extends Controller
{
    public function getAll()
    {
        try {
            return Caixa::all();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $Caixa = Caixa::find($id);
            if (!$Caixa)
                return response()->json(['message' => "Caixa não encontrada"], 404);
            $Caixa->delete();
            return response()->json(['message' => "Caixa deletada com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $Caixa = Caixa::find($id);
            if (!$Caixa) {
                return response()->json(['message' => "Caixa não encontrada."], 404);
            }
            $Caixa->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function getOne($id)
    {
        try {
            $Caixa = Caixa::find($id);
            if (!$Caixa) {
                return response()->json(['message' => "Caixa não encontrada!"], 404);
            }
            return response()->json($Caixa, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
