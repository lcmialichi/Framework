<?php

use PHPUnit\Framework\TestCase;
use Source\Exception\APIGatewayException;
use Source\Api\APIGateway\Factory\RequestFactory;
use Source\Api\APIGateway\Factory\ResponseFactory;
use Source\Api\APIGateway\Factory\SimulationRequest\SimulationRequestInterface;
use Source\Api\APIGateway\Factory\SimulationResponse\SimulationResponseInterface;

class SimulationTest extends TestCase{

    /**
     * @test
     */
    public function factory_encontra_classe_de_requisicao_simulacao()
    {
        $factory = RequestFactory::buildFromSimulation(
            product: Source\Enum\Product::FGTS
        );

        $this->assertInstanceOf(SimulationRequestInterface::class, $factory);

    }
    
    /**
     * @test
     *
     * @return void
     */
    public function factory_encontra_classe_de_resposta_simulacao()
    {
        $_SERVER["REMOTE_ADDR"] = "PHPUnit";
        $factory = ResponseFactory::buildFromSimulation(
            product: Source\Enum\Product::FGTS,
            curlResponse: new Source\Curl\CurlResponse(
                headers: [],
                curlInfo: [],
                response: json_encode([
                    "sucesso" => true,
                    "mensagem" => "success",
                    "condicoes_credito" => [
                        "proposta_parcelas" => [],
                        "valor_principal" => '1000',
                        "valor_bruto" => "1000",
                        "taxa_mes" => "0.10"
                        ]
                    ]
                )
            )
        );

        $this->assertInstanceOf(SimulationResponseInterface::class, $factory);
        $toArray = $factory->toArray();

        $this->assertArrayHasKey("message", $toArray);
        $this->assertArrayHasKey("data", $toArray);
    }

     /**
     * @test
     *
     * @return void
     */
    public function factory_encontra_classe_de_resposta_simulacao_com_resposta_false()
    {
        $_SERVER["REMOTE_ADDR"] = "PHPUnit";
        $factory = ResponseFactory::buildFromSimulation(
            product: Source\Enum\Product::FGTS,
            curlResponse: new Source\Curl\CurlResponse(
                headers: [],
                curlInfo: [],
                response: json_encode([
                    "sucesso" => false,
                    "mensagem" => "error",
                    ]
                )
            )
        );

        $this->assertInstanceOf(SimulationResponseInterface::class, $factory);
        $this->expectException(APIGatewayException::class);
        $factory->toArray();
     
    }
}