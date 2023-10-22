<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centralidade extends Model
{
    protected $table = 'tracentr';
    public $timestamps = false;
    protected $fillable = ['n_codicentr', 'c_desccentr', 'n_nblocentr', 'n_codicoord', 'n_codiender', 'n_codiadmin'];

    use HasFactory;
}
