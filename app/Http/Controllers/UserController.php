<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Bloco;
use App\Models\Caixa;
use App\Models\Centralidade;
use App\Models\Coordenador;
use App\Models\Morador;
use App\Models\Predio;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
  public static function criar($login, $password, $n_codimorad, $email){
    $dadosUser = [
      'c_logiusuar' => $login,
      'c_senhusuar' => Hash::make($password),
      'n_codientid' => $n_codimorad,
      'c_nomeentid' => 'tramorad',
      'c_emaiusuar' => $email
  ];

    //criar usuario
    return User::create($dadosUser);
}
/**
 * @OA\Post(
 *     path="/auth/centr/{idCentr}/bloco/{idbloco}'",
 *     tags={"Users"},
 *     summary="Cria um coordenador e o prédio",
 *     security={{"bearerAuth": {} }},
 *     description="",
 *     operationId="createUser",
 *     @OA\Parameter(
 *        name="idCentr",
 *        in="path",
 *        description="id da centralidade",
 *        required=false,
 *        @OA\Schema(
 *            type="int"
 *        )
 *     ),
 *     @OA\Parameter(
 *        name="idbloco",
 *        in="path",
 *        description="id do bloco",
 *        required=false,
 *        @OA\Schema(
 *            type="int"
 *        )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Dados necessario",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(ref="#/components/schemas/Coordenador")
 *          )
 *     ),
 *     @OA\Response(response="201", description="Usuário criado com sucesso"),
 *     @OA\Response(response="412", description="Erro ao validar os dados"),
 *     @OA\Response(response="404", description="Apartamento não encontrado"),
 *     @OA\Response(response="500", description="Erro interno do servidor")
 * )
 */





    public function create(Request $req, $id_centralidade, $id_bloco)
    {
        $isValidData = Validator::make($req->all(), [
            //dados do usuario
            "login" => 'required|string',
            "email" => 'required|string',
            "password" => 'required|string',
            //dados do coord
            "nome" => 'required|string',
            "apelido" => 'required|string',
            //dados do predio
            "descricao_do_bloco" => 'max:6' ,
            //"id_bloco" => 'string', // vai no ; se for vasio criar um bloco na centralidade com a descricao_do_bloco
            //dados do predio
            "descricao_do_predio" => 'required|string|max:6',
            "entrada_do_predio" => 'required|string|max:6'
        ]);

        if ($isValidData->fails()) {
            return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 412);
        }

        try {
/* *///criar um bloco, apenas se id_bloco for vasio

          $bloco = null;

          if($req->descricao_do_bloco != null)
          {
                $centralidadde = Centralidade::find($id_centralidade);
                if (!$centralidadde)
                    return response()->json(['message' => "Centralidade não encontrada!"], 404);

              /*Criar um caixa para o bloco*/
                $dataCaixaBloco = [
                  'c_nomeentid'=>'trabloco'
                ];

                $caixaBloco = Caixa::create($dataCaixaBloco);

                    //criar o bloco
                $dadosBloco = [
                    'c_descbloco' => $req->descricao_do_bloco,
                    'n_codicentr' => $id_centralidade,
                    'n_codicaixa' => $caixaBloco->n_codicaixa
                ];

                $bloco = Bloco::create($dadosBloco);
                if (!$bloco)
                    return response()->json(['message' => "Bloco não encontrada!"], 404);

                $dataCaixaBloco['n_codientid'] = (int) $bloco->n_codibloco;
                $caixaBloco->update($dataCaixaBloco);
          }else{
            $bloco = Bloco::find($id_bloco);
          }

/* */

/* *///criar um predio

        if (!$bloco)
           return response()->json(['message' => "Bloco não encontrado!"], 404);

        /*Criar um caixa para o predio*/
        $dataCaixaPredi = [
          'c_nomeentid'=>'trapredi'
        ];
        $caixaPredi = Caixa::create($dataCaixaPredi);
        if(!$caixaPredi){
        return response()->json(['message' => "Erro ao criar predio"], 412);
        }

        $dadosPredi = [
          'c_entrpredi' => $req->entrada_do_predio,
          'c_descpredi' => $req->descricao_do_predio,
          'n_codicaixa' => $caixaPredi->n_codicaixa,
          'n_codibloco' => $bloco->n_codibloco
          //d3ado1s do predi
      ];


        $predio = Predio::create($dadosPredi);
        $caixaPredi['n_codientid'] = (int) $predio->n_codipredi;
        $caixaPredi->update($dataCaixaPredi);

/* */

/* *///criar um coord

            $dadosCoord = [
                'c_nomecoord' => $req->nome,
                'c_nomeentid' => 'trapredi',
                'n_codientid' => $predio->n_codipredi,
                'c_apelcoord' => $req->apelido
            ];
          $coord = Coordenador::create($dadosCoord);

          if(!$coord)
            return response()->json(['message' => "Erro ao criar o coordenador"], 412);

          $dadosPredi['n_codicoord'] = (int) $coord->n_codicoord;
          $predio->update($dadosPredi);

/* */

/* *///criar um usuario
            $dadosUser = [
              'c_logiusuar' => $req->login,
              'c_senhusuar' => Hash::make($req->password),
              'n_codientid' => $coord->n_codicoord,
              'c_nomeentid' => 'tracoord',
              'c_emaiusuar' => $req->email
          ];

            //criar usuario
            User::create($dadosUser);

            return response()->json(['message' => "usuario criado com sucesso!"], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    /**
 * @OA\Post(
 *     path="/auth/morador/usuario",
 *     tags={"Users"},
 *     summary="Cria um novo usuário para o moradror",
 *     security={{"bearerAuth": {} }},
 *     description="Cria um novo usuário para o morador com base nos dados fornecidos",
 *     operationId="createUser",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Dados do usuário a serem criados",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(ref="#/components/schemas/User")
 *         )
 *     ),
 *     @OA\Response(response="201", description="Usuário criado com sucesso"),
 *     @OA\Response(response="412", description="Erro ao validar os dados"),
 *     @OA\Response(response="404", description="Apartamento não encontrado"),
 *     @OA\Response(response="500", description="Erro interno do servidor")
 * )
 */
    public function create_morad(Request $req){

      $isValidData = Validator::make($req->all(), [
        //dados do usuario
        "login" => 'required|string',
        "email" => 'required|email',
        "password" => 'required|string'
    ]);

    if ($isValidData->fails()) {
        return response()->json(['erros' => $isValidData->errors(), 'message' => 'erro ao validar os dados'], 412);
    }
    $user = auth()->user();
    if(!$user)
          return response()->json(['message' => "nao autorizado"], 401);

    if($user->c_nomeentid != 'tramorad')
        return response()->json(['message' => "nao autorizado"], 401);

    try{
        $morad = Morador::find($user->n_codientid);

        if(!$morad){
          return response()->json(['message' => "morador nao encontrado"], 404);
        }

        $nUsuario = $morad->usuarios->count();
        /* *///criar um usuario
        if($nUsuario > 2){
          return response()->json(['message' => "Morador possui 3 usuarios. Número limite"], 400);
        }
         $dadosUser = [
          'c_logiusuar' => $req->login,
          'c_senhusuar' => Hash::make($req->password),
          'n_codientid' => $morad->n_codimorad,
          'c_nomeentid' => 'tramorad',
          'c_emaiusuar' => $req->email
      ];

        //criar usuario
        User::create($dadosUser);

        return response()->json(['message' => "usuario para morador criado com sucesso!"], 201);
    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
    }
}
