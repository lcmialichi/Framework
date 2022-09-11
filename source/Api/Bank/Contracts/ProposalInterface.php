<?php

namespace Source\Api\Bank\Contracts;

use Source\Curl\CurlResponse;
use Source\Api\Bank\DTO\ProposalTransferObject;

interface ProposalInterface
{
    /**
     * Consulta proposta no banco
     *
     * @param integer $proposal
     * @return CurlResponse
     */
    public function consult(int $proposal) : CurlResponse;

    /**
     * Consulta proposta no banco, e retorna formatado com ProposalTransferObject
     * 
     * @param integer $proposal
     * @return ProposalTransferObject
     */
    public function consultDTO( int $proposal ) : ProposalTransferObject;

    /**
     * Retorna os dados da propsotas formatados no padrao do ATLAS
     *
     * @param integer $proposal
     * @return ProposalTransferObject
     */
    public function toPattern( int $proposal ) : ProposalTransferObject;
}