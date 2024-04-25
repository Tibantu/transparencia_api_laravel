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

    public function morador()
    {
        return $this->belongsTo(Morador::class, 'n_codimorad', 'n_codimorad');
    }
    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class, 'n_codiapart', 'n_codiapart');
    }
    public function conta()
    {
        return $this->hasOne(Conta::class, 'n_codiconta', 'n_codiconta');
    }
    public function dividas()
    {
        return $this->hasMany(Divida::class, 'n_codiconta', 'n_codiconta');
    }
    use HasFactory;
}
