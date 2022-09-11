<?php

namespace Source\Api\APIGateway\Factory\SimulationResponse;

use Source\Curl\CurlResponse;

/*
|--------------------------------------------------------------------------
| INTERFACE DE RESPOSTA (SIMULAÇAO)
|--------------------------------------------------------------------------
| Esta interface é unica para todas as respostas vindas do APIGateway.
| Classes de simulacao que encaapsule a resposta curl devem implementa-la
|
*/

interface SimulationResponseInterface
{
    public function __construct(CurlResponse $response);
    /**
     * Retorna resposta APIGATEWAY formatada em array
     * @throws APIGatewayException
     * @throws APIGatewayError
     */
    public function toArray(): array;
    /**
     * Retorna a resposta em json do APIGateway
     */
    public function __toString() : string;

}