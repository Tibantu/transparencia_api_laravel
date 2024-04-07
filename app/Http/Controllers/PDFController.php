<?php


namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;


class PDFController extends Controller
{
  /**
    * @OA\Get(
        *     tags={"/documentos"},
        *     path="/documentos",
        *     summary="stream do documento",
        *     security={{"bearerAuth": {} }},
        *     @OA\Response(response="200", description="sucesso"),
        *     @OA\Response(response="500", description="Erro no servidor"),
        * )
*/
    public function downloadPDF(){
        $data  = [
                'morador' => 'Joao Matala',
                'Predio' => '1-A50',
                'Apartamento' => '5A'
                ];
              $pdf = FacadePdf::loadView('models_pdf.pdfrecibo01', ['data' => $data]);
        if(!$pdf)
            return response()->json(['message' => "Erro no servidor"], 500);
        //return $pdf->download();
        return $pdf->stream('transp-doc.pdf');
    }

}
