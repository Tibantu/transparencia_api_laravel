<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Coordenador;
use App\Models\Morador;
use App\Models\Predio;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MoradorController extends Controller
{
  private function getPredio(){

    //dd(auth()->user());
    $user = auth()->user();

    $predio = null;
    if ($user->c_nomeentid == 'tracoord' && $user->n_codientid != null) {
      $coord = Coordenador::find($user->n_codientid);
      if(!$coord){
        return response()->json(['message' => "Coordenador não encontrado"], 404);
      }
      if($coord->c_nomeentid != 'trapredi'){
        return response()->json(['message' => "Não es coordenador do predio"], 404);
      }
      $predio = Predio::find($coord->n_codientid);
      if(!$predio){
        return response()->json(['message' => "Predio não encontrado"], 404);
      }
      //$apartamentos = $predio->apartamentos;
    }
    return $predio;
}

    /**
    * @OA\Get(
        *     tags={"moradores"},
        *     path="/moradores",
        *     summary="listar moradores de um predio",
        *     security={{ "bearerAuth": {}}},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="predio não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getAllByMoradores(/*$idPredio*/)
    {
        try {
          $predio = $this->getPredio();//Predio::with('apartamentos.moradores')->find($idPredio);
          if(!$predio){
            return response()->json(['message' => 'Não es coordenador do predio'], 404);
          }
          $moradores = $predio->apartamentos->filter(
            function($apartemento)
            {
              return !is_null($apartemento->n_codimorad);
            })->map(
              function($apartemento){
                return  $apartemento->morador;
              });

          return response()->json(['moradores' => $moradores], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

/**
    * @OA\Post(
        *     tags={"moradores"},
        *     path="/moradores/apartamento/{idApartamento}",
        *     summary="Registrar morador",
        *     security={{"bearerAuth": {} }},
        *     @OA\Parameter(
        *         name="idApartamento",
        *         in="path",
        *         description="id do apartamento onde sera registrado o morador",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\RequestBody(
        *       required=true,
        *         @OA\MediaType(
        *             mediaType="multipart/form-data",
        *             @OA\Schema(ref="#/components/schemas/Morador")
        *         )
        *     ),
        *
        *     @OA\Response(response="201", description="morador cadastrado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="404", description="apartamento não encontrado"),
        *     @OA\Response(response="405", description="apartamento oucupado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function create(Request $req, $idAparta)
    {
        $isValidData = Validator::make($req->all(), [
            "nome" => 'required|string',
            "apelido" => 'required|string',
            "bi" => 'string',
                  //dados do usuario
            "login" => 'required|string',
            "email" => 'required|email',
            "password" => 'required|string'
        ]);
        if ($isValidData->fails())
            return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 412);

    try {
      $predio = $this->getPredio();//Predio::with('apartamentos.moradores')->find($idPredio);
      if(!$predio){
        return response()->json(['message' => 'Não es coordenador do predio'], 404);
      }

        $apartamentos = $this->getPredio()->apartamentos;

        $aparta = null;
        foreach ($apartamentos as $apartamento) {
          // Verifica se o apartamento tem o ID procurado
          if ($apartamento->n_codiapart == $idAparta) {
                // Objeto encontrado
                $aparta = $apartamento;
                break;
            }
        }
        if(is_null($aparta))
              return response()->json(['message' => 'apartamento não encontrado'], 404);

        //antes de criar o morador verificar se o apartamento está oucupado
        if($aparta->n_codimorad != null)
            return response()->json(['message' => 'apartamento oucupado'], 404);
            $dadosMorad = [
              'c_nomemorad' => $req->nome,
              'c_apelmorad' => $req->apelido,
              'c_bilhmorad' => $req->bi
          ];

        $morador = Morador::create($dadosMorad);

        if(!$morador)
            return response()->json(['message' => "morador nao criado"], 401);
        //cria3r us1ua3rio do mor3ador
        $usuario = UserController::criar($req->login,$req->password,$morador->n_codimorad,$req->email);
        if(!$usuario)
            return response()->json(['message' => "Usuario nao criado"], 401);
        // atribuir o apartamento ao morador
        $aparta->n_codimorad = $morador->n_codimorad;
        $aparta->save();
        return response()->json(['message' => "Morador criado com sucesso!"], 201);
    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

 /**
    * @OA\Delete(
        *     tags={"moradores"},
        *     path="/moradores/{morador}",
        *     summary="apagar um morador",
        *       security={{"bearerAuth": {} }},
        *       @OA\Parameter(
        *         name="morador",
        *         in="path",
        *         description="id do morador",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="morador deletado com sucesso!"),
        *     @OA\Response(response="404", description="morador não encontrada"),
        *     @OA\Response(response="405", description="apartamento do morador não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function delete($id)
    {
        try {
            $Morador = Morador::find($id);
            if (!$Morador)
                return response()->json(['message' => "Morador não encontrado"], 404);

            $apartamento = Apartamento::find($Morador->n_codimorad);
            if(!$apartamento)
                return response()->json(['message' => "apartamento do morador não encontrado"], 405);

            $apartamento->n_codimorad = null;
            $apartamento->save();

            $Morador->delete();
            return response()->json(['message' => "Morador deletado com sucesso!"], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => "Hello " . $e->getMessage()], 500);
        }
    }
/**
    * @OA\Put(
        *     tags={"moradores"},
        *     path="/moradores/{morador}",
        *     summary="atualizar morador",
        *     security={{"bearerAuth": {} }},
        *     @OA\Parameter(
        *         name="morador",
        *         in="path",
        *         description="id do morador",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\RequestBody(
        *       required=true,
        *       @OA\JsonContent(
        *          type="object",
        *          @OA\Property(property="c_nomemorad",type="string",description="nome do morador"),
        *          @OA\Property(property="c_apelmorad",type="string",description="ultimo"),
        *          @OA\Property(property="n_codiapart",type="int",description="id do apartamento a vincular com o morador"),
        *          @OA\Property(property="c_bilhmorad",type="string",description="BI"),
        *       )
        *     ),
        *
        *     @OA\Response(response="201", description="morador atualizado com sucesso"),
        *     @OA\Response(response="412", description="Erro ao validar os dados"),
        *     @OA\Response(response="404", description="morador não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function update(Request $req, $id)
    {
        try {
            $Morador = Morador::find($id);
            if (!$Morador) {
                return response()->json(['message' => "Morador não encontrado."], 404);
            }
            $Morador->update($req->all());

            return response()->json($req->all());
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
/**
    * @OA\Get(
        *     tags={"moradores"},
        *     path="/moradores/{morador}",
        *     summary="mostrar morador",
        *     security={{ "bearerAuth": {}}},
        *     @OA\Parameter(
        *         name="morador",
        *         in="path",
        *         description="id do morador",
        *         required=false,
        *         @OA\Schema(type="int")
        *     ),
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="404", description="morador não encontrado"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
     */
    public function getOne($id)
    {
        try {
            $Morador = Morador::find($id);
            if (!$Morador) {
                return response()->json(['message' => "Morador não encontrado!"], 404);
            }
            return response()->json($Morador, 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

/**
    * @OA\Get(
        *     tags={"moradores"},
        *     path="/moradores/me",
        *     summary="dados do moradore logado",
        *     security={{"bearerAuth": {} }},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="500", description="Erro no servidor")
        * )
*/
    public function me(){
      $morador = null;
      $user = Auth::user();
        if ($user->c_nomeentid == 'tramorad' && $user->n_codientid != null) {
          $morador = Morador::where('n_codimorad', $user->n_codientid)->first();
          if (!$morador)
              return response()->json(['message' => "nao autorizado!"], 200);
        }
        return response()->json($morador, 200);
    }
}
