<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EnderecoController extends Controller
{
    //
    public function getAll()
    {
        try {
            return Endereco::all();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
    public function create(Request $req)
    {
        $isValidData =  Validator::make($req->all(), [
            'c_paisender' => 'string|min:2',
            'c_descender' => 'string|max:100',
            'c_provender' => 'string|max:14',
            'c_muniender' => 'string|max:15',
            'c_bairender' => 'string|max:15'
        ]);
        if ($isValidData->fails())
            return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

        try {
            $data = Endereco::create($req->all());
            // dd($data);
            return response()->json(['message' => "Endereço criada com sucesso!"], 201);;
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function getOne(Request $req, $id)
    {
        // dd($id);
        try {
            // $data = Endereco::find($id);
            $data = DB::select('select * from traender where n_codiender = ?', [$id]);

            if (!$data) {
                return response()->json(['message', "Endereço não encontrada!"], 404);
            }
            return response()->json($data);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $endereco = Endereco::findOrFail($id);

            $endereco->update($req->all());

            return response()->json($endereco);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message'=> $e->getMessage()]);
        }
    }
    public function delete($id)
    {
        try {
            $centralidade = Endereco::findOrFail($id);
            $centralidade->delete();
            return response()->json(['message' => "Endereço deletada com sucesso!"], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
