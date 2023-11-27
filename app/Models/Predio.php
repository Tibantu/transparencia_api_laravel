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

    use HasFactory;
}
