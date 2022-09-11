<?php

namespace Source\Api\APIGateway\Factory\SimulationResponse;

use Source\Log\Log;
use Source\Curl\CurlResponse;
use Source\Exception\APIGatewayError;
use Source\Exception\APIGatewayException;

class Consignado implements SimulationResponseInterface{

    public function __construct(
        private CurlResponse $response
    )
    {
    }

    /**
     * Retorna resposta sem formataçao, direto do curl
     */
    public function __toString(): string
    {
        return $this->response->getResponse();
    }

    /**
     * Retorna resposta APIGATEWAY formatada em array
     * @throws APIGatewayException
     * @throws APIGatewayError
     */
    public function toArray() : array
    {
        $response = json_decode($this->response->getResponse());
        if(!$response){
            throw new APIGatewayError("Json invalido!");

        }

        Log::info($response->mensagem);
        if($response->sucesso){
            if(!isset($response->simulacoes)){
                throw new APIGatewayError("Nao retornou simulacoes");
                
            }

            return [
                "message" => $response->mensagem,
                "data" => [
                    "simulacoes" => $response->simulacoes
                ]
            ];

        }

        throw new APIGatewayException($response->mensagem);
        
              
    }
}