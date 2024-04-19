<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coordenador extends Model
{
    protected $table = 'tracoord';
    protected $primaryKey = 'n_codicoord';
    public $timestamps = false;
    protected $fillable = [
        'c_nomeentid',
        'n_codientid',
        'c_nomecoord',
        'c_apelcoord',
        'create_at',
        'updated_at',
        'd_dacrcoord',
        'd_daimcoord',
        'n_codimorad'
        ];


    public function despesas()
    {
        return $this->hasMany(Despesa::class, 'n_codicoord', 'n_codicoord');
    }
    public function predio()
    {
        /*if($this->c_nomeentid != 'trapredi'){
          return null;
        }*/
        return $this->belongsTo(Predio::class, 'n_codicoord', 'n_codicoord');
    }

    use HasFactory;
}
