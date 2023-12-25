<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caixa extends Model
{
    protected $table = "tracaixa";
    public $timestamps = false;
    protected $primaryKey = 'n_codicaixa';
    protected $fillable = [
        'n_saldcaixa',
        'n_limicaixa',
        'create_at',
        'updated_at',
        'c_nomeentid',
        'n_codientid',
    ];

use HasFactory;
}