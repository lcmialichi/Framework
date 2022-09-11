<?php

namespace Source\Service\ValueObject;

use Source\Exception\ClientException;
use Source\Enum\Document;

class ClientDocumentObject
{
    
    public function __construct(
        private readonly mixed $id = null,
        private readonly mixed $idCliente = null,
        private readonly mixed $documento = null,
        private readonly mixed $idTipo = null,
    ){}

    /*
    |--------------------------------------------------------------------------
    | Dados dos documentos do Cliente | Model: ClientDocument
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
        }
        if(!is_numeric($this->idCliente)){

            throw ClientException::invalidField("idCliente");
        }

        return $this->idCliente;
    }

    public function getDocument($isNullable = false) : ?string
    {
        if(is_null($this->documento) && $isNullable)
        {
            return $this->documento;
        }
        if(strlen($this->documento) > 14)
        {
            throw ClientException::maxField("documento", 14);
        }
        if (empty($this->documento))
        {
            throw ClientException::invalidField("documento");
        }

        return $this->documento;
    }

    public function getIdTipo($isNullable = false) : ?string
    {
        if(is_null($this->idTipo) && $isNullable)       
        {
            return $this->idTipo;
        }
        if (empty($this->idTipo) || !is_numeric($this->idTipo))
        {
            throw ClientException::invalidField("idTipo");
        }


        return $this->idTipo;
    }

    /**
     * Método que compara os dados enviados pelo cliente com os dados cadastrados no banco de dados e retorna se houve alterações, se houver mudança retornará false
     * 
     * @return bool
     * @param ClientDocumentObject $clientDocumentObject
     */
 
    public function diff(ClientDocumentObject $clientDocumentObject) : bool
    {
        $array = [
            $clientDocumentObject->documento == $this->getDocument(),
            $clientDocumentObject->idTipo == $this->getIdTipo(),
        ];

        if(in_array(false, $array )){
            return false;
        }

        return true;
    }
    
}

