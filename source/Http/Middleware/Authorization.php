<?php

namespace Source\Http\Middleware;

use Source\Request\Request;
use Source\Attributes\Response;
use Source\Domain\Token as Token;
use Source\Http\Response\Middleware;

#[Response(Middleware::class)]
class Authorization {
    
    public function handle(Request $request) 
    {
        try{
            $token = Token::build($request);

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