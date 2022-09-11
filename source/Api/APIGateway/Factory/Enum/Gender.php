<?php

namespace Source\Api\APIGateway\Factory\Enum;

use Source\Exception\APIGatewayException;

enum Gender : string {
    
    case Masculino = "M";
    case Feminino = "F";

      /**
     * Retorna Estado civil do cliente invertido para ApiGateway
     */
    public static function get( string $gender ){
        $inverted = Gender::tryFrom($gender);

        if(is_null($inverted)){
            throw new APIGatewayException("Cliente possui genero com valor invalido para digitaçao: '$gender' ");
        }

        return $inverted->name;
    }

}