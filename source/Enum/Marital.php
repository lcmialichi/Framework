<?php

namespace Source\Enum;

enum Marital : string{

    case SOLTEIRO = "SOLTEIRO"; 
    case CASADO = "CASADO"; 
    case SEPARADO = "SEPARADO";
    case DIVORCIADO = "DIVORCIADO";
    case VIUVO = "VIUVO";
    case UNIAO_ESTAVEL = "UNIAO_ESTAVEL";
    
    public static function casesValue()
    {
        return array_column(self::cases(), 'value');
    }
}