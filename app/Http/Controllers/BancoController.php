<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\Coordenador;
use App\Models\Predio;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class BancoController extends Controller
{
    /**
    * @OA\Get(
        *     tags={"bancos"},
        *     path="/bancos",
        *     summary="listar bancos",
        *     security={{"bearerAuth": {} }},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
*/
    public function getAll()
    {
        try {
            $user = auth()->user();
            $bancos = null;

            if ($user->c_nomeentid == 'tracoord' && $user->n_codientid != null) {
              $coordenador = Coordenador::find($user->n_codientid);
              if (!$coordenador)
                  return $data = response()->json(['message' => 'Coordenador nao encontrado'], 404);

                  $bancos = $coordenador->bancos;

            }

            if (!$bancos)
                  return response()->json(['message' => 'nao autorizado'], 404);
            return response()->json(['bancos' => $bancos], 200);

        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
    * @OA\Post(
        *     tags={"bancos"},
        *     path="/bancos",
        *     summary="Cadastrar bancos",
        *     description="cadastrar bancos normalmente pertence a um coordenador",
        *     security={{"bearerAuth": {} }},
        *     @OA\RequestBody(
        *       required=true,
        *         description="Cria banco para coordenador",
        *         @OA\MediaType(
        *             mediaType="multipart/form-data",
        *             @OA\Schema(ref="#/components/schemas/Banco")
        *         )
        *     ),
        *
        *     @OA\Response(response="201", description="Banco cadastrado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="500", description="Validation errors")
        * )
     */
    public function create(Request $req)
    {
        $isValidData = Validator::make($req->all(),
        [
            'banco' => 'required|string'
        ]);
        if ($isValidData->fails())
            return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 412);

    try {
        $user = auth()->user();

        if ($user->c_nomeentid == 'tracoord' && $user->n_codientid != null) {
          $coordenador = Coordenador::find($user->n_codientid);
        }
        if (!$coordenador)
        return $data = response()->json(['message' => 'Coordenador nao encontrado'], 404);

        Banco::create(        [
          'c_entibanco' => $req->banco,
          'c_descbanco' => $req->descricao,
          'n_saldbanco' => $req->saldo,
          'n_codicoord' => $coordenador->n_codicoord
      ]);

        return response()->json(['message' => "Banco criado com sucesso!"], 201);;
    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

    /**
    * @OA\Delete(
        *     tags={"bancos"},
        *     path="/bancos/{banco}",
        *     summary="apagar um banco",
        *     security={{"bearerAuth": {} }},
        *       @OA\Parameter(
        *         name="banco",
        *         in="path",
        *         description="id do bancos",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="banco deletado com sucesso!"),
        *     @OA\Response(response="404", description="banco não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function delete($id)
    {
        try {
            $Banco = Banco::find($id);
            if (!$Banco)
                return response()->json(['message' => "Banco não encontrada"], 404);
            $Banco->delete();
            return response()->json(['message' => "Banco deletado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $Banco = Banco::find($id);
            if (!$Banco) {
                return response()->json(['message' => "Banco não encontrado."], 404);
            }
            $Banco->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
        /**
    * @OA\Get(
        *     tags={"bancos"},
        *     path="/bancos/{banco}",
        *     summary="mostrar um banco",
        *     security={{ "bearerAuth": {}}},
        *     @OA\Parameter(
        *         name="banco",
        *         in="path",
        *         description="id do banco",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="banco não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getOne($id)
    {
      $cachedBanco = null;
      try {

            $cachedBanco = Redis::get('banco_' . $id);
            if($cachedBanco) {

              $banco = json_decode($cachedBanco, FALSE);
              return response()->json([
                  'status_code' => 201,
                  'message' => 'Fetched from redis',
                  'banco' => $banco,
              ]);
            }else {
              $banco = Banco::find($id);
              if (!$banco)
                  return response()->json(['message' => "Banco não encontrado"], 404);
              else{
                  $bancoJson = json_encode($banco);
                  Redis::set('banco_' . $id, $bancoJson);
                }
            }

            } catch (QueryException $e) {
              return response()->json(['message' => $e->getMessage()], 500);
          }catch(\Exception $e){

          }finally{

            if(!$cachedBanco) {
                $banco = Banco::find($id);
                if (!$banco)
                  return response()->json(['message' => "Banco não encontrado"], 404);
                return response()->json([
                    'status_code' => 201,
                    'message' => 'Fetched from database',
                    'banco' => $banco,
              ]);
            }
          }
    }
}
