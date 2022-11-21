<?php

namespace Source\Enum;

/**
 * Enumns referentes ao produtos (APIGATEWAY)
 * Esses Enumns referenciam a factory, pense muito bem antes de fazer cagada
 * - Os Ids sao referentes aos convenios do banco de dados
 * @method static Type tryFrom(int $id)
 */
enum Product : int{
    case FGTS = 1;
    case CONSIGNADO = 2;
    case CARTAO = 3;
    case AUXILIO_BRASIL = 4;
    case CARTAO_BENEFICIO = 5;
    
    public function getName()
    {
        return strtolower($this->name);
    }
    
}