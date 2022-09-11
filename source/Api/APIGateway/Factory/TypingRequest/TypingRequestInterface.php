<?php

namespace Source\Api\APIGateway\Factory\TypingRequest;

use Source\Api\APIGateway\CreditConditions;
use Source\Service\ValueObject\ClientObject;
use Source\Service\ValueObject\ClientAddressObject;
use Source\Service\ValueObject\ClientBankObject;
use Source\Service\ValueObject\ClientContactObject;
use Source\Service\ValueObject\CredentialObject;
use Source\Service\ValueObject\ClientMailObject;
use Source\Service\ValueObject\ClientDocumentObject;
use Source\Service\ValueObject\ClientEmployerObject;

/*
|--------------------------------------------------------------------------
| INTERFACE DE REQUISIÇAO (DIGITAÇAO DE PROPOSTA)
|--------------------------------------------------------------------------
| Esta interface é unica e exclusivamente para ser implementada pelos 
| requests da digitaçao de propostas
|
*/

interface TypingRequestInterface{

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
    ) : array;

}