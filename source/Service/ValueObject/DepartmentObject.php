<?php

namespace Source\Service\ValueObject; // Definição do namespace

use Source\Exception\EmployerException; // Importa a classe de exceção especifica para o serviço

class DepartmentObject extends Validation
{
    public function __construct( // Cria o construtor da classe que defini o que será recebido quando DepartmentObject for instanciado
        private readonly mixed $id = null, //ndica que será passado um parametro para o construtor apenas leitura e de qualquer tipagem
        private readonly mixed $descricao = null //ndica que será passado um parametro para o construtor apenas leitura e de qualquer tipagem
    ) {
    }

    public function getId() // Cria função para acessar o valor do atributo id (Não está sendo utilizado por enquanto)
    {
        return $this->id;
    }

    public function getDescricao($isNullable = false) // Cria função para acessar o valor do atributo descricao
    {
        if (parent::nullEnabled($this->descricao, $isNullable)) { // Chama a função nullEnabled da classe pai para verificar se o valor é nulo
            return $this->descricao; //Caso seja nulo e estiver configurado para receber valores nulos ($isNullable = true) retorna o valor
        }
        if (!preg_match("/^[a-zA-Z ]+$/", $this->descricao)) { // Verifica se o valor é uma string e se é composta apenas por letras e espaços
            throw EmployerException::invalidField("descricao"); // Caso não seja, lança uma exceção
        }
        if (!parent::maxLength($this->descricao, 255)) { // Chama a função maxLength da classe pai para verificar se o valor é maior que o limite
            throw EmployerException::maxField("descricao", 255); // Caso seja, lança uma exceção
        }
        if (!parent::minLength($this->descricao, 3)) { // Chama a função minLength da classe pai para verificar se o valor é menor que o limite
            throw EmployerException::minField("descricao", 3); // Caso seja, lança uma exceção
        }
        return $this->descricao; // Retorna o valor caso passe por todas as validações
    }
}
