<?php

namespace App\Http\Controllers;

use App\Models\Taxa;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxaController extends Controller
{
    /**
    * @OA\Get(
        *     tags={"/taxas"},
        *     path="/api/taxas",
        *     summary="listar taxas",
        *     security={{"bearerAuth": {} }},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
*/
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
/**
    * @OA\Post(
        *     tags={"/taxas"},
        *     path="/api/taxas",
        *     summary="registrar taxa",
        *     security={{"bearerAuth": {} }},
        *     @OA\RequestBody(
        *       required=true,
        *       @OA\JsonContent(
        *          type="object",
        *          @OA\Property(property="c_desctaxa",type="string",description="descricão da taxa"),
        *          @OA\Property(property="n_valotaxa",type="float",description="valor da taxa"),
        *          @OA\Property(property="n_vmultaxa",type="int",description="valor da multa"),
        *          @OA\Property(property="n_permtaxa",type="float",description="valor da multa, percentagem"),
        *          @OA\Property(property="d_denvtaxa",type="date",description="data de início de envio da taxa"),
        *          @OA\Property(property="c_freqtaxa",type="string",description="frequência de envio da taxa"),
        *          @OA\Property(property="n_praztaxa",type="int",description="prazo em dias, para o pagamento da taxa"),
        *          @OA\Property(property="n_codicoord",type="int",description="id do coordenador do que criou a taxa"),
        *       )
        *     ),
        *     
        *     @OA\Response(response="201", description="taxa registrada com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="404", description="coordenador não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
*/
    public function create(Request $req)
    {
        $isValidData = Validator::make($req->all(), 
        [
            'c_desctaxa' => 'required|string',
            'n_valotaxa' => 'required|float',
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
        //TODO verificar a existencia do coordenador
        Taxa::create($req->all());
        // dd($data);
        return response()->json(['message' => "Taxa criado com sucesso!"], 201);;
    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

 /**
    * @OA\Delete(
        *     tags={"/taxas"},
        *     path="/api/taxas/{taxa}",
        *     summary="deletar taxa",
        *       security={{"bearerAuth": {} }},
        *       @OA\Parameter(
        *         name="taxas",
        *         in="path",
        *         description="id da taxas",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="taxa deletado com sucesso!"),
        *     @OA\Response(response="404", description="taxa não encontrada"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
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
/**
    * @OA\Get(
        *     tags={"/taxas"},
        *     path="/api/taxas/{taxa}",
        *     summary="mostrar um Taxa",
        *     security={{ "bearerAuth": {}}},   
        *     @OA\Parameter(
        *         name="taxa",
        *         in="path",
        *         description="id do taxa",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="Taxa não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
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
