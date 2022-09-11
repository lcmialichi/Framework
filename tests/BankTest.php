<?php

use PHPUnit\Framework\TestCase;
use Source\Api\Bank\DTO\ProposalTransferObject;
use Source\Api\Bank\Processor;
use Source\Api\Bank\C6Bank\Pattern;
use Source\Api\Bank\Factory;
use Source\Api\Bank\C6Bank\Proposal;
use Source\Enum\Banks;

class BankTest extends TestCase
{

    /**
     * @test
     */
    public function processor_de_pattern_retorna_no_padrao_atlas()
    {
        $processed = (new Processor(new Pattern))->proposalProcessor(
            new ProposalTransferObject(
                proposalNumber: 123456,
                status: "AND",
                activity: "AGUARDA FORM DIG WEB",
                table: "123456789",
                httpCode: 200
            )
        );

        $this->assertContainsOnlyInstancesOf(ProposalTransferObject::class, [$processed]);
        $this->assertEquals( new ProposalTransferObject(
            proposalNumber: 123456,
            status: "ANDAMENTO",
            activity: "AGUARDA FORM DIGITAL",
            table: "123456789",
            httpCode: 200
        ), $processed);
    }

    /**
     * @test
     */
    public function factory_retorna_banco_correto_de_acordo_com_enum()
    {
        $this->assertContainsOnlyInstancesOf(Proposal::class, [
            Factory::proposal(Banks::C6BANK)
        ]);

    }
    
    /**
     * @test
     */
    public function factory_retorna_exception_de_acordo_com_banco_nao_implementado()
    {
        $this->expectException(Source\Exception\BankError::class);
        Factory::proposal(Banks::NAO_REMOVE_PHPUNIT);
       
    }
}