<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloco extends Model
{
    protected $table = "trabloco";

    protected $fillable = ['n_codibloco', 'c_descbloco'];
    use HasFactory;
}
