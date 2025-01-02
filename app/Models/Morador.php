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
        'd_dacrmorad',
        'c_bilhmorad',
        'd_datnmorad',            // Data de nascimento
        'c_fotomorad',            // Foto do morador
        'c_telefone',             // Telefone do morador
        'c_generomorad',          // Gênero do morador
        'c_estcmorad',            // Estado civil do morador
        'c_nacionalidademorad',   // Nacionalidade do morador
        'c_identificacaomorad',   // Identificação pessoal do morador
        'c_emailmorad',           // E-mail do morador
        'd_entrada',              // Data de entrada do morador
        'created_at',             // Data de criação
        'updated_at'
        ];

        public function apartamento()
        {
            return $this->belongsTo(Apartamento::class, 'n_codimorad', 'n_codimorad');
        }
/*   not     public function pagamentos()
        {
        return $this->hasMany(Pagamento::class, 'n_codiapart', 'n_codiapart');
        }
*/
        public function usuarios()
        {
            // Relacionamento para buscar os usuários associados a este morador
            return $this->hasMany(User::class, 'n_codientid', 'n_codimorad')->where('c_nomeentid', 'tramorad');
        }

    use HasFactory;
}
