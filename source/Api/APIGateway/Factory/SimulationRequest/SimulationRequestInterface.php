<?php

namespace Source\Api\APIGateway\Factory\SimulationRequest;

use Source\Api\APIGateway\CreditConditions;
use Source\Service\ValueObject\ClientObject;

/*
|--------------------------------------------------------------------------
| INTERFACE DE REQUISIÇAO (SIMULAÇAO)
|--------------------------------------------------------------------------
| Esta interface é unica e exclusivamente para ser implementada pelos 
| requests da simulaçao
|
*/

interface SimulationRequestInterface {

    public function getPost( 
        ClientObject $clientObject, 
        CreditConditions $creditConditions,
        ) : array;

}