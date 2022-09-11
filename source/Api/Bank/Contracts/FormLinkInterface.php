<?php

namespace Source\Api\Bank\Contracts;

use Source\Curl\CurlResponse;
use Source\Api\Bank\DTO\FormLinkTransferObject;

interface FormLinkInterface
{
    /**
     * Retorna o link de formalizaçao do banco
     *
     * @param integer $proposal
     * @return CurlResponse
     */
    public function consult(int $proposal) : CurlResponse;
    
    /**
     * Retorna os dados da requisiçao em Data Transfer Object
     *
     * @param integer $proposal
     * @return FormLinkTransferObject
     */
    public function consultDTO( int $proposal ) : FormLinkTransferObject;
}