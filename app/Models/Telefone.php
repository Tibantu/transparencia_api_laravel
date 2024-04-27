<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telefone extends Model
{
  protected $table = "tratelef";
  protected $primaryKey = 'n_coditelef';
  public $timestamps = false;
  protected $fillable = [
    'n_coditelef',
    'c_numetelef',
    'c_emaitelef',
    'n_codientid',
    'c_nomeentid',
    'c_numatelef'
  ];

    use HasFactory;
}
