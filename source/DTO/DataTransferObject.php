<?php

namespace Source\DTO;

use InvalidArgumentException;

abstract class DataTransferObject 
{   
    private array $items;

    /**
     * Cria uma nova instancia do Transfer-Object
     * 
     */
    public function __construct(...$args)
    {
        foreach ($args as $name => $value) {
            if(!property_exists($this, $name)) {
                throw new InvalidArgumentException("Propriedade {$name} nao existe em ".self::class);
            }
            $this->__set($name, $value);
        }
    }

    /**
     * Cria uma nova instancia do Transfer-Object
     * 
     * @param mixed $args
     */
    public static function make(...$args): self
    {
        return new static(...$args);
    }

    /**
     *  Valida a propriedade antes de atribuir o valor
     */
    public function __set($item, $value)
    {
        if(method_exists($this, "validate")){
            $this->{"validate"}([$item => $value]);
        }

        $this->items[$item] = $value;
    }

    /**
     * Retorna o valor de uma propriedade
     */
    public function __get($item)
    {
        if (isset($this->items[$item])) {
            return $this->items[$item];
        }
    }
}
