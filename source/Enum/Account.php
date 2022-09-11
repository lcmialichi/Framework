<?php

namespace Source\Enum;

enum Account : string{

    case CONTA_POUPANCA_INDIVIDUAL = 'CPI'; 
    case CONTA_POUPANCA_CONJUNTA = "CPC"; 
    case CONTA_CORRENTE_CONJUNTA = "CCC"; 
    case CONTA_CORRENTE_INDIVIDUAL = "CCI"; 
    case CONTA_INVESTIMENTO = "CI";
    case CONTA_SALARIO = "CS"; 
    
    public static function casesValue()
    {
        return array_column(self::cases(), 'value');
    }
}