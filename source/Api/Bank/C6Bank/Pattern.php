<?php

namespace Source\Api\Bank\C6Bank;

use Source\Exception\BankError;
use Source\Api\Bank\Contracts\ProposalPatternInterface;

/**
 * Pattern para C6Bank
 */
class Pattern implements ProposalPatternInterface
{

    public function statusProvider(?string $status) : string
    {
        return match ($status) {
            "REP" => "REPROVADO",
            "PEN" => "PENDENTE",
            "INT" => "INTEGRADO",
            "AND" => "ANDAMENTO",
            "CAN" => "CANCELADO",
            default =>  throw new BankError("status nao identificado [C6Bank: '$status']!")
        };
    }

    public function activityProvider(?string $activity) : string
    {
        return match ($activity) {
            "REPROVA CREDITO" => "REPROVA CREDITO",
            "AGUARDA FORM DIG WEB" => "AGUARDA FORM DIGITAL",
            "PAGO" => "PAGO",
            "REPROVA FGTS" => "REPROVA FGTS",
            "ANALISE SELFIE" => "ANALISE SELFIE",
            "ANALISE DOCUMENTAL" => "ANALISE DOCUMENTAL",
            "EM AVERBACAO" => "EM AVERBACAO",
            "ANALISE CREDITO" => "ANALISE CREDITO",
            "REPROVA" => "REPROVA",
            "CANCELADA" => "CANCELADO",
            default =>  throw new BankError("atividade nao identificada [C6Bank: '$activity']!")
        };
    }

}