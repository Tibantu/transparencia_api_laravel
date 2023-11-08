<?php

namespace App\Http\Controllers;

use App\Models\Bloco;
use App\Models\Todo;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class BlocoController extends Controller
{

    public function index()
    {
        try {
            return Bloco::all();
        } catch (\Illuminate\Database\QueryException $th) {
            return response()->json(['message' => 'Erro ao lista as Bloco'], 500);
        }
    }
    public function create(Request $req, Response $res)
    {
        $isValidData = Validator::make($req->all(), [
            "c_descbloc" => 'required|string|max:50',
            "n_nblocentr" => 'integer',
            "n_codicoord" => 'integer',
            "n_codicentr" => 'integer',
        ]);
        if ($isValidData->fails())
            return response()->json(['erros' => $isValidData->errors(), 'status' => 400], 400);

        try {
            Bloco::create($req->all());
            return response()->json(['message' => "Bloco criada com sucesso!"], 201);;
        } catch (\Illuminate\Database\QueryException $e) {
            // var_dump($e);
            return response()->json(['message' => "Erro ao criar Bloco"], 500);
        }
    }
    public function delete($id)
    {
        try {
            $Bloco = Bloco::find($id);
            if (!$Bloco) {
                return response()->json(['message', "A Bloco não existe."]);
            }
            $Bloco->delete();
        } catch (\Throwable $th) {
            return response()->json(['message' => "Erro ao deletar Bloco, possivelmente há blocos nesta Bloco."], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $Bloco = Bloco::find($id);
            if (!$Bloco) {
                return response()->json(['message', "Bloco não encontrada."]);
            }
            $Bloco->update($req->all());

            return response()->json($req->all());
        } catch (\Throwable $th) {
            return response()->json(['message' => "Erro ao atualizar Bloco"], 500);
        }
    }
    public function getOne($id)
    {
        try {
            $Bloco = Bloco::find($id);
            if (!$Bloco) {
                return response()->json(['message', "Bloco não encontrada!"], 404);
            }
            return response()->json($Bloco, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Erro ao atualizar Bloco"], 500);
        }
    }
}
