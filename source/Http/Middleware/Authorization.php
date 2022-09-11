<?php

namespace Source\Http\Middleware;

use Source\Attributes\Response;
use Source\Http\Response\Middleware;
use Source\Domain\Token as Token;

#[Response(Middleware::class)]
class Authorization {
    
    public function handle() 
    {
        try{
            $token = Token::build();

        }catch(\Exception ){
            throw new \Exception("Token de autenticação Invalido!", 401);

        }

        if($token->getExpireDate() < date("Y-m-d H:i:s")){
            throw new \Exception("Token de autenticação expirado!", 401);
            return false;
            
        }
        return true;
    }
}