<?php

namespace Source\Api\APIGateway\Factory\TypingResponse;

use Source\Curl\CurlResponse;

/*
|--------------------------------------------------------------------------
| INTERFACE DE RESPOSTA (DIGITAÇAO)
|--------------------------------------------------------------------------
| Esta interface é unica para todas as respostas vindas do APIGateway.
| Classes de digitaçao que encaapsule a resposta curl devem implementa-la
|
*/

interface TypingResponseInterface
{
    public function __construct(CurlResponse $response);
    /**
     * Retorna resposta APIGATEWAY formatada em array
     * @throws APIGatewayException
     * @throws APIGatewayError
     */
    public function toArray(): array;
    /**
     * Retorna o numero da proposta digitada
     */
    public function getProposal() : string;
    /**
     * retorna a formalizaçao
     */
    public function getFomalizacao() : string;
    /**
     * Retorna mensagem da ApiGateway
     */
    public function getMessage() : string;

} 