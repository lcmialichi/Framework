<?php

namespace Source\Model;

use Source\Model\ORM\Column;
use Source\Model\ORM\Entity;
use \Source\Model\ORM\Model as Schema;

#[Entity("atendimento_log_acao")]
class AttendanceLogAction extends Schema{

    #[Column(alias: "id")]
    private $acao_id;
    #[Column(alias: "descricao")]
    private $acao_descricao;
    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $acao_data_insert;
    #[Column(alias: "dataAtualizacao", generatedValue: true)]
    private $acao_data_update;
}