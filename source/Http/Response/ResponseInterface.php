<?php

namespace Source\Http\Response;

interface ResponseInterface
{
    const JSON_CONFIG = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT;
    
    public function handle( callable $middleWare);

}