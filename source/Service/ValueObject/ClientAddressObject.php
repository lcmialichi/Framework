<?php

namespace Source\Service\ValueObject;

use Source\Exception\ClientException;
use Source\Enum\State;

class ClientAddressObject extends Validation
{

    public function __construct(
        private ?int $id = null,
        private ?int $idCliente = null,
        private readonly mixed $uf = null,
        private readonly mixed $cidade = null,
        private readonly mixed $bairro = null,
        private readonly mixed $rua = null,
        private readonly mixed $numero = null,
        private readonly mixed $cep = null,
        private readonly mixed $complemento = null,
        private readonly mixed $dataCadastro = null,
        private readonly mixed $dataAtualizacao = null
    ){}

    /*
    |--------------------------------------------------------------------------
    | Dados do endereço do Cliente | Model: ClientAddress
    |--------------------------------------------------------------------------
    */
 
    /**
     * **Vai dar ruim**
     *@param bool $isNullable esse comentario aqui
     *@return ?int
     */
    public function setIdClient(int $idClient)
    {   
        $this->idCliente = $idClient;
    }

    public function getIdClient($isNullable = false) : ?int
    {   
        if(parent::nullEnabled($this->idCliente, $isNullable)){
            return $this->idCliente;

        }
        if(!is_numeric($this->idCliente)){

            throw ClientException::invalidField("idCliente");
        }
        return $this->idCliente;


    }
 
    public function getBairro($isNullable = false) : ?string
    {
        if(parent::nullEnabled($this->bairro, $isNullable)){
            return $this->bairro;
        }
        if(!parent::maxLength($this->bairro, 145)){
            throw ClientException::maxField("bairro", 145);

        }
        if(!is_string($this->bairro)){
            throw ClientException::invalidField("bairro");
        }

        return $this->bairro;
    }

    public function getUf($isNullable = false) : ?string
    {
        if(parent::nullEnabled($this->uf, $isNullable)){
            return $this->uf;

        }
        if(!parent::maxLength($this->uf, 2)){
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

    public function getCidade($isNullable = false) : ?string
    {
        if(parent::nullEnabled($this->cidade, $isNullable)){
            return $this->cidade;

        }
        if(!parent::maxLength($this->cidade, 145)){
            throw ClientException::maxField("cidade", 145);

        }
        if (empty($this->cidade)){
            throw ClientException::invalidField("cidade");
        }

        return $this->cidade;
    }

    public function getRua($isNullable = false) : ?string
    {
        
        if(parent::nullEnabled($this->rua, $isNullable)){
            return $this->rua;

        }
        if(!parent::maxLength($this->rua, 145))
        {
            throw ClientException::maxField("rua", 145);
        }
        if (empty($this->rua))
        {
            throw ClientException::invalidField("rua");
        }

        return $this->rua;
    }

    public function getNumero($isNullable = false) : ?string
    {
        if(parent::nullEnabled($this->numero, $isNullable)){
            return $this->numero;

        }
        if(!parent::maxLength($this->numero, 10))
        {
            throw ClientException::maxField("numero", 10);
        }
        if (is_null($this->numero))
        {
            throw ClientException::invalidField("numero");
        }
        
        return $this->numero;
    }

    public function getCep($isNullable = false) : ?string
    {
        if(empty($this->cep) && $isNullable)
        {
            return $this->cep;
        }
        if(strlen($this->cep) > 8)
        {
            throw ClientException::maxField("cep", 8);
        }
        if (empty($this->cep) || !is_numeric($this->cep))
        {
            throw ClientException::invalidField("cep");
        }
        
        return $this->cep;
    }

    public function getComplemento($isNullable = false) : ?string
    {
        if(empty($this->complemento) && $isNullable)
        {
            return $this->complemento;
        }
        if(strlen($this->complemento) > 145)
        {
            throw ClientException::maxField("complemento", 145);
        }
        if (empty($this->complemento))
        {
            throw ClientException::invalidField("complemento");
        }

        return $this->complemento;
    }

    /**
    * Método que compara os dados enviados pelo cliente com os dados cadastrados no banco de dados e retorna se houve alterações, se houver mudança retornará false
    * 
    * @return bool
    * @param ClientAddressObject $clientAddressObject
    */

    public function diff(ClientAddressObject $clientAddressObject)
    {
         $array = [
            $clientAddressObject->uf == $this->getUf(),
            $clientAddressObject->cidade == $this->getCidade(),
            $clientAddressObject->bairro == $this->getBairro(),
            $clientAddressObject->rua == $this->getRua(),
            $clientAddressObject->numero == $this->getNumero(),
            $clientAddressObject->cep == $this->getCep(),
            $clientAddressObject->complemento == $this->getComplemento(isNullable: true)
         ];
         
         if(in_array(false, $array )){
            return false;
         }

        return true;
    }
}