<?php

namespace Source\Api\APIGateway\Factory\SimulationRequest;

use Source\Enum\SimulationType as Type;
use Source\Exception\APIGatewayException;
use Source\Api\APIGateway\CreditConditions;
use Source\Service\ValueObject\ClientObject;
use Source\Api\APIGateway\Factory\SimulationRequest\SimulationRequestInterface as RequestInterface;

class Fgts implements RequestInterface {

    public function getPost(
        ClientObject $clientObject, 
        CreditConditions $creditConditions
        ) : array
    {
      $this->clientObject = $clientObject;
      $this->creditConditions = $creditConditions;

       return [
            "cliente" => [
                "cpf" => $clientObject->getCpf(),
                "data_nascimento" => "1998-01-23",
                "uf" => "SP",     
            ],
            "condicoes_credito" => $this->creditConditions()
        ];

    }


    public function creditConditions()
    {
        $simulationType = $this->creditConditions->getSimulationType();

        $creditConditions = [
        "tabela_digitacao" => $this->creditConditions->getTableCode(),
        "operacao_tipo" => $simulationType->name
        ];

        switch($simulationType){
            case Type::POR_QUANTIDADE_DE_PARCELAS:
                return $creditConditions + [
                    "qnt_parcelas" => $this->creditConditions->getParcelas()
                ];

                break;
            case Type::POR_VALOR_SOLICITADO:
                return $creditConditions + [
                    "valor_solicitado" => $this->creditConditions->getValor()
                ];

                break;
            case Type::POR_VALOR_TOTAL:
                return $creditConditions;

                break;
            default:
                throw new APIGatewayException("Tipo de simulação inválido");
        }
    }


    
}

