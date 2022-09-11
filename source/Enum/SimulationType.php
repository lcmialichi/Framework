<?php

namespace Source\Enum;

/**
 *  Tipos de simulaçoes que podem ser feitas (APIGATEWAY)
 *  - Os Ids sao referentes aos convenios do banco de dados
 */
enum SimulationType : int {
    
    case POR_QUANTIDADE_DE_PARCELAS = 1;
    case POR_VALOR_SOLICITADO = 2;
    case POR_VALOR_TOTAL = 3;

    public static function casesValue()
    {
        $name =  array_column(self::cases(), 'name');
        $values = array_column(self::cases(), 'value');
        $cases = [];

        for($index = 0; $index < count($name); $index++){
            $cases[] = "({$values[$index]})"  . ": " . "'$name[$index]'";
        }
        return $cases;
    }

}

