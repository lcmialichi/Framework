<?php

namespace Source\Model\ORM;

/**
 * Define qual coluna as propriedades da classe se referem
 * Construtor nao serve pra nada, mas existe para referenciar oque
 * pode ser identificado pelos atributos
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Column {
    
    const PK = 1;
    const FK = 2; #???
    /**
     * @param string $type
     * @param string $alias
     * @param const $key
     * @param bool $generatedValues
     */
    public function __construct($type = null, $alias = null, $key = null, $generatedValue = null){}

    
}
