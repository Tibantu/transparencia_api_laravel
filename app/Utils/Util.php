<?php

namespace App\Utils;

use Exception;

class Util
{
  static function getMessage(int $code): string
  {
    $message = "";
    switch ($code) {
      case 23000:
        $message = "A centralidade já existe!";
        break;
    }
    return $message;
  }
  static function validarData(string $data)
  {
    try {
      new \DateTime($data);
      return true;
    } catch (Exception $e) {
      return false;
    }
  }
}
