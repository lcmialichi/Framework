<?php

namespace Source\Api\Bank\C6Bank;

use Source\Exception\BankError;
use Source\Connection\DBConnect;

/**
 * Gera token de acesso para o C6bank
 */
class Access {

    public string $token;

    public function __construct() 
    {
        $connection = DBConnect::getConnection();
        $token = $connection
                    ->table("api_gateway.tb_credencial_api")
                    ->select("token")
                    ->where("id", 4)
                    ->one() ;

        if(!$token){
            throw new BankError("Não foi possível obter o token do banco C6Bank");	
        }

        $this->token = $token->token;
    }

    public function getToken() : string
    {
        return $this->token;
    }
}

