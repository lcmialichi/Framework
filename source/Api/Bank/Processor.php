<?php

namespace Source\Api\Bank;

use Source\Api\Bank\DTO\ProposalTransferObject;
use Source\Api\Bank\Contracts\ProposalPatternInterface;

/**
 * Processador de informaçoes
 *  - Necessario enviar um processor do banco que ira converter as informaçoes
 */
class Processor {
    public function __construct(
        private ProposalPatternInterface $pattern
    ){}

    /**
     * Processa os dados da proposta, e converta para o pattern envidado
     *
     * @param ProposalTransferObject $proposal
     * @return ProposalTransferObject
     */
    public function proposalProcessor(ProposalTransferObject $proposal) : ProposalTransferObject
    {        
        return new ProposalTransferObject(
            proposalNumber: $proposal->proposalNumber,
            status: $this->pattern->statusProvider($proposal->status),
            activity:  $this->pattern->activityProvider($proposal->activity),
            table: $proposal->table,
            httpCode: $proposal->httpCode
        );
    }
}