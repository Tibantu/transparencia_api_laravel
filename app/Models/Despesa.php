<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    

    protected $table = 'tradespe';
    protected $primaryKey = 'n_codidespe';
    public $timestamps = false;
    protected $fillable = [
        'n_codidespe',
        'n_codicoord',
        'n_valodespe',
        'create_at',
        'updated_at',
        'c_objedespe',
        'c_fontdespe',
        'd_dacrdespe',
        'd_dasadespe',   
        ];

    use HasFactory;
}

