<?php

namespace Source\Service\ValueObject;

use Source\Exception\ClientException;
use Source\Enum\Account;
use Source\Enum\Endorse;

class ClientBankObject
{
    
    public function __construct(
        private readonly mixed $id = null,
        private readonly mixed $idCliente = null,
        private readonly mixed $numeroBanco = null,
        private readonly mixed $agencia = null,
        private readonly mixed $conta = null,
        private readonly mixed $digitoConta = null,
        private readonly mixed $tipoConta = null,
        private readonly mixed $bancoAverbacao = 0,
        private readonly mixed $dataCadastro = null,
        private readonly mixed $dataAtualizacao = null
    ){}

    /*
    |--------------------------------------------------------------------------
    | Dados bancários do Cliente | Model: ClientBank
    |--------------------------------------------------------------------------
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

    public function getAgencia($isNullable = false) : ?string
    {
        if(is_null($this->agencia) && $isNullable)
        {
            return $this->agencia;
        }
        if(strlen($this->agencia) != 4)
        {
            throw ClientException::lenghtField("agencia", 4);
        }
        if (empty($this->agencia) || !is_numeric($this->agencia))
        {
            throw ClientException::invalidField("agencia");
        }

        return $this->agencia;
    }

    public function getConta($isNullable = false) : ?string
    {
        if(is_null($this->conta) && $isNullable)
        {
            return $this->conta;
        }
        if(strlen($this->conta) > 13)
        {
            throw ClientException::maxField("conta", 13);
        }
        if (empty($this->conta) || !is_numeric($this->conta))
        {
            throw ClientException::invalidField("conta");
        }

        return $this->conta;
    }

    public function getNumeroBanco($isNullable = false) : ?string
    {
        if(is_null($this->numeroBanco) && $isNullable)
        {
            return $this->numeroBanco;
        }
        if(strlen($this->numeroBanco) != 3)
        {
            throw ClientException::lenghtField("numeroBanco", 3);
        }
        if (empty($this->numeroBanco) || !is_numeric($this->numeroBanco))
        {
            throw ClientException::invalidField("numeroBanco");
        }

        return $this->numeroBanco;
    }

    public function getDigitoConta($isNullable = false) : ?string
    {
        if(is_null($this->digitoConta) && $isNullable)
        {
            return $this->digitoConta;
        }
        if(strlen($this->digitoConta) != 1)
        {
            throw ClientException::lenghtField("digitoConta", 1);
        }
        if (!is_numeric($this->digitoConta))
        {
            throw ClientException::invalidField("digitoConta");
        }

        return $this->digitoConta;
    }

    public function getTipoConta($isNullable = false) : ?string
    {
        if(is_null($this->tipoConta) && $isNullable)
        {
            return $this->tipoConta;
        }
        if(strlen($this->tipoConta) > 3)
        {
            throw ClientException::maxField("tipoConta", 3);
        }
        if(empty($this->tipoConta))
        {
            throw ClientException::invalidField("tipoConta");

        }
        if(is_null(Account::tryFrom($this->tipoConta)))
        {   
            throw ClientException::listValidFields("tipoConta", Account::casesValue());
            // throw ClientException::invalidField("tipoConta");
        }
        return $this->tipoConta;
    }

    /**
     * Valida se bancoAverbacao é um boolean, como sempre vem um valor não necessita de um isNullable
        */
    public function getBancoAverbacao() : ?int
    {
        if(!is_bool($this->bancoAverbacao))
        {
            throw ClientException::listValidFields("bancoAverbacao", Endorse::casesName());
        }

        return $this->bancoAverbacao;
    }

    /**
    * Método que compara os dados enviados pelo cliente com os dados cadastrados no banco de dados e retorna se houve alterações, se houver mudança retornará false
    * 
    * @return bool
    * @param ClientBankObject $clientBankObject
    */

    public function diff(ClientBankObject $clientBankObject)
    {
        $array = [
        $clientBankObject->numeroBanco == $this->getNumeroBanco(),
        $clientBankObject->agencia == $this->getAgencia(),
        $clientBankObject->conta == $this->getConta(),
        $clientBankObject->digitoConta == $this->getDigitoConta(),
        $clientBankObject->tipoConta == $this->getTipoConta(),
        $clientBankObject->bancoAverbacao == $this->getBancoAverbacao()
        ];

        if(in_array(false, $array )){
        return false;
        }

    return true;
    }
    
}

