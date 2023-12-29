<?php

namespace App\Http\Controllers;

use App\Models\Bloco;
use App\Models\Caixa;
use App\Models\Centralidade;
use App\Models\Todo;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use \Illuminate\Database\QueryException;

class BlocoController extends Controller
{

    public function getAll()
    {
        try {
            return Bloco::all();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getAllByCentr($idCentralidade)
    {
        try {
            
            $centralidade = Centralidade::find($idCentralidade);
            if (!$centralidade) {
                return response()->json(['message' => "Centralidade não encontrado."], 404);
            }
            return Bloco::where('n_codicentr', '=', $idCentralidade)->get();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function create(Request $req, $idCentralidade)
    {

        $isValidData = Validator::make($req->all(), [
            "c_descbloco" => 'required|string|max:50',
            "n_nblocentr" => 'integer',
            "n_codicoord" => 'integer',
            "n_codicaixa" => 'integer',
            "c_ruablco" => 'string',
        ]);
        $centralidadde = Centralidade::find($idCentralidade);
        if (!$centralidadde)
            return response()->json(['message' => "Centralidade não encontrada!"], 404);

        if ($isValidData->fails())
            return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

        /*Criar um caixa para o bloco*/
        $dataCaixa = [
            'c_nomeentid'=>'trabloco'
        ];
        $caixa = Caixa::create($dataCaixa);
        

        $data = $req->all();

        $data['n_codicentr'] = (int) $idCentralidade;
        $data['n_codicaixa'] = (int) $caixa->n_codicaixa;

        try {
            $bloco = Bloco::create($data);
            $dataCaixa['n_codientid'] = (int) $bloco->n_codibloco;
            $caixa->update($dataCaixa);
            return response()->json(['message' => "Bloco criada com sucesso!"], 201);;
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function delete($id)
    {
        try {
            $bloco = Bloco::find($id);
            if (!$bloco)
                return response()->json(['message' => "bloco não encontrado"], 404);
            $bloco->delete();
            return response()->json(['message' => "bloco deletado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $Bloco = Bloco::find($id);
            if (!$Bloco) {
                return response()->json(['message' => "Bloco não encontrada."]);
            }
            $Bloco->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function getOne($id)
    {
        try {
            $bloco = Bloco::find($id);
            if (!$bloco) {
                return response()->json(['message' => "Bloco não encontrada!"], 404);
            }
            return response()->json($bloco, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
