<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Http\Resources\TodoResource;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function getAll(){
        return Todo::all();
    }
    public function save (Request $req)  {
        $data = $req->all();

        $todo = Todo::create($data);

        return $todo;
    }
}
