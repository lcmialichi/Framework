<?php

namespace Source\Service\ValueObject;

use Source\Exception\ClientException;

class ClientUserObject
{
    
    public function __construct(
        private readonly mixed $id = null,
        private readonly mixed $idCliente = null,
        private readonly mixed $usuario = null,
        private readonly mixed $senha = null,
    ){}

    /*
    |--------------------------------------------------------------------------
    | Dados de acesso do Cliente | Model: ClientUser
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

    public function getUser($isNullable = false) : ?string
    {
        if(is_null($this->usuario) && $isNullable)
        {
            return $this->usuario;
        }
        if(strlen($this->usuario) > 145)
        {
            throw ClientException::maxField("usuario", 145);
        }
        if (empty($this->usuario) || count(explode(".", trim($this->usuario, "."))) != 2 || !str_contains($this->usuario, ".")) 
        {
            throw ClientException::invalidField("usuario");
        }

        return $this->usuario;
    }

    public function getPassword($isNullable = false) : ?string
    {
        if(is_null($this->senha) && $isNullable)
        {
            return $this->senha;
        }
        if(strlen($this->senha) > 145)
        {
            throw ClientException::maxField("senha", 145);
        }
        if (empty($this->senha))
        {
            throw ClientException::invalidField("senha");
        }

        return $this->senha;
    }

    /**
     * Método que compara os dados enviados pelo cliente com os dados cadastrados no banco de dados e retorna se houve alterações, se houver mudança retornará false
     * 
     * @return bool
     * @param ClientUserObject $clientUserObject
     */
 
    public function diff(ClientUserObject $clientUserObject) : bool
    {
        $array = [
            $clientUserObject->usuario == $this->getUser(),
            $clientUserObject->senha == $this->getPassword(),
        ];

        if(in_array(false, $array )){
            return false;
        }

        return true;
    }
    
}

