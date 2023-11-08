<?php

namespace App\Http\Controllers;

use App\Models\Centralidade;
use App\Utils\Util;
use GuzzleHttp\Psr7\Response;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CentralidadeController extends Controller
{
    public function index()
    {
        try {
            return Centralidade::all();
        } catch (\Illuminate\Database\QueryException $th) {
            return response()->json(['message' => 'Erro ao lista as Centralidade'], 500);
        }
    }
    public function create(Request $req, Response $res)
    {
        $isValidData = Validator::make($req->all(), [
            "c_desccentr" => 'required|string|max:50',
            "n_nblocentr" => 'integer',
            "n_codicoord" => 'integer',
            "n_codiender" => 'integer',
            "n_codiadmin" => 'integer'
        ]);
        if ($isValidData->fails())
            return response()->json(['erros' => $isValidData->errors(), 'status' => 400], 400);

        try {
            Centralidade::create($req->all());
            return response()->json(['message' => "centralidade criada com sucesso!"], 201);;
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' =>"Erro ao criar Centralidade"], 500);
        }
    }
    public function delete($id){
        try {
            $centralidade = Centralidade::find($id);
            if(!$centralidade){
                return response()->json(['message', "A Centralidade não existe."]);
            }
            $centralidade->delete();
        } catch (\Throwable $th) {
            return response()->json(['message' =>"Erro ao deletar Centralidade, possivelmente há blocos nesta centralidade."], 500);
        }
    }
    public function update(Request $req, $id){
        try {
            $centralidade = Centralidade::find($id);
            if(!$centralidade){
                return response()->json(['message', "Centralidade não encontrada."]);
            }
            $centralidade->update($req->all());

            return response()->json($req->all());
        } catch (\Throwable $th) {
            return response()->json(['message' =>"Erro ao atualizar Centralidade"], 500);
        }
    }
    public function getOne($id){
        try {
            $centralidade = Centralidade::find($id);
            if(!$centralidade){
                return response()->json(['message', "Centralidade não encontrada!"], 404);
            }
            return response()->json($centralidade, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' =>"Erro ao atualizar Centralidade"], 500);
        }

    }
}
