<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Morador;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MoradorController extends Controller
{
/**
    * @OA\Get(
        *     tags={"/moradores"},
        *     path="/api/moradores",
        *     summary="listar moradores",
        *     security={{"bearerAuth": {} }},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
*/
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

/**
    * @OA\Post(
        *     tags={"/moradores"},
        *     path="/api/moradores",
        *     summary="Registrar morador",
        *     security={{"bearerAuth": {} }},
        *     @OA\RequestBody(
        *       required=true,
        *       @OA\JsonContent(
        *          type="object",
        *          @OA\Property(property="c_nomemorad",type="string",description="nome do morador"),
        *          @OA\Property(property="c_apelmorad",type="string",description="ultimo nome do morador"),
        *          @OA\Property(property="n_codiapart",type="int",description="id do apartamento a vincular com o morador"),
        *          @OA\Property(property="c_bilhmorad",type="string",description="bilhete de identidade do morador"),
        *       )
        *     ),
        *     
        *     @OA\Response(response="201", description="morador cadastrado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="404", description="apartamento não encontrado"),
        *     @OA\Response(response="405", description="apartamento oucupado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function create(Request $req)
    {
        $isValidData = Validator::make($req->all(), [
            "c_nomemorad" => 'required|string',
            "c_apelmorad" => 'required|string',
            "c_bilhmorad" => 'string'
        ]);
        if ($isValidData->fails())
        return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 412);

    try {
        $registro = Apartamento::where('n_codiapart',$req->input('n_codiapart'))->first();
        if(!$registro)
              return response()->json(['message' => 'apartamento não encontrado'], 404);
  
        //antes de criar o morador verificar se o apartamento está oucupado
        if($registro->n_codimorad != null)
            return response()->json(['message' => 'apartamento oucupado'], 405);
        $morador = Morador::create($req->all());
        // atribuir o apartamento ao morador
        $registro->n_codimorad = $morador->n_codimorad;
        $registro->save();
        // dd($data);
        return response()->json(['message' => "Morador criado com sucesso!"], 201);;
    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

 /**
    * @OA\Delete(
        *     tags={"/moradores"},
        *     path="/api/moradores/{morador}",
        *     summary="apagar um morador",
        *       security={{"bearerAuth": {} }},
        *       @OA\Parameter(
        *         name="morador",
        *         in="path",
        *         description="id do morador",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="morador deletado com sucesso!"),
        *     @OA\Response(response="404", description="morador não encontrada"),
        *     @OA\Response(response="405", description="apartamento do morador não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function delete($id)
    {
        try {
            $Morador = Morador::find($id);
            if (!$Morador)
                return response()->json(['message' => "Morador não encontrado"], 404);
            
            $apartamento = Apartamento::find($Morador->n_codimorad);
            if(!$apartamento)
                return response()->json(['message' => "apartamento do morador não encontrado"], 405);
            
            $apartamento->n_codimorad = null;
            $apartamento->save();

            $Morador->delete();
            return response()->json(['message' => "Morador deletado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
/**
    * @OA\Put(
        *     tags={"/moradores"},
        *     path="/api/moradores/{morador}",
        *     summary="atualizar morador",
        *     security={{"bearerAuth": {} }},
        *     @OA\Parameter(
        *         name="morador",
        *         in="path",
        *         description="id do morador",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\RequestBody(
        *       required=true,
        *       @OA\JsonContent(
        *          type="object",
        *          @OA\Property(property="c_nomemorad",type="string",description="nome do morador"),
        *          @OA\Property(property="c_apelmorad",type="string",description="ultimo"),
        *          @OA\Property(property="n_codiapart",type="int",description="id do apartamento a vincular com o morador"),
        *          @OA\Property(property="c_bilhmorad",type="string",description="BI"),
        *       )
        *     ),
        *     
        *     @OA\Response(response="201", description="morador atualizado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="404", description="morador não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
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
/**
    * @OA\Get(
        *     tags={"/moradores"},
        *     path="/api/moradores/{morador}",
        *     summary="mostrar morador",
        *     security={{ "bearerAuth": {}}},   
        *     @OA\Parameter(
        *         name="morador",
        *         in="path",
        *         description="id do morador",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="morador não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
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