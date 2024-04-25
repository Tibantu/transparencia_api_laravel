<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{

  protected $table = "tranotif";
  protected $primaryKey = 'n_codinotif';
  public $timestamps = false;
  protected $fillable = [
  'n_codinotif',
  'c_descnotif',
  'c_tiponotif',
  'create_at',
  'updated_at',
  'd_dacrnotif',
  'n_codiapart'

  ];
  public function apartamentos()
  {
      return $this->belongsToMany(Apartamento::class, 'apartamento_notificacao', 'n_codinotif', 'n_codiapart');
  }

  use HasFactory;
}
