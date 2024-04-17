<?php

namespace App\Http\Controllers;

use App\Models\Centralidade;
use App\Utils\Util;
use GuzzleHttp\Psr7\Response;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class CentralidadeController extends Controller
{


  /**
   * @OA\Get(
   *     tags={"/centralidades"},
   *     path="/centralidades",
   *     summary="listar centralidades",
   *     security={{"bearerAuth": {} }},
   *     @OA\Response(response="200", description="successfully"),
   *     @OA\Response(response="500", description="Erro no servidor")
   * )
   */
  public function getAll()
  {
    try {
      return response()->json(['centralidades' => Centralidade::all()], 200);
    } catch (\Illuminate\Database\QueryException $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }

  /**
   * @OA\Post(
   *     tags={"/centralidades"},
   *     path="/centralidades",
   *     summary="Cadastrar centralidade",
   *     security={{"bearerAuth": {} }},
   *     @OA\RequestBody(
   *       required=true,
   *       @OA\JsonContent(
   *          type="object",
   *          @OA\Property(property="c_desccentr",type="string",default="Centralidade B",description="denominação da centralidade"),
   *          @OA\Property(property="n_codiender",type="int",description="id do endereço da centralidade")
   *       )
   *     ),
   *
   *     @OA\Response(response="201", description="Centralidade cadastrada successfully"),
   *     @OA\Response(response="412", description="Erro ao validar os dados"),
   *     @OA\Response(response="500", description="Validation errors")
   * )
   */


  public function create(Request $req)
  {
    /*
            o que aconteice na interfafce: o usuario digita os dados do endereço
            e os dados da centralidade.
        */
    /*TODO
            criar o endereço
            pegar o id do endereço
            usa-lo para criar a centralidade

         */
    $isValidData = Validator::make($req->all(), [
      "c_desccentr" => 'required|string|max:50',
      "n_codicoord" => 'integer',
      "n_codiender" => 'required|integer'
    ]);
    if ($isValidData->fails())
      return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 412);

    try {
      Centralidade::create($req->all());
      return response()->json(['message' => "centralidade criada com sucesso!"], 201);
    } catch (\Illuminate\Database\QueryException $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }
  /**
   * @OA\Delete(
   *     tags={"/centralidades"},
   *     path="/centralidades/{centralidade}",
   *     summary="apagar uma centralidade",
   *       security={{"bearerAuth": {} }},
   *       @OA\Parameter(
   *         name="centralidade",
   *         in="path",
   *         description="id da centralidade",
   *         required=false,
   *         @OA\Schema(type="int")
   *     ),
   *     @OA\Response(response="200", description="centralidade deletada com sucesso!"),
   *     @OA\Response(response="404", description="centralidade não encontrada"),
   *     @OA\Response(response="500", description="Erro no servidor")
   * )
   */
  public function delete($id)
  {
    try {
      $centralidade = Centralidade::find($id);
      if (!$centralidade)
        return response()->json(['message' => 'centralidade não encontrada'], 404);
      $centralidade->delete();
      return response()->json(['message' => "centralidade deletada com sucesso!"], 200);
    } catch (\Illuminate\Database\QueryException $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }
  /**
   * @OA\Put(
   *     tags={"/centralidades"},
   *     path="/centralidades",
   *     summary="Atualizar um objecto no banco de dado",
   *     description="atualizar os dados de uma centralidade",
   *     security={{"bearerAuth": {} }},
   *     @OA\RequestBody(
   *       required=true,
   *       @OA\JsonContent(
   *          type="object",
   *          @OA\Property(property="c_desccentr",type="string",default="Centralidade B",description="denominação da centralidade"),
   *          @OA\Property(property="n_codiender",type="int",description="id do endereço da centralidade")
   *       )
   *     ),
   *
   *     @OA\Response(response="201", description="Centralidade atualizada com sucesso"),
   *     @OA\Response(response="412", description="Erro ao validar os dados"),
   *     @OA\Response(response="500", description="Erro no servidor")
   * )
   */
  public function update(Request $req, $id)
  {
    try {
      $centralidade = Centralidade::find($id);
      if (!$centralidade)
        return response()->json(['message' => 'centralidade não encontrada'], 404);

      $centralidade->update($req->all());

      return response()->json($req->all());
    } catch (\Illuminate\Database\QueryException $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }
  /**
   * @OA\Get(
   *     tags={"/centralidades"},
   *     path="/centralidades/{centralidade}",
   *     summary="mostrar uma centralidade",
   *     security={{ "bearerAuth": {}}},
   *     @OA\Parameter(
   *         name="centralidade",
   *         in="path",
   *         description="id da centralidade",
   *         required=false,
   *         @OA\Schema(type="int")
   *     ),
   *     @OA\Response(response="200", description="sucesso"),
   *     @OA\Response(response="404", description="centralidade não encontrada"),
   *     @OA\Response(response="500", description="Erro no servidor")
   * )
   */
  public function getOne($id)
  {
    try {
      $centralidade = Centralidade::find($id);
      if (!$centralidade)
        return response()->json(['message' => 'centralidade não encontrada'], 404);

      return response()->json($centralidade, 200);
    } catch (\Illuminate\Database\QueryException $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }
}
