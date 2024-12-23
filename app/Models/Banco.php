<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $table = "trabanco";
    public $timestamps = false;
    protected $primaryKey = 'n_codibanco';
    protected $fillable = [
        'n_codibanco',
        'c_entibanco',
        'c_descbanco',
        'n_saldbanco',
        'd_dacrbanco',
        'n_codicoord',
        'n_codientid',
        'c_nomeentid',
        'create_at',
        'updated_at'
    ];
    public function coordenador()
    {
        return $this->belongsTo(Predio::class, 'n_codicoord', 'n_codicoord');
    }
use HasFactory;
}
