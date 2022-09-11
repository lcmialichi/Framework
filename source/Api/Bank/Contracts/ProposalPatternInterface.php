<?php

namespace Source\Api\Bank\Contracts;

interface ProposalPatternInterface
{
    /**
     * Converte status do banco para o do ATLAS
     *
     * @param string|null $status
     * @return string
     */
    public function statusProvider(?string $status) : string;

    /**                                                                                     
     * Converte atividade do banco para o do ATLAS
     *
     * @param string|null $status
     * @return string
     */
    public function activityProvider(?string $activity) : string;

}