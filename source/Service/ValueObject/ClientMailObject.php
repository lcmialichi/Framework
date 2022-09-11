<?php

namespace Source\Service\ValueObject;

use Source\Exception\ClientException;

class ClientMailObject
{
    
    public function __construct(
        private readonly mixed $id = null,
        private readonly mixed $idCliente = null,
        private readonly mixed $email = null,
    ){}

    /*
    |--------------------------------------------------------------------------
    | Dados do e-mail do Cliente | Model: ClientMail
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
        }else if (is_null($this->email)){
            throw ClientException::invalidField("idClient");
        }

        return $this->idCliente;
    }

    public function getMail($isNullable = false) : ?string
    {
        if(is_null($this->email) && $isNullable)
        {
            return $this->email;
        }
        if(strlen($this->email) > 145)
        {
            throw ClientException::maxField("email", 145);
        }
        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL))
        {
            throw ClientException::invalidField("email");
        }

        return $this->email;
    }
 
    /**
     * Método que compara os dados enviados pelo cliente com os dados cadastrados no banco de dados e retorna se houve alterações, se houver mudança retornará false
     * 
     * @return bool
     * @param ClientMailObject $clientMailObject
     */
    public function diff(ClientMailObject $clientMailObject) : bool
    {
        $array = [
            $clientMailObject->email == $this->getMail(),
        ];

        if(in_array(false, $array )){
            return false;
        }

        return true;
    }
    
}

