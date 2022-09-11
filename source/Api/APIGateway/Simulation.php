<?php

namespace Source\Api\APIGateway;

use Source\Curl\Curl;
use Source\Enum\Banks;
use Source\Enum\Product;
use Source\Exception\APIGatewayException;
use Source\Service\ValueObject\ClientObject;
use Source\Api\APIGateway\Factory\RequestFactory;
use Source\Api\APIGateway\Factory\ResponseFactory;
use Source\Api\APIGateway\Factory\Interface\ResponseInterface;
use Source\Api\APIGateway\Factory\SimulationResponse\SimulationResponseInterface;

/*
|--------------------------------------------------------------------------
| SIMULACAO API GATEWAY
|--------------------------------------------------------------------------
| Para ser feita a simulaçao, é exigido que sejam enviados objeto do tipo 
| ClientObject e CreditConditions, as validaçoes ficam por responsabilidade 
| do uso destes objetos durante o tempo de excuçao da simulaçao.
| Desta forma qualquer metodo que exigir uma simulaçao pode efetua-la apenas 
| enviando estes objetos.
| Nao remova as responsabilidades desta classe e nem transfira para outros 
| objetos. Atlas agradece.
|
*/

final class Simulation extends Curl{
    
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
        private CreditConditions $creditConditions

    )
    {
        $this->setPostFormatter(fn($post) => json_encode($post));
        $this->bank = Banks::tryFrom($creditConditions->getIdBank()); #Retorna Enum do banco de acordo com id
        $this->product = Product::tryFrom($creditConditions->getIdProduct()); #Retorna Enum do produto de acordo com id

        if(!$this->bank || !$this->product){ # Se tabela nao possui produto ne banco, retorna erro
            throw new APIGatewayException("Ocorreu um erro ao tentar obter os dados do banco e produto");
        }

        $this->header = [ # Header APIGATEWAY
            "Content-Type: application/json",
            "Authorization: Basic " . getenv("API_GATEWAY_CRED")
        ];
      
    }

    /**
     * @return SimulationResponseInterface
     */
    public function send() : SimulationResponseInterface
    {
        $request = RequestFactory::buildFromSimulation(product: $this->product);
        $response = $this->post(
            url: Simulation::URL . "/" . $this->bank->getName() . "/" . $this->product->getName() . "/simulacao.php",
            header: $this->header,
            post: $request->getPost($this->clientObject,$this->creditConditions)
        );
        
        return ResponseFactory::buildFromSimulation(product: $this->product, curlResponse: $response);

    }


   
}