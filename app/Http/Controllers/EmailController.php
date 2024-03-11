<?php

namespace App\Http\Controllers;

use App\Mail\EmailTransparencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
  public function enviaEmailDeBoasVindasl()
  {
      $title = 'Bem vindo ao transparencia';
      $body = 'Obrigado por usar os nossos serviços!';

      Mail::to('admiroalfredo1742@gmail.com')->send(new EmailTransparencia($title, $body));

      return "Email sent successfully!";
  }
}
