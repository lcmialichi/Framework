<?php

namespace Source\Enum;

enum Gender : string{

    case MASCULINO = "M"; 
    case FEMININO = "F"; 
    case INDEFINIDO = "I";
    
    public static function casesValue()
    {
        return array_column(self::cases(), 'value');
    }
}