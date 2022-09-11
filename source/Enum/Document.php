<?php

namespace Source\Enum;

/**
 * INATIVO :) PQ USAMOS IDs DO BANCO DE DADOS
 */
enum Document : int {

    case RG = 1; 
    case CNH = 2;

    public static function casesName()
    {
        return array_column(self::cases(), 'name');
    }
}