<?php

namespace Source\Api\APIGateway\Factory\TypingRequest;

use Source\Api\APIGateway\CreditConditions;
use Source\Service\ValueObject\ClientObject;
use Source\Api\APIGateway\Factory\Enum\Gender;
use Source\Api\APIGateway\Factory\Enum\Marital;
use Source\Service\ValueObject\CredentialObject;
use Source\Service\ValueObject\ClientBankObject;
use Source\Service\ValueObject\ClientMailObject;
use Source\Service\ValueObject\ClientAddressObject;
use Source\Service\ValueObject\ClientContactObject;
use Source\Service\ValueObject\ClientDocumentObject;
use Source\Service\ValueObject\ClientEmployerObject;

/**
 * @todo terminar assim que resolver problema com os bancos
 */
class Cartao implements TypingRequestInterface{

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
        return [
            "cliente" => [
                "cpf" => $clientObject->getCpf(),
                "nome" => $clientObject->getNome(),
                "documento" => $clientDocumentObject->getDocument(),
                "dataEmissaoDocumento" => date("Y-m-d", strtotime("- 2 year")),
                "ufDocumento" => "SP",
                "sexo" => Gender::get($clientObject->getSexo()),
                "dataNascimento" => $clientObject->getDataNascimento(),
                "estadoCivil" => Marital::get($clientObject->getEstadoCivil()),
                "nomeMae" => $clientObject->getNomeMae(),
                "email" => $clientMailObject->getMail(isNullable: true) ?? "digitaçao_automatica@gmail.com",
                "nomePai" => "teste teste",
                "endereco" => [
                    "cep" => str_pad($clientAddressObject->getCep(), 8, "0", STR_PAD_LEFT),
                    "logradouro" => $clientAddressObject->getRua(),
                    "numero" => $clientAddressObject->getNumero(),
                    "complemento" => $clientAddressObject->getComplemento(isNullable: true),
                    "bairro" => $clientAddressObject->getBairro(),
                    "cidade" => $clientAddressObject->getCidade(),
                    "uf" => $clientAddressObject->getUf()
                ],
                "dadosBancarios" => [
                    "banco" => $clientBankObject->getNumeroBanco(),
                    "agencia" => $clientBankObject->getAgencia(),
                    "digito" => $clientBankObject->getDigitoConta(),
                    "tipoConta" => "ContaCorrenteIndividual",
                    "conta" => "3232323"
                ]
            ]
        ];
    }

}