<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PagamentoController extends Controller
{
  /**
    * @OA\Get(
        *     tags={"/pagamentos"},
        *     path="/api/pagamentos",
        *     summary="listar pagamentos",
        *     security={{"bearerAuth": {} }},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
*/
    public function getAll()
    {
        try {
            return Pagamento::all();
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
            $Pagamento = Pagamento::find($id);
            if (!$Pagamento) {
                return response()->json(['message' => "Pagamento não encontrado."], 404);
            }
            $Pagamento->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function getOne($id)
    {
        try {
            $Pagamento = Pagamento::find($id);
            if (!$Pagamento) {
                return response()->json(['message' => "Pagamento não encontrado!"], 404);
            }
            return response()->json($Pagamento, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
