<?php

namespace Source\Service\ValueObject;

abstract class Validation{

    /**
     * Validador de data
     * @return bool
     */
    protected function date( mixed $date ): bool
    {
        if(!\DateTime::createFromFormat("Y-m-d", $date)){
            return false;
        }
        $itens = explode("-", $date);
        if(!checkdate($itens[1], $itens[2], $itens[0])){
            return false;
        }
        
        return true;
    }

    /**
     * Validador de campos vazios
     * @return bool
     */
    protected function nullEnabled(mixed $item, bool $isNullable) : bool
    {   
        if(empty($item) && $isNullable){
            return true;

        }
        return false;
    }

    /**
     * Verifica se o campo de validaçao é numerico
     * @return bool
     */
    protected function numeric( mixed $item ) : bool
    {
        if (is_numeric($item)) {
            return true;
        }
        return false;
    }

    protected function maxLength(string $item, int $maxLength) : bool
    {
        if(strlen($item) > $maxLength){
            return false;
            
        }
        return true;
    }

    protected function minLength(string $item, int $min) : bool
    {
        if(strlen($item) < $min){
            return false;
            
        }
        return true;
    }

    protected function decimal(mixed $item) : bool
    {
        if (!is_numeric($item )){
           return false;
        }
        return true;
    }


}