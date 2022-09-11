<?php

namespace Source\Service\ValueObject;

use Source\Exception\ClientException;
use Source\Enum\State;

class ClientEmployerObject
{
    
    public function __construct(
        private readonly mixed $id = null,
        private readonly mixed $idCliente = null,
        private readonly mixed $especie = null,
        private readonly mixed $uf = null,
        private readonly mixed $matricula = null
    ){}

    /*
    |--------------------------------------------------------------------------
    | Dados do empregador do Cliente | Model: ClientEmployer
    |--------------------------------------------------------------------------
    */
    
    /**
     * Tome cuidade com isso para não dar ruim
     */
    public function setIdClient(int $idClient)
    {   
        $this->idCliente = $idClient;
    }

    public function getIdClient($isNullable = false) : ?int
    {
        if(is_null($this->idCliente) && $isNullable)
        {
            return $this->idCliente;

        }else if (is_null($this->idCliente)){
            throw ClientException::invalidField("idclient");
        }

        return $this->idCliente;
    }

    public function getSpecie($isNullable = false) : int
    {

        if(is_null($this->especie) && $isNullable)
        {
            return $this->especie;
        }
        if(strlen($this->especie) > 11)
        {
            throw ClientException::maxField("especie", 11);
        }
        if (empty($this->especie) || !is_numeric($this->especie))
        {
            throw ClientException::invalidField("especie");
        }

        return $this->especie;
    }

    public function getSubscription($isNullable = false) : string
    {
        if(is_null($this->matricula) && $isNullable)
        {
            return $this->matricula;
        }
        if(strlen($this->matricula) > 15)
        {
            throw ClientException::maxField("matricula", 15);
        }
        if (empty($this->matricula) || !is_numeric($this->matricula))
        {
            throw ClientException::invalidField("matricula");
        }

        return $this->matricula;
    }

    public function getUf($isNullable = false) : string
    {
        if(is_null($this->uf) && $isNullable)
        {
            return $this->uf;
        }
        if(strlen($this->uf) > 2)
        {
            throw ClientException::maxField("uf", 2);
        }
        if (empty($this->uf))
        {
            throw ClientException::invalidField("uf");
        }
        if(is_null(State::tryFrom($this->uf)))
        {
            throw ClientException::listValidFields("uf", State::casesValue());
        }

        return $this->uf;
    }

    /**
     * Método que compara os dados enviados pelo cliente com os dados cadastrados no banco de dados e retorna se houve alterações, se houver mudança retornará false
     * 
     * @return bool
     * @param ClientEmployerObject $clientEmployerObject
     */
 
    public function diff(clientEmployerObject $clientEmployerObject) : bool
    {
        $array = [
            $clientEmployerObject->especie == $this->getSpecie(),
            $clientEmployerObject->uf == $this->getUf(),
            $clientEmployerObject->matricula == $this->getSubscription(),
        ];

        if(in_array(false, $array )){
            return false;
        }

        return true;
    }
    
}

