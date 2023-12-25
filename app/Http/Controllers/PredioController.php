<?php

namespace App\Http\Controllers;

use App\Models\Bloco;
use App\Models\Caixa;
use App\Models\Centralidade;
use App\Models\Predio;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use \Illuminate\Database\QueryException;

class PredioController extends Controller
{

    public function getAll()
    {
        try {
            return Predio::all();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getAllByBloco($idBloco)
    {
        try {
            Predio::findOrFail($idBloco);

            return Predio::where('n_codibloco', '=', $idBloco)->get();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function create(Request $req, $idBloco)
    {

        $isValidData = Validator::make($req->all(), [
            "c_descpredi" => 'required|string|max:5',
            "c_entrpredi" => 'required|string|max:2',
            "n_napapredi" => 'integer',
            "n_napopredi" => 'integer',
            "d_dacrpredi" => 'string',
            "n_codicaixa" => 'integer',
            'n_codicoord' => 'integer'
        ]);
        try {
            $bloco = Bloco::find($idBloco);
            if (!$bloco)
                return response()->json(['message' => "Bloco não encontrada!"], 404);

            if ($isValidData->fails())
                return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);


            /*Criar um caixa para o bloco*/
            $dataCaixa = [
                'c_nomeentid'=>'trapredi'
            ];
            $caixa = Caixa::create($dataCaixa);

            $data = $req->all();

            $data['n_codibloco'] = (int) $idBloco;
            $data['n_codicaixa'] = (int) $caixa->n_codicaixa;


            $predio = Predio::create($data);
            $dataCaixa['n_codientid'] = (int) $predio->n_codipredi;
            $caixa->update($dataCaixa);
            return response()->json(['message' => "Predio criada com sucesso!"], 201);;
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function delete($id)
    {
        try {
            $predio = Predio::find($id);
            if (!$predio)
                return response()->json(['message' => "Predio não encontrado"], 404);
            $predio->delete();
            return response()->json(['message' => "Predio deletado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $predio = Predio::find($id);
            if (!$predio) {
                return response()->json(['message' => "predio não encontrada."], 404);
            }
            $predio->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function getOne($id)
    {
        try {
            $predio = Predio::find($id);
            if (!$predio) {
                return response()->json(['message' => "Predio não encontrada!"], 404);
            }
            return response()->json($predio, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
