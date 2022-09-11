<?php

namespace Source\Enum;

enum Nationality : string{

    case BRASILEIRO = "BRASILEIRO"; 
    case ESTRANGEIRO = "ESTRANGEIRO"; 
    
    public static function casesValue()
    {
        return array_column(self::cases(), 'value');
    }
}
