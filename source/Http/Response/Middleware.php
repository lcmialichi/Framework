<?php

namespace Source\Http\Response;

use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\BeforeValidException;
use Source\Log\Log;

class Middleware implements ResponseInterface{

    public function handle( $middleWare )
    {
        
        try{
            return $middleWare();
            
        }catch (\PDOException $e) {
            Log::critical($e->getMessage());
            $this->send($e->getMessage());
            return false;

        }catch(\Exception $e){
            
            $httpCode = $e->getCode();
            $message = $e->getMessage();
            $httpCode =  is_string($httpCode) ? 422 : $httpCode;

            http_response_code($httpCode);
            echo json_encode(["status" => false,"message" => $message], API::JSON_CONFIG); 
            return false;

        }catch(\Error $e){
            $message = $e->getMessage();
            http_response_code(400);
            echo json_encode(["status" => false,"message" => $message], API::JSON_CONFIG); 
            return false;
        }

    }

    public function send($message)
    {
        echo json_encode([
            "status" => false,
            "message" => $message
        ], API::JSON_CONFIG);

    }
}