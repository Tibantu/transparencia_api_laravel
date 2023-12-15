<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Morador extends Model
{
    protected $table = 'tramorad';
    protected $primaryKey = 'n_codimorad';
    public $timestamps = false;
    protected $fillable = [
        'n_codimorad',
        'c_nomemorad',
        'c_apelmorad',
        'create_at',
        'updated_at',
        'd_dacrmorad',
        'c_bilhmorad'
        ];

    use HasFactory;
}
