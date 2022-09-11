<?php

use PHPUnit\Framework\TestCase;
use Source\Api\APIGateway\Factory\RequestFactory;
use Source\Api\APIGateway\Factory\ResponseFactory;
use Source\Api\APIGateway\Factory\TypingRequest\TypingRequestInterface;
use Source\Api\APIGateway\Factory\TypingResponse\TypingResponseInterface;
use Source\Exception\APIGatewayException;


class TypingFactoryTest extends TestCase{

    /**
     * @test
     */
    public function factory_encontra_classe_de_requisicao_digitacao()
    {
        $factory = RequestFactory::buildFromTyping(
            product: \Source\Enum\Product::FGTS
        );

        $this->assertInstanceOf(TypingRequestInterface::class, $factory);

    }

    /**
     * @test
     *
     * @return void
     */
    public function factory_encontra_classe_de_resposta_digitacao()
    {   
        $_SERVER["REMOTE_ADDR"] = "PHPUnit";
        $factory = ResponseFactory::buildFromTyping(
            product: Source\Enum\Product::FGTS,
            curlResponse: new Source\Curl\CurlResponse(
                headers: [],
                curlInfo: [],
                response: json_encode([
                    "sucesso" => true,
                    "mensagem" => "success",
                    "numero_proposta" => "123",
                    "formalizacao" => "http://www.google.com"
                    ]
                )
            )
        );

        $this->assertInstanceOf(TypingResponseInterface::class, $factory);
        $this->assertEquals("success", $factory->getMessage());
        $this->assertEquals("123", $factory->getProposal());
        $this->assertEquals("http://www.google.com", $factory->getFomalizacao());
        $toArray = $factory->toArray();

        $this->assertArrayHasKey("message", $toArray);
        $this->assertArrayHasKey("data", $toArray);
    }

    /**
     * @test
     * @return void
     */
    public function factory_encontra_classe_de_resposta_com_retorno_para_cliente_false_digitacao()
    {
        $this->expectException(APIGatewayException::class);
        $_SERVER["REMOTE_ADDR"] = "PHPUnit";
         ResponseFactory::buildFromTyping(
            product: Source\Enum\Product::FGTS,
            curlResponse: new Source\Curl\CurlResponse(
                headers: [],
                curlInfo: [],
                response: json_encode([
                    "sucesso" => false,
                    "mensagem" => "erro_do_banco",
                    ]
                )
            )
        );

    }   




}