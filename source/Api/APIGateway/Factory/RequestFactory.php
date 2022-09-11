<?php

namespace Source\Api\APIGateway\Factory;

use Source\Enum\Product;
use Source\Api\APIGateway\Factory\SimulationRequest;
use Source\Api\APIGateway\Factory\TypingRequest;
use Source\Api\APIGateway\Factory\SimulationRequest\SimulationRequestInterface;
use Source\Api\APIGateway\Factory\TypingRequest\TypingRequestInterface;
use Source\Exception\APIGatewayError;

/**
 * Factory responsavel por criar a requisiçao da API Gateway
 * Utiliza do Enum Product para construir o objeto de requisiçao
 */
class RequestFactory{

    public static function buildFromSimulation( Product $product ) : SimulationRequestInterface
    {  
        $class = self::getClass($product);
        $instance = SimulationRequest::class . "\\" . $class;
        if(!class_exists($instance)){
            throw new APIGatewayError("Classe de requisiçao não encontrada: '$class'");
        }
        
        $instance = new $instance();
        if(!$instance instanceof SimulationRequestInterface){
            throw new APIGatewayError("Classe de requisiçao precisa implementar: " . SimulationRequestInterface::class );

        }

        return $instance;

    }

    public static function buildFromTyping( Product $product ) : TypingRequestInterface
    {  
        $class = self::getClass($product);
        $instance = TypingRequest::class . "\\" . $class;
        if(!class_exists($instance)){
            throw new APIGatewayError("Classe de requisiçao não encontrada: '$class'");
        }
        
        $instance = new $instance();
        if(!$instance instanceof TypingRequestInterface){
            throw new APIGatewayError("Classe de requisiçao precisa implementar: " . TypingRequestInterface::class );

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