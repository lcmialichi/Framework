<?php

namespace Source\Api\APIGateway\Factory\TypingRequest;

use Source\Enum\SimulationType as Type;
use Source\Exception\APIGatewayException;
use Source\Api\APIGateway\CreditConditions;
use Source\Service\ValueObject\CredentialObject;
use Source\Service\ValueObject\ClientObject;
use Source\Api\APIGateway\Factory\Enum\Gender;
use Source\Api\APIGateway\Factory\Enum\Account;
use Source\Api\APIGateway\Factory\Enum\Marital;
use Source\Service\ValueObject\ClientBankObject;
use Source\Service\ValueObject\ClientMailObject;
use Source\Service\ValueObject\ClientAddressObject;
use Source\Service\ValueObject\ClientContactObject;
use Source\Service\ValueObject\ClientDocumentObject;
use Source\Service\ValueObject\ClientEmployerObject;

class Fgts implements TypingRequestInterface{

    /**
     * @todo ajustar a coleta de credenciais da digitaçao
     */
    public function getPost( 
        ClientObject $clientObject, 
        CreditConditions $creditConditions,
        CredentialObject $credentialObject,
        bool|ClientAddressObject $clientAddressObject,
        bool|ClientBankObject $clientBankObject,
        bool|ClientContactObject $clientContactObject,
        bool|ClientMailObject $clientMailObject,
        bool|ClientDocumentObject $clientDocumentObject,
        bool|ClientEmployerObject $clientEmployerObject
    ) : array
    {
        $this->creditConditions = $creditConditions;

        return [
            "cliente" => [
                "nome" => $clientObject->getNome(),
                "cpf" => $clientObject->getCpf(),
                "nacionalidade" => $clientObject->getNacionalidade() ,
                "estado_civil" => Marital::get($clientObject->getEstadoCivil()),
                "renda_valor" => 100000 ,
                "data_nascimento" => $clientObject->getDataNascimento(),
                "sexo" => Gender::get($clientObject->getSexo()),
                "nome_mae" => $clientObject->getNomeMae(),
                "email" => $clientMailObject == false ?  "digitacao_automatica@gmail.com": $clientMailObject->getMail(isNullable: true),
                "pessoa_politicamente_exposta" => false,
                "tipo_documento" => "RG",
                "numero_documento" => $clientDocumentObject->getDocument(),
                "data_emissao_documento" => date("Y-m-d", strtotime("- 2 year")),
                "uf_documento" => "SP" ,
                "dados_bancarios" => [
                    "numero_banco" => $clientBankObject->getNumeroBanco(),
                    "numero_agencia" => $clientBankObject->getAgencia(),
                    "numero_conta" => $clientBankObject->getConta(),
                    "digito_conta" => $clientBankObject->getDigitoConta(),
                    "tipo_conta" => Account::get($clientBankObject->getTipoConta())
            ],
                "enderecos" => [
                    "logradouro" => $clientAddressObject->getRua(),
                    "numero" => $clientAddressObject->getNumero(),
                    "complemento" => $clientAddressObject->getComplemento(isNullable: true),
                    "bairro" => $clientAddressObject->getBairro(),
                    "cidade" => $clientAddressObject->getCidade(),
                    "cep" => str_pad($clientAddressObject->getCep(), 8, 0, STR_PAD_LEFT),
                    "uf" => $clientAddressObject->getUf(),
            ],
                "telefones" => [
                    "ddd" => $clientContactObject->getDdd(),
                    "fone" => $clientContactObject->getTelefone(),
                    "tipo" => "CELULAR",
            ]
        ],
            "condicoes_credito" => $this->creditConditions(),
            "promotora" => [
                "codigo_digitador" => $credentialObject->getAccess(),
                "cpf_usuario_certificado" => $credentialObject->getUserOriginCpf(), 
            ]
        ];
     
    }

    public function creditConditions()
    {
        $simulationType = $this->creditConditions->getSimulationType();

        $creditConditions = [
        "tabela_digitacao" => $this->creditConditions->getTableCode(),
        "operacao_tipo" => $simulationType->name,
        "valor" => $this->creditConditions->getValor()
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