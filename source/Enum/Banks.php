<?php

namespace Source\Enum;

/**
 * enum referente aos bancos de digitaçao no api gateway
 * @todo colocar os ids certos
 */
enum Banks : int {
    case SAFRA = 1;
    case PAN = 2;
    case C6BANK = 3;
    case NAO_REMOVE_PHPUNIT = 20000;

    public function getName()
    {
        return strtolower($this->name);
    }
}   