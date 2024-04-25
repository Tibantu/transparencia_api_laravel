<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telefone extends Model
{
  protected $table = "tratelef";
  protected $primaryKey = 'n_codifunci';
  public $timestamps = false;
  protected $fillable = [
    'n_codifunci',
    'c_nomefunci',
     'c_apelfunci',
     'create_at',
     'updated_at',
     'c_actifunci',
     'n_salafunci',
     'd_dacrfunci',
     'n_codientid',
     'c_nomeentid'
  ];

    use HasFactory;
}
