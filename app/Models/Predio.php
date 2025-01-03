<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Predio extends Model
{
    protected $table = 'trapredi';
    protected $primaryKey = 'n_codipredi';
    public $timestamps = false;
    protected $fillable = [
        'n_codipredi',
        'c_entrpredi',
        'c_descpredi',
        'n_napapredi',
        'n_napopredi',
        'd_dacrpredi',
        'n_codicaixa',
        'n_codicoord',
        'n_codibloco'
    ];

    public function apartamentos()
    {
        return $this->hasMany(Apartamento::class, 'n_codipredi', 'n_codipredi');
    }
    public function caixa()
    {
        return $this->belongsTo(Caixa::class, 'n_codicaixa', 'n_codicaixa');
    }
    public function coordenador(){

      return $this->hasOne(Coordenador::class, 'n_codicoord', 'n_codicoord')->where('c_nomeentid', 'trapredi');
    }

    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class, 'n_codientid', 'n_codipredi');
    }

    use HasFactory;
}
