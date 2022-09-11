<?php

namespace Source\Model;

use Source\Model\ORM\Entity; // Define a tabela do banco que será manipulada
use Source\Model\ORM\Column; // Define as colunas da tabela do banco e permite renomear as colunas
use Source\Model\ORM\Model as Schema; // Transforma a tabela do banco em um objeto
use Source\Service\ValueObject\DepartmentObject; // Validador de dados

#[Entity("orgao")] // Define um atributo para Classe Entity passando o nome da tabela do banco
class Department extends Schema{

    #[Column(alias: "id", key: Column::PK)] // Define um atributo para Função Column renomeando a coluna com "alias" e definindo que a coluna é a chave primária
    private $orgao_id; // Declara variável com o mesmo nome da coluna no banco de dados (Regra de Alplicação)
    #[Column(alias: "descricao")] // Define um atributo para Função Column renomenando a coluna com "alias"
    private $orgao_descricao; // Declara variável com o mesmo nome da coluna no banco de dados (Regra de Alplicação)
    #[Column(alias: "dataCadastro", generatedValue: true)] // Define um atributo para Função Column renomenando a coluna com "alias" e configurando que a coluna é gerada automaticamente
    private $orgao_data_insert; // Declara variável com o mesmo nome da coluna no banco de dados (Regra de Alplicação)
    #[Column(alias: "dataAtualizacao", generatedValue: true)] // Define um atributo para Função Column renomenando a coluna com "alias" e configurando que a coluna é gerada automaticamente
    private $orgao_data_update; // Declara variável com o mesmo nome da coluna no banco de dados (Regra de Alplicação)

    public function register(DepartmentObject $departmentObject)// Função para registrar um novo orgão
    {
        $this->orgao_descricao = $departmentObject->getDescricao(); // Atribui o valor capturado do objeto para a variável
        return $this->save(); // Salva os dados no banco de dados e retorna o resultado
    }

    public function findByName(string $descricao) : object | bool{
        return Schema::select(useAlias: true)->where("orgao_descricao", $descricao)->one(); // Busca o departamento pelo nome e retorna um objeto ou bool = false
    }
}
