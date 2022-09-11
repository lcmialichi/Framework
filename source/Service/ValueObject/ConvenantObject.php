<?php

namespace Source\Service\ValueObject; // Definição do namespace

use Source\Exception\EmployerException; // Importa a classe de exceção especifica para o serviço

class ConvenantObject extends Validation // cria a classe ConvenantObject responsável por tratar os dados recebidos pelo serviço
{

    public function __construct( // Cria o construtor da classe que defini o que será recebido quando ConvenantObject for instanciado
        private readonly mixed $id = null, // indica que será passado um parametro para o construtor apenas leitura e de qualquer tipagem
        private readonly mixed $nome = null, // indica que será passado um parametro para o construtor apenas leitura e de qualquer tipagem
        private readonly mixed $codWebcred = null, // indica que será passado um parametro para o construtor apenas leitura e de qualquer tipagem
        private readonly mixed $codApiGateway = null, // indica que será passado um parametro para o construtor apenas leitura e de qualquer tipagem
        private readonly mixed $cnpj = null, // indica que será passado um parametro para o construtor apenas leitura e de qualquer tipagem
    ) {
    }

    public function getId() // Cria função para acessar o valor do atributo id (Não está sendo utilizado por enquanto)
    {
        return $this->id;
    }

    public function getNome($isNullable = false) // Cria função para acessar o valor do atributo nome (configura no parametro que não pode entrar valor null)
    {
        if (parent::nullEnabled($this->nome, $isNullable)) { // Chama a função nullEnabled da classe pai para verificar se o valor é nulo
            return $this->nome; //Caso seja nulo e estiver configurado para receber valores nulos ($isNullable = true) retorna o valor
        }
        if (!preg_match("/^[a-zA-Z ]+$/", $this->nome)) { // Chama a função de validação de nome para verificar se o valor contém apenas letras e espaços
            throw EmployerException::invalidField("nome"); // Caso não seja válido lança uma exceção
        }
        if (!parent::maxLength($this->nome, 255)) { // Chama a função de validação de tamanho para verificar se o valor é maior que 255
            throw EmployerException::maxField("nome", 255); // Caso não seja válido lança uma exceção
        }
        if (!parent::minLength($this->nome, 4)) { // Chama a função de validação de tamanho para verificar se o valor é menor que 4
            throw EmployerException::minField("nome", 4); // Caso não seja válido lança uma exceção
        }
        return $this->nome; // Retorna o valor caso passe por todas as validações
    }

    public function getCodWebcred($isNullable = false) // Cria função para acessar o valor do atributo codWebcred (configura no parametro que não pode entrar valor null)
    {
        if (parent::nullEnabled($this->codWebcred, $isNullable)) { // Chama a função nullEnabled da classe pai para verificar se o valor é nulo
            return $this->codWebcred; // Caso seja nulo e estiver configurado para receber valores nulos ($isNullable = true) retorna o valor
        }
        if (empty($this->codWebcred) || !is_numeric($this->codWebcred)) { // Cria função que verifica se o valor é vazio ou não é numérico
            throw EmployerException::invalidField("codWebcred"); // Caso não seja válido lança uma exceção
        }
        return $this->codWebcred; // Retorna o valor caso passe por todas as validações
    }

    public function getCodApiGateway($isNullable = false) // Cria função para acessar o valor do atributo codApiGateway (configura no parametro que não pode entrar valor null)
    {
        if (parent::nullEnabled($this->codApiGateway, $isNullable)) { // Chama a função nullEnabled da classe pai para verificar se o valor é nulo
            return null; // Caso seja nulo e estiver configurado para receber valores nulos ($isNullable = true) retorna o valor
        }
        if (empty($this->codApiGateway) || !is_numeric($this->codApiGateway)) { // Cria função que verifica se o valor é vazio ou não é numérico
            throw EmployerException::invalidField("codApiGateway"); // Caso não seja válido lança uma exceção
        }
        return $this->codApiGateway; // Retorna o valor caso passe por todas as validações
    }

    public function getCnpj($isNullable = false) // Cria função para acessar o valor do atributo cnpj (configura no parametro que não pode entrar valor null)
    {
        if (parent::nullEnabled($this->cnpj, $isNullable)) { // Chama a função nullEnabled da classe pai para verificar se o valor é nulo
            return $this->cnpj; // Caso seja nulo e estiver configurado para receber valores nulos ($isNullable = true) retorna o valor
        }

        if (empty($this->cnpj) || !parent::numeric($this->cnpj)) { // Cria função que verifica se o valor é vazio ou não é numérico
            throw EmployerException::invalidField("cnpj"); // Caso não seja válido lança uma exceção
        }

        if (!parent::maxLength($this->cnpj, 14)) { // Chama a função de validação de tamanho para verificar se o valor é maior que 14
            throw EmployerException::maxField("cnpj", 14); // Caso não seja válido lança uma exceção
        }

        return $this->cnpj; // Retorna o valor caso passe por todas as validações
    }
}
