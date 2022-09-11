<?php

namespace Source\Api\APIGateway\Factory\TypingResponse;

use Source\Log\Log;
use Source\Curl\CurlResponse;
use Source\Exception\APIGatewayError;
use Source\Exception\APIGatewayException;

class Fgts implements TypingResponseInterface{

    /**
     * @var string
     */
    private string $message;
    /**
     * @var string
     */
    private string $proposal;
    /**
     * @var string
     */
    private string $formLink;

    public function __construct(
        private CurlResponse $response
    )
    {
        $response = json_decode($response->getResponse());
        if(!$response){
            throw new APIGatewayError("Json invalido!");

        }

        Log::info($response->mensagem);
        if($response->sucesso){
            if(!isset($response->numero_proposta)){
                throw new APIGatewayError("Nao retornou numero da Proposta!");
            }

            $this->message = $response->mensagem;
            $this->proposal = $response->numero_proposta;
            $this->formLink = $response->formalizacao;
            return;
        }

        throw new APIGatewayException($response->mensagem);
    }

    public function __toString() : string
    {
        return $this->response->getResponse();
    }
    
    public function toArray() : array
    {
        return [
            "message" => $this->message,
            "data" => [
                "numeroProposta" => $this->proposal
            ]
        ];
    }

    /**
     * Retorna o numero da proposta digitada
     */
    public function getProposal() : string
    {
        return $this->proposal;

    }

    /**
     * retorna a formalizaçao
     */
    public function getFomalizacao() : string
    {
        return $this->formLink;

    }

    /**
     * Retorna mensagem da ApiGateway
     */
    public function getMessage() : string
    {
        return $this->message;

    }

}