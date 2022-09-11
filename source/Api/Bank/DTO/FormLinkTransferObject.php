<?php

namespace Source\Api\Bank\DTO;

class FormLinkTransferObject{
    public function __construct(
        public readonly mixed $httpCode = null,
        public readonly mixed $formalizationUrl = null,
        public readonly mixed $status = null
    ){}
}