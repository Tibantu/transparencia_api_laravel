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
            return response() ->json(['pagamentos' => Pagamento::all()], 200);
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
/**
    * @OA\Post(
        *     tags={"/pagamentos"},
        *     path="/api/pagamentos",
        *     summary="registrar pagamento",
        *     security={{"bearerAuth": {} }},
        *     @OA\RequestBody(
        *       required=true,
        *       @OA\JsonContent(
        *          required={"n_valopagam","c_descpagam","c_formpagam","d_datapagam","n_codiapart","n_codidivid"},
        *          type="object",
        *          @OA\Property(property="n_valopagam",type="string",description="valor do pagamento"),
        *          @OA\Property(property="c_descpagam",type="float",description="descrição do pagamento"),
        *          @OA\Property(property="c_formpagam",type="int",description="forma de pagamento"),
        *          @OA\Property(property="d_datapagam",type="float",description="data de pagamento"),
        *          @OA\Property(property="n_codidivid",type="date",description="id divida"),
        *          @OA\Property(property="n_codiapart",type="string",description="id apartamento"),
        *       )
        *     ),
        *     
        *     @OA\Response(response="201", description="pagamento registrado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="404", description="Morador não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
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


 /**
    * @OA\Delete(
        *     tags={"/pagamentos"},
        *     path="/api/pagamentos/{pagamento}",
        *     summary="deletar pagamento",
        *       security={{"bearerAuth": {} }},
        *       @OA\Parameter(
        *         name="pagamento",
        *         in="path",
        *         description="id da pagamento",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="pagamento deletado com sucesso!"),
        *     @OA\Response(response="404", description="pagamento não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
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
/**
    * @OA\Get(
        *     tags={"/pagamentos"},
        *     path="/api/pagamentos/{pagamento}",
        *     summary="mostrar pagamento",
        *     security={{ "bearerAuth": {}}},   
        *     @OA\Parameter(
        *         name="pagamento",
        *         in="path",
        *         description="id do pagamento",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="pagamento não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
*/
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
