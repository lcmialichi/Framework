<?php

namespace Source\Model; // Definição do namespace

use Source\Model\ORM\Entity; // Define a tabela do banco que será manipulada
use Source\Model\ORM\Column; // Define as colunas da tabela do banco e permite renomear as colunas
use Source\Model\ORM\Model as Schema; // Transforma a tabela do banco em um objeto
use Source\Service\ValueObject\ConvenantObject; // Validador de dados

#[Entity("convenio")] // Define um atributo para Classe Entity passando o nome da tabela do banco
class Convenant extends Schema // Constrói a Classe Convenant e herda as propriedades da classe Schema
{

    #[Column(alias: "id", key: Column::PK)] // Define um atributo para Classe Column renomeando a coluna com "alias" e definindo que a coluna é a chave primária
    private $convenio_id; // Declara variável com o mesmo nome da coluna no banco de dados (Regra de Alplicação)
    #[Column(alias: "nome")] // Define um atributo para Classe Column renomenando a coluna com "alias"
    private $convenio_nome; // Declara variável com o mesmo nome da coluna no banco de dados (Regra de Alplicação)
    #[Column(alias: "codigoWebcred")] // Define um atributo para Classe Column renomenando a coluna com "alias"
    private $convenio_codigo_webcred; // Declara variável com o mesmo nome da coluna no banco de dados (Regra de Alplicação)
    #[Column(alias: "codApiGateway")] // Define um atributo para Classe Column renomenando a coluna com "alias"
    private $convenio_cod_apigateway; // Declara variável com o mesmo nome da coluna no banco de dados (Regra de Alplicação)
    #[Column(alias: "cnpj")] // Define um atributo para Classe Column renomenando a coluna com "alias"
    private $convenio_cnpj; // Declara variável com o mesmo nome da coluna no banco de dados (Regra de Alplicação)
    #[Column(alias: "dataCadastro", generatedValue: true)] // Define um atributo para Classe Column renomenando a coluna com "alias" e define um dado que não será manipulado utilizando "generatedValue"
    private $convenio_data_insert; // Declara variável com o mesmo nome da coluna no banco de dados (Regra de Alplicação)
    #[Column(alias: "dataAtualizacao", generatedValue: true)] // Define um atributo para Classe Column renomenando a coluna com "alias" e define um dado que não será manipulado utilizando "generatedValue"
    private $convenio_data_update; // Declara variável com o mesmo nome da coluna no banco de dados (Regra de Alplicação)

    public function register(ConvenantObject $convenantObject) // Função para registrar um novo convênio
    {
        $this->convenio_nome = strtoupper($convenantObject->getNome()); // Atribui o valor capturado do objeto para a variável
        $this->convenio_codigo_webcred = $convenantObject->getCodWebcred(); // Atribui o valor capturado do objeto para a variável e configura que pode ser recebido null
        $this->convenio_cod_apigateway = $convenantObject->getCodApiGateway(isNullable: true); // Atribui o valor capturado do objeto para a variável e configura que pode ser recebido null
        $this->convenio_cnpj = $convenantObject->getCnpj(); // Atribui o valor capturado do objeto para a variável
        return $this->save(); // Salva o registro no banco de dados e retorna o ID se der sucesso ou false se der erro
    }

    public function findByName(string $nome) : object | bool
    {
        return Schema::select(useAlias: true)->where("convenio_nome", $nome)->one(); // Busca o convênio pelo nome e retorna se existe
    }

    public function registerEmployer(int $idConvenant, int $idDepartment) : bool|int
    {
        return Schema::table("convenio_orgao")->insert([
            "orgao_id_convenio" => $idConvenant,
            "orgao_id_orgao" => $idDepartment
        ])->execute();
        
    }
}
