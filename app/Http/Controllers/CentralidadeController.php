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
    public function getAll()
    {
        try {
            return Centralidade::all();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
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
            return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

        try {
            Centralidade::create($req->all());
            return response()->json(['message' => "centralidade criada com sucesso!"], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function getAllByProvincia($denominacao){

        /* select que faz isso
          select * from tracentr, traender where tracentr.n_codiender = traender.n_codiender and traender.c_provender = 'Luanda';
        */
        try {
            Centralidade::findOrFail($idCentralidade);
            return Bloco::where('n_codicentr', '=', $idCentralidade)->get();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function delete($id)
    {
        try {
            $centralidade = Centralidade::findOrFail($id);
            $centralidade->delete();
            return response()->json(['message' => "centralidade deletada com sucesso!"], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $centralidade = Centralidade::findOrFail($id);

            $centralidade->update($req->all());

            return response()->json($req->all());
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getOne($id)
    {
        try {
            $centralidade = Centralidade::findOrFail($id);
            return response()->json($centralidade, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}