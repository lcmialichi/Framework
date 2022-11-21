<?php

use Source\Container\Container;

if (!function_exists("resolve")) {
    /**
     * Resolve dependencias de uma classe ou callback
     */
    function resolve(string|callable $id, array $params = []): mixed
    {
        return container()->make($id, $params);
    }
}

if (!function_exists("container")) {
    /**
     * Retorna uma instancia de Source\Container\Container
     * ou uma instancia resolvida 
     * 
     * @return Container|mixed
     */
    function container($abstract = null)
    {
        if($abstract) {
            return resolve($abstract);
        }

        return Container::getInstance();
    }
}

if (!function_exists('pascalCase')) {
    /**
     * Formata string para PascalCase
     *
     * @param string $string
     * @return string
     */
    function pascalCase(string $string): string
    {
        if (preg_match("/[A-Z]*|[_-]/", $string)) {
            $string = strtolower($string);
        }

        $string = str_replace(["_", "-"], " ", trim(strtolower(macroCase($string))));
        $string = ucwords($string);
        $string = str_replace(" ", "", $string);
        return $string;
    }
}

if (!function_exists('macroCase')) {
    /**
     * Formata string para MACRO_CASE
     *
     * @param string $string
     * @return string
     */
    function macroCase(string $string): string
    {
        return strtoupper(strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string)));
    }
}

if (!function_exists('resource')) {
    /**
     * Retorna um recurso
     *
     * @param string $string
     * @return mixed
     */
    function resource(string $resource): mixed
    {
        $baseNamespace = \Source\Resources::class;
        $baseFilePath = realpath(".") . "/source/Resources";

        $resourceClass = array_map(fn ($item) => ucfirst($item),  explode("/", $resource));
        $resourceClass = $baseNamespace . "\\" . implode("\\", $resourceClass);
        if (class_exists($resourceClass)) {
            return resolve($resourceClass);
        }

        $resource = $baseFilePath . "/" . $resource;
        if (is_dir($resource)) {
            return glob($resource . "/*.php");
        }
        if (file_exists($resource) || file_exists($resource = $resource . ".php")) {

            return require_once $resource;
        }

        return false;
    }
}

if (!function_exists('dd')) {
    /**
     * Printa uma variavel e encerra a execucao
     *
     * @param mixed $var
     * @return void
     */
    function dd(mixed $var): void
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
        die();
    }
}


if (!function_exists('dot')) {
    /**
     * navega por um array atravez de dotNotation
     *
     * @param mixed $var
     * @return void
     */
    function dot(string $search, array $array): mixed
    {
        if (array_key_exists($search, $array)) {
            return $array[$search];
        }
        if (!str_contains($search, '.')) {
            return $array[$search] ?? null;
        }
        
        foreach (explode('.', $search) as $segment) {
            if (array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return null;
            }
        }

        return $array;
    }
}

if (!function_exists('beginsWith')) {
    /**
     * compara se uma string comeca com outra
     *
     * @return bool
     */
    function beginsWith(string $search, string $string): mixed
    {
        return 0 === strncmp ($search, $string, \strlen($search));
    }
}

