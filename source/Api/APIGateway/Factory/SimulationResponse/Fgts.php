<?php

namespace Source\Api\APIGateway\Factory\SimulationResponse;

use Source\Log\Log;
use Source\Curl\CurlResponse;
use Source\Exception\APIGatewayError;
use Source\Exception\APIGatewayException;

class Fgts implements SimulationResponseInterface{

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
            if(!isset($response->condicoes_credito)){
                throw new APIGatewayError("Nao retornou condiçoes de credito!");
            }

            $creditConditions = [];
            foreach($response->condicoes_credito->proposta_parcelas as $condition){
                $creditConditions[] = [
                    "parcela" => $condition->numero_parcela,
                    "valor" => $condition->valor_parcela,
                    "data" => $condition->data_parcela
                ];

            }

            return [
                "message" => $response->mensagem,
                "data" => [
                    "valorLiberado" => $response->condicoes_credito->valor_principal,
                    "valorBruto" => $response->condicoes_credito->valor_bruto,
                    "taxa" => $response->condicoes_credito->taxa_mes,
                    "saldo" => $creditConditions
                ]
            ];

        }
        
        throw new APIGatewayException($response->mensagem, 400);
            
        
    }

}