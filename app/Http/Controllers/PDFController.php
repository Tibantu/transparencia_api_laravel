<?php


namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Divida;
use App\Models\Morador;
use App\Models\Pagamento;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;
use Illuminate\Database\QueryException;

class PDFController extends Controller
{
  /**
    * @OA\Get(
        *     tags={"/documentos"},
        *     path="/documentos/recibo",
        *     summary="stream do documento",
        *     security={{"bearerAuth": {} }},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="500", description="Erro no servidor"),
        * )
*/
    public function downloadPDF(){
      $data  = [
        'morador' => '$user->c_logiusuar',
        'data_pagamento' => '$pagamento->d_datapagam',
        'valor_pago' => '$pagamento->n_valopagam',
        'valor_pendente' => '$divida->n_vapedivid',
        'valor_da_divida' => '$divida->n_valtdivid',
        'descricao' => '$divida->c_descdivid',
        'id' => '$pagamento->n_codipagam'
        ];
              $pdf = FacadePdf::loadView('models_pdf.pdfrecibo01', ['data' => $data]);
        if(!$pdf)
            return response()->json(['message' => "Erro no servidor"], 500);
        //return $pdf->download();
        return $pdf->stream('transp-doc.pdf');
    }

  /**
   * @OA\Get(
   *     tags={"/documentos"},
   *     path="/documentos/pagamento/recibo/{idPagamento}",
   *     summary="mostrar pagamento",
   *     security={{ "bearerAuth": {}}},
   *     @OA\Parameter(
   *         name="idPagamento",
   *         in="path",
   *         description="id do pagamento",
   *         required=false,
   *         @OA\Schema(type="int")
   *     ),
   *     @OA\Response(response="200", description="sucesso"),
   *     @OA\Response(response="404", description="pagamento não encontrado"),
   *     @OA\Response(response="500", description="Erro no servidor")
   * )
   */
  public function getPagamentoPDF($id)
  {
    try {
      //apen3a1s p3ar3a mor3ador
      $user = auth()->user();
      //dd($user);
      if ($user->c_nomeentid == 'tramorad' && $user->n_codientid != null) {
          $morador = Morador::find($user->n_codientid);

          if(!$morador){
            return response()->json(['message' => "Morador não encontrado"], 404);
          }
          $apartamento = $morador->apartamento;
          $pagamentos = $apartamento->pagamentos;
          $pagamento_selecionado = null;
          foreach ($pagamentos as $pagamento) {
            if($pagamento->n_codipagam == $id){
              $pagamento_selecionado = $pagamento;
              break;
            }
          }

          if(!$pagamento_selecionado){
            return response()->json(['message' => "Pagamento não encontrado"], 404);
          }
          $divida = $pagamento_selecionado->divida;

          if(!$divida){
            return response()->json(['message' => "divida não encontrada"], 404);
          }
          $data  = [
            'morador' => $user->c_logiusuar,
            'data_pagamento' => $pagamento->d_datapagam,
            'valor_pago' => $pagamento->n_valopagam,
            'valor_pendente' => $divida->n_vapedivid,
            'valor_da_divida' => $divida->n_valtdivid,
            'descricao' => $divida->c_descdivid,
            'id' => $pagamento->n_codipagam
            ];

          if($pagamento_selecionado->n_estapagam != 1)
              return response()->json(['message' => "Pagamento não confirmado. Recibo não disponivel"], 500);
          $pdf = FacadePdf::loadView('models_pdf.pdfrecibo01', ['data' => $data]);
          if(!$pdf)
              return response()->json(['message' => "Erro no servidor"], 500);
          //return $pdf->download();
              return $pdf->stream('transp-doc.pdf');

      }
      return response()->json(['message' => "Erro ao carregar o arquivo"], 404);


    } catch (QueryException $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }
}
