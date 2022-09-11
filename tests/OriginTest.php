<?php

use PHPUnit\Framework\TestCase;

use Source\Domain\Origin as DomainOrigin;
use Source\Exception\OriginError;

class OriginTest extends TestCase
{

    /**
     * @test
     */
    public function monostate_mantem_valores_setados()
    {
        DomainOrigin::setOrigin(1);
        $origin = new DomainOrigin;
        $this->assertEquals(1, $origin->getOrigin());
        
    } 
    
    /**
     * @test
     */
    public function seta_apenas_uma_vez_a_origem()
    {   
        $this->expectException(OriginError::class);
        DomainOrigin::setOrigin(1);

    }


}
