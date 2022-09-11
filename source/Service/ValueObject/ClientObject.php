<?php

namespace Source\Service\ValueObject;

use Source\Exception\ClientException;
use Source\Enum\Gender;
use Source\Enum\Marital;
use Source\Enum\Nationality;

class ClientObject
{

    public function __construct(
        private readonly mixed $id = null,
        private readonly mixed $nome = null,
        private readonly mixed $cpf = null,
        private readonly mixed $sexo = null,
        private readonly mixed $estadoCivil = null,
        private readonly mixed $nacionalidade = null,
        private readonly mixed $nomeMae = null,
        private readonly mixed $hash = null,
        private readonly mixed $dataNascimento = null,
        private readonly mixed $dataCadastro = null,
        private readonly mixed $dataAtualizacao = null
    ){}

    /*
    |--------------------------------------------------------------------------
    | Dados pessoais do Cliente | Model: Client
    |--------------------------------------------------------------------------
    */

    public function getId() : int
    {
        return $this->id;
    }
 
    public function getNome($isNullable = false) : ?string
    {
  
        if(empty($this->nome) && $isNullable)
        {
            return $this->nome;
        }
        
        if(strlen($this->nome) > 150)
        {
            throw ClientException::maxField("nome", 150);
        }
        
        if (is_numeric($this->nome) || count(explode(" ", trim($this->nome))) <= 1)
        {
            throw ClientException::invalidField("nome");
        }

        return $this->nome;
    }

    /**
     * Método para validar o CPF e retornar caso seja válido
     */

    public function getCpf($isNullable = false) : ?int
    {
        if(is_null($this->cpf) && $isNullable)
        {
            return $this->cpf;
        }        

        if (strlen($this->cpf) > 11) 
        {
            throw ClientException::maxField("cpf", 11);
        }
        
        if(strlen($this->cpf) < 11)
        {
            throw ClientException::minField("cpf", 11);
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $this->cpf) || !is_numeric($this->cpf))
        {
            throw ClientException::invalidField("cpf");
        }
    
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $this->cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($this->cpf[$c] != $d) {

                throw ClientException::invalidField("cpf");
            }
        }
        return $this->cpf;
    }

    public function getSexo($isNullable = false) : ?string
    {
        if(empty($this->sexo) && $isNullable)
        {
            return $this->sexo;
        }
        
        if(strlen($this->sexo) > 1)
        {
            throw ClientException::maxField("sexo", 1);
        }
        
        if (empty($this->sexo))
        {
            throw ClientException::invalidField("sexo");
        }
        
        if(is_null(Gender::tryFrom($this->sexo)))
        {
            throw ClientException::listValidFields("sexo", Gender::casesValue());
        }

        return $this->sexo;
    }

    public function getEstadoCivil($isNullable = false) : ?string
    {
        if(empty($this->estadoCivil) && $isNullable)
        {
            return $this->estadoCivil;
        }
        
        if(strlen($this->estadoCivil) > 145)
        {
            throw ClientException::maxField("estadoCivil", 145);
        }
        
        if(empty($this->estadoCivil))
        {
            throw ClientException::invalidField("estadoCivil");
        }
        
        if(is_null(Marital::tryFrom($this->estadoCivil)))
        {
            throw ClientException::listValidFields("estadoCivil", Marital::casesValue());
        }
      
        return $this->estadoCivil;
    }

    public function getNacionalidade($isNullable = false) : ?string
    {
        if(empty($this->nacionalidade) && $isNullable)
        {
            return $this->nacionalidade;
        }
        
        if(strlen($this->nacionalidade) > 145)
        {
            throw ClientException::maxField("nacionalidade", 145);
        }
        
        if (empty($this->nacionalidade))
        {
            throw ClientException::invalidField("nacionalidade");
        }
        
        if(is_null(Nationality::tryFrom($this->nacionalidade)))
        {
            throw ClientException::listValidFields("nacionalidade", Nationality::casesValue());
        }

        return $this->nacionalidade;
    } 

    public function getNomeMae($isNullable = false) : ?string
    {
        if(empty($this->nomeMae) && $isNullable)
        {
            return $this->nomeMae;
        }
        
        if(strlen($this->nomeMae) > 80)
        {
            throw ClientException::maxField("nomeMae", 80);
        }
        
        if (is_numeric($this->nomeMae) || count(explode(" ", trim($this->nomeMae))) <= 1)
        {
            throw ClientException::invalidField("nomeMae");
        }

        return $this->nomeMae;
    }

    public function getHash($isNullable = false) : ?string
    {
        if(empty($this->hash) && $isNullable)
        {
            return $this->hash;
        }
        
        if(strlen($this->hash) > 32)
        {
            throw ClientException::maxField("hash", 32);
        }
        
        if (empty($this->hash))
        {
            throw ClientException::invalidField("hash");
        }

        return $this->hash;
    }

    public function getDataNascimento($isNullable = false) : ?string
    {
        if(empty($this->dataNascimento) && $isNullable)
        {
            return $this->dataNascimento;
        }
        
        if(strlen($this->dataNascimento) > 145)
        {
            throw ClientException::maxField("dataNascimento", 145);
        }
        
        if (!(\DateTime::createFromFormat("Y-m-d", $this->dataNascimento) || is_null($this->dataNascimento)))
        {
            throw ClientException::invalidField("dataNascimento");
        }

        return $this->dataNascimento;
    }

    /**
     * Método que compara os dados enviados pelo cliente com os dados cadastrados no banco de dados e retorna se houve alterações, se houver mudança retornará false
     * 
     * @return bool
     * @param ClientObject $clientObject
     */

    public function diff(ClientObject $clientObject) : bool
    {
         $array = [
            $clientObject->nome == $this->getNome(isNullable: true),
            $clientObject->sexo == $this->getSexo(isNullable: true),
            $clientObject->estadoCivil == $this->getEstadoCivil(isNullable: true),
            $clientObject->nacionalidade == $this->getNacionalidade(isNullable: true),
            $clientObject->nomeMae == $this->getNomeMae(isNullable: true),
            $clientObject->dataNascimento == $this->getDataNascimento(isNullable: true),
         ];

         if(in_array(false, $array )){
            return false;
         }

        return true;
    }
}