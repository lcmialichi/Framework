<?php

namespace Source\Router;

class Request
{
    private readonly mixed $middleware;
    private readonly mixed $request;
    private readonly array $queryParams;

    private array $userFuncs = [];

    public function __construct(mixed $middlewares = [], mixed $request, array $queryParams)
    {
        $this->queryParams = $queryParams;
        $this->request = $request;
        $middleware = [];
        $middlewares ?? [];
        
        foreach ($middlewares as $key => $value) {
            if (explode("\\", $key) > 1) {
                $array = explode("\\", $key);
                $key = end($array);
            }
            $middleware[$key] = $value;
        }
        $this->middleware = $middleware;
    }

    /**
     * @return array|string
     */
    public function inputs(?string $input = null)
    {
        if (!empty($input)) {
            return $this->request[$input] ?? null;
        }

        return $this->request;
    }

    /**
     * @var string $class
     * @return mixed
     */
    public function middleware(?string $class = null)
    {
        if (!is_null($class)) {
            return $this->middleware[$class] ?? null;
        }

        return $this->middleware;
    }

    public function query(?string $key = null)
    {
        if (!is_null($key)) {
            return $this->queryParams[$key] ?? null;
        }

        return $this->queryParams;
    }

    public function __get($name)
    {
        return $this->inputs($name);
    }

}
