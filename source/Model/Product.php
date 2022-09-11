<?php

namespace Source\Model;

use \Source\Model\ORM\Model as Schema;
use Source\Model\ORM\Entity;
use Source\Model\ORM\Column;

#[Entity("produto")]
class Product extends Schema{
    
    #[Column(alias:"id", key: Column::PK)]
    private $produto_id;
    
    #[Column(alias:"nome")]
    private $produto_nome;
    
    #[Column(alias:"dataCadastro", generatedValue: true)]
    private $produto_data_insert;
    
    #[Column(alias:"dataAtualizacao", generatedValue: true)]
    private $produto_data_update;
}