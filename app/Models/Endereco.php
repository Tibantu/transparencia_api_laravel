<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    protected $table = "traender";
    protected $primaryKey = "n_codiender";
    public $timestamps = false;
    protected $fillable = [
        'n_codiender',
        'c_paisender',
        'c_descender',
        'c_provender',
        'c_muniender',
        'c_bairender'
    ];
    use HasFactory;
}
