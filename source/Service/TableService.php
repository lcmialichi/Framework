<?php

namespace Source\Service;

use Source\Router\Router;
use Source\Router\Request;
use Source\Model\Table;
use Source\Attributes\Response;
use Source\Http\Response\Api;

#[Response(API::class)]
class TableService{

    public function __construct(private Router $router){
    }

    public function consultBank(Request $request){

        $table = (new Table)->all(useAlias: true);
        if(!$table){
            throw new \Exception("Nao existem tabelas cadastradas!");

        }

        return [
            "message" => "Tabelas encontradas com sucesso",
            "data" => $table
        ];
    }
}