<?php

namespace Tests;

class ValueObjectsExecuter{

    /**
     * @var array
     */
    private array $classMethods;
    /**
     * @var array
     */
    private array $getter = [];
    /**
     * @var array
     */
    private array $diff = [];

    public function __construct( 
        private object $instance
        ){
        
        $this->classMethods = get_class_methods($this->instance);
        array_walk($this->classMethods, function($methodName){
            substr($methodName,0,3) == "get" ? $this->setGetter($methodName) : false;
            $methodName == "diff" ? $this->setDiff($methodName) : false;
        });
    }

    /**
     * seta os getters da classe
     *
     * @param string $methodName
     * @return void
     */
    private function setGetter(string $methodName){
        $this->getter[] = $methodName;
    }

    /**
     * @return array
     */
    public function getGetter()
    {
        return $this->getter;
    }

    /**
     * seta a funcao diff
     *
     * @param string $methodName
     * @return void
     */
    private function setDiff(string $methodName){
        $this->diff[] = $methodName;
    }

    /**
     * Executa todas as funcoes getters da classe com null nao habilitado
     *
     * @return string[]
     */
    public function testWithNullDisabled(): array 
    {
        foreach($this->getter as $getter){
            $data[$getter] = $this->instance->{$getter}();
        }

        return $data ?? false;
    }

    /**
     * Executa todas as funcoes getters da classe com null nao habilitado
     *
     * @return string[]
     */
    public function testWithNullEnabled(): array 
    {
        foreach($this->getter as $getter){
            $data[$getter] = $this->instance->{$getter}(true);
        }

        return $data ?? false;
    }

    public function unsetGetter(string $getter) : void
    {
        $this->getter = array_filter($this->getter, function($item) use ($getter){
            return $item != $getter;
        });

     
    }

    
}