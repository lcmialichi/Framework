<?php

namespace Source\Model;

use \Source\Model\ORM\Model as Schema;
use Source\Model\ORM\Entity;
use Source\Model\ORM\Column;

#[Entity("tabela_banco")]
class Table extends schema{

    #[Column(alias: "id", key: Column::PK)]
    private $banco_id;
    #[Column(alias: "descricao")]
    private $banco_descricao;
    #[Column(alias: "codigo")]
    private $banco_codigo;
    #[Column(alias: "status")]
    private $banco_status;
    #[Column(alias: "idOperacao")]
    private $banco_id_operacao;
    #[Column(alias: "idProduto")]
    private $banco_id_produto;
    #[Column(alias: "idBanco")]
    private $banco_id_banco;
    #[Column(alias: "idOrgao")]
    private $banco_id_orgao;
    #[Column(alias: "idConvenio")]
    private $banco_id_convenio;
    #[Column(alias: "dataCriacao", generatedValue: true)]
    private $banco_data_insert;
    #[Column(alias: "dataAtualizacao", generatedValue: true)]
    private $banco_data_update;

    
}