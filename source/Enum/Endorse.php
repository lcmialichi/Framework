<?php

namespace Source\Enum;
/**
 * Enum para validar se a conta bancária é de averbação ou não.
 */
enum Endorse : int{

    case TRUE = 1; 
    case FALSE = 0; 

    public static function casesName()
    {
        return array_map(function ($bool){
            return strtolower($bool);
        },array_column(self::cases(), 'name'));
    } 
}