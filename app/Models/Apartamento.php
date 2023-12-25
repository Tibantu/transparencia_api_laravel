<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartamento extends Model
{
    protected $table = "traapart";
    public $timestamps = false;
    protected $primaryKey = 'n_codiapart';

    protected $fillable = [
        'n_codiapart',
        'c_portapart',
        'c_tipoapart', 
        'n_nandapart', 
        'd_dacrapart', 
        'n_codiconta', 
        'n_codipredi', 
        'n_codimorad'
    ];
    use HasFactory;
}
