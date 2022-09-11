<?php

namespace Source\Http\Middleware;

use Source\Model\Channel;
use Source\Attributes\Response;
use Source\Http\Response\Middleware;
use Source\Domain\Origin as DomainOrigin;
use Source\Exception\OriginException;

/**
 * Middleware responsavel por validar origem do cliente
 */
#[Response(Middleware::class)]
class Origin{

    public function handle()
    {
        $appKey = apache_request_headers()["app-key"];
        if($appKey){
            $origin = (new Channel)->findByKey($appKey);
            if($origin){
                DomainOrigin::setOrigin($origin->idOrigem);
                return true;
            }
        }

        throw new OriginException("Origem dos dados nao localizada!", 401);
    }
}