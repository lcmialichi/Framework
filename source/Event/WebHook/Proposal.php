<?php

namespace Source\Event\WebHook;

use Source\Api\Bank\DTO\ProposalTransferObject;

class Proposal extends WebHook
{
    /**
     *
     * @param ProposalTransferObject $formLinkTransferObject
     * @param string $clientHash
     */
    public function __construct(
        private ProposalTransferObject $proposalTransferObject,
        private string $clientHash
    ) {

        parent::__construct("CONSULTA PROPOSTA");
    }


    public function provider(): array
    {
        return [
            "clientHash" => $this->clientHash,
            "status" => $this->proposalTransferObject->status,
            "atividade" => $this->proposalTransferObject->activity,
            "proposta" => $this->proposalTransferObject->proposalNumber
        ];
       
    }
}

