<?php

namespace Source\Service\ValueObject;

use Source\Exception\AttendanceException;

class AttendanceObject extends Validation {

    public function __construct(
        private ?int $idAtendimento = null,
        private readonly mixed $idCliente = null,
        private readonly mixed $idUsuario = null,
        private readonly mixed $status = 1,
        private readonly mixed $idCanal = null,
        private readonly mixed $idFinalizacao = null,
        private readonly mixed $dataFinalizacao = null,
        private readonly mixed $idTabulacao = null,
        private readonly mixed $idProduto = null,
        private readonly mixed $idEtapa = null,
        private readonly mixed $idAtendimentoAnterior = null,
    ) {
    }

    public function getIdAtendimentoAnterior(bool $isNullable = false) : ?int
    {
        if(parent::nullEnabled($this->idAtendimentoAnterior, $isNullable)){
            return null;
        }
        if(parent::numeric($this->idAtendimentoAnterior)){
            return $this->idAtendimentoAnterior;
        }
        throw AttendanceException::invalidField("idAtendimento");
    }

    public function getIdAtendimento(bool $isNullable = false) : ?int
    {
        if(parent::nullEnabled($this->idAtendimento, $isNullable)){
            return $this->idAtendimento;
        }
        if(parent::numeric($this->idAtendimento)){
            return $this->idAtendimento;
        }
        throw AttendanceException::invalidField("idAtendimento");
    }

    public function setIdAtendimento(int $id)
    {
        $this->idAtendimento = $id;
    }

    public function getIdCliente(bool $isNullable = false) : ?int
    {   
        if(parent::nullEnabled($this->idCliente, $isNullable)){
            return $this->idCliente;
        }
        if(parent::numeric($this->idCliente)){
            return $this->idCliente;
        }

        throw AttendanceException::invalidField("idCliente");
    }

    public function setIdCliente(int $id)
    {
        $this->idCliente = $id;
    }

    public function getIdUsuario(bool $isNullable = false) : ?int
    {
        if(parent::nullEnabled($this->idUsuario, $isNullable)){
            return $this->idUsuario;
        }
        if(parent::numeric($this->idUsuario)){
            return $this->idUsuario;
        }
        throw AttendanceException::invalidField("idUsuario");
    }

    public function setIdUsuario(int $id)
    {
        $this->idUsuario = $id;
    }

    public function getStatus(bool $isNullable = false) : int
    {
        if(($this->status == 1 || $this->status == 0 ||$this->status == 2) || $isNullable){
            return $this->status;
        }
    
        throw AttendanceException::invalidField("status");
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    public function getIdCanal(bool $isNullable = false) : int
    {
        if(parent::nullEnabled($this->idCanal, $isNullable)){
            return $this->idCanal;
        }
        if(parent::numeric($this->idCanal)){
            return $this->idCanal;
        }
        throw AttendanceException::invalidField("idCanal");
    }

    public function setIdCanal(int $id)
    {
        $this->idCanal = $id;
    }

    public function getIdFinalizacao(bool $isNullable = false) : int
    {
        if(parent::nullEnabled($this->idFinalizacao, $isNullable)){
            return $this->idFinalizacao;
        }
        if(parent::numeric($this->idFinalizacao)){
            return $this->idFinalizacao;
        }
        throw AttendanceException::invalidField("idFinalizacao");
    }

    public function setIdFinalizacao(int $id)
    {
        $this->idFinalizacao = $id;
    }

    public function getDataFinalizacao(bool $isNullable = false) 
    {      
        if(parent::nullEnabled($this->dataFinalizacao, $isNullable)){
            return $this->dataFinalizacao;
        }
        if(parent::date($this->dataFinalizacao)){
            return $this->dataFinalizacao;
        }
        throw AttendanceException::invalidField("dataFinalizacao");
    }

    public function setDataFinalizacao(string $date)
    {
        $this->dataFinalizacao = $date;
    }

    public function getIdTabulacao(bool $isNullable = false) : ?int
    {   
        if(parent::nullEnabled($this->idTabulacao, $isNullable)){
            return $this->idTabulacao;
        }
        if(parent::numeric($this->idTabulacao)){
            return $this->idTabulacao;
        }
        throw AttendanceException::invalidField("idTabulacao");
    }

    public function setIdTabulacao(int $id)
    {
        $this->idTabulacao = $id;
    }

    public function getIdEtapa(bool $isNullable = false){

        if(parent::nullEnabled($this->idEtapa, $isNullable)){
            return $this->idEtapa;
        }
        if(parent::numeric($this->idTabulacao)){
            return $this->idEtapa;
        }
        throw AttendanceException::invalidField("idEtapa");

    }

    public function getIdProduto(bool $isNullable = false){

        if(parent::nullEnabled($this->idProduto, $isNullable)){
            return $this->idProduto;
        }
        if(parent::numeric($this->idProduto)){
            return $this->idProduto;
        }
        throw AttendanceException::invalidField("idProduto");


        
    }

}