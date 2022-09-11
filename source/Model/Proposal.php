<?php

namespace Source\Model;

use Source\Model\ORM\Column;
use Source\Model\ORM\Entity;
use \Source\Model\ORM\Model as Schema;
use Source\Service\ValueObject\ProposalObject;

#[Entity("proposta")]
class Proposal extends Schema { 

    #[Column(alias: "id", key: Column::PK)]
    private $proposta_id;
    #[Column(alias: "idSimulacao")]
    private $proposta_id_simulacao;
    #[Column(alias: "codPropostaBanco")]
    private $proposta_cod_proposta_banco;
    #[Column(alias: "idPropostaWebcred")]
    private $proposta_cod_proposta_webcred;
    #[Column(alias: "codStatusBanco")]
    private $proposta_cod_status_banco;
    #[Column(alias: "dataAverbacao")]
    private $proposta_data_averbacao;
    #[Column(alias: "dataPagamento")]
    private $proposta_data_pagamento;
    #[Column(alias: "situacao")]
    private $proposta_situacao;
    #[Column(alias: "statusFormalizacao")]
    private $proposta_status_formalizacao;
    #[Column(alias: "idFormalizacao")]
    private $proposta_id_formalizacao;
    #[Column(alias: "linkFormalizacao")]
    private $proposta_link;
    #[Column(alias: "ultimaAtualizacao", generatedValue: true)]
    private $proposta_data_update;
    #[Column(alias: "dataCriacao", generatedValue: true)]
    private $proposta_data_insert;
    
    public function register(ProposalObject $proposalObject)
    {
        $this->proposta_id_simulacao = $proposalObject->getIdSimulacao();
        $this->proposta_cod_proposta_banco = $proposalObject->getCodPropostaBanco();
        $this->proposta_cod_proposta_webcred = $proposalObject->getIdPropostaWebcred(isNullable: true);
        $this->proposta_cod_status_banco = $proposalObject->getCodStatusBanco(isNullable: true);
        $this->proposta_data_averbacao = $proposalObject->getDataAverbacao(isNullable: true) ;
        $this->proposta_data_pagamento = $proposalObject->getDataPagamento(isNullable: true);
        $this->proposta_situacao = $proposalObject->getSituacao(isNullable: true);
        $this->proposta_status_formalizacao = $proposalObject->getStatusFormalizacao(isNullable: true);
        $this->proposta_id_formalizacao = $proposalObject->getIdFormalizacao(isNullable: true);
        return $this->save();

    }

    /**
     * Busca pelo codigo da proposta no banco
     *
     * @param integer $proposalBankCode
     * @return bool|object
     */
    public function findByBankCode(int $proposalBankCode)
    {
        return Schema::select(useAlias: true)->where("proposta_cod_proposta_banco", $proposalBankCode)->one();
    }

    public function findByAttendanceId(int $attendanceId){
        return Schema::select(
            get: [
                "proposta_link" => "linkFormalizacao",
                "proposta_cod_proposta_banco" => "codPropostaBanco",
                "proposta_situacao" => "situacao",
                "proposta_status_formalizacao" => "statusFormalizacao",
                "atendimento_id_produto" => "idProduto",
                "simulacao_valor" => "valor",
                "simulacao_prazo" => "prazo",
                "proposta_data_insert" => "dataCriacao"
            ]
        )
        ->join("simulacao as t2", "proposta_id_simulacao", "=" , "t2.simulacao_id")
        ->join("atendimento as t3", "t2.simulacao_id_atendimento", "=" , "t3.atendimento_id")
        ->where("t3.atendimento_id", "=", $attendanceId)
        ->execute();

    }

}