<?php

namespace Source\Attributes;

/**
 * Informa qual classe sera responsavel por tratar as informacoes do retorno da API
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Response{

    public function __construct(
        private string $class
        )
    {}

    public function getResponseClass()
    {
      
         if(method_exists( $this->class, "handle")){
            return new $this->class;

        }
   
        return false;
     

    }
    
}