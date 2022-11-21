<?php

namespace Source\Event;

use Source\Enum\Banks;
use Source\Model\Proposal;
use Source\Api\Bank\Factory;
use Source\Exception\BankError;
use Source\Http\Response\Script;
use Source\Api\Bank\DTO\ProposalTransferObject;

class ProposalEvent extends ScriptEvent
{
    /**
     * @var ProposalTransferObject
     */
    public $proposalDTO;
    /**
     * @var string
     */
    public $hashClient;
    /**
     * @var int
     */
    public $idAtendimento;
    /**
     * @var string
     */
    public $status;
    /**
     * @var string
     */
    public $activity;

       /**
     *  {@inheritDoc}
     */
    public function handle(): array
    {

        $proposalModel = new  Proposal;
        $proposals = $proposalModel->select([
            "banco_id_banco" => "idBanco",
            "proposta_cod_proposta_banco" => "propostaBanco",
            "proposta_id" => "id",
            "proposta_situacao" => "status",
            "proposta_status_formalizacao" => "atividade",
            "cliente_hash" => "hashClient",
            "atendimento_id" => "idAtendimento"
        ])
            ->join("simulacao as t2", "t2.simulacao_id", "=", "proposta.proposta_id_simulacao")
            ->join("tabela_banco as t3", "t2.simulacao_id_tabela_banco", "=", "t3.banco_id")
            ->join("atendimento as t4", "t4.atendimento_id", "=", "t2.simulacao_id_atendimento")
            ->join("cliente as t5", "t5.cliente_id", "=", "t4.atendimento_id_cliente")
            ->whereNotNull("cliente_hash")
            ->where(function ($query) {
                $query->whereNotIn("proposta_situacao", [
                    "REPROVADO", "INTEGRADO", "CANCELADO"
                ])
                    ->orWhere(function ($query) {
                        $query->whereNull("proposta_situacao");
                        $query->orWhereNull("proposta_status_formalizacao");
                    });
            })
            ->get();

        if (!$proposals) {
            throw new BankError("Nao existe propostas para serem consultadas");
        }

        foreach ($proposals as $proposal) {

            (new Script)->handle(function () use ($proposal, $proposalModel) {

                $proposalDTO = Factory::proposal(
                    Banks::tryFrom($proposal->idBanco)
                )->toPattern($proposal->propostaBanco);

                if (
                    $proposalDTO->status != $proposal->status ||
                    $proposalDTO->activity != $proposal->atividade
                ) {

                    $this->status = $proposalDTO->status;
                    $this->activity = $proposalDTO->activity;
                    $this->hashClient = $proposal->hashClient;
                    $this->idAtendimento = $proposal->idAtendimento;
                    $this->proposalDTO = $proposalDTO;
                    $this->notify();

                    $proposalModel->updateModel(
                        data: [
                            "proposta_status_formalizacao" =>  $proposalDTO->activity,
                            "proposta_situacao" =>  $proposalDTO->status
                        ],
                        id: $proposal->id
                    );
                }
                return [
                    "message" =>  $proposalDTO->status
                ];
            });
        }

        return [
            "message" => "\033[42mFINALIZADO.\033[m",
            "outPut" => true
        ];
    }
}
