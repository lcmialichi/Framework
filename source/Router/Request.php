<?php

namespace Source\Router;

class Request
{   
    private readonly mixed $middleware;
    private readonly mixed $request;
    private readonly array $queryParams;

    public function __construct(mixed $middlewares, mixed $request, array $queryParams)
    {
        $this->queryParams = $queryParams;
        $this->request = $request;
        $middleware = [];
        $middlewares ?? [];
        
        foreach( $middlewares as $key => $value){
            if(explode("\\", $key) > 1){
                $array = explode("\\", $key);
                $key = end($array);
            }
            $middleware[$key] = $value;

        }
        $this->middleware = $middleware;
    }

    /**
     * @return array
     */
    public function inputs()
    {
        return $this->request;
    }

    /**
     * @var string $class
     * @return mixed
     */
    public function middleware(?string $class = null)
    {
        if(!is_null($class)){
            return $this->middleware[$class];
        }
       
        return $this->middleware;
    }

    public function query(?string $key = null)
    {   
        if(!is_null($key)){
            return $this->queryParams[$key];
        }
    
        return $this->queryParams;
    }
    
}