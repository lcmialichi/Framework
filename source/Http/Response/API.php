<?php

namespace Source\Http\Response;

use Source\Log\Log;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\BeforeValidException;


/**
 * Classe responsável por tratar as mensagens de resposta ao cliente
 */
class API implements ResponseInterface
{

    public function handle(callable $controller): bool
    {
        try {
            $return = $controller();

            if (is_null($return)) { // Retorno Vazio 
                http_response_code(400);
                $this->send("Nao houve um retorno da api");
                return true;
                
            } else {  // Retorno com dados e sucesso true 
                isset($return['status']) ?: $return = ['status' => true] + $return;
                echo json_encode($return, API::JSON_CONFIG);
                return false;

            }
        } catch (BeforeValidException | ExpiredException | SignatureInvalidException $e) {
            http_response_code(401);
            $this->send("Token de autenticacao invalido");
            return false;
            
        } catch (\PDOException $e) {
            Log::critical($e->getMessage());
            $this->send($e->getMessage());
            return false;
            
        } catch (\Exception $e ) {
            $httpCode = $e->getCode();
            $httpCode =  is_string($httpCode) ? 400 : $httpCode;
            http_response_code($httpCode);
            $this->send($e->getMessage());
            return false;

        } catch (\Error $e) { // Erros da aplicaçao 
            echo $e;
            http_response_code(500);
            Log::critical($e->getMessage());
            $message = $e->getMessage();
            $this->send($message);
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
