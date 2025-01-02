<?php

namespace App\Http\Controllers;

use App\Models\Centralidade;
use App\Models\Endereco;
use Illuminate\Support\Facades\DB;
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
   *     tags={"centralidades"},
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
 *     tags={"centralidades"},
 *     path="/centralidades",
 *     summary="Cadastrar centralidade",
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *       required=false,
 *       @OA\JsonContent(
 *          type="object",
 *          @OA\Property(property="c_desccentr", type="string", default="Centralidade B", description="Denominação da centralidade"),
 *          @OA\Property(property="c_paisender", type="string", example="Angola", description="País do endereço"),
 *          @OA\Property(property="c_descender", type="string", example="Descrição detalhada do endereço", description="Descrição do endereço"),
 *          @OA\Property(property="c_provender", type="string", example="Luanda", description="Província do endereço"),
 *          @OA\Property(property="c_muniender", type="string", example="Viana", description="Município do endereço"),
 *          @OA\Property(property="c_bairender", type="string", example="Zango", description="Bairro do endereço")
 *       )
 *     ),
 *     @OA\Response(response="201", description="Centralidade cadastrada com sucesso"),
 *     @OA\Response(response="412", description="Erro ao validar os dados"),
 *     @OA\Response(response="500", description="Erro interno no servidor")
 * )
 */



 public function create(Request $req)
 {
     // Apenas o sistema pode usar esta rota
     // return response()->json(['message' => 'APENAS O SISTEMA PODE USAR ESTA ROTA'], 404);

     /*
         TODO:
         - Criar o endereço.
         - Pegar o ID do endereço criado.
         - Usar o ID para criar a centralidade.
     */

     // Validação dos dados do endereço
     $enderecoValidator = Validator::make($req->only([
         "c_paisender", "c_descender", "c_provender", "c_muniender", "c_bairender"
     ]), [
         "c_paisender" => 'string|min:2',
         "c_descender" => 'string|max:100',
         "c_provender" => 'string|max:14',
         "c_muniender" => 'required|string|max:15',
         "c_bairender" => 'string|max:15'
     ]);

     if ($enderecoValidator->fails()) {
         return response()->json([
             'errors' => $enderecoValidator->errors(),
             'message' => 'Erro ao validar os dados do endereço'
         ], 412);
     }

     // Validação dos dados da centralidade
     $centralidadeValidator = Validator::make($req->only([
         "c_desccentr"
     ]), [
         "c_desccentr" => 'required|string|max:50'
     ]);

     if ($centralidadeValidator->fails()) {
         return response()->json([
             'errors' => $centralidadeValidator->errors(),
             'message' => 'Erro ao validar os dados da centralidade'
         ], 412);
     }

     DB::beginTransaction();  // Inicia a transação

     try {
         // Criar o endereço
         $endereco = Endereco::create($req->only([
             "c_paisender", "c_descender", "c_provender", "c_muniender", "c_bairender"
         ]));

         // Criar a centralidade com o ID do endereço criado
         $centralidadeData = $req->only(["c_desccentr", "n_codicoord"]);
         $centralidadeData["n_codiender"] = $endereco->n_codiender;

         // Criar a centralidade
         Centralidade::create($centralidadeData);

         DB::commit();  // Comita a transação se tudo ocorrer bem

         return response()->json([
             'message' => "Centralidade criada com sucesso!",
             'endereco' => $endereco,
             'centralidade' => $centralidadeData
         ], 201);

     } catch (\Exception $e) {
         DB::rollBack();  // Reverte a transação se houver erro

         // Apagar o endereço se a criação da centralidade falhar
         if ($endereco) {
             $endereco->delete();
         }

         return response()->json(['message' => $e->getMessage()], 500);
     }
 }



  /**
   * @OA\Delete(
   *     tags={"centralidades"},
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
    //APENAS O SISTEMA PODE USAR ESTA ROTA
   // return response()->json(['message' => 'APENAS O SISTEMA PODE USAR ESTA ROTA'], 404);
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
   *     tags={"centralidades"},
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
        //APENAS O SISTEMA PODE USAR ESTA ROTA
     //   return response()->json(['message' => 'APENAS O SISTEMA PODE USAR ESTA ROTA'], 404);
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
   *     tags={"centralidades"},
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
