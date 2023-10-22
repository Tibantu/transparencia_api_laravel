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
        return Centralidade::all();
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
            // var_dump($e);
            return response()->json(['message' =>Util::getMessage( $e->getCode())], 500);
        }
    }
}
