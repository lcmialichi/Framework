<?php

namespace Source\Enum;

/**
 * Enum referente aos convenios de digitaçao (APIGATEWAY)
 *  - Os Ids sao referentes aos convenios do banco de dados
 * @todo colocar os ids certos, pois na existem cadastrados no banco
 */
enum Convenant : int {
    case FGTS = 1;
    case CONSIGNADO = 2;

}