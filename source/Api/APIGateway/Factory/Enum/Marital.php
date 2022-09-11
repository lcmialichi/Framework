<?php

namespace Source\Api\APIGateway\Factory\Enum;

use Source\Exception\APIGatewayException;

enum Marital : string {
    
    case Solteiro = "SOLTEIRO";
    case Casado = "CASADO";
    case SeparadoJudicialmente = "SEPARADO";
    case Divorciado = "DIVORCIADO";
    case Viuvo = "VIUVO";
    case UniaoEstavel = "UNIAO_ESTAVEL";

    /**
     * Retorna Estado civil do cliente invertido para ApiGateway
     */
    public static function get( string $marital ){
        $inverted = Marital::tryFrom($marital);

        if(is_null($inverted)){
            throw new APIGatewayException("Cliente possui estado civil com valor invalido para digitaçao!");
        }

        return $inverted->name;
    }
}