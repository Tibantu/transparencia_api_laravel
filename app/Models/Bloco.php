<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloco extends Model
{
    protected $table = "trabloco";
    public $timestamps = false;
    protected $primaryKey = 'n_codibloco';
    protected $fillable = ['n_codibloco', 'c_descbloco', 'n_nblocentr', 'n_codicoord', 'n_codicaixa', 'c_ruablco', 'n_codicentr'];
    use HasFactory;
}

