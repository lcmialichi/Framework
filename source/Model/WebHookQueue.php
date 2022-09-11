<?php

namespace Source\Model;

use Source\Model\ORM\Column;
use Source\Model\ORM\Entity;
use \Source\Model\ORM\Model as Schema;

#[Entity("webhook_fila")]
class WebHookQueue extends Schema {

    #[Column(alias: "id")]
    private $fila_id;
    #[Column(alias: "idWebhook")]
    private $fila_id_webhook;
    #[Column(alias: "requisicao")]
    private $fila_requisicao;
    #[Column(alias: "dataCadastro")]
    private $fila_data_insert;
    #[Column(alias: "dataUpdate")]
    private $fila_data_update;
}