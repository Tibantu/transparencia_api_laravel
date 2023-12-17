<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxa extends Model
{
    protected $table = 'trataxa';
    protected $primaryKey = 'n_coditaxa';
    public $timestamps = false;
    protected $fillable = [
        'n_coditaxa',
        'c_desctaxa',
        'n_valotaxa',
        'n_vmultaxa',
        'n_permtaxa',
        'n_diaetaxa',
        'create_at',
        'updated_at',
        'd_dacrtaxa',
        'd_denvtaxa',
        'c_freqtaxa',
        'n_praztaxa',
        'c_constaxa',
        'n_codicoord',
        ];

    use HasFactory;
}
