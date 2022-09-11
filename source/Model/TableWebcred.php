<?php

namespace Source\Model;

use \Source\Model\ORM\Model as Schema;
use Source\Model\ORM\Entity;
use Source\Model\ORM\Column;

#[Entity("tabela_web")]
class TableWebcred extends Schema {

    #[Column(alias: "id", key: Column::PK)]
    private $web_id;
    #[Column(alias:"idIProduto")]
    private $web_id_produto;
    #[Column(alias:"idBanco")]
    private $web_id_banco;
    #[Column(alias: "codigo")]
    private $web_codigo;
    #[Column(alias: "descricao")]
    private $web_descricao;
    #[Column(alias: "status")]
    private $web_status;
    #[Column(alias: "dataCriacao", generatedValue: true)]
    private $web_data_insert;
    #[Column(alias: "dataAtualizacao", generatedValue: true)]
    private $web_data_update;
}   