<?php

use Source\Container\Container;
use Source\Request\Request;

/*
|--------------------------------------------------------------------------
| Deploy da aplicacao
|--------------------------------------------------------------------------
| No futuro, vou refatorar:
| - a dependencia que existe do request com as rotas a (feitas pelo R.L)
| - o handler (QUE lida com exceptions / trata como os dados serao retornados 
| para o usuario) do controller ser executado direto no dispatch da rota
| 
|
*/

$container = Container::getInstance();
$container->bindStatic(
    Container::class,
    fn () => Container::getInstance()
);

$container->bind(
    Request::class,
    fn () => Request::creacteFromGlobals()
);

return $container;
