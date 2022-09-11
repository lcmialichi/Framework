<?php

namespace Source\Service\ValueObject;

use Source\Exception\SimulationException;
use Source\Enum\SimulationType as Type;

class SimulationObject extends Validation
{

    public function __construct(
        private readonly mixed $id = null,
        private readonly mixed $valor = null,
        private readonly mixed $descricao = null,
        private readonly mixed $idAtendimento = null,
        private readonly mixed $idTabelaBanco = null,
        private readonly mixed $idPortabilidade = null,
        private readonly mixed $idTabelaWeb = null,
        private readonly mixed $idTipo = null,
        private readonly mixed $margem = null,
        private readonly mixed $renda = null,
        private readonly mixed $prazo = null,
        private readonly mixed $seguro = null
    )
    {
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getMargem( bool $isNullable = false )
    {               
        if(parent::nullEnabled($this->margem, $isNullable)){
            return $this->margem;
        }
        if (!parent::decimal($this->margem)) {
            throw SimulationException::invalidField("margem");
        }
   
        return $this->margem;
    }


    public function getRenda( bool $isNullable = false)
    {
        if(parent::nullEnabled($this->renda, $isNullable)){
            return $this->renda;
        }
        if (!parent::decimal($this->renda)) {
            throw SimulationException::invalidField("renda");
        }

        return $this->renda;
    }

    public function getDescricao( bool $isNullable = false)
    {
        if(parent::nullEnabled($this->descricao, $isNullable)){
            return $this->descricao;

        }
        if(empty($this->descricao)){
            throw SimulationException::invalidField("descricao");
        }
        if(!parent::maxLength($this->descricao, 145)){
            throw SimulationException::maxField("descricao", 145);

        }

        return $this->descricao;
    }

    public function getIdAtendimento( bool $isNullable = false)
    {
        if(parent::nullEnabled($this->idAtendimento, $isNullable)){
            return $this->idAtendimento;
        }
        if(!is_numeric($this->idAtendimento)){
            throw SimulationException::invalidField("idAtendimento");
        }
        return $this->idAtendimento;
    }

    public function getIdTabelaWeb( bool $isNullable = false)
    {
        if(parent::nullEnabled($this->idTabelaWeb, $isNullable)){
            return $this->idTabelaWeb;
        }
        if(!is_numeric($this->idTabelaWeb) || empty($this->idTabelaWeb)){
            throw SimulationException::invalidField("idTabelaWeb");
        }
        return $this->idTabelaWeb;

    }

    public function getPrazo( bool $isNullable = false)
    {
        if(parent::nullEnabled($this->prazo, $isNullable)){
            return $this->prazo;
        }
        if(!is_numeric($this->prazo)){
            throw SimulationException::invalidField("prazo");
        }
        return $this->prazo;
    }

    public function getIdPortabilidade( bool $isNullable = false)
    {
        if(parent::nullEnabled($this->idPortabilidade, $isNullable)){
            return $this->idPortabilidade;
        }
        if(!is_numeric($this->idPortabilidade)){
            throw SimulationException::invalidField("idPortabilidade");
        }

        return $this->idPortabilidade;
    }

    public  function getIdTipo( bool $isNullable = false ) : ?int
    {
        if(empty($this->idTipo) && $isNullable){
            return $this->idTipo;

        }
        if(!is_numeric($this->idTipo) || !Type::tryFrom($this->idTipo) ){
            throw SimulationException::listValidFields("idTipo", Type::casesValue());
        }

        return $this->idTipo;
    }

    public function getValor(  bool $isNullable = false )
    {
        if(empty($this->valor) && $isNullable){
            return $this->valor;

        }else if (!is_numeric($this->valor)){
            throw SimulationException::invalidField("valor");
    
        }

        return $this->valor;
        
    }

    public function getIdTabelaBanco( bool $isNullable = false ) : ?int
    {
        
        if(empty($this->idTabelaBanco) && $isNullable){
            return $this->idTabelaBanco;

        }else if (!is_numeric($this->idTabelaBanco)){
            throw SimulationException::invalidField("idTabelaBanco");
    
        }

        return $this->idTabelaBanco;
        
    }

    public function getSeguro( bool $isNullable = false)
    {
        if(parent::nullEnabled($this->seguro, $isNullable)){
            return $this->seguro;
        }
        if(!is_numeric($this->seguro)){
            throw SimulationException::invalidField("seguro");
        }
        return $this->seguro;
    }


}