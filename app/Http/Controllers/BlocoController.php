<?php

namespace App\Http\Controllers;

use App\Models\Bloco;
use App\Models\Todo;
use Illuminate\Http\Request;

class BlocoController extends Controller
{
    public function getAll(Request $request){
        return Bloco::all();
    }
}
