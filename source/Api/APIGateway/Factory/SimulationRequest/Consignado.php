<?php

namespace Source\Api\APIGateway\Factory\SimulationRequest;

use Source\Enum\SimulationType as Type;
use Source\Exception\APIGatewayException;
use Source\Api\APIGateway\CreditConditions;
use Source\Service\ValueObject\ClientObject;
use Source\Api\APIGateway\Factory\SimulationRequest\SimulationRequestInterface as RequestInterface;

class Consignado implements RequestInterface {

    public function getPost( 
        ClientObject $clientObject, 
        CreditConditions $creditConditions ) : array
    {   
        $this->clientObject = $clientObject;
        $this->creditConditions = $creditConditions;

        return [
            "cliente" => [
                "cpf" => $clientObject->getCpf()
            ],
            "condicoes_credito" => $this->creditConditions()
        ];

    }   

    public function creditConditions()
    {
        $simulationType = $this->creditConditions->getSimulationType();
        $creditConditions = [
            "id_convenio" => $this->creditConditions->getIdConvenio(),
            "operacao_tipo" => $simulationType->name,
            "margem" => $this->creditConditions->getMargem(),
            "renda" => $this->creditConditions->getRenda(),
            "seguro" => $this->creditConditions->getSeguro()
        ];

        switch($simulationType){
            case Type::POR_QUANTIDADE_DE_PARCELAS:
                return $creditConditions + [
                    "prazo" => $this->creditConditions->getParcelas()
                ];

                break;
            case Type::POR_VALOR_SOLICITADO:
                return $creditConditions ;

                break;
            case Type::POR_VALOR_TOTAL:
                throw new APIGatewayException("tipo de simulaçao por valor total nao esta disponivel para este produto");

                break;
           
            default:
                throw new APIGatewayException("Tipo de simulação inválido");
        }

    }
}