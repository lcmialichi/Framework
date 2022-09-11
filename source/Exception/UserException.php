<?php

namespace Source\Exception;

use Source\Exception\HTTPStatus;
use Source\Log\Log; # vale a utilizaçao aqui?

/**
 * Exceptions do usuario
 */
class UserException extends \Exception {

    use ExceptionTrait;
    
    public static function invalidUser() : self
    {
       $code = HTTPStatus::UNAUTHORIZED;
       http_response_code($code->value);
       return new static("Usuario ou senha invalidos", $code->value);

    }

}