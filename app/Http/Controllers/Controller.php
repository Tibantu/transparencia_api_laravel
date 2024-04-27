<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;
/**
 * @OA\Server(url="http://localhost:8000/api"),
 * @OA\Info(title="Transparência API", version="0.1")
  * @OA\SecurityScheme(
 *     type="http",
 *     securityScheme="bearerAuth",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  /**
  *             @OA\Schema(
  *                     schema="Despesa",
  *                     title="Despesa",
  *                     required={"objectivo", "valor", "fonte", "data"},
  *                     @OA\Property(property="objectivo",type="string",description="objectivo da despesa",example="pagamento do jardineiro para para mes de abril"),
  *                     @OA\Property(property="valor",type="number",description="valores da despesa"),
  *                     @OA\Property(property="fonte",type="int",default="caixa" ,description="fonte dos valores", enum={"caixa","BFA","BAI","BIC","BPC","BCA","BIR","BNI","BPR","BDA","BMF","BPPH","SBA","BPA","BCI","BANC"}),
  *                     @OA\Property(property="data",type="string", format="date",description="data do saque dos valores")
  *             )
  */

    /**
  *             @OA\Schema(
  *                     schema="User",
  *                     title="User",
  *                     required={"login", "email", "password"},
  *                     @OA\Property(property="login", type="string", example="example_user", description="nome de acesso do usuário"),
  *                     @OA\Property(property="email", type="string", format="email", example="example@example.com", description="Email do usuário"),
  *                     @OA\Property(property="password", type="string", example="password123", description="Senha do usuário")
  *               )
  */

  /**
  *             @OA\Schema(
  *                     schema="Coordenador",
  *                     title="Coordenador",
  *                     required={"nome", "apelido", "descricao_do_predio", "entrada_do_predio", "login", "email", "password"},
  *                     @OA\Property(property="nome", type="string", example="Manuel Alfredo",description="nome do coordenador"),
  *                     @OA\Property(property="apelido", type="string", example="Tunguno",description="ultimo nome do coordenador"),
  *                     @OA\Property(property="login", type="string", example="example_user", description="nome de acesso do usuário"),
  *                     @OA\Property(property="email", type="string", format="email", example="example@example.com", description="Email do usuário"),
  *                     @OA\Property(property="password", type="string", example="password123", description="Senha do usuário"),
  *                     @OA\Property(property="descricao_do_bloco", type="string", example="K",description="identificacao do bloco, no caso de nao passares pela url (náo registrado)"),
  *                     @OA\Property(property="descricao_do_predio", type="string",example="A-22", description="identificacao do prédios"),
  *                     @OA\Property(property="entrada_do_predio", type="string", example="1", description="identificacao da entrada do predio, no caso de prédios gémios"),
  *               )
  */

  /**
  *             @OA\Schema(
  *                     schema="Predio",
  *                     title="Predio",
  *                     required={"descricao_do_predio", "entrada_do_predio"},
  *                     @OA\Property(property="descricao_do_predio", type="string", description="identificacao do prédios"),
  *                     @OA\Property(property="entrada_do_predio", type="string", example="A", description="identificacao da entrada do predio, no caso de prédios gémios"),
  *               )
  */

      /**
  *             @OA\Schema(
  *                     schema="Pagamento",
  *                     title="Pagamento",
  *                     required={"descricao", "valor", "forma_pagamemto", "data_pagamemto"},
  *                     @OA\Property(property="valor",type="number",description="valor do pagamento"),
  *                     @OA\Property(property="descricao",type="string",description="descrição do pagamento"),
  *                     @OA\Property(property="forma_pagamemto",type="string", enum={"transferência","cash"},description="forma de pagamento"),
  *                     @OA\Property(property="data_pagamemto",type="string", format="date",description="data de pagamento"),
  *                     @OA\Property(property="banco",type="string", enum={"BFA","BAI","BIC","BPC","BCA","BIR","BNI","BPR","BDA","BMF","BPPH","SBA","BPA","BCI","BANC"},description="denominacao do banco para onde transferio, se a forma de pagamento foi transferência"),
  *               )
  */

    /**
  *             @OA\Schema(
  *                     schema="Notificacao",
  *                     title="Notificacao",
  *                     required={"mensagem", "tipo"},
  *                     @OA\Property(property="mensagem", type="string", example="reuniao no data 20/05/2025"),
  *                     @OA\Property(property="tipo", type="string", enum={"aviso","geral","notificação"}, description="Tipo de notificação (valores permitidos: aviso, geral, notificação)"),
  *               )
  */

    /**
  *             @OA\Schema(
  *                     schema="Morador",
  *                     title="Morador",
  *                     required={"nome", "apelido", "login", "email", "password"},
  *                     @OA\Property(property="nome",type="string",description="nome do morador"),
  *                     @OA\Property(property="apelido",type="string",description="ultimo nome do morador"),
  *                     @OA\Property(property="bi",type="string",description="bilhete de identidade do morador"),
  *                     @OA\Property(property="login", type="string", example="example_user", description="Login do usuário"),
  *                     @OA\Property(property="email", type="string", format="email", example="example@example.com", description="Email do usuário"),
  *                     @OA\Property(property="password", type="string", example="password123", description="Senha do usuário")
  *               )
  */


    /**
  *             @OA\Schema(
  *                     schema="Funcionario",
  *                     title="Funcionario",
  *                     required={"nome", "apelido", "salario", "funcao","email", "telefone"},
  *                     @OA\Property(property="nome",type="string",example="Matias Amaral",description="nome do funcionario"),
  *                     @OA\Property(property="apelido",type="string",description="ultimo nome do funcionario"),
  *                     @OA\Property(property="salario",type="number",description="valor do salario mensal do funcionario em AKZ"),
  *                     @OA\Property(property="funcao", type="string", example="example_user", description="funcao do funcionario"),
  *                     @OA\Property(property="email", type="string", format="email", example="example@example.com", description="Email do funcionario"),
  *                     @OA\Property(property="telefone", type="string", example="92222956", description="telefone do funcionario"),
  *                     @OA\Property(property="telefone_alternativo", type="string", description="telefone alternativo do funcionario")
  *               )
  */

    /**
  *             @OA\Schema(
  *                     schema="Apartamento",
  *                     title="Apartamento",
  *                     required={"porta", "tipo"},
  *                     @OA\Property(property="porta",type="string",description="identificaçao da porta do apartamento", example="A-22"),
  *                     @OA\Property(property="tipo",type="string",enum={"T1","T2","T3","T4","T5","T6","T6","T7","T8"}, description="tipologia segundo a quantidade de quarto"),
  *                     @OA\Property(property="andar",type="integer",description="andar do prédio onde está o apartamento"),
  *             )
  */

      /**
  *             @OA\Schema(
  *                     schema="Banco",
  *                     title="Banco",
  *                     required={"banco"},
  *                     @OA\Property(property="descricao",type="string",default="",description="obs"),
  *                     @OA\Property(property="saldo",type="number",description="valores já existente no banco"),
  *                     @OA\Property(property="banco",type="number",description="entidade bancária a simular", enum={"BFA","BAI","BIC","BPC","BCA","BIR","BNI","BPR","BDA","BMF","BPPH","SBA","BPA","BCI","BANC"})
  *             )
  */

        /**
  *             @OA\Schema(
  *                     schema="Taxa",
  *                     title="Taxa",
  *                     required={"descricao","valor_taxa","data_envio","frequencia_envio","prazo"},
  *                     @OA\Property(property="descricao",type="string",description="descricão da taxa", example="manutensao das escadas"),
  *                     @OA\Property(property="valor_taxa",type="number",description="valor da taxa"),
  *                     @OA\Property(property="percentagem_valor_multa",type="integer",description="valor da multa, percentagem"),
  *                     @OA\Property(property="data_envio",type="string", format="date",description="data de início de envio da taxa"),
  *                     @OA\Property(property="frequencia_envio",type="string",description="frequência de envio da taxa", enum={"uma vez","quinzenalmente","mensalmente","trimestralmente","semestralmente","anualmente","semanalmente","diariamente","TESTE"}),
  *                     @OA\Property(property="prazo",type="integer", default="1",description="prazo em dias, para o pagamento da taxa"),
  *             )
  */
}
