<?php

namespace App\Policies;

use App\Models\Morador;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MoradorPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function verMorador(User $user, Morador $morador){
      return $user->n_codientid === $morador->n_codimorad;
    }
}
