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
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="idApartamento",
 *         in="path",
 *         description="ID do apartamento onde será registrado o morador",
 *         required=false,
 *         @OA\Schema(type="int")
 *     ),
 *     @OA\RequestBody(
 *       required=false,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *               type="object",
 *               @OA\Property(property="nome", type="string", example="João", description="Nome do morador"),
 *               @OA\Property(property="apelido", type="string", example="Silva", description="Apelido do morador"),
 *               @OA\Property(property="bi", type="string", example="123456789", description="BI do morador"),
 *               @OA\Property(property="foto", type="string", format="binary", description="Foto do morador"),
 *               @OA\Property(property="telefone", type="string", example="+244922292854", description="Telefone do morador"),
 *               @OA\Property(property="data_nascimento", type="string", format="date", example="1990-05-20", description="Data de nascimento do morador"),
 *               @OA\Property(property="genero", type="string", example="Masculino", description="Gênero do morador"),
 *               @OA\Property(property="estado_civil", type="string", example="Solteiro", description="Estado civil do morador"),
 *               @OA\Property(property="nacionalidade", type="string", example="Angolana", description="Nacionalidade do morador"),
 *               @OA\Property(property="identificacao_pessoal", type="string", example="123456789012", description="Identificação pessoal do morador"),
 *               @OA\Property(property="data_entrada", type="string", format="date", example="2022-01-15", description="Data de entrada do morador"),
 *               @OA\Property(property="login", type="string", example="joao123", description="Login do usuário"),
 *               @OA\Property(property="email", type="string", example="joao.silva@exemplo.com", description="E-mail do usuário"),
 *               @OA\Property(property="password", type="string", example="senha123", description="Senha do usuário")
 *             )
 *         )
 *     ),
 *     @OA\Response(response="201", description="Morador cadastrado com sucesso"),
 *     @OA\Response(response="412", description="Erro ao validar os dados"),
 *     @OA\Response(response="404", description="Apartamento não encontrado"),
 *     @OA\Response(response="405", description="Apartamento ocupado"),
 *     @OA\Response(response="500", description="Erro no servidor")
 * )
 */




 public function create(Request $req, $idAparta)
 {
     $isValidData = Validator::make($req->all(), [
         "nome" => 'required|string',
         "apelido" => 'required|string',
         "bi" => 'nullable|string',
         // Dados do usuário
         "login" => 'required|string',
         "email" => 'required|email',
         "password" => 'required|string',
         // Dados adicionais
         "foto" => 'nullable|string', // Foto do morador
         "telefone" => 'nullable|string', // Telefone do morador
         "data_nascimento" => 'nullable|date', // Data de nascimento do morador
         "genero" => 'nullable|string', // Gênero do morador
         "estado_civil" => 'nullable|string', // Estado civil
         "nacionalidade" => 'nullable|string', // Nacionalidade
         "identificacao_pessoal" => 'nullable|string', // Identificação pessoal
         "data_entrada" => 'nullable|date' // Data de entrada do morador
     ]);

     if ($isValidData->fails()) {
         return response()->json(['erros' => $isValidData->errors(), 'message' => 'Erro ao validar os dados'], 412);
     }

     try {
         $predio = $this->getPredio(); // Predio::with('apartamentos.moradores')->find($idPredio);
         if(!$predio) {
             return response()->json(['message' => 'Você não é coordenador do prédio'], 404);
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

         if(is_null($aparta)) {
             return response()->json(['message' => 'Apartamento não encontrado'], 404);
         }

         // Verificar se o apartamento está ocupado
         if($aparta->n_codimorad != null) {
             return response()->json(['message' => 'Apartamento ocupado'], 404);
         }

         // Dados do morador com novos campos
         $dadosMorad = [
             'c_nomemorad' => $req->nome,
             'c_apelmorad' => $req->apelido,
             'c_bilhmorad' => $req->bi,
             'c_fotomorad' => $req->foto,
             'c_telefone' => $req->telefone,
             'd_datnmorad' => $req->data_nascimento,
             'c_generomorad' => $req->genero,
             'c_estcmorad' => $req->estado_civil,
             'c_nacionalidademorad' => $req->nacionalidade,
             'c_identificacaomorad' => $req->identificacao_pessoal,
             'd_entrada' => $req->data_entrada
         ];

         // Criar o morador
         $morador = Morador::create($dadosMorad);

         if(!$morador) {
             return response()->json(['message' => "Morador não criado"], 401);
         }

         // Criar usuário do morador
         $usuario = UserController::criar($req->login, $req->password, $morador->n_codimorad, $req->email);
         if(!$usuario) {
             return response()->json(['message' => "Usuário não criado"], 401);
         }

         // Atribuir o apartamento ao morador
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
