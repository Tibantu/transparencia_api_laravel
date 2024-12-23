<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    protected $table = 'trapagam';
    protected $primaryKey = 'n_codipagam';
    public $timestamps = false;
    protected $fillable = [
        'n_codipagam',
        'n_valopagam',
        'n_vadipagam',
        'c_descpagam',
        'c_formpagam',
        'd_datapagam',
        'd_dacrpagam',
        'create_at',
        'updated_at',
        'd_dacopagam',
        'c_bancpagam',
        'n_codibanco',
        'n_estapagam',
        'n_codicoord',
        'n_codidivid',
        'n_codiapart'
        ];

        public function apartamento()
        {
            return $this->belongsTo(Apartamento::class, 'n_codiapart', 'n_codiapart');
        }
        public function divida()
        {
            return $this->hasOne(Divida::class, 'n_codidivid', 'n_codidivid');
        }
        public function morador()
        {
            return $this->belongsTo(Morador::class, 'n_codimorad', 'n_codimorad');
        }

    use HasFactory;
}
