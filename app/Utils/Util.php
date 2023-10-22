<?php

namespace App\Utils;

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
}
