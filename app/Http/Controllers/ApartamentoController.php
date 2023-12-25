<?php

namespace App\Http\Controllers;

use App\Models\Bloco;
use App\Models\Centralidade;
use App\Models\Apartamento;
use App\Models\Conta;
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

    public function getAllByPredio($idPredio)
    {
        try {
            Apartamento::findOrFail($idPredio);

            return Apartamento::where('n_codipredi', '=', $idPredio)->get();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function create(Request $req, $idPredio)
    {

        $isValidData = Validator::make($req->all(), [
            'c_portapart'=> 'required',
            'c_tipoapart'=> 'required', 
            'n_nandapart', 
            'd_dacrapart', 
            'n_codiconta', 
            'n_codipredi', 
            'n_codimorad'

        ]);
        try {
            $bloco = Bloco::find($idPredio);
            if (!$bloco)
                return response()->json(['message' => "Bloco não encontrada!"], 404);

            if ($isValidData->fails())
                return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);
                /*criar conta do apartamento*/
                $dataConta = [
                    'n_saldconta'=> 0
                ];
                $conta = Conta::create($dataConta);

            $data = $req->all();

            $data['n_codipredi'] = (int) $idPredio;
            $data['n_codiconta'] = (int) $conta->n_codiconta;


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