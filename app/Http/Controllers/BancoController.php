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
    /**
    * @OA\Get(
        *     tags={"/bancos"},
        *     path="/api/bancos",
        *     summary="listar bancos",
        *     security={{"bearerAuth": {} }},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
*/
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

    /**
    * @OA\Post(
        *     tags={"/bancos"},
        *     path="/api/bancos",
        *     summary="Cadastrar bancos",
        *     description="cadastrar bancos normalmente pertence a um coordenador",
        *     security={{"bearerAuth": {} }},
        *     @OA\RequestBody(
        *       required=true,
        *       @OA\JsonContent(
        *          type="object",
        *          @OA\Property(property="c_entibanco",type="string",default="BAI",description="entidade bancária a simular"),
        *          @OA\Property(property="n_codicoord",type="int",description="id do coordenador"),
        *          @OA\Property(property="n_codientid",type="int",default=null,description="id da entidade proprietario do banco se não for um coordenador"),
        *          @OA\Property(property="c_nomeentid",type="string",default=null,description="nome da entidade proprietario do banco se não for um coordenador")
        *       )
        *     ),
        *     
        *     @OA\Response(response="201", description="Banco cadastrado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="500", description="Validation errors")
        * )
     */
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
            return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 412);

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

    /**
    * @OA\Delete(
        *     tags={"/bancos"},
        *     path="/api/bancos/{banco}",
        *     summary="apagar um banco",
        *     security={{"bearerAuth": {} }},
        *       @OA\Parameter(
        *         name="banco",
        *         in="path",
        *         description="id do bancos",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="banco deletado com sucesso!"),
        *     @OA\Response(response="404", description="banco não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
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
        /**
    * @OA\Get(
        *     tags={"/bancos"},
        *     path="/api/bancos/{banco}",
        *     summary="mostrar um banco",
        *     security={{ "bearerAuth": {}}},   
        *     @OA\Parameter(
        *         name="banco",
        *         in="path",
        *         description="id do banco",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="banco não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
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