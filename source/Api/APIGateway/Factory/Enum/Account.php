<?php

namespace Source\Api\APIGateway\Factory\Enum;

use Source\Exception\APIGatewayException;

enum Account : string{

    case ContaCorrenteIndividual = "CCI";
    case ContaPoupancaIndividual = "CPI";
    case ContaSalario = "CS";
    case ContaCorrenteConjunta = "CCC";
    case ContaPoupancaConjunta = "CPC";
    case ContaInvestimento = "CI";

    /**
     * Retorna tipo de conta invertido para ApiGateway
     */
    public static function get( string $account ){
        $inverted = Account::tryFrom($account);

        if(is_null($inverted)){
            throw new APIGatewayException("Cliente possui tipo de conta invalido para digitaçao: '$account' ");
        }

        return $inverted->name;
    }
}