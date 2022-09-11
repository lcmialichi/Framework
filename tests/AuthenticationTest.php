<?php

require __DIR__ . "/../vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use Source\Exception\UserException;
use Source\Service\AuthenticateService;
use Source\Router\Request;

final class AuthenticationTest extends TestCase
{
    /**
     * @test
     */
    public function usuario_invalido()
    {
    
        $this->expectException(UserException::class);
       (new AuthenticateService)->authenticate(
            new Request([], [
                "login" => "login_invalido",
                "senha" => "senha_invalida"
            ],[])
       );

    }

    /**
     * @test
     */
    public function usuario_valido()
    {
       $return = (new AuthenticateService)->authenticate(
            new Request([], [
                "login" => "robo.lindo",
                "senha" => "Mudar123"
            ],[])
       );
    
       $this->assertArrayHasKey("message", $return);
       $this->assertArrayHasKey("data", $return);
    }
}

