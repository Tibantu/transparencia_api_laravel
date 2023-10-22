<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $table = "todo";

    protected $fillable = ['id', 'titulo', 'descricao', 'data', 'created_at'];
    use HasFactory;
}
