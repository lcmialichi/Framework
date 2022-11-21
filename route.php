<?php

require __DIR__ . "/vendor/autoload.php";

ini_set('display_errors', 0);

/*
|--------------------------------------------------------------------------
| ROTAS DA API \ VERBO HTTP
|--------------------------------------------------------------------------
| Aqui ficam as rotas da aplicacao, que serao utilizadas para  a chamada dos 
| servicos.
|
*/

use Source\Router\Router;
use Source\Http\Middleware;
use Source\Request\Request;

header('Content-Type: application/Json');

$router = new Router(getenv("BASE_PREFIX"));
$router->namespace("Source\Service");

/** Erros */
$router->group("erro");
$router->get("/404", "ErrorService:notFound");
$router->get("/403", "ErrorService:forbidden");
$router->get("/501", "ErrorService:notImplemented");
$router->dispatch(resolve(Request::class));

/*** Erro de redirect */
if (!is_null($router->error())) {
    $router->redirect("/erro/{$router->error()}");
}
