<?php

namespace App\Http\Controllers;

use App\Models\Bloco;
use App\Models\Centralidade;
use App\Models\Apartamento;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use \Illuminate\Database\QueryException;

class ApartamentoController extends Controller
{

    public function getAll()
    {
        try {
            return Apartamento::all();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getAllByPredio($idBloco)
    {
        try {
            Apartamento::findOrFail($idBloco);

            return Apartamento::where('n_codibloco', '=', $idBloco)->get();
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
            "n_codicaixa" => 'required|integer',
            'n_codicoord' => 'integer'
        ]);
        try {
            $bloco = Bloco::find($idBloco);
            if (!$bloco)
                return response()->json(['message' => "Bloco não encontrada!"], 404);

            if ($isValidData->fails())
                return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);


            $data = $req->all();

            $data['n_codibloco'] = (int) $idBloco;


            Apartamento::create($data);
            return response()->json(['message' => "Apartamento criada com sucesso!"], 201);;
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function delete($id)
    {
        try {
            $Apartamento = Apartamento::find($id);
            if (!$Apartamento)
                return response()->json(['message' => "Apartamento não encontrado"], 404);
            $Apartamento->delete();
            return response()->json(['message' => "Apartamento deletado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $Apartamento = Apartamento::find($id);
            if (!$Apartamento) {
                return response()->json(['message' => "Apartamento não encontrada."], 404);
            }
            $Apartamento->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function getOne($id)
    {
        try {
            $Apartamento = Apartamento::find($id);
            if (!$Apartamento) {
                return response()->json(['message' => "Apartamento não encontrada!"], 404);
            }
            return response()->json($Apartamento, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
