<?php

namespace Source\Model;

use Source\Model\ORM\Column;
use Source\Model\ORM\Entity;
use \Source\Model\ORM\Model as Schema;
use Source\Service\ValueObject\TabLogObject;

#[Entity("atendimento_tabulacao_log")]
class TabLog extends Schema{
    
    #[Column(alias: "id", key: Column::PK)]
    private $log_id;
    
    #[Column(alias: "idAtendimento")]
    private $log_id_atendimento;
    
    #[Column(alias: "idEtapa")]
    private $log_id_etapa;
    
    #[Column(alias: "idProduto")]
    private $log_id_produto;
    
    #[Column(alias: "idTabulacao")]
    private $log_id_tabulacao;
    
    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $log_data_insert;

    public function register(TabLogObject $tabLogObject)
    {
        $this->log_id_atendimento = $tabLogObject->getIdAtendimento();
        $this->log_id_etapa = $tabLogObject->getIdEtapa();
        $this->log_id_produto= $tabLogObject->getIdProduto();
        $this->log_id_tabulacao = $tabLogObject->getIdTabulacao();
        return $this->save();
    }
}