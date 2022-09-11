<?php

namespace Source\Service\ValueObject;

use Source\Exception\LogException;

class TabLogObject extends Validation{
    
    public function __construct(
        private readonly mixed $id = null,
        private readonly mixed $idAtendimento = null,
        private readonly mixed $idEtapa = null,
        private readonly mixed $idProduto = null,
        private readonly mixed $idTabulacao = null
    ){
    }

    public function getId( bool $isNullable = false )
    {
        if(parent::nullEnabled($this->id, $isNullable)){
            return $this->id;
        }
        if (!parent::numeric($this->id)) {
            throw LogException::invalidField("id");

        }

        return $this->id;
    }


    public function getIdAtendimento( bool $isNullable = false )
    {
        if(parent::nullEnabled($this->idAtendimento, $isNullable)){
            return $this->idAtendimento;
        }
        if (!parent::numeric($this->idAtendimento)) {
            throw LogException::invalidField("idAtendimento");

        }

        return $this->idAtendimento;
    }

    public function getIdEtapa( bool $isNullable = false )
    {
        if(parent::nullEnabled($this->idEtapa, $isNullable)){
            return $this->idEtapa;
        }
        if (!parent::numeric($this->idEtapa)) {
            throw LogException::invalidField("idEtapa");

        }

        return $this->idEtapa;
    }

    public function getIdProduto( bool $isNullable = false )
    {
        if(parent::nullEnabled($this->idProduto, $isNullable)){
            return $this->idProduto;
        }
        if (!parent::numeric($this->idProduto)) {
            throw LogException::invalidField("idProduto");

        }

        return $this->idProduto;
    }

    public function getIdTabulacao( bool $isNullable = false )
    {
        if(parent::nullEnabled($this->idTabulacao, $isNullable)){
            return $this->idTabulacao;
        }
        if (!parent::numeric($this->idTabulacao)) {
            throw LogException::invalidField("idTabulacao");

        }

        return $this->idTabulacao;
    }



}