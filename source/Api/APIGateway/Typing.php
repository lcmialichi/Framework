<?php

namespace Source\Api\APIGateway;

use Source\Curl\Curl;
use Source\Enum\Banks;
use Source\Enum\Product;
use Source\Exception\APIGatewayException;
use Source\Service\ValueObject\ClientObject;
use Source\Service\ValueObject\ClientBankObject;
use Source\Service\ValueObject\ClientMailObject;
use Source\Api\APIGateway\Factory\RequestFactory;
use Source\Api\APIGateway\Factory\ResponseFactory;
use Source\Service\ValueObject\ClientAddressObject;
use Source\Service\ValueObject\ClientContactObject;
use Source\Service\ValueObject\ClientDocumentObject;
use Source\Service\ValueObject\ClientEmployerObject;
use Source\Service\ValueObject\CredentialObject;
use Source\Api\APIGateway\Factory\TypingResponse\TypingResponseInterface;

/*
|--------------------------------------------------------------------------
| DIGITAÇAO API GATEWAY
|--------------------------------------------------------------------------
| As dependencias nao sao muito diferentes da simulacao, apenas os dados do
| cliente necessitam ser mais complexos.
|
*/

class Typing extends Curl{

    /**
     * URL base API-GATEWAY
     */
    private const URL = "http://apigateway.phng.com.br/marketplace";
    /**
     * @var Banks
     */    
    private Banks $bank;
    /**
     * @var Product
     */    
    private Product $product;
    /**
     * @var array
     */
    private array $header;
    /**
     * @param ClientObject
     * @param CreditConditions 
     */
    public function __construct(
        private ClientObject $clientObject,
        private CreditConditions $creditConditions,
        private CredentialObject $credentialObject,
        private bool|ClientAddressObject $clientAddressObject = false,
        private bool|ClientBankObject $clientBankObject = false,
        private bool|ClientContactObject $clientContactObject = false,
        private bool|ClientMailObject $clientMailObject = false,
        private bool|ClientDocumentObject $clientDocumentObject = false,
        private bool|ClientEmployerObject $clientEmployerObject = false

    )
    {
        $this->setPostFormatter(fn($post) => json_encode($post));
        $this->bank = Banks::tryFrom($creditConditions->getIdBank()); #Retorna Enum do banco de acordo com id
        $this->product = Product::tryFrom($creditConditions->getIdProduct()); #Retorna Enum do produto de acordo com id

        if(!$this->bank || !$this->product){ # Se tabela nao possui produto ne banco, retorna erro
            throw new APIGatewayException("Ocorreu um erro ao tentar obter os dados do banco e produto", 400);
        }

        $this->header = [ # Header APIGATEWAY
            "Content-Type: application/json",
            "Authorization: Basic " . getenv("API_GATEWAY_CRED")
        ];
      
    }

    /**
     * @return TypingResponseInterface
     */
    public function send() : TypingResponseInterface
    {
        $request = RequestFactory::buildFromTyping(product: $this->product);
        $response = $this->post(
            url: Typing::URL . "/" . $this->bank->getName() . "/" . $this->product->getName() . "/proposta.php",
            header: $this->header,
            post: $request->getPost(
                clientObject: $this->clientObject,
                creditConditions: $this->creditConditions,
                credentialObject: $this->credentialObject,
                clientAddressObject: $this->clientAddressObject,
                clientBankObject: $this->clientBankObject,
                clientContactObject: $this->clientContactObject,
                clientMailObject: $this->clientMailObject,
                clientDocumentObject: $this->clientDocumentObject,
                clientEmployerObject: $this->clientEmployerObject
                )
        );
        
        return ResponseFactory::buildFromTyping(product: $this->product, curlResponse: $response);

    }


}