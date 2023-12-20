<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divida extends Model
{

    protected $table = 'tradivid';
    protected $primaryKey = 'n_codidivid';
    public $timestamps = false;
    protected $fillable = [
        'n_codidivid',
        'c_estadivid',
        'c_descdivid',
        'n_muaddivid',
        'n_valtdivid',
        'n_valodivid',
        'n_vapedivid',
        'n_vapadivid',
        'n_prazdivid',
        'd_dcomdivid',
        'd_dapadivid',
        'd_dacodivid',
        'd_dappdivid',
        'n_vmuldivid',
        'n_cododivid',
        'n_codicoord',
        'n_codiconta',
        'create_at',
        'updated_at',
        'd_dacrdivid'   
        ];

    use HasFactory;
}