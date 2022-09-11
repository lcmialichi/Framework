<?php 

namespace Source\Model;

use Source\Model\ORM\Column;
use Source\Model\ORM\Entity;
use \Source\Model\ORM\Model as Schema;

#[Entity("webhook")]
class WebHook extends Schema{
    
    #[Column(alias: "id")]
    private $webhook_id;
    
    #[Column(alias: "url")]
    private $webhook_url;
    
    #[Column(alias: "servico")]
    private $webhook_servico;
    
    #[Column(alias: "status")]
    private $webhook_status;
    
    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $webhook_data_insert;
    
    #[Column(alias: "ultimaAtualizacao", generatedValue: true)]
    private $webhook_data_update;

    public function findByService(string $service, bool $status = true)
    {
        return Schema::select(useAlias: true)
                ->where("webhook_servico", $service)
                ->where("webhook_status", (int)$status)
                ->execute();

    }

    public function updateModel(array $data, int $id)
    {
        return $this->update($data)->where("webhook_id" , $id)->execute();
    }

}