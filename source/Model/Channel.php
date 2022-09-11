<?php

namespace Source\Model;

use Source\Model\ORM\Column;
use Source\Model\ORM\Entity;
use \Source\Model\ORM\Model as Schema;

#[Entity("origem")]
class Channel extends Schema{

    #[Column(alias: "idOrigem", key: Column::PK)]
    private $origem_id;

    #[Column(alias: "descricao")]
    private $origem_descricao;

    #[Column(alias: "key")]
    private $origem_key;

    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $origem_data_insert;

    #[Column(alias: "dataAtualizacao", generatedValue: true)]
    private $origem_data_update;

    /**
     * retorna origem pela app-key
     *
     * @param string $key
     * @return object|boolean
     */
    public function findByKey(string $key) :object|bool
    {
        return Schema::select(useAlias: true)->where("origem_key", $key)->one();
    }

}