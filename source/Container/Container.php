<?php

namespace Source\Container;

use Source\Exception\ContainerException;

class Container
{
    /**
     * @var array
     */
    private array $container = [];
    /**
     * @var array
     */
    private static array $staticContainer = [];
    /**
     * @var self $intance
     */
    private static ?self $instance = null;

    /**
     * vincula um callback a uma classe 
     * 
     */
    public function bind(string $id, callable $concrete): void
    {
        $this->container[$id] = $concrete;
    }

    /**
     * vincula um callback a uma classe  de maneira estatica
     * 
     */
    public function bindStatic(string $id, callable $concrete): void
    {
        self::$staticContainer[$id] = $concrete;
    }

    /**
     * Retorna todos os vinculos dentro do container
     * 
     */
    public function allBinding(): array
    {
        return array_merge($this->container, self::$staticContainer);
    }

    /**
     * Resolve classe e metodo
     * ex Classe@metodo
     */
    public function call(string $classMethod, array $parameters = [])
    {
        $items = explode("@", $classMethod);
        if (count($items) != 2) {
            throw new ContainerException("formato nao suportado pelo container!");
        }
        $callback = ($this->make($items[0]))->{$items[1]}(...);
        return $this->make($callback, $parameters);
    }

    /**
     * Remove um serviço atrelado a uma classe
     */
    public function remove($id): bool
    {

        if (isset($this->container[$id])) {
            unset($this->container[$id]);
            return true;
        }

        if (isset(self::$staticContainer[$id])) {
            unset(self::$staticContainer[$id]);
            return true;
        }

        return false;
    }

    /**
     * Verifica se existe um serviço atrelado a uma classe
     *
     */
    public function has($id)
    {
        return isset($this->container[$id]) || isset(self::$staticContainer[$id]);
    }

    /**
     * Coleta o serviço atrelado a classe
     * 
     */
    public function get($id)
    {
        return $this->container[$id] ?? self::$staticContainer[$id];
    }

    /**
     * Resolve dependencia em cascata
     * 
     */
    public function make(string|callable $id, array $params = [])
    {

        if (is_callable($id)) {
            return $this->resolveCallable($id, $params);
        }

        if (class_exists($id)) {
            $reflection = new \ReflectionClass($id);

            if ($this->has($id)) {
                return $this->get($id)($this);
            }

            if (!$this->hasParams($id) && $reflection->isInstantiable()) {
                return new $id;
            }
            if (!$reflection->isInstantiable()) {
                throw new ContainerException(sprintf("'%s' nao pode ser instanciada", $id));
            }

            return $this->resolve($id, $params);
        }

        throw new ContainerException(sprintf("'%s' nao pode ser resolvido", $id));
    }

    /**
     * @param string $id
     * @param array $params
     */
    private function resolve(string $id, array $params = [])
    {

        $constructor = (new \ReflectionClass($id))->getConstructor();
        if (!$constructor) {
            return new $id;
        }

        $dependencies = $constructor->getParameters();

        foreach ($dependencies as $dependency) {
            if (array_key_exists($dependency->getName(), $params)) {
                $resolved[$dependency->getName()] = $params[$dependency->getName()];
                continue;
            }

            if (($type = $dependency->getType()) instanceof \ReflectionNamedType) {
                if (($default = $this->getDefaultValue($dependency)) !== false) {
                    $resolved[$dependency->getName()] = $default;
                    continue;
                }
                if (!is_string($type->getName())) {
                    throw new ContainerException(sprintf(
                        "Parametro '%s' de '%s' nao pode ser resolvido",
                        $dependency->getName(),
                        $id
                    ));
                }

                $resolved[$dependency->getName()] = $this->make($type->getName());
            }
        }

        return new $id(...$resolved ?? []);
    }

    /**
     * @param Closure $callback
     * @param array $params
     */
    public function resolveCallable(callable $callable, array $params = [])
    {

        $dependencies = (new \ReflectionFunction($callable))->getParameters();

        foreach ($dependencies as $dependency) {
            if (isset($params[$dependency->getName()])) {
                $resolved[$dependency->getName()] = $params[$dependency->getName()];
                continue;
            }
            if (($type = $dependency->getType()) instanceof \ReflectionNamedType) {
                if (($default = $this->getDefaultValue($dependency)) !== false) {
                    $resolved[$dependency->getName()] = $default;
                    continue;
                }
                if (!is_string($type->getName())) {;
                    throw new ContainerException(sprintf(
                        "Parametro '%s' de '%s' nao pode ser resolvido",
                        $dependency->getName(),
                        "Closure"
                    ));
                }

                $resolved[$dependency->getName()] = $this->make($type->getName());
            }
        }

        return $callable(...$resolved ?? []);
    }

    /**
     * Verifica se a classe tem parametros no construtor
     * 
     * @param object|string $class
     */
    private function hasParams(object|string $class): bool
    {
        $reflector = new \ReflectionClass($class);
        $constructor = $reflector->getConstructor();
        return $constructor !== null && $constructor->getNumberOfParameters() > 0;
    }

    /**
     * Retorna o valor padrao do parametro caso exista
     * 
     * @param \ReflectionParameter $param
     */
    private function getDefaultValue(\ReflectionParameter $dependency): mixed
    {
        if ($dependency->isOptional()) {
            return $dependency->getDefaultValue();
        }
        return false;
    }

    public static function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }
}
