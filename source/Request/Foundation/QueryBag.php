<?php

namespace Source\Request\Foundation;

class QueryBag
{

    public function __construct(private array $parameters = [])
    {
        unset($this->parameters["route"]);
    
    }

    public function all(): array
    {
        return $this->parameters ?? [];
    }

    public function __get($key)
    {
        return $this->parameters[$key];
    }
}
