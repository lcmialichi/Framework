<?php

namespace Source\Service\ValueObject;

use Source\Exception\ProposalException;

class ProposalObject extends Validation{
    
    public function __construct(
        private readonly mixed $id = null,
        private readonly mixed $idSimulacao = null,
        private readonly mixed $codPropostaBanco = null,
        private readonly mixed $idPropostaWebcred = null,
        private readonly mixed $codStatusBanco = null,
        private readonly mixed $dataAverbacao = null,
        private readonly mixed $dataPagamento = null,
        private readonly mixed $situacao = null,
        private readonly mixed $statusFormalizacao = null,
        private readonly mixed $idFormalizacao = null
    ){
    }

    public function getId( bool $isNullable = false )
    {
        if(parent::nullEnabled($this->id, $isNullable)){
            return $this->id;
        }
        if (!parent::numeric($this->id)) {
            throw ProposalException::invalidField("id");

        }

        return $this->id;
    }

    public function getIdSimulacao( bool $isNullable = false )
    {
        if(parent::nullEnabled($this->idSimulacao, $isNullable)){
            return $this->idSimulacao;
        }
        if (!parent::numeric($this->idSimulacao)) {
            throw ProposalException::invalidField("idSimulacao");

        }

        return $this->idSimulacao;
    }

    public function getCodPropostaBanco( bool $isNullable = false )
    {
        if(parent::nullEnabled($this->codPropostaBanco, $isNullable)){
            return $this->codPropostaBanco;
        }
        if (!parent::numeric($this->codPropostaBanco)) {
            throw ProposalException::invalidField("codPropostaBanco");

        }

        return $this->codPropostaBanco;
    }

    public function getIdPropostaWebcred( bool $isNullable = false )
    {
        if(parent::nullEnabled($this->idPropostaWebcred, $isNullable)){
            return $this->idPropostaWebcred;
        }
        if (!parent::numeric($this->idPropostaWebcred)) {
            throw ProposalException::invalidField("idPropostaWebcred");

        }

        return $this->idPropostaWebcred;
    }

    public function getCodStatusBanco( bool $isNullable = false )
    {
        if(parent::nullEnabled($this->codStatusBanco, $isNullable)){
            return $this->codStatusBanco;
        }
        if (!parent::numeric($this->codStatusBanco)) {
            throw ProposalException::invalidField("codStatusBanco");

        }

        return $this->codStatusBanco;
    }

    public function getDataAverbacao( bool $isNullable = false )
    {
        if(parent::nullEnabled($this->dataAverbacao, $isNullable)){
            return $this->dataAverbacao;
        }
        if (!parent::date($this->dataAverbacao)) {
            throw ProposalException::invalidField("dataAverbacao");

        }

        return $this->dataAverbacao;
    }

    public function getDataPagamento( bool $isNullable = false )
    {
        if(parent::nullEnabled($this->dataPagamento, $isNullable)){
            return $this->dataPagamento;
        }
        if (!parent::date($this->dataPagamento)) {
            throw ProposalException::invalidField("dataPagamento");

        }

        return $this->dataPagamento;
    }

    public function getSituacao( bool $isNullable = false )
    {
        if(parent::nullEnabled($this->situacao, $isNullable)){
            return $this->situacao;
        }
        if (!is_string($this->situacao)) {
            throw ProposalException::invalidField("situacao");

        }

        return $this->situacao;
    }

    public function getStatusFormalizacao( bool $isNullable = false )
    {
        if(parent::nullEnabled($this->statusFormalizacao, $isNullable)){
            return $this->statusFormalizacao;
        }
        if (!is_string($this->statusFormalizacao)) {
            throw ProposalException::invalidField("statusFormalizacao");

        }

        return $this->statusFormalizacao;
    }

    public function getIdFormalizacao( bool $isNullable = false )
    {
        if(parent::nullEnabled($this->idFormalizacao, $isNullable)){
            return $this->idFormalizacao;
        }
        if (!parent::numeric($this->idFormalizacao)) {
            throw ProposalException::invalidField("idFormalizacao");

        }

        return $this->idFormalizacao;
    }

    
}