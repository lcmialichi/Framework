<?php

namespace Source\Service;

use Source\Attributes\Response;
use Source\Http\Response\API;
use Source\Log\Log;

/*
|--------------------------------------------------------------------------
| REDIRECT DE ERROR HTTP
|--------------------------------------------------------------------------
| Aqui ficam os redirects de erro http
| Atençao a classe de Response, se nao for passada uma classe de resposta,
| que implemente a interface Response, ocorrera um erro.
| Nao mexe no Response pmds, pois o erro de redirect nao é um erro da 
| aplicaçao
|
*/

#[Response(API::class)]
class ErrorService
{

    public function __construct($router)
    {
        LOG::notice("Erro HTTP", [$router->route["action"]]);

    }

    public function notFound(){
        throw new \Exception("Metodo nao encontrado!", 404);

    }

    public function forbidden(){
        throw new \Exception("Usuario nao autorizado para acessar a rota!", 403);

    }

    public function notImplemented(){
        throw new \Exception("metodo nao implementado!", 501);
        
    }       
}