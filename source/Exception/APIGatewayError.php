<?php

namespace Source\Exception;

class APIGatewayError extends \Error
{   
    public function __construct(string $message){

        $code = HTTPStatus::INTERNAL_SERVER_ERROR;
        parent::__construct("[APIGATEWAY] ". $message, $code->value);
    }
}