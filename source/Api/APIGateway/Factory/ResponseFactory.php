<?php

namespace Source\Api\APIGateway\Factory;

use Source\Curl\CurlResponse;
use Source\Enum\Product;
use Source\Api\APIGateway\Factory\SimulationResponse;
use Source\Api\APIGateway\Factory\TypingResponse;
use Source\Api\APIGateway\Factory\SimulationResponse\SimulationResponseInterface;
use Source\Api\APIGateway\Factory\typingResponse\TypingResponseInterface;
use Source\Exception\APIGatewayError;

/**
 * Factory responsavel por criar a resposta da API Gateway
 * Utiliza do Enum Product para construir o objeto de resposta
 */
class ResponseFactory{

    public static function buildFromSimulation( Product $product, CurlResponse $curlResponse ) : SimulationResponseInterface
    {  
        $class = self::getClass($product);
        $instance = SimulationResponse::class . "\\" . $class;
        if(!class_exists($instance)){
            throw new APIGatewayError("Classe de resposta não encontrada: '$class'");
        }
        
        $instance = new $instance($curlResponse);
        if(!$instance instanceof SimulationResponseInterface){
            throw new APIGatewayError("Classe de resposta precisa implementar: " . SimulationResponseInterface::class );

        }

        return $instance;

    }

    public static function buildFromTyping( Product $product, CurlResponse $curlResponse ) : TypingResponseInterface
    {  
        $class = self::getClass($product);
        $instance = TypingResponse::class . "\\" . $class;
        if(!class_exists($instance)){
            throw new APIGatewayError("Classe de resposta não encontrada: '$class'");
        }
        
        $instance = new $instance($curlResponse);
        if(!$instance instanceof TypingResponseInterface){
            throw new APIGatewayError("Classe de resposta precisa implementar: " . TypingResponseInterface::class );

        }

        return $instance;

    }

    public static function getClass( Product $product ): string
    {
        $class = explode("_", $product->getName());
        array_walk($class, function(&$item){
           $item = ucfirst($item);
        });

        return implode($class);
    }

}