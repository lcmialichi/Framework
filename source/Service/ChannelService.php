<?php

namespace Source\Service;

use Source\Model\Channel;
use Source\Router\Router;
use Source\Router\Request;
use Source\Http\Response\API;
use Source\Attributes\Response;
use Source\Attributes\Permission;

#[Response(API::class)]
class ChannelService {
    
    public function __construct( private Router $router ) {}

    #[Permission(
        service: Permission::CHANNEL, 
        level: Permission::READ
    )]
    public function consultById(Request $request) : array{

        $id = $request->inputs()["id"];
        if(!$id){
            throw new \Exception("Id não informado");

        }
        if(!is_numeric($id)){
            throw new \Exception("Id informado não é numérico");

        }

        $channel = new Channel;
        $channel = $channel->find($id, useAlias:true);

        if(!$channel){
            throw new \Exception("Nenhum canal encontrado com esse id", 400);
        }
        
        return [
            "status" => true,
            "message" => "Canal encontrado com sucesso",
            "data" => [
                "canal" => $channel
            ]
        ];
    }

    #[Permission(
        service: Permission::CHANNEL, 
        level: Permission::READ
    )]
    public function consultAll(Request $request) : array{

        $permissionType = $request->middleware("Permission")['type'];
        if($permissionType != Permission::ALL && $permissionType != Permission::ROBOT){
            $this->router->redirect("/erro/403"); #forbidden

        }
        
        $channel = new Channel;
        $channels = $channel->all(useAlias:true);

        if(!$channels){
            throw new \Exception("Nenhum canal encontrado", 400);
        }
        
        return [
            "status" => true,
            "message" => "Canal encontrado com sucesso",
            "data" => [
                "canal" => $channels
            ]
        ];
    }
    
}