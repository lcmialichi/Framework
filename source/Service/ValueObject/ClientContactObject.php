<?php

namespace Source\Service\ValueObject;

use Source\Exception\ClientException;

class ClientContactObject
{
    
    public function __construct(
        private readonly mixed $id = null,
        private readonly mixed $idCliente = null,
        private readonly mixed $ddd = null,
        private readonly mixed $telefone = null,
    ){}
    
    /*
    |--------------------------------------------------------------------------
    | Dados do contato do Cliente | Model: ClientContact
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
            throw ClientException::invalidField("idCliente");
        }

        return $this->idCliente;
    }

    public function getDdd($isNullable = false) : ?int
    {
        if(is_null($this->ddd) && $isNullable)
        {
            return $this->ddd;
        }
        if(strlen($this->ddd) != 2)
        {
            throw ClientException::lenghtField("ddd", 2);
        }
        if(is_null($this->ddd) || !is_numeric($this->ddd))
        {
            throw ClientException::invalidField("ddd");
        }

        return $this->ddd;
    }
    
    public function getTelefone($isNullable = false) : ?int
    {
        if(is_null($this->telefone) && $isNullable)
        {
            return $this->telefone;
        }
        if( !in_array(strlen($this->telefone), range(8, 9)))
        {
            throw ClientException::betweenField("telefone", 8, 9);
        }
        if (is_null($this->telefone) || !is_numeric($this->telefone))
        {
            throw ClientException::invalidField("telefone");
        }

        return $this->telefone;
    }

    /**
     * Método que compara os dados enviados pelo cliente com os dados cadastrados no banco de dados e retorna se houve alterações, se houver mudança retornará false
     * 
     * @return bool
     * @param ClientContactObject $clientContactObject
     */
 
    public function diff(ClientContactObject $clientContactObject)
    {
        $array = [
            $clientContactObject->ddd == $this->getDdd(),
            $clientContactObject->telefone == $this->getTelefone(),
        ];

        if(in_array(false, $array )){
            return false;
        }

        return true;
    }
    
}

