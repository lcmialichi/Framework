<?php

namespace Source\Exception;

trait ErrorTrait{

    /**
     * Constroi o erro da classe informando o nome da classe
     *
     * @param [type] $message
     */
    public function __construct($message)
    {
        $code = HTTPStatus::INTERNAL_SERVER_ERROR;
        parent::__construct("[ " . __CLASS__ ." ] ". $message, $code->value);
    }

    public static function invalidField( string $field ) : self
    {
        $code = HTTPStatus::BAD_REQUEST;
        http_response_code($code->value);
        return new static("[ " . __CLASS__ . " ] campo '$field' possui valor invalido de entrada", $code->value);
    }

}