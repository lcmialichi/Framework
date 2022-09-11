<?php

namespace Source\Api\Bank\DTO;

class ProposalTransferObject{

    public function __construct(
        public readonly mixed $httpCode = null,
        public readonly mixed $proposalNumber = null,
        public readonly mixed $status = null,
        public readonly mixed $registrationDate = null,
        public readonly mixed $activity = null,
        public readonly mixed $table = null
    ){}

}