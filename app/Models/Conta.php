<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    protected $table = "traconta";
    public $timestamps = false;
    protected $primaryKey = 'n_codiconta';
    protected $fillable = [
        'n_saldconta',
        'create_at',
        'updated_at',
        'n_diviconta',
        'n_limiconta'
    ];

    public function apartamento()
    {
        return $this->belongsTo(Apartamento::class, 'n_codiconta', 'n_codiconta');
    }
use HasFactory;
}
