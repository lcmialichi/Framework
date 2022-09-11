<?php

namespace Source\Service;

use Source\Model\Accept;
use Source\Model\Client;
use Source\Router\Request;
use Source\Http\Response\API;
use Source\Attributes\Response;
use Source\Attributes\Permission;
use Source\Exception\LGPDException;
use Source\Service\ValueObject\AcceptObject;

#[Response(API::class)]
class LGPDService{

    #[Permission(
        service: Permission::LGPD_ACCEPT, 
        level: Permission::READ
        )]
    public function consult(Request $request)
    {
        $hashClient = $request->inputs()["hashCliente"];
 
         //valida se a hash tem o formato valido
         if(!preg_match("/^[0-9a-f]{32}$/", $hashClient)){
            throw new LGPDException("Hash do cliente inválido", 400);

        }
        //verificar se o cliente existe
        $client = (new Client())->findByHash($hashClient);

        if(!$client){
            throw new LGPDException("Cliente não encontrado", 400);
        }
        
        $accept = new Accept;
        $acceptList = $accept->findByClientId($client->id);

        /**
         * Remove o Id do cliente do objeto
         */
        array_walk($acceptList, function($item){
            unset($item->idCliente);
        });

        if(!$acceptList){
            throw new LGPDException("Nenhum dado encontrado para hash informada!");

        }

        return [
            "message" => "lista de aceite gerada com sucesso!",
            "data" => $acceptList
        ];

    }   

    #[Permission(
        service: Permission::LGPD_ACCEPT,
        level: Permission::CREATE
    )]
    public function acceptRegister( Request $request )
    {
        $Accept = new Accept;
        $inputs = $request->inputs();
        $accept =  $inputs["aceite"];
        $hashClient = $inputs["hashCliente"];

        //valida se a hash tem o formato valido
        if(!preg_match("/^[0-9a-f]{32}$/", $hashClient)){
            throw new LGPDException("Hash do cliente inválido", 400);

        }
        if(is_null($accept)){
            throw new LGPDException("Dados para aceite não informados", 400);

        }

        $client = (new Client())->getClient($hashClient);

        $Accept->register(new AcceptObject(
            idAcao: $accept["idAcao"],
            finalidade: "OFERTA DE EMPRESTIMO", 
            idCliente : $client->getId()
        ));

        return [
            "message" => "aceite registrado com sucesso!",
            "data" => []
        ];

    }
}