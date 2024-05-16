<?php

namespace App\Utils;

class MyUtils
{
    public static function isCoordPredi($user)
    {
      if ($user->c_nomeentid == 'tracoord' && $user->c_tipocoord == 'trapredi') {
          // O usuário é do tipo "tracoord" de trapredi
          return true;
      } else {
          // O usuário não é do tipo "tracoord" de trapredi
          return false;
      }
    }

    public static function isCoordBloco($user)
    {
      if ($user->c_nomeentid == 'tracoord' && $user->c_tipocoord == 'trabloco') {
          // O usuário é do tipo "tracoord" de trabloco
          return true;
      } else {
          // O usuário não é do tipo "tracoord" de trabloco
          return false;
      }
    }

    public static function isMorad($user)
    {
        if ($user->c_nomeentid == 'tramorad') {
            // O usuário é do tipo "tramorad"
            return true;
        } else {
            // O usuário não é do tipo "tramorad"
            return false;
        }
    }
}
