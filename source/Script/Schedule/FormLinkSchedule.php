<?php

require_once __DIR__ . "/../../../vendor/autoload.php";

use Source\Enum\Banks;
use Source\Model\Proposal;
use Source\Api\Bank\Factory;
use Source\Exception\BankError;
use Source\Http\Response\Script;
use Source\Exception\BankException;
use Source\Event\WebHook\FormLink as WebHook;

/**
 * @todo Sei que ta feio, uma hora eu arrumo
 */
(new Script)->handle(function () {

    $instances = shell_exec("ps aux | grep 'FormLinkSchedule.php' | grep -v grep | wc -l");
    if ($instances > 1) {
        throw new BankError("Uma instancia ja esta rodando");
    }

    $proposalModel = new  Proposal;
    $proposals = $proposalModel->select([
        "banco_id_banco" => "idBanco",
        "proposta_cod_proposta_banco" => "propostaBanco",
        "proposta_id" => "id",
        "cliente_hash" => "hashClient"
    ])
        ->join("simulacao as t2", "t2.simulacao_id", "=", "proposta.proposta_id_simulacao")
        ->join("tabela_banco as t3", "t2.simulacao_id_tabela_banco", "=", "t3.banco_id")
        ->join("atendimento as t4", "t4.atendimento_id", "=", "t2.simulacao_id_atendimento")
        ->join("cliente as t5", "t5.cliente_id", "=", "t4.atendimento_id_cliente")
        ->whereNotNull("cliente_hash")
        ->where(function ($query) {
            $query->where("proposta_status_formalizacao", "=", "AGUARDA FORM DIGITAL");
            $query->whereNull("proposta_link");
        })
        ->get();

    if (!$proposals) {
        throw new BankException("Nao existe propostas para serem consultadas");
    }

    foreach ($proposals as $proposal) {
        (new Script)->handle(function () use ($proposal, $proposalModel) {

            $formLink = Factory::formLink(
                Banks::tryFrom($proposal->idBanco)
            )->consultDTO($proposal->propostaBanco);

            $proposalModel->update(["proposta_link" => $formLink->formalizationUrl])
                ->where("proposta_id", $proposal->id)
                ->execute();

            (new WebHook(
                formLinkTransferObject: $formLink,
                clientHash: $proposal->hashClient
            ))->submit();

            return [
                "message" => $formLink->formalizationUrl
            ];
        });
    }

    return [
        "message" => "\033[34mFINALIZADO.\033[m"
    ];
});
