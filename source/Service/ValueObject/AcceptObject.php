<?php

namespace Source\Service\ValueObject;

use Source\Exception\AcceptError;
use Source\Exception\AcceptException;

/**
 * @throws AcceptException
 * @throws AcceptError
 */
class AcceptObject extends Validation {

    public function __construct(
        private ?int $id = null,
        private readonly mixed $finalidade = null,
        private readonly mixed $idOrigem = null,
        private readonly mixed $idAcao = 0,
        private readonly mixed $idCliente = null
    )
    {
    }

    public function getId(bool $isNullable = false) : int
    {
        if(parent::nullEnabled($this->id, $isNullable)){
            return $this->id;
        }
        if(empty($this->id)){
            throw AcceptError::invalidField("aceite_id");
        }

        return $this->id;
    }

    public function getFinalidade(bool $isNullable = false) : mixed
    {
        if(parent::nullEnabled($this->finalidade, $isNullable)){
            return $this->finalidade;
        }
        if(empty($this->finalidade)){
            throw AcceptError::invalidField("aceite_finalidade");
        }
       
        return $this->finalidade;
    }

    public function getIdOrigem(bool $isNullable = false) : mixed
    {
        if(parent::nullEnabled($this->idOrigem, $isNullable)){
            return $this->idOrigem;
        }
        if(empty($this->idOrigem)){
            throw AcceptError::invalidField("aceite_id_origem");
        }
       
        return $this->idOrigem;
    }

    public function getIdAcao(bool $isNullable = false) : mixed
    {   
        
        if(parent::nullEnabled($this->idAcao, $isNullable)){
            return $this->idAcao;
        }
        if(empty($this->idAcao) || !is_numeric($this->idAcao)){
            throw AcceptException::invalidField("idAcao");
        }

        return $this->idAcao;

    }

    public function getIdCliente(bool $isNullable = false) : mixed
    {
        if(parent::nullEnabled($this->idCliente, $isNullable)){
            return $this->idCliente;
        }

        if(empty($this->idCliente)){
            throw AcceptError::invalidField("aceite_id_cliente");
        }
        return $this->idCliente;
  
    }





}