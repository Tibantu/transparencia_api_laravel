<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Divida;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DividaController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"/dividas"},
     *     path="/dividas",
     *     summary="listar dividas",
     *     security={{"bearerAuth": {} }},
     *     @OA\Response(response="200", description="sucesso"),
     *     @OA\Response(response="500", description="Erro no servidor")
     * )
     */
    public function getAll()
    {

        try {
                //$data = response()->json(['pagamentos' => Pagamento::all()], 200);
              $user = auth()->user();
              $data = response()->json(['dividas' => []], 200);

              if ($user->c_nomeentid == 'tramorad' && $user->n_codientid != null) {
                  $apartamento = Apartamento::where('n_codimorad', $user->n_codientid)->first();
                  if ($apartamento) {
                      $data = response()->json(['dividas' => Divida::where('n_codiconta', $apartamento->n_codiconta)->get()], 200);
                  }
              }

              return $data;

         } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    /**
     * @OA\Get(
     *     tags={"/dividas"},
     *     path="/dividas/apartamento/{idApartamento}",
     *     summary="mostrar divida do apartamento",
     *     security={{ "bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="apartamento",
     *         in="path",
     *         description="id do apartamento",
     *         required=false,
     *         @OA\Schema(type="int")
     *     ),
     *     @OA\Response(response="200", description="sucesso"),
     *     @OA\Response(response="404", description="apartamento não encontrado"),
     *     @OA\Response(response="500", description="Erro no servidor")
     * )
     */
    public function getAllByApartamento($idApartamento)
    {
        try {
            $apartamento = Apartamento::find($idApartamento);
            if (!$apartamento)
                return response()->json(['message' => "Apartamento não encontrado!"], 404);

            return Divida::where('n_codiconta', '=', $apartamento->n_codiconta)->get();
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function create(Request $req)
    {
        $isValidData = Validator::make(
            $req->all(),
            [
                'c_estadivid',
                'c_descdivid' => 'required|string',
                'n_muaddivid',
                'n_valtdivid',
                'n_valodivid' => 'required|string',
                'n_vapedivid',
                'n_vapadivid',
                'n_prazdivid',
                'd_dcomdivid',
                'd_dapadivid',
                'd_dacodivid',
                'd_dappdivid',
                'n_vmuldivid',
                'n_cododivid',
                'n_codicoord' => 'required|string',
                'n_codiconta' => 'required|string',
                'create_at',
                'updated_at',
                'd_dacrdivid',
            ]
        );
        if ($isValidData->fails())
            return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 400);

        try {
            Divida::create($req->all());
            // dd($data);
            return response()->json(['message' => "Divida criada com sucesso!"], 201);;
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $Divida = Divida::find($id);
            if (!$Divida)
                return response()->json(['message' => "Divida não encontrada"], 404);
            $Divida->delete();
            return response()->json(['message' => "Divida deletada com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
    public function update(Request $req, $id)
    {
        try {
            $Divida = Divida::find($id);
            if (!$Divida) {
                return response()->json(['message' => "Divida não encontrada."], 404);
            }
            $Divida->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    /**
     * @OA\Get(
     *     tags={"/dividas"},
     *     path="/dividas/{divida}",
     *     summary="mostrar divida",
     *     security={{ "bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="divida",
     *         in="path",
     *         description="id do divida",
     *         required=false,
     *         @OA\Schema(type="int")
     *     ),
     *     @OA\Response(response="200", description="sucesso"),
     *     @OA\Response(response="404", description="divida não encontrada"),
     *     @OA\Response(response="500", description="Erro no servidor")
     * )
     */
    public function getOne($id)
    {
        try {
            $Divida = Divida::find($id);
            if (!$Divida) {
                return response()->json(['message' => "Divida não encontrada!"], 404);
            }
            return response()->json($Divida, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
