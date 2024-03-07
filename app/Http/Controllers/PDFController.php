<?php


namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;


class PDFController extends Controller
{
    public function downloadPDF(){
        $data  = [
            [
                'quantity' => 1,
                'description' => '1 ano de descriacao',
                'price' => 500.00
            ]
                ];
        $pdf = FacadePdf::loadView('models_pdf.pdfR1', ['data' => $data]);
        //return $pdf->download();
        return $pdf->stream();
    }

}
