<?php

namespace Source\Factory;

/*
|--------------------------------------------------------------------------
| BASE PARA A CONSTRUCAO DE FACTORY
|--------------------------------------------------------------------------
| Essa classe deve ser herdada por uma classa ao qual executara seus 
| metodos para retornar uma instancia de um objeto
|
| - setNamespace() : Define o namespace da classe que sera instanciada
| - setContracts() : Define o contrato que a classe deve implementar
| - inject() : Define os parametros que serao injetados no construtor
| - fromEnum() : Retorna a instancia da classe com base no enum
| - fromString() : Retorna a instancia da classe com base em uma string
| 
| Outros metodos como beforeClass e afterClass irao adicionar string antes
| e depois da string ou enum que definir a instancia do objeto
| 
*/

use Source\Exception\FactoryError;

abstract class Factory
{
    /*
     * @var string
     */
    private string $namespace;
    /**
     * Contrato
     * @var array
     */
    private array $contracts;
    /**
     * @var array|null
     */
    private array $inject = [];
    /**
     * @var string|null
     */
    private string $beforeClass;
    /**
     * @var string|null
     */
    private string $afterClass;

    protected function setNamespace(string $namespace = __CLASS__): self
    {
        if ($namespace == get_class($this)) {
            $namespace = explode("\\", $namespace);
            array_pop($namespace);
            $namespace = implode("\\", $namespace);
        }
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * Adiciona uma string antes da classe e depois do namespace
     *
     * @param string $before
     * @return self
     */
    protected function beforeClass(string $before): self
    {
        $this->beforeClass = $before;
        return $this;
    }

    /**
     * Adiciona uma string depois da classe
     *
     * @param string $after
     * @return self
     */
    protected function afterClass(string $after): self
    {
        $this->afterClass = $after;
        return $this;
    }

    protected function setContracts(array|string $interfaces): self
    {
        if (is_string($interfaces)) {
            $interfaces = [$interfaces];
        }
        $this->contracts = $interfaces;
        return $this;
    }

    /**
     * injeta as dependencias da classe
     *
     * @param mixed ...$params
     * @return self
     */
    protected function inject(...$params): self
    {
        $this->inject = $params;
        return $this;
    }

    /**
     * retorna a instancia da classe com base no enum
     *
     * @param object $instance
     * @return object
     */
    protected function fromEnum(object $enum): object
    {
        if (!enum_exists($enum::class)) {
            throw new FactoryError(
                sprintf(
                    "Enum %s não existe na execuçao da factory: %s",
                    $enum::class,
                    __CLASS__
                )
            );
        }

        $completeString = $this->buildFromString($enum->name);

        return $this->getInstanceOf($completeString);
    }

    /**
     * retorna a instancia da classe com base na string
     *
     * @param string $string
     * @return object
     */
    protected function fromString(string $string)
    {
        $completeString = $this->buildFromString($string);
        return $this->getInstanceOf($completeString);
    }

    /**
     * Retorna instancia de uma classe
     *
     * @param string $instance
     * @return object
     */
    protected function getInstanceOf(string $instance): object
    {
        if (class_exists($instance)) {
            $instance = new $instance(...$this->inject);
            if ($this->hasContracts($instance)) {
                return $instance;
            }
        }
        throw new FactoryError(
            sprintf(
                "A classe %s não existe ou nao foi implementada na execuçao da factory: %s",
                is_object($instance) ? $instance::class : $instance,
                __CLASS__
            )
        );
    }

    protected function hasContracts(object $instance): bool
    {
        if (isset($this->contracts)) {
            foreach ($this->contracts as $interface) {
                if (!$instance instanceof $interface) {
                    throw new FactoryError(
                        sprintf(
                            "A classe %s precisa implementar a interface %s na execuçao da factory: %s",
                            is_object($instance) ? $instance::class : $instance,
                            $interface,
                            __CLASS__
                        )
                    );
                }
            }
        }

        return true;
    }

    protected function buildFromString(string $class): string
    {
        $class = explode("_", strtolower($class));
        array_walk($class, function (&$item) {
            $item = ucfirst($item);
        });

        if (!isset($this->namespace)) {
            $this->setNamespace($this::class);
        }

        $before = !isset($this->beforeClass) ? null : "\\" . $this->beforeClass;
        $after = !isset($this->afterClass) ? null : "\\" . $this->afterClass;

        return $this->namespace . $before . "\\" . implode($class) . $after;
    }
}
